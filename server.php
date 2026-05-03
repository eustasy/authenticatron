<?php

include __DIR__ . '/assets/header.php';

$RandomBytes = false;







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
		<p class="color-flatui-nephritis"><strong>Your installation uses RandomBytes.</strong></p>';
} else {
	$Security_Block .= '
		<p class="color-flatui-pomegranate"><strong>Your installation will not work. PHP &gt;= 8.2 is required.</strong></p>';
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
echo $GD_Block;

include __DIR__ . '/assets/footer.php';
