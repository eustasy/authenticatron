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
			vertical-align: bottom;
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
		<h1><img alt="Padlock Icon" src="assets/iconarchive_simiographics_padlock.png"></h1>
	</div>
	<div class="right">
		<h1>Authenticatron</h1>
		<p>A simple, procedural PHP script to create Google Authenticator secrets and corresponding QR codes,<br>
		then verify the entered response over a given time variance.<br>
		<a href="http://labs.eustasy.org/authenticatron/examples.php">examples</a> &emsp;
		<a href="http://labs.eustasy.org/authenticatron/documentation.php">documentation</a> &emsp;
		<a href="https://codeclimate.com/github/eustasy/authenticatron"><img src="https://codeclimate.com/github/eustasy/authenticatron/badges/gpa.svg" /></a> &emsp;
		<a href="https://www.codacy.com/public/eustasy/authenticatron"><img src="https://www.codacy.com/project/badge/670334725e9240d1beddb0b34f0d8c3c"/></a></p>
	</div>