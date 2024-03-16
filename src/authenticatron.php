<?php

////    Authenticatron
// MIT Licensed - Property of eustasy
// https://github.com/eustasy/authenticatron
// http://labs.eustasy.org/authenticatron/example

// A few quick notes:
// Secret Length defaults to 16.
// Code Length is set to 6.
// Both of these are set with Google Authenticator in mind.
// Any other length is your own problem.

//declare(strict_types=1);
namespace eustasy;

use QRcode\QRcode;
use QRcode\QRstr;

abstract class Authenticatron
{
	// A reference for Base32 valid characters.
	const BASE32CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

	////    Create a new Secret
	public static function makeSecret(int $length = 16): ?string
	{
		if (
			!function_exists('random_bytes') && // Requires PHP 7
			!function_exists('openssl_random_pseudo_bytes') // Requires OpenSSL
		) {
			return null;
		} elseif (function_exists('random_bytes')) {
			$random = random_bytes($length);
		} elseif (function_exists('openssl_random_pseudo_bytes')) {
			// Otherwise try to use OpenSSL
			$random = openssl_random_pseudo_bytes($length, $strong);
			if (!$strong) {
				// Fail if not strong.
				return null;
			}
		}

		// For each letter of the secret, generate a random Base32 Characters.
		$secret = '';
		for ($i = 0; $i < $length; $i++) {
			$secret .= self::BASE32CHARS[ord($random[$i]) & 31];
		}

		return $secret;
	}

	////    Create an OTPAuth URL
	public static function getUrl(string $accountName, string $secret, string $issuer): string
	{

		// Strip any colons, they screw things up.
		$toStrip = array(':', '?', '&', '=', '+', '@', '/', '\\', '#');
		// TODO It might also be a good idea to other strip special characters
		$issuer = str_replace($toStrip, '', $issuer);
		$accountName = str_replace($toStrip, '', $accountName);

		// The Issuer and Account are not encoded as part of the path, but are when they are parameters.
		// This could cause issues with certain characters. Try to keep it alphanumeric.
		return 'otpauth://totp/' . $issuer . ': ' . $accountName . '?secret=' . urlencode($secret) . '&issuer=' . urlencode($issuer);
	}

	////    Create a Base64 PNG QR Code
	public static function generateQrCode(string $URL, int $Size = 4, int $Margin = 2): ?string
	{
		try {
			$base64_data = QRcode :: base64_png ($URL, QRstr :: QR_ECLEVEL_L, $Size, $Margin);
			return $base64_data;
		} catch (\Exception $e) {
			return null;
		}
	}

	////    Decode as Base32
	protected static function base32Decode(string $secret): ?string
	{
		// If there is no secret or it is too small.
		if (empty($secret) || strlen($secret) < 16) {
			return null;
		}

		// A reference for converting from Base32
		$base32CharsArray = str_split(self::BASE32CHARS);
		$base32CharsFlipped = array_flip($base32CharsArray);

		// Remove padding characters (there shouldn't be any)
		$secret = str_replace('=', '', $secret);

		// Split into an array
		$secret = str_split($secret);

		// Set an empty string.
		$secretDecoded = '';
		$secretCount = count($secret);

		// While $i is less than the length of $secret, 8 bits at a time.
		for ($i = 0; $i < $secretCount; $i = $i + 8) {
			$string = '';

			// If the letter is not a Base32 Character
			if (!in_array($secret[$i], $base32CharsArray)) {
				return null;
			}

			// Create 8 letters
			for ($j = 0; $j < 8; $j++) {
				// Convert the characters to numbers, and pad them if necessary.
				$string .= str_pad(base_convert($base32CharsFlipped[$secret[$i + $j]], 10, 2), 5, '0', STR_PAD_LEFT);
				// Flipped and Secret both had an @ for suppression originally.
			}

			// Turn into an array
			$eightBits = str_split($string, 8);
			$eightBitsCount = count($eightBits);

			// Got each bit, convert the numbers to ASCII codes.
			for ($z = 0; $z < $eightBitsCount; $z++) {
				$secretDecoded .= (($convert = chr(base_convert($eightBits[$z], 2, 10))) || ord($convert) == 48) ? $convert : '';
			}
		}

		return $secretDecoded;
	}

	////    Calculate the current code.
	public static function getCode(string $secret, int $timestamp = null, int $codeLength = 6): string
	{
		// Set the timestamp to something sensible.
		// You should only over-ride this if you really know why.
		if ($timestamp === null) {
			$timestamp = (int) floor(time() / 30);
		}

		// Pack the Timestamp into a binary string
		// N = Unsigned long (always 32 bit, big endian byte order)
		$timestampPacked = chr(0) . chr(0) . chr(0) . chr(0) . pack('N*', $timestamp);

		// Decode (?) the Secret
		$secretDecoded = self::base32Decode($secret);

		// Hash the Timestamp and Secret with HMAC using the SHA1 algorithm
		$hmac = hash_hmac('SHA1', $timestampPacked, $secretDecoded, true);

		// Use last nibble of result as index/offset
		$offset = ord(substr($hmac, -1)) & 0x0F;
		// Gives a generated number that varies.

		// Take 4 bytes of the result from the Offset
		$part = substr($hmac, $offset, 4);

		// Unpack the binary value
		$value = unpack('N', $part);
		$value = $value[1];

		// Make it a 32bit signed value.
		$value = $value & 0x7FFFFFFF;

		// Make a Modulo
		// When the $CodeLength is 6, it is
		// equivalent to 10**6, 10^6, or 1,000,000
		$denominator = pow(10, $codeLength);

		// This function adds leading zeros (the third parameter) to the left-hand side (the fourth)
		// to the remainder of our unpacked hash-part divided by 10 to the power of the required code length.
		return str_pad($value % $denominator, $codeLength, '0', STR_PAD_LEFT);
	}

	////    Create an array of all codes within an acceptable range.
	public static function getCodesInRange(string $secret, int $variance = 2): array
	{
		// The output will look like this.
		//
		//    array(5) {
		//        [-2] => string(6) "398599"
		//        [-1] => string(6) "283062"
		//        [0] => string(6) "809226"
		//        [1] => string(6) "541727"
		//        [2] => string(6) "667780"
		//    }
		//
		// Note the indexes, which can be used to determine the time difference,
		// and perhaps warn users on the outer bounds. Code generation is expensive,
		// so avoid generating any you don't want to check against later.

		// Create an empty array to be returned.
		$acceptable = array();

		// From the negative of the variance to the positive equivalent.
		for ($i = -$variance; $i <= $variance; $i++) {
			// Add that amount in increments of 30 seconds.
			$loopTime = floor(time() / 30) + $i;
			// Add the code to the array.
			$acceptable[$i] = self::getCode($secret, $loopTime);
		}

		// Return the list of codes.
		return $acceptable;
	}

	////    Check a given Code against a Secret
	public static function checkCode(string $code, string $secret, int $variance = 2): bool
	{
		$acceptable = self::getCodesInRange($secret, $variance);

		// Return a simple boolean to avoid data-leakage or zero-equivalent code issues.
		if (in_array($code, $acceptable)) {
			return true;
		}

		return false;
	}

	////    Create a Secret and QR code for a given Member
	public static function new(string $accountName, string $issuer): array
	{
		$return = array();
		$return['Secret'] = self::makeSecret();
		// TODO Handle makeSecret returning null.
		$return['URL'] = self::getUrl($accountName, $return['Secret'], $issuer);
		$return['QR'] = self::generateQrCode($return['URL']);
		// WARNING QR returns null if not available 
		return $return;
	}
}
