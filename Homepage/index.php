<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="Timo Bayer">
	<title>AVR - Login</title>
	<link href="css/login.css" rel="stylesheet" />
</head>
<body>
	<section class="container">
		<div class="login">
			<h1>Anmeldung</h1>
			<form name="form" method="post" action="login.php">
				<p>
					<input type="text" name="name" value="" placeholder="Benutzername">
				</p>
				<p>
					<input type="password" name="passwort" value="" placeholder="Passwort">
				</p>
				<p class="submit">
					<input type="submit" name="commit" value="Login">
				</p>
			</form>
			<?php
			if (isset($_GET["f"]) && $_GET["f"] == 1) {
				echo "<p class='fehler'>Login-Daten nicht korrekt</p>";
			}
			?>
		</div>
	</section>
</body>
</html>
<body>