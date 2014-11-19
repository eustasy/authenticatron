<?php include __DIR__.'/assets/header.php'; ?>

	<div class="break clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Code</p>
		<p>&nbsp;</p>
		<p>Output</p>
	</div>
	<div class="right">
		<h3>Authentricatron Secret</h3>
		<p><code>Authentricatron_Secret();</code></p>
		<p><code>Authentricatron_Secret($Length = 16);</code></p>
		<p>Returns a <code>$Length</code> long string with 32bit only Characters, or <code>false</code> on failure (usually security).</p>
		<p>For generating secrets. Usually accessed only from within the URL/QRCode functions</p>
		<p><code>$Length</code> should be an integer, longer than 16. Usually left to default.</p>
		<p>Generated using MCrypt if it is available, falling back to OpenSSL if it is secure.</p>
		<p>If returning <code>false</code>, try installing <code>php5-mcrypt</code> on Ubuntu.</p>
	</div>
	<?php
		$MCrypt = false;
		$OpenSSL = false;
		echo '
	<div class="clear"></div>
	<div class="left">
		<p>Debug</p>
	</div>
	<div class="right">';
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
		echo '
	</div>';
	?>

	<div class="break clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Code</p>
		<p>&nbsp;</p>
		<p>Output</p>
	</div>
	<div class="right">
		<h3>Authentricatron URL</h3>
		<p><code>Authentricatron_URL($Member_Name, $Secret);</code></p>
		<p><code>Authentricatron_URL($Member_Name, $Secret, $Issuer = DEFAULT);</code></p>
		<p>Outputs an OTPAuth URL that gives people their Secret along with a passed Member Name and an optional Issuer.</p>
		<p>All parameters should be strings, with the optional issuer defaulting to the configured value if not passed.</p>
	</div>

	<div class="break clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Code</p>
	</div>
	<div class="right">
		<h3>Authentricatron QR</h3>
		<p><code>Authentricatron_QR($URL);</code></p>
		<p><code>Authentricatron_QR($URL, $Size = 4);</code></p>
		<p><code>Authentricatron_QR($URL, $Size = 4, $Margin = 0);</code></p>
		<p><code>Authentricatron_QR($URL, $Size = 4, $Margin = 0, $Level = 'M');</code></p>
		<p><code>$URL</code> is a valid OTPAuth URL in string form.</p>
		<p><code>$Size</code> is a non-zero integer, defaults to 4.</p>
		<p><code>$Margin</code> is an integer, defaults to 0.</p>
		<p><code>$Level</code> is a string, defaults to 'M'. It defines the error correction level.</p>
		<ul>
			<li>Level L (Low) &emsp;&emsp; 7% of codewords can be restored.</li>
			<li>Level M (Medium) &emsp; 15% of codewords can be restored.</li>
			<li>Level Q (Quartile) &emsp; 25% of codewords can be restored.</li>
			<li>Level H (High) &emsp;&emsp; 30% of codewords can be restored.</li>
		</ul>
	</div>
	<div class="clear"></div>
	<div class="left">
		<p>Output</p>
	</div>
	<div class="right">
		<p>Outputs a QR Code image in 64bit data-URI form.</p>
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

	<div class="break clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Code</p>
		<p>Output</p>
	</div>
	<div class="right">
		<h3>Base32 Decode</h3>
		<p><code>Base32_Decode($Secret);</code></p>
		<p>Outputs a string of the numeric representation of the Secret as ASCII text.</p>
		<p>The lone parameter is a string value that expects a valid Base32 secret.</p>
	</div>

	<div class="break clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Code</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>Output</p>
	</div>
	<div class="right">
		<h3>Authentricatron Code</h3>
		<p><code>Authentricatron_Code($Secret);</code></p>
		<p><code>Authentricatron_Code($Secret, $Timestamp = false);</code></p>
		<p><code>Authentricatron_Code($Secret, $Timestamp = false, $CodeLength = 6);</code></p>
		<p>Outputs the calculated code for the current or provided timestamp.</p>
		<p><code>$Secret</code> is a valid Base32 Secret in string form.</p>
		<p><code>$Timestamp</code> is a unix timestamp, defaults to false to use the current timestamp.</p>
		<p><code>$CodeLength</code> is a non-zero integer, the desired length of the generated code. Defaults to 6.</p>
	</div>

	<div class="break clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Code</p>
		<p>&nbsp;</p>
		<p>Output</p>
	</div>
	<div class="right">
		<h3>Authentricatron Acceptable</h3>
		<p><code>Authentricatron_Acceptable($Secret);</code></p>
		<p><code>Authentricatron_Acceptable($Secret, $Variance = 2));</code></p>
		<pre>array(5) {
	[-2] => string(6) "398599"
	[-1] => string(6) "283062"
	[0] => string(6) "809226"
	[1] => string(6) "541727"
	[2] => string(6) "667780"
}</pre>
		<p><code>$Secret</code> is a valid Base32 Secret in string form.</p>
		<p><code>$Variance</code> is an integer indicating the adjustment of codes with a 30 second value. Default to 2, or 1 minute.</p>
		<p>Note the indexes, which can be used to determine the time difference, and perhaps warn users on the outer bounds.</p>
		<p>Code generation is expensive, so avoid generating any you don't want to check against later.</p>
	</div>

	<div class="break clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Code</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>Output</p>
	</div>
	<div class="right">
		<h3>Authentricatron Check</h3>
		<p><code>Authentricatron_Check($Code);</code></p>
		<p><code>Authentricatron_Check($Code, $Secret);</code></p>
		<p><code>Authentricatron_Check($Code, $Secret, $Variance = false);</code></p>
		<p>Outputs a boolean value, true or false.</p>
		<p><code>$Code</code> is a 6 digit string, usually numeric, but not necessarily an integer.</p>
		<p><code>$Secret</code> is a valid Base32 Secret in string form.</p>
		<p><code>$Variance</code> is an integer indicating the adjustment of codes with a 30 second value. Default to 2, or 1 minute.</p>
	</div>

	<div class="break clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
		<p>Code</p>
		<p>Output</p>
	</div>
	<div class="right">
		<h3>Authentricatron New</h3>
		<p><code>Authentricatron_New($Member_Name);</code></p>
		<p>Outputs an array.</p>
		<pre>array(3) {
	['Secret'] => string // the Secret for the member
	['URL'] => string // an OTPAuth URL
	['QR'] => string // the Data64 URI for the QR code
}</pre>
		<p><code>$Member_Name</code> is a string containing the data your member will identify with.</p>
	</div>

	<div class="break clear"></div>
	<div class="left">
		<h3>&nbsp;</h3>
	</div>
	<div class="right">
		<h3>Glossary</h3>
		<p><strong>Base32</strong> is an encoding, effectively an alphabet, that computers use made up of 32 characters.</p>
		<p><strong>Base32 Characters</strong> are A to Z (upper-case only), and 2 to 7.</p>
	</div>

	<div class="break clear"></div>

</body>
</html>