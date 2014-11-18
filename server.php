<?php

require __DIR__.'/assets/header.php';

$MCrypt = false;
$OpenSSL = false;
$Secure = false;







////	MCrypt
$MCrypt_Block = '
	<div class="clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Status</p>
		<p>Information</p>
	</div>
	<div class="right">
		<h3>MCrypt</h3>';
if ( function_exists('mcrypt_create_iv') ) {
	$MCrypt = true;
	$Secure = true;
	$MCrypt_Block .= '
		<p class="color-nephritis">Installed</p>';
} else {
	$MCrypt_Block .= '
		<p class="color-pomegranate">Not Installed</p>';
}
$MCrypt_Block .= '
		<p>MCrypt is used for secure key generation.</p>
	</div>';







////	OpenSSL
$OpenSSL_Block = '
	<div class="clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Status</p>
		<p>Information</p>
	</div>
	<div class="right">
		<h3>OpenSSL</h3>';
if ( function_exists('openssl_random_pseudo_bytes') ) {
	openssl_random_pseudo_bytes(1, $Strong);
	if ( $Strong ) {
		$OpenSSL = true;
		$Secure = true;
		$OpenSSL_Block .= '
		<p class="color-nephritis">Installed, Secure.</p>';
	} else {
		$OpenSSL_Block .= '
		<p class="color-pomegranate">Installed, Insecure.</p>';
	}
} else {
	$OpenSSL_Block .= '
		<p class="color-pomegranate">Not Installed</p>';
}
$OpenSSL_Block .= '
		<p>OpenSSL is used as a fallback for secure key generation.</p>
	</div>';







////	Security
$Security_Block = '
	<div class="clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Status</p>
		<p>Information</p>
	</div>
	<div class="right">
		<h3>Security</h3>';
if ( $MCrypt ) {
	$Security_Block .= '
		<p class="color-nephritis"><strong>Your installation will use MCrypt.</strong></p>
		<p>This is the best option, but it\'s good to have a fallback.</p>';
} else if ( $OpenSSL ) {
	$Security_Block .= '
		<p class="color-nephritis"><strong>Your installation will use OpenSSL.</strong></p>
		<p>This is the second best option, maybe try installing <code>php5-mcrypt</code> ?</p>';
} else {
	$Security_Block .= '
		<p class="color-pomegranate"><strong>Your installation will not work.</strong></p>
		<p>Maybe try installing <code>php5-mcrypt</code> or <code>openssl</code> ?</p>';
}
$Security_Block .= '
	</div>';







////	GD
$GD_Block = '
	<div class="clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Status</p>
		<p>Information</p>
	</div>
	<div class="right">
		<h3>GD</h3>';
if (
	extension_loaded('gd') &&
	function_exists('gd_info')
) {
	$GD_Block .= '
		<p class="color-nephritis">The GD functions are loaded. You can create QR Codes.</p>
		<p>The GD extension is used for generating a secure QR code.</p>';
} else {
	$GD_Block .= '
		<p class="color-pomegranate">The GD functions are not loaded. You cannot create QR Codes.</p>
		<p>The GD extension is used for generating a secure QR code.</p>
		<p>Try installing <code>php5-gd</code> in Ubuntu.</p>';
}
$GD_Block .= '
	</div>';








echo $Security_Block;
echo $MCrypt_Block;
echo $OpenSSL_Block;

echo $GD_Block;

echo '
</body>
</html>';