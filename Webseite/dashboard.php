<?php
session_start();
if(isset($_SESSION["login"]) && $_SESSION["login"]=="ok"){
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
		<link href="css/dashboard.css" rel="stylesheet" />
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
							<?php
								if($_SESSION["be"]==1){
									echo "	
									<li draggable=\"true\">
									<a href=\"manuell.php\">Manuelle Steuerung</a>
									</li>
									<li draggable=\"true\">
									<a href=\"einstellungen.php\">Einstellungen</a>
									</li>";									
								}
							?>
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

						<div style="margin-top: 20%" class="col-xs-10 col-sm-6">
							<div class="circle-tile">
								<a href="auditStatus.html">
								<div class="circle-tile-heading hp-primary">
									<i class="fa fa-cubes fa-3x"></i>
								</div> </a>
								<div class="circle-tile-content hp-secondary">
									<div class="circle-tile-description text-faded">
										Vorhandene Boards
									</div>
									<div class="circle-tile-number text-faded">
										<label style="display: none" id="runningTasks"></label>
										<?php
										$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
										Benutzername oder Passwort sind falsch");
										mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
										$sql = "select count(id) AS number from AVR.Boards";
										$ergebnis = mysql_query($sql);
										$row = mysql_fetch_object($ergebnis);
										echo "$row->number";
										?>
										<label style="display: none" id="runningTasksSpinner"><i class="fa fa-spinner"></i></label>
										<span id="sparklineA"></span>
										<br>
										<br>
									</div>
									<a href="manuell.html" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
								</div>
							</div>
						</div>
						<div style="margin-top: 20%" class="col-xs-10 col-sm-6">
							<div class="circle-tile">
								<a href="auditStatus.html">
								<div class="circle-tile-heading hp-primary">
									<i class="fa fa-cogs fa-3x"></i>
								</div> </a>
								<div class="circle-tile-content hp-secondary">
									<div class="circle-tile-description text-faded">
										Aktive Anwendungen
									</div>
									<div class="circle-tile-number text-faded">
										<label style="display: none" id="runningTasks"></label>
										<?php
										$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
										Benutzername oder Passwort sind falsch");
										mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
										$sql = "select count(id) AS number from AVR.Anwendungen where status ='1'";
										$ergebnis = mysql_query($sql);
										$row = mysql_fetch_object($ergebnis);
										echo "$row->number";
										?>
										<label style="display: none" id="runningTasksSpinner"><i class="fa fa-spinner"></i></label>
										<span id="sparklineA"></span>
										<br>
										<br>
									</div>
									<a href="anwendungen.html" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
								</div>
							</div>
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