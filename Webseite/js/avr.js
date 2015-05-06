var aktiv = window.setInterval("refresh()", 100);
function getStat(board) {
	getOutStat(board);
	getInStat(board);
	getAnIn(board);
}

function changeBoard(board) {
	if (board == 1) {
		document.getElementById("board2").checked = false;
		document.getElementById("board3").checked = false;
	}
	if (board == 2) {
		document.getElementById("board1").checked = false;
		document.getElementById("board3").checked = false;
	}
	if (board == 3) {
		document.getElementById("board1").checked = false;
		document.getElementById("board2").checked = false;
	}

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

function refresh() {
	var board;
	if (document.getElementById("board1").checked == true) {
		board = 1;
	} else if (document.getElementById("board2").checked == true) {
		board = 2;
	} else if (document.getElementById("board3").checked == true) {
		board = 3;
	} else {
		return false;
	}
	getStat(board);
}

function getOutStat(board) {
	$.ajax({
		type : "GET",
		dataType : "json",
		url : "/rest/getStatus?boardID=" + board,
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

function getInStat(board) {
	$.ajax({
		type : "GET",
		dataType : "json",
		url : "/rest/getInPort?boardID=" + board,
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

function getAnIn(board) {
	$.ajax({
		type : "GET",
		dataType : "json",
		url : "/rest/getAnalogInPort?boardID=" + board,
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

function initLCD() {
	if (document.getElementById("board1").checked == true) {
		board = 1;
	} else if (document.getElementById("board2").checked == true) {
		board = 2;
	} else if (document.getElementById("board3").checked == true) {
		board = 3;
	} else {
		alert("please select board");
		return;
	}
	$.ajax({
		type : "GET",
		dataType : "json",
		url : "/rest/initLCD?boardID=" + board,
		success : function(data) {
			
		},
		error : function() {

		}
	});
}

function clearLCD() {
	if (document.getElementById("board1").checked == true) {
		board = 1;
	} else if (document.getElementById("board2").checked == true) {
		board = 2;
	} else if (document.getElementById("board3").checked == true) {
		board = 3;
	} else {
		alert("please select board");
		return;
	}
	$.ajax({
		type : "GET",
		dataType : "json",
		url : "/rest/clearLCD?boardID=" + board,
		success : function(data) {
			
		},
		error : function() {

		}
	});
}

function writeLCD() {
	if (document.getElementById("board1").checked == true) {
		board = 1;
	} else if (document.getElementById("board2").checked == true) {
		board = 2;
	} else if (document.getElementById("board3").checked == true) {
		board = 3;
	} else {
		alert("please select board");
		return;
	}
	var text1 = document.getElementById('text1').value;
	var text2 = document.getElementById('text2').value;
	$.ajax({
		type : "GET",
		dataType : "json",
		url : "/rest/writeLCD?boardID=" + board+"&line=1&text="+text1,
		success : function(data) {
			
		},
		error : function() {

		}
	});
		$.ajax({
		type : "GET",
		dataType : "json",
		url : "/rest/writeLCD?boardID=" + board+"&line=2&text="+text2,
		success : function(data) {
			
		},
		error : function() {

		}
	});
}

function setPorts() {
	var board;
	if (document.getElementById("board1").checked == true) {
		board = 1;
	} else if (document.getElementById("board2").checked == true) {
		board = 2;
	} else if (document.getElementById("board3").checked == true) {
		board = 3;
	} else {
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
		url : "/rest/setPorts?boardID=" + board + "&values=" + ports,
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
