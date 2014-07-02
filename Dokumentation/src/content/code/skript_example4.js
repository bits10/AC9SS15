var count = getDb("count", "0");

var set7Digit = function(number){
	var pins = [[0,0,1,0,0,0,0],
				[0,1,1,1,1,1,0],
				[0,0,0,1,0,0,1],
				[0,0,0,1,1,0,0],
				[0,1,0,0,1,1,0],
				[1,0,0,0,1,0,0],
				[1,0,0,0,0,0,0],
				[0,0,1,1,1,1,0],
				[0,0,0,0,0,0,0],
				[0,0,0,0,1,0,0]]

	setValue('C0',pins[number][0]);
	setValue('C1',pins[number][1]);
	setValue('C2',pins[number][2]);
	setValue('C3',pins[number][3]);
	setValue('C4',pins[number][4]);
	setValue('C5',pins[number][5]);
	setValue('C6',pins[number][6]);
};

switch (count) {
case "0":
	set7Digit(count);
	count = 1;
	break;
case "1":
	set7Digit(count);
	count = 2;
	break;
case "2":
	set7Digit(count);
	count = 3;
	break;
case "3":
	set7Digit(count);
	count = 4;
	break;
case "4":
	set7Digit(count);
	count = 5;
	break;
case "5":
	set7Digit(count);
	count = 6;
	break;
case "6":
	set7Digit(count);
	count = 7;
	break;
case "7":
	set7Digit(count);
	count = 8;
	break;
case "8":
	set7Digit(count);
	count = 9;
	break;
case "9":
	set7Digit(count);
	count = 0;
	break;
} 

putDb("count", count);