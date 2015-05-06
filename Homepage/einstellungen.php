<?php
session_start();
if(isset($_SESSION["login"]) && $_SESSION["login"]=="ok" && $_SESSION["be"]==1){
?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="Timo Bayer">
		<title>AVR</title>
		<link href="css/a4cloud.css" rel="stylesheet">
		<link href="css/prettify.css" rel="stylesheet">
		<script src="../../assets/js/ie-emulation-modes-warning.js"></script>
		
	</head>

	<body>
		<div id="wrapper">
			<nav class="navbar navbar-default navbar-fixed-top">
				<div class="container">
					<div class="navbar-header">
						<a class="navbar-brand" href="dashboard.php"><span style="margin-right: 5px" class="fa fa-dashboard" aria-hidden="true"></span>Dashboard</a>
					</div>
					<div id="navbar" class="navbar-collapse collapse">
						<ul class="nav navbar-nav navbar-right">
							<li>
								<a class="dropdown-toggle" id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="/page.html"> Anwendungen <b class="caret"></b> </a>
							<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
								<li draggable="true">
									<a href="anwendungen.php">Übersicht</a>
								</li>
								<li draggable="true">
									<a href="aErstellen.php">Erstellen</a>
								</li>
							</ul>
							</li>
							<li draggable="true">
								<a href="manuell.php">Manuelle Steuerung</a>
							</li>
							<li draggable="true">
								<a href="einstellungen.php">Einstellungen</a>
							</li>
							<li draggable="true">
								<a href="logout.php">Abmelden</a>
							</li>
						</ul>
					</div>
				</div>
			</nav>
			<div class="container">
				<div class="row">
				<div class="col-md-12">
						<h1>Einstellungen</h1>
						<hr />
						<div class="col-md-6">
							<h3>Benutzer:</h2>
							<br />
							<label>Benutzer löschen:</label><br />
							<select>
								<option>Benutzer 1</option>
								<option>Benutzer 2</option>
							</select>
							<button>Löschen</button><br /><br /><br />
							<label>Benutzer anlegen:</label><br /><br />
							<label style="width: 20%">Name:</label>
							<input type="text" /><br />
							<label style="width: 20%">Passwort:</label>
							<input type="text" /><br />
							<label style="width: 20%">Berechtigung:</label>
							<select>
								<option>Benutzer</option>
								<option>Admin</option>
							</select><br /><br />
							<button>Anlegen</button>
						</div>
						<div class="col-md-6">
							<h3>Boards:</h2>
							<br />
							<label>Board löschen:</label><br />
							<select>
								<option>Board 1</option>
								<option>Board 2</option>
							</select>
							<button>Löschen</button><br /><br /><br />
							<label>Board hinzufügen:</label><br /><br />
							<label style="width: 20%">IP Adresse:</label>
							<input type="text" /><br />
							<label style="width: 20%">Beschreibung:</label><br />
							<textarea name="beschreibung" cols="40" rows="2"></textarea><br /><br />
							<button>Hinzufügen</button>
						</div>
					</div>
				</div>

			</div>
		</div>
		<script src="js/jquery/jquery-1.11.2.min.js"></script>
		<script src="js/bootstrap/bootstrap.min.js"></script>
		<script src="js/prettify/prettify.js"></script>
		<script src="js/a4cloud-UITemplate.js"></script>
	</body>
</html>
<?php
}else{
	$host = htmlspecialchars($_SERVER["HTTP_HOST"]);
	$uri = rtrim(dirname(htmlspecialchars($_SERVER["PHP_SELF"])), "/\\");
	$extra = "index.php";
	header("Location: http://$host$uri/$extra");
}
?>