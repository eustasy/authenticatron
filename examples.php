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
			<div class="left"></div>
			<div class="right">
				<h3>No cryptographically secure random available.</h3>
				<p>Try installing OpenSSL.</p>
				<p>Proceeding with <code>AUTHENTICATRION23</code>.</p>
			</div>
		<?php
	}

?>

	<?php $New =  Authenticatron_New('Member Name'); ?>
	<div class="break clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Code</p>
		<p>Output</p>
		<p>Information</p>
	</div>
	<div class="right">
		<h3>Authenticatron New</h3>
		<p><code>Authenticatron_New($Member_Name);</code></p>
		<p><pre><?php var_dump($New); ?></pre></p>
		<p>Create a new Secret and get the QR Code all in one.</p>
	</div>

	<?php
		$Code = Authenticatron_Code($Secret);
		$Check =  Authenticatron_Check($Code, $Secret);
	?>
	<div class="break clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Code</p>
		<p>Output</p>
		<p>Information</p>
	</div>
	<div class="right">
		<h3>Check a Code</h3>
		<p><code>Authenticatron_Check($Code, $Secret);</code></p>
		<p><pre><?php var_dump($Check); ?></pre></p>
		<p>This returns a simple boolean value to prevent data-leakage and zero-equivalent values from codes or keys.</p>
	</div>

	<?php $URL = Authenticatron_URL($Member_Name, $Secret); ?>
	<div class="break clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Code</p>
		<p>Output</p>
	</div>
	<div class="right">
		<h3>Authenticatron URL</h3>
		<p><code>Authenticatron_URL($Member_Name, $Secret);</code></p>
		<p><?php echo '<a href="'.$URL.'">'.$URL.'</a></p>'; ?>
		<p>Generates the URL for launching and adding the Secret we made earlier.</p>
		<p>This link won't do anything unless you have a Authentication program on your computer.</p>
	</div>

	<div class="break clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Code</p>
		<p>Output</p>
		<img alt="Google Authenticator Icon" src="assets/google_images-128.png">
	</div>
	<div class="right">
		<h3>Authenticatron QR</h3>
		<p><code>Authenticatron_QR($URL);</code></p>
		<p><strong>Try scanning this QR code with your phone.</strong></p>
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
		<p>This should open an app like <a href="https://m.google.com/authenticator">Google Authenticator</a>.</p>
	</div>

	<div class="break clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Code</p>
		<p>Output</p>
		<p>Information</p>
	</div>
	<div class="right">
		<h3>Authenticatron Secret</h3>
		<p><code>Authenticatron_Secret();</code></p>
		<?php echo '<p><a href="?secret='.$Secret.'">'.$Secret.'</a></p>'; ?>
		<p>Generates a 16-digit secret, never to be shared with anyone except via internal non-cachable QR code.</p>
		<p>Valid characters are Base32, which means A to Z and 2 through 7.</p>
		<p>While most applications will tolerate lowercase, they should really be uppercase.</p>
		<p><strong>Click the link to keep the secret the same when you refresh the page.</strong></p>
	</div>

	<?php $Decoded = Base32_Decode($Secret); ?>
	<div class="break clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Code</p>
		<p>Output</p>
		<p>Information</p>
	</div>
	<div class="right">
		<h3>Base32 Decoded</h3>
		<p><code>Base32_Decode($Secret);</code></p>
		<p><pre><?php echo $Decoded; ?></pre></p>
		<p>You shouldn't need to be using this function, it's just part of the hashing.</p>
		<p>It also isn't decoding, at least not in any real sense.</p>
	</div>

	<?php $Code = Authenticatron_Code($Secret); ?>
	<div class="break clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Code</p>
		<p>Output</p>
		<p>Information</p>
	</div>
	<div class="right">
		<h3>Current Code</h3>
		<p><code>Authenticatron_Code($Secret);</code></p>
		<p><pre><?php echo $Code; ?></pre></p>
		<p>This is the current authentication code.</p>
		<p>Check the Acceptable list to see the two either side.</p>
	</div>

	<?php $Acceptable = Authenticatron_Acceptable($Secret); ?>
	<div class="break clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Code</p>
		<p>Output</p>
	</div>
	<div class="right">
		<h3>Acceptable Codes</h3>
		<p><code>Authenticatron_Acceptable($Secret);</code></p>
		<p><pre><?php var_dump($Acceptable); ?></pre></p>
	</div>
	<div class="clear"></div>
	<div class="left">
		<p>Information</p>
		<img alt="Vault Icon" src="assets/google_authenticator-128.png">
	</div>
	<div class="right">
		<p>This is the array <code>Authenticatron_Check</code> uses to check for valid codes.</p>
		<p><strong>Your phone should produce one of these from the QR code above.</strong></p>
		<p>These are only valid for 30 seconds, so click the Secret link to get a new list.</p>
	</div>

	<div class="break clear"></div>

</body>
</html>