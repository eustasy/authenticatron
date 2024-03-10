<?php

include __DIR__ . '/assets/header.php';

require_once __DIR__ . '/authenticatron.php';
$auth = new Authenticatron('Authenticatron Example Page');
$accountName = 'John Smith';
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
		<p class="subtitle">Use <code>$auth = new Authenticatron()</code> to initialize an instance and <code>$auth->new</code> to create a new secret for a member, and fetch a secure image for scanning.</p>
	</div>
	<div class="break clear"></div>
	<div class="left">
		<p>Code</p>
	</div>
	<div class="right">
		<p>
		<pre>$auth = new Authenticatron()
$auth->new($accountName)</pre>
		</p>
	</div>
	<div class="break clear"></div>
	<div class="left">
		<p>Input</p>
	</div>
	<div class="right">
		<p><code>$accountName</code> is a string containing your members username or nice-name, perferably something unique and quickly identifiable.</p>
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
			$secondAuth['URL'] = $auth->getUrl($accountName, $secret);
			$secondAuth['QR'] = $auth->generateQrCode($secondAuth['URL']);
		} else {
			$secondAuth = $auth->new($accountName);
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
		<img src="<?php echo $secondAuth['QR']; ?>" alt="Second Factor Authentication Code">
	</div>
</div>

<div class="break-small clear-small"></div>

<div class="half half-right">
	<div class="right fake-left">
		<h2 class="text-center">Step 2.</h2>
		<p class="subtitle">Use <code>$auth->checkCode</code> to confirm the setup and check time-unique codes at every login.</p>
	</div>
	<div class="break clear"></div>
	<div class="left">
		<p>Code</p>
	</div>
	<div class="right">
		<p><code>$auth->checkCode($Code, $secret)</code></p>
	</div>
	<div class="break clear"></div>
	<div class="left">
		<p>Input</p>
	</div>
	<div class="right">
		<p><code>$Code</code> is the user input, the code that is generated on their device for authentication. Should be numeric-only in most cases, alpha-numeric if you change some settings.</p>
		<p><code>$secret</code> is the secret the member scanned that you securely stored for later.</p>
		<p><code>$Variance</code> is an optional integer indicating the adjustment of codes with a 30 second value. Defaults to 2 either side, or 1 minute.</p>
	</div>
	<div class="break clear"></div>
	<div class="left">
		<p>Output</p>
	</div>
	<div class="right">
		<p>Outputs a boolean value, <code>true</code> if the entered code is within allowed range, <code>false</code> if not.</p>
		<pre><?php
				$code = $auth->getCode($secret);
				$check = $auth->checkCode($code, $secret);
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
	if ( $auth->checkCode($_POST['secondfactor_code'], $secret) ) {
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
		<img alt="Google Authenticator Icon" src="assets/google_authenticator-128.png">
	</div>
	<div class="right">
		<?php
		if (!empty($_POST['secondfactor_code'])) {
			if (
				strlen($_POST['secondfactor_code']) == 6 &&
				ctype_alnum($_POST['secondfactor_code'])
			) {
				if ($auth->checkCode($_POST['secondfactor_code'], $secret)) {
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
			<input name="secondfactor_code" type="text" maxlength="6">
			<input type="submit" value="Check">
		</form>
	</div>
</div>

<div class="break clear"></div>
<hr>
<div class="break clear"></div>

<div class="left">
	<img alt="lifefloat" src="assets/google_help-128.png">
</div>
<div class="right">
	<h3 class="color-flatui-pomegranate">Further Reading</h3>
	<p>Visit our <a href="documentation.php">documentation</a> for a more thorough description of the options and functions available to you.</p>
	<p>Take a look at the <a href="documentation.php#glossary">glossary</a> if there are any terms you don't understand.</p>
	<p>The <a href="server.php">server</a> page can be used if this script is installed on your server to check for requirements.</p>
	<p>If you're ready to rock, check out the <a href="https://github.com/eustasy/authenticatron">source</a>!</p>
</div>
<div class="break clear"></div>
<div class="left">
	<img src="assets/Open_Source_Initiative_keyhole.svg" alt="Open Source Initiative Keyhole">
</div>
<div class="right">
	<p>This work is predominantly MIT licensed. See the <a href="https://github.com/eustasy/authenticatron/blob/mainCENSE.md">LICENSE.md file</a> for more information.</p>
	<p>
		<a href="https://codeclimate.com/github/eustasy/authenticatron"><img src="https://codeclimate.com/github/eustasy/authenticatron/badges/gpa.svg"></a> &emsp;
		<a href="https://github.com/eustasy/authenticatron/actions/workflows/normal.yml"><img src="https://github.com/eustasy/authenticatron/actions/workflows/normal.yml/badge.svg"></a>
	</p>
	</p>
</div>

<div class="clear break"></div>
</body>

</html>
