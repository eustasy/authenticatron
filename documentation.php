<?php

	include __DIR__.'/assets/header.php';

	require_once __DIR__.'/authenticatron.php';

	if ( !empty($_GET['secret']) ) {
		$Secret = $_GET['secret'];
	} else {
		$Secret = Authenticatron_Secret();
	}

	if ( !$Secret ) {
		$Secret = 'AUTHENTICATRION23';
		?>
	<div class="break clear"></div>
	<hr>
	<div class="break clear"></div>

	<div class="left">
		<img alt="lifefloat" src="assets/google_help-128.png">
	</div>
	<div class="right">
		<h3 class="color-pomegranate">Warning: No cryptographically secure random available.</h3>
		<p>Try installing MCrypt or OpenSSL.</p>
		<p>Proceeding with <code>AUTHENTICATRION23</code>.</p>
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
		<p><code>Authenticatron_New($Member_Name)</code></p>
	</div>
	<div class="clear"></div>
	<div class="left">
		<p>Input</p>
	</div>
	<div class="right">
		<p><code>$Member_Name</code> is a string containing the data your member will identify with.</p>
	</div>
	<div class="clear"></div>
	<div class="left">
		<p>Output</p>
	</div>
	<div class="right">
		<p>Outputs an array, where <code>Secret</code> is the Secret for the member, <code>URL</code> is an OTPAuth URL, and <code>QR</code> is the Data64 URI for the QR code.</p>
		<pre><?php
			$New =  Authenticatron_New('Member Name');
			var_dump($New);
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
		<p><code>Authenticatron_Check($Code, $Secret)</code></p>
		<p><code>Authenticatron_Check($Code, $Secret, $Variance = false)</code></p>
	</div>
	<div class="clear"></div>
	<div class="left">
		<p>Input</p>
	</div>
	<div class="right">
		<p><code>$Code</code> is what the user enters to authenticate. A 6 digit string, usually numeric, but not necessarily an integer.</p>
		<p><code>$Secret</code> is the first result from <code>Authenticatron_Check</code>, that you securely stored for later.</p>
		<p><code>$Variance</code> is an integer indicating the adjustment of codes with a 30 second value. Defaults to 2 either side, or 1 minute.</p>
	</div>
	<div class="clear"></div>
	<div class="left">
		<p>Output</p>
	</div>
	<div class="right">
		<p>Outputs a boolean value, true or false.</p>
		<pre><?php
			$Code = Authenticatron_Code($Secret);
			$Check = Authenticatron_Check($Code, $Secret);
			var_dump($Check);
		?></pre>
	</div>

	<div class="break clear"></div>
	<hr>
	<div class="break clear"></div>

	<div class="left">
		<img alt="lifefloat" src="assets/google_help-128.png">
	</div>
	<div class="right">
		<h3 class="color-pomegranate">Warning: The functions below are for advanced users only.</h3>
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
		<p>Generated using MCrypt if it is available, falling back to OpenSSL if it is secure.</p>
		<?php
			$MCrypt = false;
			$OpenSSL = false;
			if ( function_exists('mcrypt_create_iv') ) {
				$MCrypt = true;
				echo '
					<p class="color-nephritis">MCrypt is installed.</p>';
			} else {
				echo '
					<p class="color-pomegranate">MCrypt is not installed.</p>';
			}
			if ( function_exists('openssl_random_pseudo_bytes') ) {
				$Random = openssl_random_pseudo_bytes(1, $Strong);
				if ( $Strong ) {
					$OpenSSL = true;
					echo '
						<p class="color-nephritis">OpenSSL is installed, and secure.</p>';
				} else {
					echo '
						<p class="color-pomegranate">OpenSSL is installed, but not secure.</p>';
				}
			} else {
				echo '
					<p class="color-pomegranate">OpenSSL is not installed.</p>';
			}
			if ( $MCrypt ) {
				echo '
					<p class="color-nephritis"><strong>Your installation will use MCrypt.</strong></p>';
			} else if ( $OpenSSL ) {
				echo '
					<p class="color-nephritis"><strong>Your installation will use OpenSSL.</strong></p>';
			} else {
				echo '
					<p class="color-pomegranate"><strong>Your installation will not work.</strong></p>';
			}
		?>
	</div>
	<div class="clear"></div>
	<div class="left">
		<p>Code</p>
	</div>
	<div class="right">
		<p><code>Authenticatron_Secret()</code></p>
		<p><code>Authenticatron_Secret($Length = 16)</code></p>
	</div>
	<div class="clear"></div>
	<div class="left">
		<p>Input</p>
	</div>
	<div class="right">
		<p><code>$Length</code> should be an integer, longer than 16. Usually left to default.</p>
	</div>
	<div class="clear"></div>
	<div class="left">
		<p>Output</p>
	</div>
	<div class="right">
		<p>Returns a <code>$Length</code> long string with 32bit only Characters, or <code>false</code> on failure (usually due to a lack of security).</p>
		<p><strong>Click the link to keep the secret the same when you refresh the page.</strong></p>
		<pre><?php
			echo '<p><a href="?secret='.$Secret.'">'.$Secret.'</a></p>';
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
		<p><code>Authenticatron_URL($Member_Name, $Secret)</code></p>
		<p><code>Authenticatron_URL($Member_Name, $Secret, $Issuer = DEFAULT)</code></p>
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
			$URL = Authenticatron_URL('Member Name', $Secret);
			echo '<a href="'.$URL.'">'.$URL.'</a>';
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
					<p class="color-nephritis">The GD functions are loaded.</p>';
			} else {
				echo '
					<p class="color-pomegranate">The GD functions are not loaded.</p>
					<p>Try installing <code>php5-gd</code> in Ubuntu.</p>';
			}
		?>
	</div>
	<div class="clear"></div>
	<div class="left">
		<p>Code</p>
	</div>
	<div class="right">
		<p><code>Authenticatron_QR($URL)</code></p>
		<p><code>Authenticatron_QR($URL, $Size = 4)</code></p>
		<p><code>Authenticatron_QR($URL, $Size = 4, $Margin = 0)</code></p>
		<p><code>Authenticatron_QR($URL, $Size = 4, $Margin = 0, $Level = 'M')</code></p>
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
		<img alt="Google Authenticator Icon" src="assets/google_images-128.png">
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
				$QR_Base64 = Authenticatron_QR($URL);
				echo '<p><img src="'.$QR_Base64.'"></p>';
			} else {
				echo '<!-- Google Chart -->';
				if ( !extension_loaded('gd') || !function_exists('gd_info') ) {
					echo '<p>The required image functions don\'t seem to exist, so we\'re falling back to Google Charts.</p>';
					echo '<p>This isn\'t secure, and you should install <code>php5-gd</code> to fix it.</p>';
				}
				if ( isset($_GET['googlechart']) ) {
					echo '<p>You asked for a Google Chart instead. This isn\'t secure, but here you go.</p>';
				}
				echo '<p><img src="https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl='.urlencode($URL).'"></p>';
			}
		?>
		<p><strong>Try scanning this QR code with your phone.</strong></p>
		<p>This should open an app like <a href="https://m.google.com/authenticator">Google Authenticator</a>.</p>
	</div>
	<div class="break clear"></div>



	<div class="right fake-left">
		<h3>Base32 Decode</h3>
	</div>
	<div class="clear"></div>
	<div class="left">
		<p>Information</p>
	</div>
	<div class="right">
		<p>You shouldn't need to be using this function, it's just part of the hashing.</p>
		<p>It also isn't decoding, at least not in any real sense.</p>
	</div>
	<div class="clear"></div>
	<div class="left">
		<p>Code</p>
	</div>
	<div class="right">
		<p><code>Base32_Decode($Secret)</code></p>
	</div>
	<div class="clear"></div>
	<div class="left">
		<p>Input</p>
	</div>
	<div class="right">
		<p>The lone parameter is a string value that expects a valid Base32 secret.</p>
	</div>
	<div class="clear"></div>
	<div class="left">
		<p>Output</p>
	</div>
	<div class="right">
		<p>Outputs a string of the numeric representation of the Secret as ASCII text.</p>
		<pre><?php
			$Decode = Base32_Decode($Secret);
			var_dump($Decode);
		?></pre>
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
		<p><code>Authenticatron_Code($Secret)</code></p>
		<p><code>Authenticatron_Code($Secret, $Timestamp = false)</code></p>
		<p><code>Authenticatron_Code($Secret, $Timestamp = false, $CodeLength = 6)</code></p>
	</div>
	<div class="clear"></div>
	<div class="left">
		<p>Input</p>
	</div>
	<div class="right">
		<p><code>$Secret</code> is a valid Base32 Secret in string form.</p>
		<p><code>$Timestamp</code> is a unix timestamp, defaults to false to use the current timestamp.</p>
		<p><code>$CodeLength</code> is a non-zero integer, the desired length of the generated code. Defaults to 6.</p>
	</div>
	<div class="clear"></div>
	<div class="left">
		<p>Output</p>
	</div>
	<div class="right">
		<p>Outputs the calculated code for the current or provided timestamp.</p>
		<pre><?php
			$Code = Authenticatron_Code($Secret);
			var_dump($Code);
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
		<p><code>Authenticatron_Acceptable($Secret)</code></p>
		<p><code>Authenticatron_Acceptable($Secret, $Variance = 2)</code></p>
	</div>
	<div class="clear"></div>
	<div class="left">
		<p>Input</p>
	</div>
	<div class="right">
		<p><code>$Secret</code> is a valid Base32 Secret in string form.</p>
		<p><code>$Variance</code> is an integer indicating the adjustment of codes with a 30 second value. Defaults to 2, or 1 minute.</p>
	</div>
	<div class="clear"></div>
	<div class="left">
		<p>Output</p>
		<img alt="Vault Icon" src="assets/google_authenticator-128.png">
	</div>
	<div class="right">
		<p>Outputs the calculated code for the current or provided timestamp.</p>
		<p>Note the indexes, which can be used to determine the time difference, and perhaps warn users on the outer bounds.</p>
		<p>Code generation is expensive, so avoid generating any you don't want to check against later.</p>
		<pre><?php
			$Codes = Authenticatron_Acceptable($Secret);
			var_dump($Codes);
		?></pre>
		<p><strong>Your phone should produce one of these from the QR code above.</strong></p>
		<p>These are only valid for 30 seconds, so click the Secret link to get a new list.</p>
	</div>

	<div class="break clear"></div>
	<hr>
	<div class="break clear"></div>

	<div class="left">
		<img alt="lifefloat" src="assets/google_help-128.png">
	</div>
	<div class="right">
		<h3>Glossary</h3>
		<p><strong>Base32</strong> is an encoding, effectively an alphabet, that computers use made up of 32 characters.</p>
		<p><strong>Base32 Characters</strong> are A to Z (upper-case only), and 2 to 7.</p>
	</div>

	<div class="break clear"></div>

</body>
</html>
