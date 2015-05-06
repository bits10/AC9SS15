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
		<link href="css/a4cloud.css" rel="stylesheet">
		<link href="css/prettify.css" rel="stylesheet">
		<script src="../../assets/js/ie-emulation-modes-warning.js"></script>
		<script src="js/avr.js"></script>
	</head>

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
						<h1>Manuelle Steuerung</h1>
						<hr />
			
					</div>
					<div class="col-md-12">
					
						<div style="display: none">
							<h1 style="color: red">Backend unavailable</h1>
						</div>
						<div>
							<p>
								<b>Gewünschtes Board:</b>:
							</p>
							<p>
								<input id="board1" type="radio" name="board1" value="board1" onclick="changeBoard(1)">
								Board 1
								<input id="board2" style="margin-left: 20px" type="radio" name="board2" value="board2" onclick="changeBoard(2)">
								Board 2
								<input id="board3" style="margin-left: 20px" type="radio" name="board3" value="board3" onclick="changeBoard(3)">
								Board 3
							</p>
							<br />
						</div>
						<div style="float: left">
							<p>
								<b>Ausgänge:</b>
							</p>
							<p>
								<input id="out1" type="checkbox" name="out" value="out1">
								Ausgang 1
								<input style="margin-left: 20px" id="out2" type="checkbox" name="out" value="out2">
								Ausgang 2
								<br>
								<input type="checkbox" id="out3" name="out" value="out3">
								Ausgang 3

								<input style="margin-left: 20px" id="out4" type="checkbox" name="out" value="out4">
								Ausgang 4
								<br>
								<input type="checkbox" id="out5" name="out" value="out5">
								Ausgang 5

								<input style="margin-left: 20px" id="out6" type="checkbox" name="out" value="out6">
								Ausgang 6
								<br>
								<input type="checkbox" id="out7" name="out" value="out7">
								Ausgang 7

								<input style="margin-left: 20px" id="out8" type="checkbox" name="out" value="out8">
								Ausgang 8
								<br />
								<br />
								<button style="margin-top: 10px" onclick="setPorts()">
									Senden
								</button>
							</p>
							<br />
							<p>
								<b>LCD:</b>
							</p>
							<button onclick="initLCD()">Initialisieren</button>
							<button onclick="clearLCD()">Löschen</button><br /><br />
							Zeile 1: <input id="text1" type="text" maxlength="16" /><br />
							Zeile 2: <input id="text2" type="text" maxlength="16" /><br /><br />
							<button onclick="writeLCD()">Senden</button>
						</div>
						<div style="float:left; margin-left: 150px">
							<p>
								<b>Status Ausgänge:</b>
							</p>
							<table class="table">
								<tr>
									<td></td>
									<td>HIGH</td>
									<td>LOW</td>
								</tr>
								<tr>
									<td>1</td>
									<td align="center">
									<input onclick="return false" id="out1HIGH" type="radio" name="out1HIGH" value="out1HIGH">
									</td>
									<td align="center">
									<input onclick="return false" id="out1LOW" type="radio" name="out1LOW" value="out1LOW">
									</td>
								</tr>
								<tr>
									<td>2</td>
									<td align="center">
									<input onclick="return false" id="out2HIGH" type="radio" name="out2HIGH" value="out2HIGH">
									</td>
									<td align="center">
									<input onclick="return false" id="out2LOW" type="radio" name="out2LOW" value="out2LOW">
									</td>
								</tr>
								<tr>
									<td>3</td>
									<td align="center">
									<input onclick="return false" id="out3HIGH" type="radio" name="out3HIGH" value="out3HIGH">
									</td>
									<td align="center">
									<input onclick="return false" id="out3LOW" type="radio" name="out3LOW" value="out3LOW">
									</td>
								</tr>
								<tr>
									<td>4</td>
									<td align="center">
									<input onclick="return false" id="out4HIGH" type="radio" name="out4HIGH" value="out4HIGH">
									</td>
									<td align="center">
									<input onclick="return false" id="out4LOW" type="radio" name="out4LOW" value="out4LOW">
									</td>
								</tr>
								<tr>
									<td>5</td>
									<td align="center">
									<input onclick="return false" id="out5HIGH" type="radio" name="out5HIGH" value="out5HIGH">
									</td>
									<td align="center">
									<input onclick="return false" id="out5LOW" type="radio" name="out5LOW" value="out5LOW">
									</td>
								</tr>
								<tr>
									<td>6</td>
									<td align="center">
									<input onclick="return false" id="out6HIGH" type="radio" name="out6HIGH" value="out6HIGH">
									</td>
									<td align="center">
									<input onclick="return false" id="out6LOW" type="radio" name="out6LOW" value="out6LOW">
									</td>
								</tr>
								<tr>
									<td>7</td>
									<td align="center">
									<input onclick="return false" id="out7HIGH" type="radio" name="out7HIGH" value="out7HIGH">
									</td>
									<td align="center">
									<input onclick="return false" id="out7LOW" type="radio" name="out7LOW" value="out7LOW">
									</td>
								</tr>
								<tr>
									<td>8</td>
									<td align="center">
									<input onclick="return false" id="out8HIGH" type="radio" name="out8HIGH" value="out8HIGH">
									</td>
									<td align="center">
									<input onclick="return false" id="out8LOW" type="radio" name="out8LOW" value="out8LOW">
									</td>
								</tr>
							</table>
						</div>
						<div style="float:left; margin-left: 150px">
							<p>
								<b>Status Eingänge:</b>
							</p>
							<table class="table">
								<tr>
									<td></td>
									<td>HIGH</td>
									<td>LOW</td>
								</tr>
								<tr>
									<td>1</td>
									<td align="center">
									<input onclick="return false" id="in1HIGH" type="radio" name="in1HIGH" value="in1HIGH">
									</td>
									<td align="center">
									<input onclick="return false" id="in1LOW" type="radio" name="in1LOW" value="in1LOW">
									</td>
								</tr>
								<tr>
									<td>2</td>
									<td align="center">
									<input onclick="return false" id="in2HIGH" type="radio" name="in2HIGH" value="in2HIGH">
									</td>
									<td align="center">
									<input onclick="return false" id="in2LOW" type="radio" name="in2LOW" value="in2LOW">
									</td>
								</tr>
								<tr>
									<td>3</td>
									<td align="center">
									<input onclick="return false" id="in3HIGH" type="radio" name="in3HIGH" value="in3HIGH">
									</td>
									<td align="center">
									<input onclick="return false" id="in3LOW" type="radio" name="in3LOW" value="in3LOW">
									</td>
								</tr>
								<tr>
									<td>4</td>
									<td align="center">
									<input onclick="return false" id="in4HIGH" type="radio" name="in4HIGH" value="in4HIGH">
									</td>
									<td align="center">
									<input onclick="return false" id="in4LOW" type="radio" name="in4LOW" value="in4LOW">
									</td>
								</tr>
							</table>
						</div>
						<div style="float:left; margin-left: 150px">
							<p>
								<b>ADC:</b>
							</p>
							<p>
								ADC1:<label style="margin-left: 10px" id="ADCvalue1"></label>
								<br />
								ADC2:<label style="margin-left: 10px" id="ADCvalue2"></label>
								<br />
								ADC3:<label style="margin-left: 10px" id="ADCvalue3"></label>
								<br />
								ADC4:<label style="margin-left: 10px" id="ADCvalue4"></label>
								<br />
							</p>
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