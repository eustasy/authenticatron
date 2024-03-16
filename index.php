<?php

require_once __DIR__ . '/vendor/autoload.php';
use eustasy\Authenticatron;

include __DIR__ . '/assets/header.php';

$accountName = 'John Smith';
$issuer = 'Authenticatron Example Page';
if (!empty($_POST['secondfactor_secret'])) {
	$secret = $_POST['secondfactor_secret'];
} elseif (!empty($_GET['secret'])) {
	$secret = $_GET['secret'];
}

?>

<div class="break clear"></div>

<div class="half half-left">
	<div class="right fake-left">
		<h2 class="text-center">Step 1.</h2>
		<p class="subtitle"><code>Authenticatron::new</code> to create a new secret for a member, and fetch a secure image for scanning.</p>
	</div>
	<div class="break clear"></div>
	<div class="left">
		<p>Code</p>
	</div>
	<div class="right">
		<p><code>Authenticatron::new($accountName, $issuer)</code></p>
	</div>
	<div class="break clear"></div>
	<div class="left">
		<p>Input</p>
	</div>
	<div class="right">
		<p><code>$accountName</code> is a string containing your members username or nice-name, perferably something unique and quickly identifiable.</p>
		<p><code>$issuer</code> is a string containing the name of your app or site.</p>
	</div>
	<div class="break clear"></div>
	<div class="left">
		<p>Output</p>
	</div>
	<div class="right">
		<p>Outputs an array, where <code>Secret</code> is the Secret for the member, <code>URL</code> is an OTPAuth URL, and <code>QR</code> is the Data64 URI for the QR code.</p>
		<pre><?php
		if (!empty($secret)) {
			$secondAuth['Secret'] = $secret;
			$secondAuth['URL'] = Authenticatron::getUrl($accountName, $secret, $issuer);
			$secondAuth['QR'] = Authenticatron::generateQrCode($secondAuth['URL']);
		} else {
			$secondAuth = Authenticatron::new($accountName, $issuer);
			$secret = $secondAuth['Secret'];
		}
		var_dump($secondAuth);
		?></pre>
	</div>
	<div class="break clear"></div>
	<div class="left">
		<p>Handling</p>
	</div>
	<div class="right">
		<p>You'll want to store <code>['Secret']</code> with the member, but make sure you get them to confirm a code before enforcing it, or it might not have worked and they would be locked out of their account. Make sure that this is as protected as a password hash.</p>
		<br>
		<p><code>['QR']</code> is the Data64 URI for the QR code. You can simply echo it into an <code>img</code> element like this:</p>
		<pre>&lt;img src="&lt;?php echo $secondAuth['QR']; ?&gt;" alt="Second Factor Authentication Code"&gt;</pre>
	</div>
	<div class="break clear"></div>
	<div class="left">
		<p>Example</p>
		<img alt="Google Camera Icon" src="assets/google_images-128.png">
	</div>
	<div class="right">
		<p>Try scanning this into an app like <a href="https://m.google.com/authenticator">Google Authenticator</a>. You should see a code and a countdown clock until it changes.</p>
		<img alt="QR Code for 2nd factor authentication" src="<?php echo $secondAuth['QR']; ?>">
	</div>
</div>

<div class="break-small clear-small"></div>

<div class="half half-right">
	<div class="right fake-left">
		<h2 class="text-center">Step 2.</h2>
		<p class="subtitle">Use <code>Authenticatron::checkCode</code> to confirm the setup and check time-unique codes at every login.</p>
	</div>
	<div class="break clear"></div>
	<div class="left">
		<p>Code</p>
	</div>
	<div class="right">
		<p><code>Authenticatron::checkCode($code, $secret)</code></p>
	</div>
	<div class="break clear"></div>
	<div class="left">
		<p>Input</p>
	</div>
	<div class="right">
		<p><code>$code</code> is the user input, the code that is generated on their device for authentication. Should be numeric-only in most cases, alpha-numeric if you change some settings.</p>
		<p><code>$secret</code> is the secret the member scanned that you securely stored for later.</p>
		<p><code>$variance</code> is an optional integer indicating the adjustment of codes with a 30 second value. Defaults to 2 either side, or 1 minute.</p>
	</div>
	<div class="break clear"></div>
	<div class="left">
		<p>Output</p>
	</div>
	<div class="right">
		<p>Outputs a boolean value, <code>true</code> if the entered code is within allowed range, <code>false</code> if not.</p>
		<pre><?php
				$code = Authenticatron::getCode($secret);
				$check = Authenticatron::checkCode($code, $secret);
				var_dump($check);
				?></pre>
	</div>
	<div class="break clear"></div>
	<div class="left">
		<p>Handling</p>
	</div>
	<div class="right">
		<p>You only need to check an input is alpha-numeric, and maybe 6 characters long before checking it against a retreieved secret.</p>
		<pre>$secret = ...;
if (
	strlen($_POST['secondfactor_code']) == 6 &&
	ctype_alnum($_POST['secondfactor_code'])
) {
	if ( Authenticatron::checkCode($_POST['secondfactor_code'], $secret) ) {
		// Authenticated, log in...
	} else {
		// Incorrect code
	}
} else {
	// Invalid entry
}</pre>
	</div>
	<div class="break clear"></div>
	<div class="left" id="example">
		<p>Example</p>
		<img alt="Google Authenticator Icon" src="assets/google_authenticator_v3_480s.png">
	</div>
	<div class="right">
		<?php
		if (!empty($_POST['secondfactor_code'])) {
			if (
				strlen($_POST['secondfactor_code']) == 6 &&
				ctype_alnum($_POST['secondfactor_code'])
			) {
				if (Authenticatron::checkCode($_POST['secondfactor_code'], $secret)) {
					echo '<p class="color-flatui-nephritis">Correct Code: The code you entered was correct, congratulations!</h3>';
				} else {
					echo '<p class="color-flatui-pomegranate">Incorrect Code: The code you entered was not valid at this time. Codes are valid for 30 seconds.</p>';
				}
			} else {
				echo '<p class="color-flatui-pomegranate">Invalid Entry: The code you entered was not 6 characters long, and alphanumeric.</p>';
			}
			echo '<div class="break clear"></div>';
		}
		?>
		<p>Enter the code that your device generates after scanning the image to from Step 1.</p>
		<form action="#example" method="POST">
			<!-- WARNING: You should never reveal real secrets like this. -->
			<input name="secondfactor_secret" type="hidden" value="<?php echo $secret; ?>">
			<label for="secondfactor_code">2fa Code</label>
			<input name="secondfactor_code" id="secondfactor_code" type="text" maxlength="6">
			<input type="submit" value="Check">
		</form>
	</div>
</div>

<?php

include __DIR__ . '/assets/footer.php';
