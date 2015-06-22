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
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="css/prettify.css" rel="stylesheet">
		<link href="css/details.css" rel="stylesheet" />
		<script>
			function showHideLayer(id) {
				e = document.getElementById(id);
				if (e.style.display == "block") {
					e.style.display = "none";
				} else {
					e.style.display = "block";
				}
			}
			
			
			function showAnwendungen(){
			 <?php
				$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
				Benutzername oder Passwort sind falsch");
				mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
				$user = $_SESSION["username"];
				$sql = "select id from AVR.Benutzer where name = '$user'";
				$ergebnis = mysql_query($sql);
				$row = mysql_fetch_object($ergebnis);
				$user = $row->id;
				$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
				$ergebnis = mysql_query($sql);
				$row = mysql_fetch_object($ergebnis);
				$ber = $row->berechtigung;
				if($ber == 0){
					$sql = "select count(id) AS number from AVR.Anwendungen where benutzerID = '$user'";		
				}else{
					$sql = "select count(id) AS number from AVR.Anwendungen";		
				}
				$ergebnis = mysql_query($sql);
				$row = mysql_fetch_object($ergebnis);
				echo "var anzahl = $row->number";
			 ?>	
			 for(var i=1;i<=anzahl;i++){
			 	e = document.getElementById("a"+i);
			 	e.style.display = "";
			 }
			}
			
			function deleteAnwendung(id){
				if (confirm("Wollen Sie die Anwendung wirklich löschen?") == true) {
     			  $.ajax({url: "delete.php?ad="+id, async: false})
					window.location.reload(); 
    			}   
		    }
		    
		    function startAnwendung(id, board){
		    	<?php
		    		$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
					Benutzername oder Passwort sind falsch");
					mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
					$sql = "select count(id) as anzahl from AVR.Anwendungen where status = 1";
					$ergebnis = mysql_query($sql);
					$row = mysql_fetch_object($ergebnis);
					$laufendeAnwendungen = $row->anzahl;
					echo "var anzahl=$laufendeAnwendungen;";
					$sql = "select boardID from AVR.Anwendungen where status = 1";
					$ergebnis = mysql_query($sql);
					for ($i=0; $i < $laufendeAnwendungen; $i++) {
						echo "var board$i;"; 
						$row = mysql_fetch_object($ergebnis);
						echo "board$i = $row->boardID;";
					}
		    	?>
		    	 for (i = 0; i < anzahl; i++) {
 				   var boardID = eval("board" + i);
 				   if(board==boardID){
							$.ajax({
   								 type: "GET",
    					   		 url: "checkStart.php?ad="+id,
    					   		 dataType: "text",
  								 success: function(data) {
  									if(data == "false"){
  										alert("Auf diesem Board läuft bereits eine Anwendung");
  									}else{
  										$.ajax({
										type : "GET",
										dataType : "json",
										url : "/rest/start?id=" + id,
										success : function(data) {
										},
										error : function() {
										}
									});
									window.location.reload();
  									}
  									
  						
   								 }
							});
							return;
 				   }
				}
		    	$.ajax({
					type : "GET",
					dataType : "json",
					url : "/rest/start?id=" + id,
					success : function(data) {
					},
					error : function() {
			
					}
				});
				$.ajax({url: "start.php?ad="+id, async: false})
					window.location.reload();  
		    }
		    
		    function stopAnwendung(id){
		    	$.ajax({
					type : "GET",
					dataType : "json",
					url : "/rest/stop?id=" + id,
					success : function(data) {
					},
					error : function() {
			
					}
				});
				$.ajax({url: "stop.php?ad="+id, async: false})
				window.location.reload();  
		    }
		    </script>

		<script src="../../assets/js/ie-emulation-modes-warning.js"></script>

	</head>

	<body onload="showAnwendungen()">
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
						<h1>Anwendungen</h1>
						
						<hr />
						<div class="bs-example">
							<div class="panel-group" id="accordion">
								<div style="display: none" id="a1" class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title"><label style="width: 30%"><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
											01.
											<?php
												$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select name from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>
											</a></label><span style="margin-left: 10%"> 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select beschreibung from AVR.Boards where id ='$row->boardID'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													echo "Board: $row->beschreibung";
												?>
												 </span>
											<span style="float: right"><span 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select status from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->status)==1){
														echo "class=\"fa fa-check-circle fa\" style=\"color:green\"";
													}else{
														echo "class=\"fa fa-times-circle fa\"";
													}
												?>
												 draggable="true"></span>
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
    												$row = mysql_fetch_object($ergebnis);
													$id = $row->id;
													$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
    												$row = mysql_fetch_object($ergebnis);
    												$board = $row->boardID;
													$sql = "select status from AVR.Anwendungen where id ='$id'";
													$ergebnis = mysql_query($sql);
    												$row = mysql_fetch_object($ergebnis);
													if($row->status == 1){
														echo "<button onclick=\"stopAnwendung($id)\" style=\"margin-left: 20px\">Stop</button>";
													}else{
														echo "<button onclick=\"startAnwendung($id,$board) \" style=\"margin-left: 20px\">Start</button>";
													}
												?>
											<button 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													echo "onclick=\"deleteAnwendung('$row->id')\"";
												?>
											>Delete</button></span></h4>
									</div>
									<div id="collapseOne" class="panel-collapse collapse">
										<div class="panel-body"></div>
										<label style="width: 20%; margin-left: 5%">Name der Anwendung:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select name from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>	
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Beschreibung:</label>
										<label> 
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select beschreibung from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->beschreibung";
											?>	
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Grafik:</label>
										<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select grafik from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "<img src=\"$row->grafik\" width=\"10%\"/>";
											?>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Betroffenes Board:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->boardID";
											?>
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Benutzer:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select benutzerID from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select name from AVR.Benutzer where id ='$row->benutzerID'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Betroffene Pins:</label>
										<br />
										<table style="margin-left: 25%" class="table2">
											<tr>
												<td></td>
												<td align="center">Ausgänge</td>
												<td align="center">Eingänge</td>
												<td align="center">ADC</td>
											</tr>
											<tr>
												<td>1</td>
												<td align="center">
												<input onclick="return false" id="out1" type="checkbox"
											
												
													<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in1" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC1" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>2</td>
												<td align="center">
												<input onclick="return false" id="out2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>3</td>
												<td align="center">
												<input onclick="return false" id="out3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked=";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked=";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>4</td>
												<td align="center">
												<input onclick="return false" id="out4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>5</td>
												<td align="center">
												<input onclick="return false" id="out5" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out5'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>6</td>
												<td align="center">
												<input onclick="return false" id="out6" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out6'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>7</td>
												<td align="center">
												<input onclick="return false" id="out7" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out7'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>8</td>
												<td align="center">
												<input onclick="return false" id="out8" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out8'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
										</table>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Skript:</label>
										<a onclick="showHideLayer('qwe')">Skript ein-/ausblenden</a>
										<div style="display: none; margin-left: 5%; margin-bottom: 5%; margin-right: 5%" id="qwe">
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select skript from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$text = nl2br($row->skript);
												echo "<code><pre>$text</pre></code>";
											?>
										</div>
									</div>
								</div>
								<div id="a2" style="display: none" class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title"><label style="width: 30%"><a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
											02.
											<?php
												$verbindung = mysql_connect("localhost", "root", "ProjektSS15");
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select name from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>
										</a></label><span style="margin-left: 10%"> 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select beschreibung from AVR.Boards where id ='$row->boardID'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													echo "Board: $row->beschreibung";
												?>
												 </span>
										
										
										<span style="float: right"><span
										<?php
			   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select status from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												if(($row->status)==1){
													echo "class=\"fa fa-check-circle fa\" style=\"color:green\"";
												}else{
													echo "class=\"fa fa-times-circle fa\"";
												}
										?>
										draggable="true"></span>
											<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
    												$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$id = $row->id;
													$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
    												$row = mysql_fetch_object($ergebnis);
    												$board = $row->boardID;
													$sql = "select status from AVR.Anwendungen where id ='$id'";
													$ergebnis = mysql_query($sql);
    												$row = mysql_fetch_object($ergebnis);
													if($row->status == 1){
														echo "<button onclick=\"stopAnwendung($id)\" style=\"margin-left: 20px\">Stop</button>";
													}else{
														echo "<button onclick=\"startAnwendung($id,$board) \" style=\"margin-left: 20px\">Start</button>";
													}
												?>
											<button 
											<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													echo "onclick=\"deleteAnwendung('$row->id')\"";
												?>
										>Delete</button></span></h4>
									</div>
									<div id="collapseTwo" class="panel-collapse collapse">
										<div class="panel-body"></div>
										<label style="width: 20%; margin-left: 5%">Name der Anwendung:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select name from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>	
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Beschreibung:</label>
										<label> 
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select beschreibung from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->beschreibung";
											?>	
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Grafik:</label>
										<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select grafik from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "<img src=\"$row->grafik\" width=\"10%\"/>";
											?>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Betroffenes Board:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->boardID";
											?>
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Benutzer:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select benutzerID from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select name from AVR.Benutzer where id ='$row->benutzerID'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Betroffene Pins:</label>
										<br />
										<table style="margin-left: 25%" class="table2">
											<tr>
												<td></td>
												<td align="center">Ausgänge</td>
												<td align="center">Eingänge</td>
												<td align="center">ADC</td>
											</tr>
											<tr>
												<td>1</td>
												<td align="center">
												<input onclick="return false" id="out1" type="checkbox"
													<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in1" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC1" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>2</td>
												<td align="center">
												<input onclick="return false" id="out2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>3</td>
												<td align="center">
												<input onclick="return false" id="out3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked=";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked=";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>4</td>
												<td align="center">
												<input onclick="return false" id="out4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>5</td>
												<td align="center">
												<input onclick="return false" id="out5" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out5'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>6</td>
												<td align="center">
												<input onclick="return false" id="out6" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out6'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>7</td>
												<td align="center">
												<input onclick="return false" id="out7" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out7'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>8</td>
												<td align="center">
												<input onclick="return false" id="out8" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out8'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
										</table>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Skript:</label>
										<a onclick="showHideLayer('skript2')">Skript ein-/ausblenden</a>
										<div style="display: none; margin-left: 5%; margin-bottom: 5%; margin-right: 5%" id="skript2">
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select skript from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$text = nl2br($row->skript);
												str_replace("", "&nbsp", $text);
												echo "<code><pre>$text</pre></code>";
											?>
										</div>
									</div>
								</div>
								<div id="a3" style="display: none" class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title"><label style="width: 30%"><a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
											03.
											<?php
												$verbindung = mysql_connect("localhost", "root", "ProjektSS15");
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$row = mysql_fetch_object($ergebnis);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select name from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>	
										</a></label><span style="margin-left: 10%"> 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select beschreibung from AVR.Boards where id ='$row->boardID'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													echo "Board: $row->beschreibung";
												?>
										</span>
										<span style="float: right"><span 
										<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$row = mysql_fetch_object($ergebnis);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select status from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												if(($row->status)==1){
													echo "class=\"fa fa-check-circle fa\" style=\"color:green\"";
												}else{
													echo "class=\"fa fa-times-circle fa\"";
												}
										?>	
										draggable="true"></span>
											<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
    												$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$id = $row->id;
													$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
    												$row = mysql_fetch_object($ergebnis);
    												$board = $row->boardID;
													$sql = "select status from AVR.Anwendungen where id ='$id'";
													$ergebnis = mysql_query($sql);
    												$row = mysql_fetch_object($ergebnis);
													if($row->status == 1){
														echo "<button onclick=\"stopAnwendung($id)\" style=\"margin-left: 20px\">Stop</button>";
													}else{
														echo "<button onclick=\"startAnwendung($id,$board) \" style=\"margin-left: 20px\">Start</button>";
													}
												?>
											<button 
											<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													echo "onclick=\"deleteAnwendung('$row->id')\"";
												?>
											>Delete</button></span></h4>
									</div>
									<div id="collapseThree" class="panel-collapse collapse">
										<div class="panel-body"></div>
											<label style="width: 20%; margin-left: 5%">Name der Anwendung:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$row = mysql_fetch_object($ergebnis);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select name from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>	
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Beschreibung:</label>
										<label> 
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$row = mysql_fetch_object($ergebnis);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select beschreibung from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->beschreibung";
											?>	
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Grafik:</label>
										<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$row = mysql_fetch_object($ergebnis);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select grafik from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "<img src=\"$row->grafik\" width=\"10%\"/>";
											?>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Betroffenes Board:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$row = mysql_fetch_object($ergebnis);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->boardID";
											?>
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Benutzer:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$row = mysql_fetch_object($ergebnis);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select benutzerID from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select name from AVR.Benutzer where id ='$row->benutzerID'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>
										</label>
										<br />
										<br />

										<label style="width: 20%; margin-left: 5%">Betroffene Pins:</label>
										<br />
										<table style="margin-left: 25%" class="table2">
											<tr>
												<td></td>
												<td align="center">Ausgänge</td>
												<td align="center">Eingänge</td>
												<td align="center">ADC</td>
											</tr>
											<tr>
												<td>1</td>
												<td align="center">
												<input onclick="return false" id="out1" type="checkbox"
													<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in1" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC1" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>2</td>
												<td align="center">
												<input onclick="return false" id="out2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>3</td>
												<td align="center">
												<input onclick="return false" id="out3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked=";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked=";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>4</td>
												<td align="center">
												<input onclick="return false" id="out4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>5</td>
												<td align="center">
												<input onclick="return false" id="out5" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out5'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>6</td>
												<td align="center">
												<input onclick="return false" id="out6" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out6'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>7</td>
												<td align="center">
												<input onclick="return false" id="out7" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out7'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>8</td>
												<td align="center">
												<input onclick="return false" id="out8" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out8'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
										</table>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Skript:</label>
										<a onclick="showHideLayer('skript3')">Skript ein-/ausblenden</a>
										<div style="display: none; margin-left: 5%; margin-bottom: 5%; margin-right: 5%" id="skript3">
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$row = mysql_fetch_object($ergebnis);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select skript from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$text = nl2br($row->skript);
												echo "<code><pre>$text</pre></code>";
											?>
										</div>
									</div>
								</div>
								<div id="a4" style="display: none" class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title"><label style="width: 30%"><a data-toggle="collapse" data-parent="#accordion" href="#collapse4">
											04.
											<?php
												$verbindung = mysql_connect("localhost", "root", "ProjektSS15");
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 4; $i++) {
    												$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select name from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>	
										</a></label><span style="margin-left: 10%"> 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 4; $i++) {
    												$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select beschreibung from AVR.Boards where id ='$row->boardID'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													echo "Board: $row->beschreibung";
												?>
										</span>
										<span style="float: right"><span
										<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 4; $i++) {
    												$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select status from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												if(($row->status)==1){
													echo "class=\"fa fa-check-circle fa\" style=\"color:green\"";
												}else{
													echo "class=\"fa fa-times-circle fa\"";
												}
										?>	
										draggable="true"></span>
											<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
    												for ($i = 1; $i <= 4; $i++) {
    												$row = mysql_fetch_object($ergebnis);
													}
													$id = $row->id;
													$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
    												$row = mysql_fetch_object($ergebnis);
    												$board = $row->boardID;
													$sql = "select status from AVR.Anwendungen where id ='$id'";
													$ergebnis = mysql_query($sql);
    												$row = mysql_fetch_object($ergebnis);
													if($row->status == 1){
														echo "<button onclick=\"stopAnwendung($id)\" style=\"margin-left: 20px\">Stop</button>";
													}else{
														echo "<button onclick=\"startAnwendung($id,$board) \" style=\"margin-left: 20px\">Start</button>";
													}
												?>
											<button 
											<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 4; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													echo "onclick=\"deleteAnwendung('$row->id')\"";
												?>
											>Delete</button></span></h4>
									</div>
									<div id="collapse4" class="panel-collapse collapse">
										<div class="panel-body"></div>
										<label style="width: 20%; margin-left: 5%">Name der Anwendung:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 4; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select name from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>	
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Beschreibung:</label>
										<label> 
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 4; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select beschreibung from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->beschreibung";
											?>	
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Grafik:</label>
										<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 4; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select grafik from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "<img src=\"$row->grafik\" width=\"10%\"/>";
											?>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Betroffenes Board:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 4; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->boardID";
											?>
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Benutzer:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select benutzerID from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select name from AVR.Benutzer where id ='$row->benutzerID'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Betroffene Pins:</label>
										<br />
										<table style="margin-left: 25%" class="table2">
											<tr>
												<td></td>
												<td align="center">Ausgänge</td>
												<td align="center">Eingänge</td>
												<td align="center">ADC</td>
											</tr>
											<tr>
												<td>1</td>
												<td align="center">
												<input onclick="return false" id="out1" type="checkbox"
													<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 4; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in1" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 4; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC1" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 4; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>2</td>
												<td align="center">
												<input onclick="return false" id="out2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 4; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 4; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 4; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>3</td>
												<td align="center">
												<input onclick="return false" id="out3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 4; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked=";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 4; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked=";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 4; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>4</td>
												<td align="center">
												<input onclick="return false" id="out4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 4; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 4; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 4; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>5</td>
												<td align="center">
												<input onclick="return false" id="out5" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 4; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out5'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>6</td>
												<td align="center">
												<input onclick="return false" id="out6" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 4; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out6'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>7</td>
												<td align="center">
												<input onclick="return false" id="out7" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 4; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out7'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>8</td>
												<td align="center">
												<input onclick="return false" id="out8" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 4; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out8'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
										</table>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Skript:</label>
										<a onclick="showHideLayer('skript4')">Skript ein-/ausblenden</a>
										<div style="display: none; margin-left: 5%; margin-bottom: 5%; margin-right: 5%" id="skript4">
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 4; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select skript from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$text = nl2br($row->skript);
												echo "<code><pre>$text</pre></code>";
											?>
										</div>
									</div>
								</div>
								<div id="a5" style="display: none" class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title"><label style="width: 30%"><a data-toggle="collapse" data-parent="#accordion" href="#collapse5">
											05.
											<?php
												$verbindung = mysql_connect("localhost", "root", "ProjektSS15");
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 5; $i++) {
    												$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select name from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>		
										</a></label><span style="margin-left: 10%"> 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 5; $i++) {
    												$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select beschreibung from AVR.Boards where id ='$row->boardID'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													echo "Board: $row->beschreibung";
												?>
										</span>
										<span style="float: right"><span
											<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 5; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select status from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->status)==1){
														echo "class=\"fa fa-check-circle fa\" style=\"color:green\"";
													}else{
														echo "class=\"fa fa-times-circle fa\"";
													}
												?>
											 draggable="true"></span>
											<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
    												for ($i = 1; $i <= 5; $i++) {
    												$row = mysql_fetch_object($ergebnis);
													}
													$id = $row->id;
													$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
    												$row = mysql_fetch_object($ergebnis);
    												$board = $row->boardID;
													$sql = "select status from AVR.Anwendungen where id ='$id'";
													$ergebnis = mysql_query($sql);
    												$row = mysql_fetch_object($ergebnis);
													if($row->status == 1){
														echo "<button onclick=\"stopAnwendung($id)\" style=\"margin-left: 20px\">Stop</button>";
													}else{
														echo "<button onclick=\"startAnwendung($id,$board) \" style=\"margin-left: 20px\">Start</button>";
													}
												?>
											<button 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 5; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													echo "onclick=\"deleteAnwendung('$row->id')\"";
												?>
											>Delete</button></span></h4>
									</div>
									<div id="collapse5" class="panel-collapse collapse">
										<div class="panel-body"></div>
										<label style="width: 20%; margin-left: 5%">Name der Anwendung:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 5; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select name from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>	
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Beschreibung:</label>
										<label> 
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 5; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select beschreibung from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->beschreibung";
											?>	
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Grafik:</label>
										<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 5; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select grafik from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "<img src=\"$row->grafik\" width=\"10%\"/>";
											?>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Betroffenes Board:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 5; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->boardID";
											?>
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Benutzer:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 5; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select benutzerID from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select name from AVR.Benutzer where id ='$row->benutzerID'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Betroffene Pins:</label>
										<br />
										<table style="margin-left: 25%" class="table2">
											<tr>
												<td></td>
												<td align="center">Ausgänge</td>
												<td align="center">Eingänge</td>
												<td align="center">ADC</td>
											</tr>
											<tr>
												<td>1</td>
												<td align="center">
												<input onclick="return false" id="out1" type="checkbox"
													<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 5; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in1" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 5; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC1" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 5; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>2</td>
												<td align="center">
												<input onclick="return false" id="out2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 5; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 5; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 5; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>3</td>
												<td align="center">
												<input onclick="return false" id="out3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 5; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked=";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 5; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked=";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 5; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>4</td>
												<td align="center">
												<input onclick="return false" id="out4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 5; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 5; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 5; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>5</td>
												<td align="center">
												<input onclick="return false" id="out5" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 5; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out5'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>6</td>
												<td align="center">
												<input onclick="return false" id="out6" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 5; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out6'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>7</td>
												<td align="center">
												<input onclick="return false" id="out7" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 5; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out7'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>8</td>
												<td align="center">
												<input onclick="return false" id="out8" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 5; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out8'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
										</table>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Skript:</label>
										<a onclick="showHideLayer('skript5')">Skript ein-/ausblenden</a>
										<div style="display: none; margin-left: 5%; margin-bottom: 5%; margin-right: 5%" id="skript5">
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 5; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select skript from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$text = nl2br($row->skript);
												echo "<code><pre>$text</pre></code>";											?>
										</div>
									</div>
								</div>
								<div id="a6" style="display: none" class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title"><label style="width: 30%"><a data-toggle="collapse" data-parent="#accordion" href="#collapse6">
											06.
											<?php
												$verbindung = mysql_connect("localhost", "root", "ProjektSS15");
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 6; $i++) {
    												$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select name from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>		
										</a></label><span style="margin-left: 10%"> 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select beschreibung from AVR.Boards where id ='$row->boardID'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													echo "Board: $row->beschreibung";
												?>
										</span>
										<span style="float: right"><span
											<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select status from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->status)==1){
														echo "class=\"fa fa-check-circle fa\" style=\"color:green\"";
													}else{
														echo "class=\"fa fa-times-circle fa\"";
													}
												?>
											 draggable="true"></span>
											<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
    												for ($i = 1; $i <= 6; $i++) {
    												$row = mysql_fetch_object($ergebnis);
													}
													$id = $row->id;
													$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
    												$row = mysql_fetch_object($ergebnis);
    												$board = $row->boardID;
													$sql = "select status from AVR.Anwendungen where id ='$id'";
													$ergebnis = mysql_query($sql);
    												$row = mysql_fetch_object($ergebnis);
													if($row->status == 1){
														echo "<button onclick=\"stopAnwendung($id)\" style=\"margin-left: 20px\">Stop</button>";
													}else{
														echo "<button onclick=\"startAnwendung($id,$board) \" style=\"margin-left: 20px\">Start</button>";
													}
												?>
											<button
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													echo "onclick=\"deleteAnwendung('$row->id')\"";
												?>
											>Delete</button></span></h4>
									</div>
									<div id="collapse6" class="panel-collapse collapse">
										<div class="panel-body"></div>
										<label style="width: 20%; margin-left: 5%">Name der Anwendung:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select name from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>	
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Beschreibung:</label>
										<label> 
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select beschreibung from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->beschreibung";
											?>	
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Grafik:</label>
										<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select grafik from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "<img src=\"$row->grafik\" width=\"10%\"/>";
											?>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Betroffenes Board:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->boardID";
											?>
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Benutzer:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select benutzerID from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select name from AVR.Benutzer where id ='$row->benutzerID'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Betroffene Pins:</label>
										<br />
										<table style="margin-left: 25%" class="table2">
											<tr>
												<td></td>
												<td align="center">Ausgänge</td>
												<td align="center">Eingänge</td>
												<td align="center">ADC</td>
											</tr>
											<tr>
												<td>1</td>
												<td align="center">
												<input onclick="return false" id="out1" type="checkbox"
													<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in1" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC1" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>2</td>
												<td align="center">
												<input onclick="return false" id="out2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>3</td>
												<td align="center">
												<input onclick="return false" id="out3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked=";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked=";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>4</td>
												<td align="center">
												<input onclick="return false" id="out4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>5</td>
												<td align="center">
												<input onclick="return false" id="out5" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out5'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>6</td>
												<td align="center">
												<input onclick="return false" id="out6" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out6'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>7</td>
												<td align="center">
												<input onclick="return false" id="out7" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out7'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>8</td>
												<td align="center">
												<input onclick="return false" id="out8" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out8'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
										</table>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Skript:</label>
										<a onclick="showHideLayer('skript6')">Skript ein-/ausblenden</a>
										<div style="display: none; margin-left: 5%; margin-bottom: 5%; margin-right: 5%" id="skript6">
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 6; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select skript from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$text = nl2br($row->skript);
												echo "<code><pre>$text</pre></code>";
											?>
										</div>
									</div>
								</div>
								<div id="a7" style="display: none" class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title"><label style="width: 30%"><a data-toggle="collapse" data-parent="#accordion" href="#collapse7">
											07.
											<?php
												$verbindung = mysql_connect("localhost", "root", "ProjektSS15");
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 7; $i++) {
    												$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select name from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>		
										</a></label><span style="margin-left: 10%"> 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select beschreibung from AVR.Boards where id ='$row->boardID'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													echo "Board: $row->beschreibung";
												?>
										</span>
										<span style="float: right"><span
											<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select status from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->status)==1){
														echo "class=\"fa fa-check-circle fa\" style=\"color:green\"";
													}else{
														echo "class=\"fa fa-times-circle fa\"";
													}
												?>
											draggable="true"></span>
											<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
    												for ($i = 1; $i <= 7; $i++) {
    												$row = mysql_fetch_object($ergebnis);
													}
													$id = $row->id;
													$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
    												$row = mysql_fetch_object($ergebnis);
    												$board = $row->boardID;
													$sql = "select status from AVR.Anwendungen where id ='$id'";
													$ergebnis = mysql_query($sql);
    												$row = mysql_fetch_object($ergebnis);
													if($row->status == 1){
														echo "<button onclick=\"stopAnwendung($id)\" style=\"margin-left: 20px\">Stop</button>";
													}else{
														echo "<button onclick=\"startAnwendung($id,$board) \" style=\"margin-left: 20px\">Start</button>";
													}
												?>
											<button
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													echo "onclick=\"deleteAnwendung('$row->id')\"";
												?>
											>Delete</button></span></h4>
									</div>
									<div id="collapse7" class="panel-collapse collapse">
										<div class="panel-body"></div>
										<label style="width: 20%; margin-left: 5%">Name der Anwendung:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select name from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>	
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Beschreibung:</label>
										<label> 
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select beschreibung from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->beschreibung";
											?>	
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Grafik:</label>
										<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select grafik from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "<img src=\"$row->grafik\" width=\"10%\"/>";
											?>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Betroffenes Board:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->boardID";
											?>
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Benutzer:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select benutzerID from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select name from AVR.Benutzer where id ='$row->benutzerID'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Betroffene Pins:</label>
										<br />
										<table style="margin-left: 25%" class="table2">
											<tr>
												<td></td>
												<td align="center">Ausgänge</td>
												<td align="center">Eingänge</td>
												<td align="center">ADC</td>
											</tr>
											<tr>
												<td>1</td>
												<td align="center">
												<input onclick="return false" id="out1" type="checkbox"			
													<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in1" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC1" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>2</td>
												<td align="center">
												<input onclick="return false" id="out2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>3</td>
												<td align="center">
												<input onclick="return false" id="out3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked=";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked=";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>4</td>
												<td align="center">
												<input onclick="return false" id="out4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>5</td>
												<td align="center">
												<input onclick="return false" id="out5" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out5'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>6</td>
												<td align="center">
												<input onclick="return false" id="out6" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out6'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>7</td>
												<td align="center">
												<input onclick="return false" id="out7" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out7'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>8</td>
												<td align="center">
												<input onclick="return false" id="out8" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out8'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
										</table>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Skript:</label>
										<a onclick="showHideLayer('skript7')">Skript ein-/ausblenden</a>
										<div style="display: none; margin-left: 5%; margin-bottom: 5%; margin-right: 5%" id="skript7">
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 7; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select skript from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$text = nl2br($row->skript);
												echo "<code><pre>$text</pre></code>";
											?>
										</div>
									</div>
								</div>
								<div id="a8" style="display: none" class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title"><label style="width: 30%"><a data-toggle="collapse" data-parent="#accordion" href="#collapse8">
											08.
											<?php
												$verbindung = mysql_connect("localhost", "root", "ProjektSS15");
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 8; $i++) {
    												$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select name from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>		
										</a></label><span style="margin-left: 10%"> 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select beschreibung from AVR.Boards where id ='$row->boardID'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													echo "Board: $row->beschreibung";
												?>
										</span>
										<span style="float: right"><span
											<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select status from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->status)==1){
														echo "class=\"fa fa-check-circle fa\" style=\"color:green\"";
													}else{
														echo "class=\"fa fa-times-circle fa\"";
													}
												?>
											 draggable="true"></span>
											<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
    												for ($i = 1; $i <= 8; $i++) {
    												$row = mysql_fetch_object($ergebnis);
													}
													$id = $row->id;
													$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
    												$row = mysql_fetch_object($ergebnis);
    												$board = $row->boardID;
													$sql = "select status from AVR.Anwendungen where id ='$id'";
													$ergebnis = mysql_query($sql);
    												$row = mysql_fetch_object($ergebnis);
													if($row->status == 1){
														echo "<button onclick=\"stopAnwendung($id)\" style=\"margin-left: 20px\">Stop</button>";
													}else{
														echo "<button onclick=\"startAnwendung($id,$board) \" style=\"margin-left: 20px\">Start</button>";
													}
												?>
											<button 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													echo "onclick=\"deleteAnwendung('$row->id')\"";
												?>
											>Delete</button></span></h4>
									</div>
									<div id="collapse8" class="panel-collapse collapse">
										<div class="panel-body"></div>
										<label style="width: 20%; margin-left: 5%">Name der Anwendung:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select name from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>	
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Beschreibung:</label>
										<label> 
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select beschreibung from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->beschreibung";
											?>	
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Grafik:</label>
										<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select grafik from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "<img src=\"$row->grafik\" width=\"10%\"/>";
											?>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Betroffenes Board:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->boardID";
											?>
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Benutzer:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select benutzerID from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select name from AVR.Benutzer where id ='$row->benutzerID'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Betroffene Pins:</label>
										<br />
										<table style="margin-left: 25%" class="table2">
											<tr>
												<td></td>
												<td align="center">Ausgänge</td>
												<td align="center">Eingänge</td>
												<td align="center">ADC</td>
											</tr>
											<tr>
												<td>1</td>
												<td align="center">
												<input onclick="return false" id="out1" type="checkbox"			
													<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in1" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC1" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>2</td>
												<td align="center">
												<input onclick="return false" id="out2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>3</td>
												<td align="center">
												<input onclick="return false" id="out3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked=";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked=";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>4</td>
												<td align="center">
												<input onclick="return false" id="out4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>5</td>
												<td align="center">
												<input onclick="return false" id="out5" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out5'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>6</td>
												<td align="center">
												<input onclick="return false" id="out6" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out6'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>7</td>
												<td align="center">
												<input onclick="return false" id="out7" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out7'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>8</td>
												<td align="center">
												<input onclick="return false" id="out8" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out8'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
										</table>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Skript:</label>
										<a onclick="showHideLayer('skript8')">Skript ein-/ausblenden</a>
										<div style="display: none; margin-left: 5%; margin-bottom: 5%; margin-right: 5%" id="skript8">
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 8; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select skript from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$text = nl2br($row->skript);
												echo "<code><pre>$text</pre></code>";
											?>
										</div>
									</div>
								</div>
								<div id="a9" style="display: none" class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title"><label style="width: 30%"><a data-toggle="collapse" data-parent="#accordion" href="#collapse9">
											09.
											<?php
												$verbindung = mysql_connect("localhost", "root", "ProjektSS15");
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 9; $i++) {
    												$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select name from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>	
										</a></label><span style="margin-left: 10%"> 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select beschreibung from AVR.Boards where id ='$row->boardID'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													echo "Board: $row->beschreibung";
												?>
										</span>
										<span style="float: right"><span
											<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select status from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->status)==1){
														echo "class=\"fa fa-check-circle fa\" style=\"color:green\"";
													}else{
														echo "class=\"fa fa-times-circle fa\"";
													}
												?>
											 draggable="true"></span>
											<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
    												for ($i = 1; $i <= 9; $i++) {
    												$row = mysql_fetch_object($ergebnis);
													}
													$id = $row->id;
													$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
    												$row = mysql_fetch_object($ergebnis);
    												$board = $row->boardID;
													$sql = "select status from AVR.Anwendungen where id ='$id'";
													$ergebnis = mysql_query($sql);
    												$row = mysql_fetch_object($ergebnis);
													if($row->status == 1){
														echo "<button onclick=\"stopAnwendung($id)\" style=\"margin-left: 20px\">Stop</button>";
													}else{
														echo "<button onclick=\"startAnwendung($id,$board) \" style=\"margin-left: 20px\">Start</button>";
													}
												?>
											<button 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													echo "onclick=\"deleteAnwendung('$row->id')\"";
												?>
											>Delete</button></span></h4>
									</div>
									<div id="collapse9" class="panel-collapse collapse">
										<div class="panel-body"></div>
										<label style="width: 20%; margin-left: 5%">Name der Anwendung:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select name from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>	
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Beschreibung:</label>
										<label> 
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select beschreibung from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->beschreibung";
											?>	
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Grafik:</label>
										<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select grafik from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "<img src=\"$row->grafik\" width=\"10%\"/>";
											?>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Betroffenes Board:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->boardID";
											?>
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Benutzer:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select benutzerID from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select name from AVR.Benutzer where id ='$row->benutzerID'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Betroffene Pins:</label>
										<br />
										<table style="margin-left: 25%" class="table2">
											<tr>
												<td></td>
												<td align="center">Ausgänge</td>
												<td align="center">Eingänge</td>
												<td align="center">ADC</td>
											</tr>
											<tr>
												<td>1</td>
												<td align="center">
												<input onclick="return false" id="out1" type="checkbox"
											
												
													<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in1" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC1" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>2</td>
												<td align="center">
												<input onclick="return false" id="out2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>3</td>
												<td align="center">
												<input onclick="return false" id="out3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked=";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked=";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>4</td>
												<td align="center">
												<input onclick="return false" id="out4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>5</td>
												<td align="center">
												<input onclick="return false" id="out5" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out5'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>6</td>
												<td align="center">
												<input onclick="return false" id="out6" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out6'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>7</td>
												<td align="center">
												<input onclick="return false" id="out7" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out7'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>8</td>
												<td align="center">
												<input onclick="return false" id="out8" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out8'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
										</table>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Skript:</label>
										<a onclick="showHideLayer('skript9')">Skript ein-/ausblenden</a>
										<div style="display: none; margin-left: 5%; margin-bottom: 5%; margin-right: 5%" id="skript9">
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 9; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select skript from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$text = nl2br($row->skript);
												echo "<code><pre>$text</pre></code>";
											?>
										</div>
									</div>
								</div>
								<div id="a10" style="display: none" class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title"><label style="width: 30%"><a data-toggle="collapse" data-parent="#accordion" href="#collapse10">
											10.
											<?php
												$verbindung = mysql_connect("localhost", "root", "ProjektSS15");
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 10; $i++) {
    												$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select name from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>	
										</a></label><span style="margin-left: 10%"> 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$sql = "select beschreibung from AVR.Boards where id ='$row->boardID'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													echo "Board: $row->beschreibung";
												?>
										</span>
										<span style="float: right"><span
											<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select status from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->status)==1){
														echo "class=\"fa fa-check-circle fa\" style=\"color:green\"";
													}else{
														echo "class=\"fa fa-times-circle fa\"";
													}
												?>
											 draggable="true"></span>
											<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
    												for ($i = 1; $i <= 10; $i++) {
    												$row = mysql_fetch_object($ergebnis);
													}
													$id = $row->id;
													$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
													$ergebnis = mysql_query($sql);
    												$row = mysql_fetch_object($ergebnis);
    												$board = $row->boardID;
													$sql = "select status from AVR.Anwendungen where id ='$id'";
													$ergebnis = mysql_query($sql);
    												$row = mysql_fetch_object($ergebnis);
													if($row->status == 1){
														echo "<button onclick=\"stopAnwendung($id)\" style=\"margin-left: 20px\">Stop</button>";
													}else{
														echo "<button onclick=\"startAnwendung($id,$board) \" style=\"margin-left: 20px\">Start</button>";
													}
												?>
											<button
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													echo "onclick=\"deleteAnwendung('$row->id')\"";
												?>
											>Delete</button></span></h4>
									</div>
									<div id="collapse10" class="panel-collapse collapse">
										<div class="panel-body"></div>
										<label style="width: 20%; margin-left: 5%">Name der Anwendung:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select name from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>	
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Beschreibung:</label>
										<label> 
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select beschreibung from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->beschreibung";
											?>	
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Grafik:</label>
										<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select grafik from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "<img src=\"$row->grafik\" width=\"10%\"/>";
											?>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Betroffenes Board:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select boardID from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->boardID";
											?>
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Benutzer:</label>
										<label>
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select benutzerID from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$sql = "select name from AVR.Benutzer where id ='$row->benutzerID'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												echo "$row->name";
											?>
										</label>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Betroffene Pins:</label>
										<br />
										<table style="margin-left: 25%" class="table2">
											<tr>
												<td></td>
												<td align="center">Ausgänge</td>
												<td align="center">Eingänge</td>
												<td align="center">ADC</td>
											</tr>
											<tr>
												<td>1</td>
												<td align="center">
												<input onclick="return false" id="out1" type="checkbox"
											
												
													<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in1" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC1" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC1'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>2</td>
												<td align="center">
												<input onclick="return false" id="out2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC2" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC2'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>3</td>
												<td align="center">
												<input onclick="return false" id="out3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked=";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked=";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC3" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC3'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>4</td>
												<td align="center">
												<input onclick="return false" id="out4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="in4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'in4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
												<td align="center">
												<input onclick="return false" id="ADC4" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'ADC4'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>5</td>
												<td align="center">
												<input onclick="return false" id="out5" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out5'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>6</td>
												<td align="center">
												<input onclick="return false" id="out6" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out6'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>7</td>
												<td align="center">
												<input onclick="return false" id="out7" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out7'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
											<tr>
												<td>8</td>
												<td align="center">
												<input onclick="return false" id="out8" type="checkbox" 
												<?php
				   						        	$verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
													mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
													$user = $_SESSION["username"];
													$sql = "select id from AVR.Benutzer where name = '$user'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$user = $row->id;
													$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													$ber = $row->berechtigung;
													if($ber == 0){
														$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
													}else{
														$sql = "select id from AVR.Anwendungen";		
													}
													$ergebnis = mysql_query($sql);
													for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
													}
													$sql = "select count(id) as number from AVR.Pins where anwendungID = '$row->id' and name = 'out8'";
													$ergebnis = mysql_query($sql);
													$row = mysql_fetch_object($ergebnis);
													if(($row->number)>0){
														echo "checked";
													}
												?>
												/>
												</td>
											</tr>
										</table>
										<br />
										<br />
										<label style="width: 20%; margin-left: 5%">Skript:</label>
										<a onclick="showHideLayer('skript10')">Skript ein-/ausblenden</a>
										<div style="display: none; margin-left: 5%; margin-bottom: 5%; margin-right: 5%" id="skript10">
											<?php
				   						        $verbindung = mysql_connect("localhost", "root", "ProjektSS15") ;
												mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
												$user = $_SESSION["username"];
												$sql = "select id from AVR.Benutzer where name = '$user'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$user = $row->id;
												$sql = "select berechtigung from AVR.Benutzer where id = '$user'";	
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$ber = $row->berechtigung;
												if($ber == 0){
													$sql = "select id from AVR.Anwendungen where benutzerID = '$user'";		
												}else{
													$sql = "select id from AVR.Anwendungen";		
												}
												$ergebnis = mysql_query($sql);
												for ($i = 1; $i <= 10; $i++) {
    													$row = mysql_fetch_object($ergebnis);
												}
												$sql = "select skript from AVR.Anwendungen where id ='$row->id'";
												$ergebnis = mysql_query($sql);
												$row = mysql_fetch_object($ergebnis);
												$text = nl2br($row->skript);
												echo "<code><pre>$text</pre></code>";
											?>
										</div>
									</div>
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