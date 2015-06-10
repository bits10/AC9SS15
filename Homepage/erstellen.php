<?php
session_start();
$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
 Benutzername oder Passwort sind falsch");
mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
$name = $_POST["name"];
$board = $_POST["board"];
$grafik = $_POST["grafik"];
$user = $_POST["user"];
$beschreibung = $_POST['beschreibung'];
$skript = $_POST['skript'];
$dateityp = GetImageSize($_FILES['grafik']['tmp_name']);
$path = "uploads/".$_FILES['grafik']['name'];
if ($dateityp[2] != 0) {
	move_uploaded_file($_FILES['grafik']['tmp_name'], "uploads/" . $_FILES['grafik']['name']);
}
$sql = "select id from AVR.Benutzer where name = '$user'";
$ergebnis = mysql_query($sql);
$row = mysql_fetch_object($ergebnis);
$user = "$row->id"; 
$sql = "select id from AVR.Boards where beschreibung = '$board'";
$ergebnis = mysql_query($sql);
$row = mysql_fetch_object($ergebnis);
$boardID = "$row->id"; 
$sql = "insert into AVR.Anwendungen(skript,beschreibung,grafik,boardID,name,status,benutzerID) values ('$skript','$beschreibung','$path','$boardID','$name','0','$user')" OR die("Error: $abfrage <br>" . mysql_error());
$ergebnis = mysql_query($sql);
$sql = "select id from AVR.Anwendungen where name = '$name'";
$ergebnis = mysql_query($sql);
$row = mysql_fetch_object($ergebnis);
$anwendung = "$row->id";
if (isset($_POST['out1'])) {
	$sql = "insert into AVR.Pins(name,typ,beschreibung,boardID,anwendungID) values('out1','out','Ausgang1','$boardID','$anwendung')";
	$ergebnis = mysql_query($sql);
}
if (isset($_POST['in1'])) {
	$sql = "insert into AVR.Pins(name,typ,beschreibung,boardID,anwendungID) values('in1','in','Eingang1','$boardID','$anwendung')";
	$ergebnis = mysql_query($sql);
} 
if (isset($_POST['ADC1'])) {
	$sql = "insert into AVR.Pins(name,typ,beschreibung,boardID,anwendungID) values('ADC1','ADC','ADC1','$boardID','$anwendung')";
	$ergebnis = mysql_query($sql);
}if (isset($_POST['out2'])) {
	$sql = "insert into AVR.Pins(name,typ,beschreibung,boardID,anwendungID) values('out2','out','Ausgang2','$boardID','$anwendung')";
	$ergebnis = mysql_query($sql);
}
if (isset($_POST['in2'])) {
	$sql = "insert into AVR.Pins(name,typ,beschreibung,boardID,anwendungID) values('in2','in','Eingang2','$boardID','$anwendung')";
	$ergebnis = mysql_query($sql);
}
if (isset($_POST['ADC2'])) {
	$sql = "insert into AVR.Pins(name,typ,beschreibung,boardID,anwendungID) values('ADC2','ADC','ADC2','$boardID','$anwendung')";
	$ergebnis = mysql_query($sql);
}
if (isset($_POST['out3'])) {
	$sql = "insert into AVR.Pins(name,typ,beschreibung,boardID,anwendungID) values('out3','out','Ausgang3','$boardID','$anwendung')";
	$ergebnis = mysql_query($sql);
}
if (isset($_POST['in3'])) {
	$sql = "insert into AVR.Pins(name,typ,beschreibung,boardID,anwendungID) values('in3','in','Eingang3','$boardID','$anwendung')";
	$ergebnis = mysql_query($sql);
}
if (isset($_POST['ADC3'])) {
	$sql = "insert into AVR.Pins(name,typ,beschreibung,boardID,anwendungID) values('ADC3','ADC','ADC3','$boardID','$anwendung')";
	$ergebnis = mysql_query($sql);
}
if (isset($_POST['out4'])) {
	$sql = "insert into AVR.Pins(name,typ,beschreibung,boardID,anwendungID) values('out4','out','Ausgang4','$boardID','$anwendung')";
	$ergebnis = mysql_query($sql);
}
if (isset($_POST['in4'])) {
	$sql = "insert into AVR.Pins(name,typ,beschreibung,boardID,anwendungID) values('in4','in','Eingang4','$boardID','$anwendung')";
	$ergebnis = mysql_query($sql);
}
if (isset($_POST['ADC4'])) {
	$sql = "insert into AVR.Pins(name,typ,beschreibung,boardID,anwendungID) values('ADC4','ADC','ADC4','$boardID','$anwendung')";
	$ergebnis = mysql_query($sql);
}
if (isset($_POST['out5'])) {
	$sql = "insert into AVR.Pins(name,typ,beschreibung,boardID,anwendungID) values('out5','out','Ausgang5','$boardID','$anwendung')";
	$ergebnis = mysql_query($sql);
}
if (isset($_POST['out6'])) {
	$sql = "insert into AVR.Pins(name,typ,beschreibung,boardID,anwendungID) values('out6','out','Ausgang6','$boardID','$anwendung')";
	$ergebnis = mysql_query($sql);
}
if (isset($_POST['out7'])) {
	$sql = "insert into AVR.Pins(name,typ,beschreibung,boardID,anwendungID) values('out7','out','Ausgang7','$boardID','$anwendung')";
	$ergebnis = mysql_query($sql);
}
if (isset($_POST['out8'])) {
	$sql = "insert into AVR.Pins(name,typ,beschreibung,boardID,anwendungID) values('out8','out','Ausgang8','$boardID','$anwendung')";
	$ergebnis = mysql_query($sql);
}
$host = htmlspecialchars($_SERVER["HTTP_HOST"]);
$uri = rtrim(dirname(htmlspecialchars($_SERVER["PHP_SELF"])), "/\\");
$extra = "aErstellen.php";
header("Location: http://$host$uri/$extra");
?>