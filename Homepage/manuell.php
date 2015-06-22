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
			var aktiv = window.setInterval("refresh()", 1000);
			function getStat(board) {
				getOutStat(board);
				getInStat(board);
				getAnIn(board);
			}
		
			function changeBoard(board) {
				if (board == 1) {
					document.getElementById("board2").checked = false;
					//document.getElementById("board3").checked = false;
					<?php
						$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
						Benutzername oder Passwort sind falsch");
						mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
						$sql = "select ip from AVR.Boards";
						$ergebnis = mysql_query($sql);
						$row = mysql_fetch_object($ergebnis);
						echo "board = \"$row->ip\";";
					 ?>
				}
				if (board == 2) {
					document.getElementById("board1").checked = false;
					//document.getElementById("board3").checked = false;
					<?php
						$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
						Benutzername oder Passwort sind falsch");
						mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
						$sql = "select ip from AVR.Boards";
						$ergebnis = mysql_query($sql);
						$row = mysql_fetch_object($ergebnis);
						$row = mysql_fetch_object($ergebnis);
						echo "board = \"$row->ip\";";
					 ?>
				}
				/*if (board == 3) {
					document.getElementById("board1").checked = false;
					document.getElementById("board2").checked = false;
					<?php
						$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
						Benutzername oder Passwort sind falsch");
						mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
						$sql = "select ip from AVR.Boards";
						$ergebnis = mysql_query($sql);
						$row = mysql_fetch_object($ergebnis);
						$row = mysql_fetch_object($ergebnis);
						$row = mysql_fetch_object($ergebnis);
						echo "board = \"$row->ip\";";
					 ?>
				}*/
				for (var i = 1; i <= 8; i++) {
					document.getElementById("out" + i + "HIGH").checked = false;
					document.getElementById("out" + i + "LOW").checked = false;
				}
				for (var i = 1; i <= 4; i++) {
					document.getElementById("in" + i + "HIGH").checked = false;
					document.getElementById("in" + i + "LOW").checked = false;
				}
				for (var i = 1; i <= 8; i++) {
					document.getElementById("out" + i).checked = false;
				}
				for (var i = 1; i <= 4; i++) {
					document.getElementById("ADCvalue" + i).innerHTML = "";
				}
				getStat(board);
			}
			
			function getAnIn(board) {
				$.ajax({
					type : "GET",
					dataType : "json",
					url : "/rest/getAnalogInPort?boardIP=" + board,
					success : function(data) {
						var data1 = data.values;
						document.getElementById("ADCvalue1").innerHTML = data1[0];
						document.getElementById("ADCvalue2").innerHTML = data1[1];
						document.getElementById("ADCvalue3").innerHTML = data1[2];
						document.getElementById("ADCvalue4").innerHTML = data1[3];
					},
					error : function() {
			
					}
				});
			}

			
			function getInStat(board) {
				$.ajax({
					type : "GET",
					dataType : "json",
					url : "/rest/getInPort?boardIP=" + board,
					success : function(data) {
						var data1 = data.status;
						if (data1[0] == true) {
							document.getElementById("in1HIGH").checked = true;
							document.getElementById("in1LOW").checked = false;
						} else {
							document.getElementById("in1HIGH").checked = false;
							document.getElementById("in1LOW").checked = true;
						}
						if (data1[1] == true) {
							document.getElementById("in2HIGH").checked = true;
							document.getElementById("in2LOW").checked = false;
						} else {
							document.getElementById("in2HIGH").checked = false;
							document.getElementById("in2LOW").checked = true;
						}
						if (data1[2] == true) {
							document.getElementById("in3HIGH").checked = true;
							document.getElementById("in3LOW").checked = false;
						} else {
							document.getElementById("in3HIGH").checked = false;
							document.getElementById("in3LOW").checked = true;
						}
						if (data1[3] == true) {
							document.getElementById("in4HIGH").checked = true;
							document.getElementById("in4LOW").checked = false;
						} else {
							document.getElementById("in4HIGH").checked = false;
							document.getElementById("in4LOW").checked = true;
						}
					},
				});
			}

			function refresh() {
				var board;
				if (document.getElementById("board1").checked == true) {
					<?php
						$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
						Benutzername oder Passwort sind falsch");
						mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
						$sql = "select ip from AVR.Boards";
						$ergebnis = mysql_query($sql);
						$row = mysql_fetch_object($ergebnis);
						echo "board = \"$row->ip\";";
					 ?>
				} else if (document.getElementById("board2").checked == true) {
					<?php
						$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
						Benutzername oder Passwort sind falsch");
						mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
						$sql = "select ip from AVR.Boards";
						$ergebnis = mysql_query($sql);
						$row = mysql_fetch_object($ergebnis);
						$row = mysql_fetch_object($ergebnis);
						echo "board = \"$row->ip\";";
					 ?>
				}/* else if (document.getElementById("board3").checked == true) {
					<?php
						$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
						Benutzername oder Passwort sind falsch");
						mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
						$sql = "select ip from AVR.Boards";
						$ergebnis = mysql_query($sql);
						$row = mysql_fetch_object($ergebnis);
						$row = mysql_fetch_object($ergebnis);
						$row = mysql_fetch_object($ergebnis);
						echo "board = \"$row->ip\";";
					 ?>
				}*/else {
					return false;
				}
				getStat(board);
			}
		
			function getOutStat(board) {
				$.ajax({
					type : "GET",
					dataType : "json",
					url : "/rest/getStatus?boardIP=" + board,
					success : function(data) {
						var data1 = data.status;
						if (data1[0] == true) {
							document.getElementById("out1HIGH").checked = true;
							document.getElementById("out1LOW").checked = false;
						} else {
							document.getElementById("out1HIGH").checked = false;
							document.getElementById("out1LOW").checked = true;
						}
						if (data1[1] == true) {
							document.getElementById("out2HIGH").checked = true;
							document.getElementById("out2LOW").checked = false;
						} else {
							document.getElementById("out2HIGH").checked = false;
							document.getElementById("out2LOW").checked = true;
						}
						if (data1[2] == true) {
							document.getElementById("out3HIGH").checked = true;
							document.getElementById("out3LOW").checked = false;
						} else {
							document.getElementById("out3HIGH").checked = false;
							document.getElementById("out3LOW").checked = true;
						}
						if (data1[3] == true) {
							document.getElementById("out4HIGH").checked = true;
							document.getElementById("out4LOW").checked = false;
						} else {
							document.getElementById("out4HIGH").checked = false;
							document.getElementById("out4LOW").checked = true;
						}
						if (data1[4] == true) {
							document.getElementById("out5HIGH").checked = true;
							document.getElementById("out5LOW").checked = false;
						} else {
							document.getElementById("out5HIGH").checked = false;
							document.getElementById("out5LOW").checked = true;
						}
						if (data1[5] == true) {
							document.getElementById("out6HIGH").checked = true;
							document.getElementById("out6LOW").checked = false;
						} else {
							document.getElementById("out6HIGH").checked = false;
							document.getElementById("out6LOW").checked = true;
						}
						if (data1[6] == true) {
							document.getElementById("out7HIGH").checked = true;
							document.getElementById("out7LOW").checked = false;
						} else {
							document.getElementById("out7HIGH").checked = false;
							document.getElementById("out7LOW").checked = true;
						}
						if (data1[7] == true) {
							document.getElementById("out8HIGH").checked = true;
							document.getElementById("out8LOW").checked = false;
						} else {
							document.getElementById("out8HIGH").checked = false;
							document.getElementById("out8LOW").checked = true;
						}
					},
				});
			}
		
			function initLCD() {
				if (document.getElementById("board1").checked == true) {
					<?php
						$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
						Benutzername oder Passwort sind falsch");
						mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
						$sql = "select ip from AVR.Boards";
						$ergebnis = mysql_query($sql);
						$row = mysql_fetch_object($ergebnis);
						echo "board = \"$row->ip\";";
					 ?>
				} else if (document.getElementById("board2").checked == true) {
					<?php
						$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
						Benutzername oder Passwort sind falsch");
						mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
						$sql = "select ip from AVR.Boards";
						$ergebnis = mysql_query($sql);
						$row = mysql_fetch_object($ergebnis);
						$row = mysql_fetch_object($ergebnis);
						echo "board = \"$row->ip\";";
					 ?>
				}/* else if (document.getElementById("board3").checked == true) {
					<?php
						$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
						Benutzername oder Passwort sind falsch");
						mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
						$sql = "select ip from AVR.Boards";
						$ergebnis = mysql_query($sql);
						$row = mysql_fetch_object($ergebnis);
						$row = mysql_fetch_object($ergebnis);
						$row = mysql_fetch_object($ergebnis);
						echo "board = \"$row->ip\";";
					 ?>
				}*/ else {
					alert("please select board");
					return;
				}
				$.ajax({
					type : "GET",
					dataType : "json",
					url : "/rest/initLCD?boardIP=" + board,
					success : function(data) {

					},
					error : function() {

					}
				});
			}

			function clearLCD() {
				if (document.getElementById("board1").checked == true) {
					<?php
						$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
						Benutzername oder Passwort sind falsch");
						mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
						$sql = "select ip from AVR.Boards";
						$ergebnis = mysql_query($sql);
						$row = mysql_fetch_object($ergebnis);
						echo "board = \"$row->ip\";";
					 ?>	
				} else if (document.getElementById("board2").checked == true) {
					<?php
						$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
						Benutzername oder Passwort sind falsch");
						mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
						$sql = "select ip from AVR.Boards";
						$ergebnis = mysql_query($sql);
						$row = mysql_fetch_object($ergebnis);
						$row = mysql_fetch_object($ergebnis);
						echo "board = \"$row->ip\";";
					 ?>	
				}/* else if (document.getElementById("board3").checked == true) {
					<?php
						$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
						Benutzername oder Passwort sind falsch");
						mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
						$sql = "select ip from AVR.Boards";
						$ergebnis = mysql_query($sql);
						$row = mysql_fetch_object($ergebnis);
						$row = mysql_fetch_object($ergebnis);
						$row = mysql_fetch_object($ergebnis);
						echo "board = \"$row->ip\";";
					 ?>	
				}*/ else {
					alert("please select board");
					return;
				}
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

			function writeLCD() {
				if (document.getElementById("board1").checked == true) {
					<?php
						$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
						Benutzername oder Passwort sind falsch");
						mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
						$sql = "select ip from AVR.Boards";
						$ergebnis = mysql_query($sql);
						$row = mysql_fetch_object($ergebnis);
						echo "board = \"$row->ip\";";
					 ?>	
				} else if (document.getElementById("board2").checked == true) {
				 	<?php
						$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
						Benutzername oder Passwort sind falsch");
						mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
						$sql = "select ip from AVR.Boards";
						$ergebnis = mysql_query($sql);
						$row = mysql_fetch_object($ergebnis);
						$row = mysql_fetch_object($ergebnis);
						echo "board = \"$row->ip\";";
					 ?>	
				}/* else if (document.getElementById("board3").checked == true) {
					<?php
						$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
						Benutzername oder Passwort sind falsch");
						mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
						$sql = "select ip from AVR.Boards";
						$ergebnis = mysql_query($sql);
						$row = mysql_fetch_object($ergebnis);
						$row = mysql_fetch_object($ergebnis);
						$row = mysql_fetch_object($ergebnis);
						echo "board = \"$row->ip\";";
					 ?>	
				}*/ else {
					alert("please select board");
					return;
				}
				var text1 = document.getElementById('text1').value;
				var text2 = document.getElementById('text2').value;
				$.ajax({
					type : "GET",
					dataType : "json",
					url : "/rest/writeLCD?boardIP=" + board+"&line=1&text="+text1,
					success : function(data) {
			
					},
					error : function() {

					}
				});
				$.ajax({
					type : "GET",
					dataType : "json",
					url : "/rest/writeLCD?boardIP=" + board+"&line=2&text="+text2,
					success : function(data) {
				
					},
					error : function() {

					}
				});
			}
		
			function setPorts() {
				var board;
				if (document.getElementById("board1").checked == true) {
				 	<?php
						$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
						Benutzername oder Passwort sind falsch");
						mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
						$sql = "select ip from AVR.Boards";
						$ergebnis = mysql_query($sql);
						$row = mysql_fetch_object($ergebnis);
						echo "board = \"$row->ip\";";
					 ?>	
				} else if (document.getElementById("board2").checked == true) {
				 	<?php
						$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
						Benutzername oder Passwort sind falsch");
						mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
						$sql = "select ip from AVR.Boards";
						$ergebnis = mysql_query($sql);
						$row = mysql_fetch_object($ergebnis);
						$row = mysql_fetch_object($ergebnis);
						echo "board = \"$row->ip\";";
					 ?>
				} /*else if (document.getElementById("board3").checked == true) {
					<?php
						$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
						Benutzername oder Passwort sind falsch");
						mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
						$sql = "select ip from AVR.Boards";
						$ergebnis = mysql_query($sql);
						$row = mysql_fetch_object($ergebnis);
						$row = mysql_fetch_object($ergebnis);
						$row = mysql_fetch_object($ergebnis);
						echo "board = \"$row->ip\";";
					 ?>
				}*/ else {
					alert("please select board");
					return;
				}
					var ports = "S";
	if (document.getElementById("out1").checked == true) {
		ports = ports + "1";
	} else {
		ports = ports + "0";
	}
	if (document.getElementById("out2").checked == true) {
		ports = ports + "1";
	} else {
		ports = ports + "0";
	}
	if (document.getElementById("out3").checked == true) {
		ports = ports + "1";
	} else {
		ports = ports + "0";
	}
	if (document.getElementById("out4").checked == true) {
		ports = ports + "1";
	} else {
		ports = ports + "0";
	}
	if (document.getElementById("out5").checked == true) {
		ports = ports + "1";
	} else {
		ports = ports + "0";
	}
	if (document.getElementById("out6").checked == true) {
		ports = ports + "1";
	} else {
		ports = ports + "0";
	}
	if (document.getElementById("out7").checked == true) {
		ports = ports + "1";
	} else {
		ports = ports + "0";
	}
	if (document.getElementById("out8").checked == true) {
		ports = ports + "1";
	} else {
		ports = ports + "0";
	}
	$.ajax({
		type : "GET",
		dataType : "json",
		url : "/rest/setPorts?boardIP=" + board + "&values=" + ports,
		success : function(data) {
			var data1 = data.status;
			if (data1[0] == true) {
				document.getElementById("out1").checked = true;
			} else {
				document.getElementById("out1").checked = false;
			}
			if (data1[1] == true) {
				document.getElementById("out2").checked = true;
			} else {
				document.getElementById("out2").checked = false;
			}
			if (data1[2] == true) {
				document.getElementById("out3").checked = true;
			} else {
				document.getElementById("out3").checked = false;
			}
			if (data1[3] == true) {
				document.getElementById("out4").checked = true;
			} else {
				document.getElementById("out4").checked = false;
			}
			if (data1[4] == true) {
				document.getElementById("out5").checked = true;
			} else {
				document.getElementById("out5").checked = false;
			}
			if (data1[5] == true) {
				document.getElementById("out6").checked = true;
			} else {
				document.getElementById("out6").checked = false;
			}
			if (data1[6] == true) {
				document.getElementById("out7").checked = true;
			} else {
				document.getElementById("out7").checked = false;
			}
			if (data1[7] == true) {
				document.getElementById("out8").checked = true;
			} else {
				document.getElementById("out8").checked = false;
			}
		},
		error : function() {

		}
	});
			}
		</script>
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
								<?php
								$verbindung = mysql_connect("localhost", "root", "ProjektSS15") or die("keine Verbindung möglich.
								Benutzername oder Passwort sind falsch");
								mysql_select_db("AVR") or die("Die Datenbank existiert nicht.");
								$sql = "select beschreibung from AVR.Boards";
								$ergebnis = mysql_query($sql);
								$i = 1;
								while ($row = mysql_fetch_object($ergebnis)) {
									echo "
									<input id=\"board$i\" type=\"radio\" name=\"board$i\" value=\"board$i\" onclick=\"changeBoard($i)\">$row->beschreibung";
									echo "<span style=\"margin-left: 20px\"></span>";
									$i++;
								}
								?>
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
							<button onclick="initLCD()">
								Initialisieren
							</button>
							<button onclick="clearLCD()">
								Löschen
							</button>
							<br />
							<br />
							Zeile 1:
							<input id="text1" type="text" maxlength="16" />
							<br />
							Zeile 2:
							<input id="text2" type="text" maxlength="16" />
							<br />
							<br />
							<button onclick="writeLCD()">
								Senden
							</button>
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