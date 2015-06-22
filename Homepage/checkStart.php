<?php
	$id =  $_GET['ad'];
	$verbindung = mysql_connect("localhost", "root", "ProjektSS15");
	mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
	$sql = "select count(name) as test from (select name from AVR.Pins a where anwendungID in (select id from AVR.Anwendungen b where status =1) union all select name from AVR.Pins c where anwendungID = '$id') d group by name having count(name)>1";
	$ergebnis = mysql_query($sql);
	if(mysql_num_rows($ergebnis)>=1){
	 echo "false";
		exit;
	}else{
		echo "true";	
		$host = htmlspecialchars($_SERVER["HTTP_HOST"]);
		$uri = rtrim(dirname(htmlspecialchars($_SERVER["PHP_SELF"])), "/\\");
		$extra = "start.php?ad=$id";
		header("Location: http://$host$uri/$extra");
	}
?>	