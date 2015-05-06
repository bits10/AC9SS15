<?php
session_start();
if((!isset($_POST["name"])) || $_POST["name"] == ""){
	$host = htmlspecialchars($_SERVER["HTTP_HOST"]);
	$uri = rtrim(dirname(htmlspecialchars($_SERVER["PHP_SELF"])), "/\\");
	$extra = "index.php?f=1";
	header("Location: http://$host$uri/$extra");
	exit;
	
}
if(!isset($_POST["passwort"]) || $_POST["passwort"] == ""){
	$host = htmlspecialchars($_SERVER["HTTP_HOST"]);
	$uri = rtrim(dirname(htmlspecialchars($_SERVER["PHP_SELF"])), "/\\");
	$extra = "index.php?f=1";
	header("Location: http://$host$uri/$extra");
	exit;
}
$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
 Benutzername oder Passwort sind falsch");

mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
$username = $_POST["name"];
$passwort = $_POST["passwort"];
$abfrage = "SELECT name,passwort,berechtigung FROM AVR.Benutzer where name = '$username'";
$ergebnis = mysql_query($abfrage);
$row = mysql_fetch_object($ergebnis);

if ($row -> passwort == $passwort) {
	$_SESSION["username"] = $username;
	$_SESSION["login"] = "ok";
	if($row->berechtigung == 1){
		$_SESSION["be"] = 1;
	}else{
		$_SESSION["be"] = 0;
	}
	$host = htmlspecialchars($_SERVER["HTTP_HOST"]);
	$uri = rtrim(dirname(htmlspecialchars($_SERVER["PHP_SELF"])), "/\\");
	$extra = "dashboard.php";
	header("Location: http://$host$uri/$extra");
} else {
	$host = htmlspecialchars($_SERVER["HTTP_HOST"]);
	$uri = rtrim(dirname(htmlspecialchars($_SERVER["PHP_SELF"])), "/\\");
	$extra = "index.php?f=1";
	header("Location: http://$host$uri/$extra");

}
?>
