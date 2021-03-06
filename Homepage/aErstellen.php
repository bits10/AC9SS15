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
		<script src="../../assets/js/ie-emulation-modes-warning.js"></script>

		<script>
			 function pruefeName(){
		    	<?php
		    		$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
					Benutzername oder Passwort sind falsch");
					mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
					$sql = "select count(id) as anzahl from AVR.Anwendungen";
					$ergebnis = mysql_query($sql);
					$row = mysql_fetch_object($ergebnis);
					$anwendungen = $row->anzahl;
					echo "var anzahl=$anwendungen;";
					$sql = "select name from AVR.Anwendungen";
					$ergebnis = mysql_query($sql);
					for ($i=0; $i < $anwendungen; $i++) {
						echo "var aName$i;"; 
						$row = mysql_fetch_object($ergebnis);
						echo "aName$i = \"$row->name\";";
					}
		    	?>
		    	e = document.getElementById('name').value;
		    	for (i = 0; i < anzahl; i++) {
 				   	var aName = eval("aName" + i);
 				  
 				   if(e==aName){
 				   		d = document.getElementById('vergeben');
 				   		d.style.display = "";
 				   		return;
 				   }else{
 				   		d = document.getElementById('vergeben');
 				   		d.style.display = "none";
 				   }
				}
		   }
		</script>


	</head>
<label style="width: 10%"></label>
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
									<a href="#">Erstellen</a>
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
						
						<h1>Anwendung erstellen</h1>
						<hr />
						<form name="form" method="post" action="erstellen.php" enctype="multipart/form-data">
						<div class="col-md-6">
						<label style="width: 30%">Name der Anwendung:</label>
						<input required="required" pattern="[A-Za-z0-9]*" type="text" onkeyup="pruefeName()" id="name" name="name"/><label id="vergeben" style="display:none ;margin-left:4px; color: red"> Name bereits vergeben</label><br /><br />
						<label style="width: 30%">Beschreibung:</label><br />
						<textarea name="beschreibung" cols="40" rows="2"></textarea><br /><br />
						<label style="width: 30%">Grafik:</label>
						<input type="file" name="grafik"/><br />
						<label style="width: 30%">Gewünschtes Board:</label>
						<select name="board">
							<?php
								$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
								Benutzername oder Passwort sind falsch");
								mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
								$sql = "select beschreibung from AVR.Boards";
								$ergebnis = mysql_query($sql);
								while ($row=mysql_fetch_object($ergebnis)){
									 echo "<option>$row->beschreibung</option>";      
           					    } 
							 ?>	
						</select>
						<br /><br />
						<label style="width: 30%">Benutzer:</label>
						<?php
							$user = $_SESSION["username"];
							echo "<input type=\"text\" name=\"user\" value=\"$user\" readonly/>";
						 ?>	
						<br /><br />
						<label style="width: 30%">Betroffene Pins:</label>
						<table class="table">
							<tr>
									<td></td>
									<td align="center">Ausgänge</td>
									<td align="center">Eingänge</td>
									<td align="center">ADC</td>
								</tr>
								<tr>
									<td>1</td>
									<td align="center">
									<input name="out1" type="checkbox" checked="true"/>
									</td>
									<td align="center">
									<input name="in1" type="checkbox" checked="true"/>
									</td>
									<td align="center">
									<input name="ADC1" type="checkbox" checked="true"/>
									</td>
								</tr>
								<tr>
									<td>2</td>
									<td align="center">
									<input name="out2" type="checkbox" checked="true"/>
									</td>
									<td align="center">
									<input name="in2" type="checkbox" checked="true"/>
									</td>
									<td align="center">
									<input name="ADC2" type="checkbox" checked="true"/>
									</td>
								</tr>
								<tr>
									<td>3</td>
									<td align="center">
									<input name="out3" type="checkbox" checked="true"/>
									</td>
									<td align="center">
									<input name="in3" type="checkbox" checked="true"/>
									</td>
									<td align="center">
									<input name="ADC3" type="checkbox" checked="true"/>
									</td>
								</tr>
								<tr>
									<td>4</td>
									<td align="center">
									<input name="out4" type="checkbox" checked="true"/>
									</td>
									<td align="center">
									<input name="in4" type="checkbox" checked="true"/>
									</td>
									<td align="center">
									<input name="ADC4" type="checkbox" checked="true"/>
									</td>
								</tr>
								<tr>
									<td>5</td>
									<td align="center">
									<input name="out5" type="checkbox" checked="true"/>
									</td>
								</tr>
								<tr>
									<td>6</td>
									<td align="center">
									<input name="out6" type="checkbox" checked="true"/>
									</td>
								</tr>
								<tr>
									<td>7</td>
									<td align="center">
									<input name="out7" type="checkbox" checked="true"/>
									</td>
								</tr>
								<tr>
									<td>8</td>
									<td align="center">
									<input name="out8" type="checkbox" checked="true"/>
									</td>
								</tr>
						</table>
						</div>
						<div class="col-md-6">
						<label style="width: 20%">Skript:</label><br />
						<textarea id="text" name="skript" cols="60" rows="20">int main(int argc, char **argv) {&#10	char* board = argv[1];&#10 &#10 &#10 &#10	return 0;&#10}
							
						</textarea>
						</div>
						<div class="col-lg-12">
							<p align="right">
							<button>Submit</button>
							</p>
						</div>
						</form>
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