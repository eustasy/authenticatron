<?php

include __DIR__ . '/assets/header.php';

require_once __DIR__ . '/authenticatron.php';
$auth = new Authenticatron('Authenticatron Documentation Page');

if (!empty($_GET['secret'])) {
	$secret = $_GET['secret'];
} else {
	$secret = $auth->makeSecret();
}

if (!$secret) {
	$secret = 'AUTHENTICATRON23';
?>
	<div class="break clear"></div>
	<hr>
	<div class="break clear"></div>

	<div class="left">
		<img alt="lifefloat" src="assets/google_help-128.png">
	</div>
	<div class="right">
		<h3 class="color-flatui-pomegranate">Warning: No cryptographically secure random available.</h3>
		<p>Try upgrading PHP or installing OpenSSL.</p>
		<p>Proceeding with <code>AUTHENTICATRON23</code>.</p>
	</div>

	<div class="break clear"></div>
	<hr>
	<div class="break clear"></div>
<?php
} else {
?>
	<div class="break clear"></div>
<?php
}

?>




<div class="right fake-left">
	<h3>Initialize Authenticatron</h3>
</div>
<div class="clear"></div>
<div class="left">
	<p>Information</p>
</div>
<div class="right">
	<p>Get ready to use Authenticatron.</p>
</div>
<div class="clear"></div>
<div class="left">
	<p>Code</p>
</div>
<div class="right">
	<p>
	<pre>$auth = Authenticatron(
		string $issuerDefault = 'Example Site',
		string $phpQrCode = __DIR__ . '/_libs/phpqrcode_2010100721_1.1.4.php'
	): class</pre>
	</p>
</div>
<div class="clear"></div>
<div class="left">
	<p>Input</p>
</div>
<div class="right">
	<p><code>$issuerDefault</code> is a string containing the default name you with to use to identify your app.</p>
	<p><code>$phpQrCode</code> is an <span class="color-flatui-belize-hole">optional</span> string containing the location of the PHP QR Code Library, if diferent from the default.</p>
</div>
<div class="break clear"></div>




<div class="right fake-left">
	<h3>Authenticatron New</h3>
</div>
<div class="clear"></div>
<div class="left">
	<p>Information</p>
</div>
<div class="right">
	<p>Create a new Secret and get the QR Code all in one.</p>
</div>
<div class="clear"></div>
<div class="left">
	<p>Code</p>
</div>
<div class="right">
	<p><code>$auth->new(string $accountName): array</code></p>
</div>
<div class="clear"></div>
<div class="left">
	<p>Input</p>
</div>
<div class="right">
	<p><code>$accountName</code> is a string containing the data your member will identify with.</p>
</div>
<div class="clear"></div>
<div class="left">
	<p>Output</p>
</div>
<div class="right">
	<p>Outputs an array, where <code>Secret</code> is the Secret for the member, <code>URL</code> is an OTPAuth URL, and <code>QR</code> is the Data64 URI for the QR code.</p>
	<pre><?php
			$new = $auth->new('Member Name');
			var_dump($new);
			?></pre>
</div>
<div class="break clear"></div>



<div class="right fake-left">
	<h3>Authenticatron Check</h3>
</div>
<div class="clear"></div>
<div class="left">
	<p>Information</p>
</div>
<div class="right">
	<p>This returns a simple boolean value to prevent data-leakage and zero-equivalent values from codes or keys.</p>
</div>
<div class="clear"></div>
<div class="left">
	<p>Code</p>
</div>
<div class="right">
	<p><code>$auth->checkCode(string $code, string $secret, int $variance = 2): bool</code></p>
</div>
<div class="clear"></div>
<div class="left">
	<p>Input</p>
</div>
<div class="right">
	<p><code>$code</code> is what the user enters to authenticate. A 6 digit string, usually numeric, but not necessarily an integer.</p>
	<p><code>$secret</code> is the first result from <code>Authenticatron_Check</code>, that you securely stored for later.</p>
	<p><code>$variance</code> is an integer indicating the adjustment of codes with a 30 second value. Defaults to 2 either side, or 1 minute.</p>
</div>
<div class="clear"></div>
<div class="left">
	<p>Output</p>
</div>
<div class="right">
	<p>Outputs a boolean value, true or false.</p>
	<pre><?php
			$code = $auth->getCode($secret);
			$check = $auth->checkCode($code, $secret);
			var_dump($check);
			?></pre>
</div>



<div class="break clear"></div>
<hr>
<div class="break clear"></div>

<div class="left">
	<img alt="lifefloat" src="assets/google_help-128.png">
</div>
<div class="right">
	<h3 class="color-flatui-pomegranate">Warning: The functions below are for advanced users only.</h3>
	<p>You should only need the two functions above this point to implement two-factor authentication.</p>
	<p>Functions listed below this point should not need to be used in most production-ready environments.</p>
</div>

<div class="break clear"></div>
<hr>
<div class="break clear"></div>



<div class="right fake-left">
	<h3>Authenticatron Secret</h3>
</div>
<div class="clear"></div>
<div class="left">
	<p>Information</p>
</div>
<div class="right">
	<p>Generates a 16-digit secret, never to be shared with anyone except via internal non-cachable QR code.</p>
	<p>Generated using RandomBytes if it is available, falling back to OpenSSL if it is secure.</p>
	<?php
	$RandomBytes = false;
	$OpenSSL = false;
	if (function_exists('random_bytes')) {
		$RandomBytes = true;
		echo '
					<p class="color-flatui-nephritis">RandomBytes is available.</p>';
	} else {
		echo '
					<p class="color-flatui-pomegranate">RandomBytes is not available.</p>';
	}
	if (function_exists('openssl_random_pseudo_bytes')) {
		$Random = openssl_random_pseudo_bytes(1, $Strong);
		if ($Strong) {
			$OpenSSL = true;
			echo '
						<p class="color-flatui-nephritis">OpenSSL is installed, and secure.</p>';
		} else {
			echo '
						<p class="color-flatui-pomegranate">OpenSSL is installed, but not secure.</p>';
		}
	} else {
		echo '
					<p class="color-flatui-pomegranate">OpenSSL is not installed.</p>';
	}
	if ($RandomBytes) {
		echo '
					<p class="color-flatui-nephritis"><strong>Your installation will use RandomBytes.</strong></p>';
	} elseif ($OpenSSL) {
		echo '
					<p class="color-flatui-nephritis"><strong>Your installation will use OpenSSL.</strong></p>';
	} else {
		echo '
					<p class="color-flatui-pomegranate"><strong>Your installation will not work.</strong></p>';
	}
	?>
</div>
<div class="clear"></div>
<div class="left">
	<p>Code</p>
</div>
<div class="right">
	<p><code>$auth->makeSecret(int $length = 16): ?string</code></p>
</div>
<div class="clear"></div>
<div class="left">
	<p>Input</p>
</div>
<div class="right">
	<p><code>$length</code> should be an integer, longer than 16. Usually left to default.</p>
</div>
<div class="clear"></div>
<div class="left">
	<p>Output</p>
</div>
<div class="right">
	<p>Returns a <code>$length</code> long string with 32bit only Characters, or <code>null</code> on failure (usually due to a lack of security).</p>
	<p><strong>Click the link to keep the secret the same when you refresh the page.</strong></p>
	<pre><?php
			echo '<p><a href="?secret=' . $secret . '">' . $secret . '</a></p>';
			?></pre>
</div>
<div class="break clear"></div>



<div class="right fake-left">
	<h3>Authenticatron URL</h3>
</div>
<div class="clear"></div>
<div class="left">
	<p>Information</p>
</div>
<div class="right">
	<p>Generates the URL for launching and adding the Secret we made earlier.</p>
	<p>This link won't do anything unless you have a Authentication program on your computer.</p>
</div>
<div class="clear"></div>
<div class="left">
	<p>Code</p>
</div>
<div class="right">
	<p><code>$auth->getUrl(string $accountName, string $secret, string $issuer = null): string</code></p>
</div>
<div class="clear"></div>
<div class="left">
	<p>Input</p>
</div>
<div class="right">
	<p>All parameters should be strings, with the optional issuer defaulting to the configured value if not passed.</p>
</div>
<div class="clear"></div>
<div class="left">
	<p>Output</p>
</div>
<div class="right">
	<p>Outputs an OTPAuth URL that gives people their Secret along with a passed Member Name and an optional Issuer.</p>
	<pre><?php
			$url = $auth->getUrl('Member Name', $secret);
			echo '<a href="' . $url . '">' . $url . '</a>';
			?></pre>
</div>
<div class="break clear"></div>



<div class="right fake-left">
	<h3>Authenticatron QR</h3>
</div>
<div class="clear"></div>
<div class="left">
	<p>Information</p>
</div>
<div class="right">
	<p>Outputs a QR Code in Data64 for direct embedding from a given URL.</p>
	<?php
	if (
		extension_loaded('gd') &&
		function_exists('gd_info')
	) {
		echo '
					<p class="color-flatui-nephritis">The GD functions are loaded.</p>';
	} else {
		echo '
					<p class="color-flatui-pomegranate">The GD functions are not loaded.</p>
					<p>Try installing <code>php[version]-gd</code> in Ubuntu.</p>';
	}
	?>
</div>
<div class="clear"></div>
<div class="left">
	<p>Code</p>
</div>
<div class="right">
	<p><code>generateQrCode(string $URL, int $Size = 4, int $Margin = 0, string $Level = 'M'): ?string</code></p>
</div>
<div class="clear"></div>
<div class="left">
	<p>Input</p>
</div>
<div class="right">
	<p><code>$URL</code> is a valid OTPAuth URL in string form.</p>
	<p><code>$Size</code> is a non-zero integer, defaults to 4.</p>
	<p><code>$Margin</code> is an integer, defaults to 0.</p>
	<p><code>$Level</code> is a string, defaults to 'M'. It defines the error correction level.</p>
	<ul>
		<li>Level L (Low) &mdash; 7% of codewords can be restored.</li>
		<li>Level M (Medium) &mdash; 15% of codewords can be restored.</li>
		<li>Level Q (Quartile) &mdash; 25% of codewords can be restored.</li>
		<li>Level H (High) &mdash; 30% of codewords can be restored.</li>
	</ul>
</div>
<div class="clear"></div>
<div class="left">
	<p>Output</p>
	<img alt="Google Camera Icon" src="assets/google_images-128.png">
</div>
<div class="right">
	<p>Outputs a QR Code image in 64bit data-URI form.</p>
	<?php
	if (
		extension_loaded('gd') &&
		function_exists('gd_info') &&
		!isset($_GET['googlechart'])
	) {
		echo '<!-- PHPQRCode -->';
		$URL = $auth->getUrl('John Smith', $secret);
		$QR_Base64 = $auth->generateQrCode($URL);
		echo '<p><img src="' . $QR_Base64 . '"></p>';
	} else {
		echo '<!-- Google Chart -->';
		if (!extension_loaded('gd') || !function_exists('gd_info')) {
			echo '<p>The required image functions don\'t seem to exist, so we\'re falling back to Google Charts.</p>';
			echo '<p>This isn\'t secure, and you should install <code>php[version]-gd</code> to fix it.</p>';
		}
		if (isset($_GET['googlechart'])) {
			echo '<p>You asked for a Google Chart instead. This isn\'t secure, but here you go.</p>';
		}
		echo '<p><img src="https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=' . urlencode($URL) . '"></p>';
	}
	?>
	<p><strong>Try scanning this QR code with your phone.</strong></p>
	<p>This should open an app like <a href="https://m.google.com/authenticator">Google Authenticator</a>.</p>
</div>
<div class="break clear"></div>



<div class="right fake-left">
	<h3>Authenticatron Code</h3>
</div>
<div class="clear"></div>
<div class="left">
	<p>Information</p>
</div>
<div class="right">
	<p>This is the current authentication code.</p>
	<p>Check the Acceptable list to see the two either side.</p>
</div>
<div class="clear"></div>
<div class="left">
	<p>Code</p>
</div>
<div class="right">
	<p><code>getCode(string $secret, int $timestamp = null, int $codeLength = 6): string</code></p>
</div>
<div class="clear"></div>
<div class="left">
	<p>Input</p>
</div>
<div class="right">
	<p><code>$secret</code> is a valid Base32 Secret in string form.</p>
	<p><code>$timestamp</code> is a unix timestamp, defaults to false to use the current timestamp.</p>
	<p><code>$codeLength</code> is a non-zero integer, the desired length of the generated code. Defaults to 6.</p>
</div>
<div class="clear"></div>
<div class="left">
	<p>Output</p>
</div>
<div class="right">
	<p>Outputs the calculated code for the current or provided timestamp.</p>
	<pre><?php
			$code = $auth->getCode($secret);
			var_dump($code);
			?></pre>
</div>
<div class="break clear"></div>



<div class="right fake-left">
	<h3>Authenticatron Acceptable</h3>
</div>
<div class="clear"></div>
<div class="left">
	<p>Information</p>
</div>
<div class="right">
	<p>This is the array <code>Authenticatron_Check</code> uses to check for valid codes.</p>
</div>
<div class="clear"></div>
<div class="left">
	<p>Code</p>
</div>
<div class="right">
	<p><code>$auth->getCodesInRange(string $secret, int $variance = 2): array</code></p>
</div>
<div class="clear"></div>
<div class="left">
	<p>Input</p>
</div>
<div class="right">
	<p><code>$secret</code> is a valid Base32 Secret in string form.</p>
	<p><code>$variance</code> is an integer indicating the adjustment of codes with a 30 second value. Defaults to 2, or 1 minute.</p>
</div>
<div class="clear"></div>
<div class="left">
	<p>Output</p>
	<img alt="Google Authenticator Icon" src="assets/google_authenticator-128.png">
</div>
<div class="right">
	<p>Outputs the calculated code for the current or provided timestamp.</p>
	<p>Note the indexes, which can be used to determine the time difference, and perhaps warn users on the outer bounds.</p>
	<p>Code generation is expensive, so avoid generating any you don't want to check against later.</p>
	<pre><?php
			$codes = $auth->getCodesInRange($secret);
			var_dump($codes);
			?></pre>
	<p><strong>Your phone should produce one of these from the QR code above.</strong></p>
	<p>These are only valid for 30 seconds, so click the Secret link to get a new list.</p>
</div>

<div class="break clear"></div>
<hr>
<div class="break clear"></div>

<div class="left" id="glossary">
	<img alt="lifefloat" src="assets/google_help-128.png">
</div>
<div class="right">
	<h3>Glossary</h3>
	<p><strong>Base32</strong> is an encoding, effectively an alphabet, that computers use made up of 32 characters.</p>
	<p><strong>Base32 Characters</strong> are A to Z (upper-case only), and 2 to 7.</p>
	<p><strong>HOTP</strong> is HMAC-based one-time password algorithm. HOTP Algorithms generate passwords from a given secret that do not expose the secret over time.</p>
	<p><strong>OATH</strong> is the short name for the <a href="https://openauthentication.org/">Initiative for Open Authentication</a>, an organisation dedicated to keeping secure authentication free.</p>
	<p><strong>OTP Auth</strong> stands for one-time password authentication.</p>
	<p><strong>QR Code</strong> (Quick Response Code) is a type of 2D matrix barcodes with built in redundancy, commonly used to scan links into mobile phones through cameras.</p>
	<p><strong>TOTP</strong> abbreviates Time-based One-time Password Algorithm. TOTP Algorithms generate passwords from a given secret that are only valid over a very specific time period.</p>
</div>

<div class="break clear"></div>

</body>

</html>