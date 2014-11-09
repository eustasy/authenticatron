<!DocType html>
<html>
<head>
	<meta charset="ASCII">
	<title>Authenticatron</title>
	<link rel="icon" href="assets/favicon.ico">
	<link rel="stylesheet" media="all" href="assets/normalize.css">
	<style>
	
		body {
			padding: 0 15%;
		}

		code {
			background: #eee;
			border-radius: .3em;
			padding: .3em .5em;
		}
		img {
			max-width: 100%;
		}

		a {
			text-decoration: none;
		}
		p {
			margin: 0 0 1em;
		}

		.break {
			padding-top: 10%;
			width: 100%;
		}
		.clear {
			clear: both;
		}
		.left {
			color: #888;
			float: left;
			text-align: right;
			padding-right: 3%;
			min-height: 1em;
			width: 10%;
		}
		.right {
			float: left;
			max-width: 80%;
		}

	</style>
</head>
<body>

	<div class="break clear"></div>
	<div class="left">
		<img alt="Padlock Icon" src="assets/iconarchive_simiographics_padlock.png">
	</div>
	<div class="right">
		<h1>Authenticatron</h1>
		<p>A simple, procedural PHP script to create Google Authenticator secrets and corresponding QR codes,<br>
		then verify the entered response over a given time variance.<br>
		<a href="http://labs.eustasy.org/authenticatron/example.php">labs.eustasy.org/authenticatron/example.php</a></p>
	</div>

	<?php

		require __DIR__.'/authenticatron.php';

		if ( !empty($_GET['secret']) ) {
			$Secret = $_GET['secret'];
		} else {
			$Secret = Authentricatron_Secret();
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

	<div class="break clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Code</p>
		<p>Output</p>
		<p>Information</p>
	</div>
	<div class="right">
		<h3>Authentricatron Secret</h3>
		<p><code>Authentricatron_Secret();</code></p>
		<?php echo '<p><a href="?secret='.$Secret.'">'.$Secret.'</a></p>'; ?>
		<p>Generates a 16-digit secret, never to be shared with anyone except via internal non-cachable QR code.</p>
		<p>Valid characters are Base32, which means A to Z and 2 through 7.</p>
		<p>While most applications will tolerate lowercase, they should really be uppercase.</p>
		<p><strong>Click the link to keep the secret the same when you refresh the page.</strong></p>
	</div>

	<?php $URL = Authentricatron_URL($Member_Name, $Secret); ?>
	<div class="break clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Code</p>
		<p>Output</p>
	</div>
	<div class="right">
		<h3>Authentricatron URL</h3>
		<p><code>Authentricatron_URL($Member_Name, $Secret);</code></p>
		<p><?php echo '<a href="'.$URL.'">'.$URL.'</a></p>'; ?>
	</div>
	<div class="clear"></div>
	<div class="left">
		<p>Information</p>
		<img alt="Google Authenticator Icon" src="assets/google_images-128.png">
	</div>
	<div class="right">
		<p>Generates the URL for launching and adding the Secret we made earlier.</p>
		<p>This link won't do anything unless you have a Authentication program on your computer.</p>
		<p><strong>Try scanning this QR code with your phone instead.</strong></p>
		<?php
			if (
				extension_loaded('gd') &&
				function_exists('gd_info') &&
				!isset($_GET['googlechart'])
			) {
				echo '<!-- PHPQRCode -->';
				$QR_Base64 = Authentricatron_QR($URL);
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
		<p>This should open an app like <a href="https://m.google.com/authenticator">Google Authenticator</a>.
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

	<?php $Code = Authentricatron_Code($Secret); ?>
	<div class="break clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Code</p>
		<p>Output</p>
		<p>Information</p>
	</div>
	<div class="right">
		<h3>Current Code</h3>
		<p><code>Authentricatron_Code($Secret);</code></p>
		<p><pre><?php echo $Code; ?></pre></p>
		<p>This is the current authentication code.</p>
		<p>Check the Acceptable list to see the two either side.</p>
	</div>

	<?php $Acceptable = Authentricatron_Acceptable($Secret); ?>
	<div class="break clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Code</p>
		<p>Output</p>
	</div>
	<div class="right">
		<h3>Acceptable Codes</h3>
		<p><code>Authentricatron_Acceptable($Secret);</code></p>
		<p><pre><?php var_dump($Acceptable); ?></pre></p>
	</div>
	<div class="clear"></div>
	<div class="left">
		<p>Information</p>
		<img alt="Vault Icon" src="assets/google_authenticator-128.png">
	</div>
	<div class="right">
		<p>This is the array <code>Authentricatron_Check</code> uses to check for valid codes.</p>
		<p><strong>Your phone should produce one of these from the QR code above.</strong></p>
		<p>These are only valid for 30 seconds, so click the Secret link to get a new list.</p>
	</div>

	<?php $Check =  Authentricatron_Check($Code, $Secret); ?>
	<div class="break clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Code</p>
		<p>Output</p>
		<p>Information</p>
	</div>
	<div class="right">
		<h3>Check a Code</h3>
		<p><code>Authentricatron_Check($Code, $Secret);</code></p>
		<p><pre><?php var_dump($Check); ?></pre></p>
		<p>This returns a simple boolean value to prevent data-leakage and zero-equivalent values from codes or keys.</p>
	</div>

	<div class="break clear"></div>

</body>
</html>
