<!DocType html>
<html>
<head>
	<meta charset="ASCII">
	<title>Authenticatron</title>
</head>
<body>

	<h1>Authenticatron</h1>

	<?php

		require __DIR__.'/authenticatron.php';

		if ( !empty($_GET['secret']) ) $Secret = $_GET['secret'];
		else $Secret = Authenticator_Secret();
		if ( !$Secret ) echo '<h3>No cryptographically secure random available.</h3>';
		else {

			echo '<h3>Authenticator Secret: <a href="?secret='.$Secret.'">'.$Secret.'</a></h3>';
			echo '<p>Click the link to keep the secret the same when you refresh the page.</p>';

			$URL = Authenticator_URL($Member_Name, $Secret);
			echo '<h3>Authenticator URL: '.$URL.'</h3>';
			if (
				isset($_GET['googlechart']) ||
				!extension_loaded('gd') ||
				!function_exists('gd_info')
			) {
				echo '<h4>Google Chart</h4>';
				echo '<img src="https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl='.urlencode($URL).'">';
			} else {
				echo '<h4>PHPQRCode</h4>';
				$QR = Authenticator_QR($URL);
				echo '<img src="'.$QR.'">';
			}

			$Decoded = Base32_Decode($Secret);
			echo '<h3>Base32 Decoded: '.$Decoded.'</h3>';

			$Code = Authenticator_Code($Secret);
			echo '<h3>Current Code: '.$Code.'</h3>';

			$Acceptable =  Authenticator_Acceptable($Secret);
			echo '<h3>Acceptable:</h3>';
			echo '<pre>';
			var_dump($Acceptable);
			echo '</pre>';

			$Check =  Authenticator_Check($Code, $Secret);
			echo '<h3>Check:</h3>';
			echo '<pre>';
			var_dump($Check);
			echo '</pre>';

		}

	?>

</body>
</html>