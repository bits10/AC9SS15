<?php
	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
 	Benutzername oder Passwort sind falsch");
	mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
	$besch = $_POST["besch"];
	$ip = $_POST["ip"];
	$verbindung = mysql_connect("localhost", "root", "ProjektSS15");
	mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
	$sql = "insert into AVR.Boards(ip,beschreibung) values('$ip','$besch')";
	$ergebnis = mysql_query($sql);
	$host = htmlspecialchars($_SERVER["HTTP_HOST"]);
	$uri = rtrim(dirname(htmlspecialchars($_SERVER["PHP_SELF"])), "/\\");
	$extra = "einstellungen.php";
	header("Location: http://$host$uri/$extra");
?>	