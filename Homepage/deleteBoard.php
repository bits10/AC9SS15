<?php
	$id =  $_GET['ad'];
	

	$verbindung = mysql_connect("localhost", "root", "ProjektSS15");
	mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
	$sql = "delete from AVR.Boards where beschreibung ='$id'";
	$ergebnis = mysql_query($sql);
?>	