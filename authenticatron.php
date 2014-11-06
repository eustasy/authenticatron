<?php

// Authentricatron
// MIT License
// Property of eustasy
// eustasy.org

$Sitewide_Title = 'Example Site'; // This is a short name to identify your site or service.
$Member_Name = 'John Smith'; // This could be their email, name, or username.

// Secret Length defaults to 16.
// Code Length is set to 6.
// Both of these are set with Google Authenticator in mind.
// Any other length is your own problem.

// Where can we find PHPQRCode?
$PHPQRCode = __DIR__.'/phpqrcode_2010100721_1.1.4.php';

////	END CONFIGURATION







// A reference for Base32 valid characters.
$Base32_Chars = array(
	'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', //  8
	'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', // 16
	'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', // 24
	'Y', 'Z', '2', '3', '4', '5', '6', '7'  // 32
);








// Create a new Secret
function Authenticator_Secret($Length = 16) {
	global $Base32_Chars;
	$Secret = '';
	$Random = openssl_random_pseudo_bytes($Length);
	for ($i = 0; $i < $Length; $i++)
		$Secret .= $Base32_Chars[ord($Random[$i]) & 31];
	return $Secret;
}







// Create an OTPAuth URL
function Authenticator_URL($Account, $Secret, $Issuer = null) {
	global $Sitewide_Title;
	$Issuer = isset($Issuer) ? $Issuer : $Sitewide_Title;
	$Issuer = str_replace (':', '', $Issuer);
	$Account = str_replace (':', '', $Account);
	return 'otpauth://totp/'.$Issuer.': '.$Account.'?secret='.urlencode($Secret).'&issuer='.urlencode($Issuer);
}







// Create a Base64 PNG QR Code
function Authenticator_QR($URL, $Size = 4, $Margin = 0, $Level = 'M') {

	// Require the PHPQRCode Library
	global $PHPQRCode;
	require_once $PHPQRCode;

	// Use the object cache to capture the PNG without outputting it.
	// Kind of hacky but the best way I can find without writing a new QR Library.
	ob_start();
	QRCode::png($URL, null, constant('QR_ECLEVEL_'.$Level), $Size, $Margin);
	$QR = base64_encode(ob_get_contents());
	ob_end_clean();

	// Return it as a Base64 PNG
	return 'data:image/png;base64,'.$QR;

}







// Decode as Base32
function Base32_Decode($Secret) {
	global $Base32_Chars;
	if (empty($Secret)) return false;
	// Flip the array
	$Base32_CharsFlipped = array_flip($Base32_Chars);
	// Remove padding characters (there shouldn't be any)
	$Secret = str_replace('=','', $Secret);
	// Split into an array
	$Secret = str_split($Secret);
	$String = '';
	for ($i = 0; $i < count($Secret); $i = $i+8) {
		$x = '';
		if (!in_array($Secret[$i], $Base32_Chars)) return false;
		for ($j = 0; $j < 8; $j++) {
			$x .= str_pad(base_convert(@$Base32_CharsFlipped[@$Secret[$i + $j]], 10, 2), 5, '0', STR_PAD_LEFT);
		}
		$eightBits = str_split($x, 8);
		for ($z = 0; $z < count($eightBits); $z++) {
			$String .= ( ($y = chr(base_convert($eightBits[$z], 2, 10))) || ord($y) == 48 ) ? $y:'';
		}
	}
	return $String;
}







// Calculate the current code.
function Authenticator_Code($Secret, $timeSlice = null, $CodeLength = 6) {

	if ($timeSlice === null) $timeSlice = floor(time() / 30);

	$Secret = Base32_Decode($Secret);

	// Pack time into binary string
	$time = chr(0).chr(0).chr(0).chr(0).pack('N*', $timeSlice);

	// Hash it with users secret key
	$hm = hash_hmac('SHA1', $time, $Secret, true);

	// Use last nibble of result as index/offset
	$offset = ord(substr($hm, -1)) & 0x0F;

	// grab 4 bytes of the result
	$hashpart = substr($hm, $offset, 4);

	// Unpak binary value
	$value = unpack('N', $hashpart);
	$value = $value[1];

	// Only 32 bits
	$value = $value & 0x7FFFFFFF;

	$modulo = pow(10, $CodeLength);

	return str_pad($value % $modulo, $CodeLength, '0', STR_PAD_LEFT);

}







// Create an array of all codes within an acceptable range.
function Authenticator_Acceptable($Secret, $Variance = 2) {

	$Acceptable = array();

	// From the negative of the variance to the positive equivalent.
	for ($i = -$Variance; $i <= $Variance; $i++) {
		// Add that amount in increments of 30 seconds.
		$LoopTime_Negative = floor(time() / 30) + $i;
		// Add the code to the array.
		$Acceptable[$i] = Authenticator_Code($Secret, $LoopTime_Negative);
	}

	return $Acceptable;

}








// Check a given Code against a Secret
function Authenticator_Check($Code, $Secret, $Variance = false) {

	if ( $Variance === false ) $Acceptable = Authenticator_Acceptable($Secret);
	else $Acceptable = Authenticator_Acceptable($Secret, $Variance);

	if ( in_array($Code, $Acceptable) ) return true;
	else return false;

}






