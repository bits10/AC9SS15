<?php
	$id =  $_GET['ad'];
	$verbindung = mysql_connect("localhost", "root", "ProjektSS15");
	mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
	$sql = "update AVR.Anwendungen set status = '0' where id = '$id'";
	$ergebnis = mysql_query($sql);
?>	