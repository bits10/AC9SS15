<?php
	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
 	Benutzername oder Passwort sind falsch");
	mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
	$name = $_POST["name"];
	$passwort = $_POST["passwort"];
	$berechtigung = $_POST["berechtigung"];
	$ber;
	if($berechtigung == "Benutzer"){
		$ber =  0;	
	}else{
		$ber = 1;
	}
	$verbindung = mysql_connect("localhost", "root", "ProjektSS15");
	mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
	$sql = "insert into AVR.Benutzer(name,passwort, berechtigung) values('$name','$passwort','$ber')";
	$ergebnis = mysql_query($sql);
	$host = htmlspecialchars($_SERVER["HTTP_HOST"]);
	$uri = rtrim(dirname(htmlspecialchars($_SERVER["PHP_SELF"])), "/\\");
	$extra = "einstellungen.php";
	header("Location: http://$host$uri/$extra");
?>	