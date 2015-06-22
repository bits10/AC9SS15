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
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="css/prettify.css" rel="stylesheet">
		<script src="../../assets/js/ie-emulation-modes-warning.js"></script>
		
		<script>
			function deleteUser(){
			var e = document.getElementById("user");
			var strUser = e.options[e.selectedIndex].text;
			$.ajax({url: "deleteUser.php?ad="+strUser, async: false})
				window.location.reload();  
		    }
				
			function deleteBoard(){
			var e = document.getElementById("boards");
			var strBoard = e.options[e.selectedIndex].text;
			$.ajax({url: "deleteBoard.php?ad="+strBoard, async: false})
				window.location.reload();  
		    }
		    
		    function shUser(){
			var e = document.getElementById("user");
			var strUser = e.options[e.selectedIndex].text;
			var e = document.getElementById("boards");
			var strBoard = e.options[e.selectedIndex].text;
			$.ajax({url: "einstellungen.php?ad="+strUser+"&bo="+strBoard, async: false})
				window.location = "einstellungen.php?ad="+strUser+"&bo="+strBoard;  
		    }
		    
		    function reset(){
		    	shUser();
		    	var ip;
		    	<?php
		    		$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
					Benutzername oder Passwort sind falsch");
					mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
					if (isset($_GET['bo'])) {
						$id =  $_GET['bo'];
						$sql = "select ip from AVR.Boards where beschreibung ='$id'";
						$ergebnis = mysql_query($sql);
						$row=mysql_fetch_object($ergebnis);
						echo "ip = \"$row->ip\";";
					}    
		    	?>
		    	clearLCD(ip);
		    	setPorts(ip);
		    }
		    
		    function clearLCD(board) {
				$.ajax({
					type : "GET",
					dataType : "json",
					url : "/rest/clearLCD?boardIP=" + board,
					success : function(data) {
			
					},
					error : function() {

					}
				});
			}
			
			function setPorts(board) {
				$.ajax({
					type : "GET",
					dataType : "json",
					url : "/rest/setPorts?boardIP=" + board + "&values=S00000000",
					success : function(data) {
					},
					error : function() {
					}
				});
			}
		</script>
		
	</head>

	<body">
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
							<h4>Benutzer auswählen:</h4><br />
							<select id="user">
							<?php
								$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
								Benutzername oder Passwort sind falsch");
								mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
								$sql = "select name from AVR.Benutzer";
								$ergebnis = mysql_query($sql);
								while ($row=mysql_fetch_object($ergebnis)){
									 if($row->name == $_GET['ad']){	
										 echo "<option selected=\"selected\">$row->name</option>"; 
									 }else{
									 	echo "<option>$row->name</option>";
									 }     
           					    } 
							 ?>	
							</select>	
							<button onclick="shUser()">Anzeigen</button>
							<button onclick="deleteUser()">Löschen</button><br /><br />
							<label style="width: 20%">Name:</label>
								<?php
								$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
								Benutzername oder Passwort sind falsch");
								mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
								if (isset($_GET['ad'])) {
									$id =  $_GET['ad'];
									$sql = "select name from AVR.Benutzer where name ='$id'";
								} else {
									$sql = "select name from AVR.Benutzer";
								}
								$ergebnis = mysql_query($sql);
								$row=mysql_fetch_object($ergebnis);
								echo "$row->name<br />";     
							 ?>	
								<label style="width: 20%">Passwort:</label>
								<?php
								$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
								Benutzername oder Passwort sind falsch");
								mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
								if (isset($_GET['ad'])) {
									$id =  $_GET['ad'];
									$sql = "select passwort from AVR.Benutzer where name ='$id'";
								} else {
									$sql = "select passwort from AVR.Benutzer";
								}
								$ergebnis = mysql_query($sql);
								$row=mysql_fetch_object($ergebnis);
								echo "$row->passwort<br />";     
							 ?>	
								<label style="width: 20%">Berechtigung:</label>
								<?php
								$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
								Benutzername oder Passwort sind falsch");
								mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
								if (isset($_GET['ad'])) {
									$id =  $_GET['ad'];
									$sql = "select berechtigung from AVR.Benutzer where name ='$id'";
								} else {
									$sql = "select berechtigung from AVR.Benutzer";
								}
								$ergebnis = mysql_query($sql);
								$row=mysql_fetch_object($ergebnis);
								if($row->berechtigung == 1){
									$ber = "Admin";
								}else if($row->berechtigung == 0){
									$ber = "Benutzer";
								}
								echo "$ber<br />";     
							 ?>	
								<br /><br /><br /><br />
							<form name="form" method="post" action="createUser.php" enctype="multipart/form-data">
								<h4>Benutzer anlegen:</h4><br /><br />
								<label style="width: 20%">Name:</label>
								<input name="name" type="text" /><br />
								<label style="width: 20%">Passwort:</label>
								<input name="passwort" type="text" /><br />
								<label style="width: 20%">Berechtigung:</label>
								<select name="berechtigung">
									<option>Benutzer</option>
									<option>Admin</option>
								</select><br /><br />
								<button>Anlegen</button>
							</form>
						</div>
						<div class="col-md-6">
							<h3>Boards:</h2>
							<br />
							<h4>Board auswählen:</h4><br />
							<select id="boards">
							<?php
								$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
								Benutzername oder Passwort sind falsch");
								mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
								$sql = "select beschreibung from AVR.Boards";
								$ergebnis = mysql_query($sql);
								while ($row=mysql_fetch_object($ergebnis)){
									 if($row->beschreibung == $_GET['bo']){	
										 echo "<option selected=\"selected\">$row->beschreibung</option>"; 
									 }else{
									 	echo "<option>$row->beschreibung</option>";
									 }	
           					    } 
							 ?>	
							</select>
							<button onclick="shUser()">Anzeigen</button>
							<button onclick="deleteBoard()">Löschen</button>
							<button onclick="reset()">Reset</button><br /><br />
								<label style="width: 20%">Beschreibung:</label>
								<?php
								$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
								Benutzername oder Passwort sind falsch");
								mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
								if (isset($_GET['bo'])) {
									$id =  $_GET['bo'];
									$sql = "select beschreibung from AVR.Boards where beschreibung ='$id'";
								} else {
									$sql = "select beschreibung from AVR.Boards";
								}
								$ergebnis = mysql_query($sql);
								$row=mysql_fetch_object($ergebnis);
								echo "$row->beschreibung<br />";     
							 ?>
							 <label style="width: 20%">IP-Adresse:</label>
								<?php
								$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
								Benutzername oder Passwort sind falsch");
								mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
								if (isset($_GET['bo'])) {
									$id =  $_GET['bo'];
									$sql = "select ip from AVR.Boards where beschreibung ='$id'";
								} else {
									$sql = "select ip from AVR.Boards";
								}
								$ergebnis = mysql_query($sql);
								$row=mysql_fetch_object($ergebnis);
								echo "$row->ip<br />";     
							 ?>	</br></br></br></br></br>
							<form name="form" method="post" action="createBoard.php" enctype="multipart/form-data">
								<h4>Board hinzufügen:</h4><br /><br />
								<label style="width: 20%">IP Adresse:</label>
								<input name="ip" type="text" /><br />
								<label style="width: 20%">Beschreibung:</label>
								<input name="besch" type="text" /><br /><br />
								<button>Hinzufügen</button>
							</form>
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