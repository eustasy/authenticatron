<!DocType html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="A simple PHP script to create HOTP / TOTP / Google Authenticator secrets, corresponding QR links and code verification.">
	<title>Authenticatron</title>
	<link rel="icon" href="assets/favicon.ico">
	<link rel="stylesheet" media="all" href="https://fonts.googleapis.com/css?family=Droid+Sans:400,700|Droid+Sans+Mono&display=swap">
	<link rel="stylesheet" media="all" href="https://cdn.jsdelivr.net/combine/gh/necolas/normalize.css@8/normalize.min.css,gh/eustasy/Colors.css@2/flatui.min.css">
	<link rel="stylesheet" media="all" href="assets/styles.css">
</head>
<body>

	<div class="break clear"></div>
	<div class="left">
		<h1><img alt="Padlock Icon" src="assets/Flat-Icons-Inspired-by-Google-vol3_padlock.png"></h1>
	</div>
	<div class="right">
		<h1>Authenticatron</h1>
		<p>A simple PHP script to create TOTP secrets and corresponding QR codes,<br>
		then verify the entered response over a given time variance.<br>
		<a href="index.php">homepage</a> &emsp;
		<a href="documentation.php">documentation</a> &emsp;
		<a href="documentation.php#glossary">glossary</a> &emsp;
		<a href="server.php">server</a> &emsp;
		<a href="https://github.com/eustasy/authenticatron">source</a></p>
	</div>
