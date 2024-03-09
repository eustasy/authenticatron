<?php

include __DIR__ . '/assets/header.php';

$RandomBytes = false;
$MCrypt = false;
$OpenSSL = false;
$Secure = false;







////	RandomBytes
$RandomBytes_Block = '
	<div class="clear"></div>
	<div class="left">
		<p>RandomBytes</p>
	</div>
	<div class="right">
		<p>RandomBytes is used for secure key generation.</p>';
if (function_exists('random_bytes')) {
	$RandomBytes = true;
	$Secure = true;
	$RandomBytes_Block .= '
		<p class="color-flatui-nephritis">Available</p>';
} else {
	$RandomBytes_Block .= '
		<p class="color-flatui-pomegranate">Not Available</p>';
}
$RandomBytes_Block .= '
	</div>';






////	MCrypt
$MCrypt_Block = '
	<div class="clear"></div>
	<div class="left">
		<p>MCrypt</p>
	</div>
	<div class="right">
		<p>MCrypt is used for secure key generation.</p>
		<p>MCrypt is deprecated in PHP 7.1, removed in PHP 7.2</p>';
if (function_exists('mcrypt_create_iv')) {
	$MCrypt = true;
	$Secure = true;
	$MCrypt_Block .= '
		<p class="color-flatui-nephritis">Installed</p>';
} else {
	$MCrypt_Block .= '
		<p class="color-flatui-pomegranate">Not Installed</p>';
}
$MCrypt_Block .= '
	</div>';







////	OpenSSL
$OpenSSL_Block = '
	<div class="clear"></div>
	<div class="left">
		<p>OpenSSL</p>
	</div>
	<div class="right">
		<p>OpenSSL is used as a fallback for secure key generation.</p>';
if (function_exists('openssl_random_pseudo_bytes')) {
	openssl_random_pseudo_bytes(1, $Strong);
	if ($Strong) {
		$OpenSSL = true;
		$Secure = true;
		$OpenSSL_Block .= '
		<p class="color-flatui-nephritis">Installed, Secure.</p>';
	} else {
		$OpenSSL_Block .= '
		<p class="color-flatui-pomegranate">Installed, Insecure.</p>';
	}
} else {
	$OpenSSL_Block .= '
		<p class="color-flatui-pomegranate">Not Installed</p>';
}
$OpenSSL_Block .= '
	</div>';







////	Security
$Security_Block = '
	<div class="clear break"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Status</p>
		<p>Information</p>
	</div>
	<div class="right">
		<h3>Security</h3>';
if ($RandomBytes) {
	$Security_Block .= '
		<p class="color-flatui-nephritis"><strong>Your installation will use RandomBytes.</strong></p>';
	if ($MCrypt) {
		$Security_Block .= '
		<p>MCrypt is available as a fallback if necessary.</p>';
	}
	if ($OpenSSL) {
		$Security_Block .= '
		<p>OpenSSL is available as a fallback if necessary.</p>';
	}
} else if ($MCrypt) {
	$Security_Block .= '
		<p class="color-flatui-nephritis"><strong>Your installation will use MCrypt.</strong></p>';
	if ($OpenSSL) {
		$Security_Block .= '
		<p>OpenSSL is available as a fallback if necessary.</p>';
	}
} else if ($OpenSSL) {
	$Security_Block .= '
		<p class="color-flatui-nephritis"><strong>Your installation will use OpenSSL.</strong></p>
		<p>This is the second best option, maybe try upgrading to PHP 8.x or installing <code>php[version]-mcrypt</code> ?</p>';
} else {
	$Security_Block .= '
		<p class="color-flatui-pomegranate"><strong>Your installation will not work.</strong></p>
		<p>Maybe try upgrading your PHP version, or installing <code>php[version]-mcrypt</code> or <code>openssl</code> ?</p>';
}
$Security_Block .= '
	</div>';







////	GD
$GD_Block = '
	<div class="clear break"></div>
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
		<p class="color-flatui-nephritis">The GD functions are loaded. You can create QR Codes.</p>
		<p>The GD extension is used for generating a secure QR code.</p>';
} else {
	$GD_Block .= '
		<p class="color-flatui-pomegranate">The GD functions are not loaded. You cannot create QR Codes.</p>
		<p>The GD extension is used for generating a secure QR code.</p>
		<p>Try installing <code>php[version]-gd</code> in Ubuntu.</p>';
}
$GD_Block .= '
	</div>';








echo $Security_Block;
echo $RandomBytes_Block;
echo $MCrypt_Block;
echo $OpenSSL_Block;

echo $GD_Block;

echo '
	<div class="clear break"></div>
</body>
</html>';
