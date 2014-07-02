var count = getDb("count", "0");
var timer = getDb("timer", "0");

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

if (getValue('A0') == "1") {
	if (timer == "1") {
		setValue('C7', 1);
		set7Digit(count);
		if (count > 8) {
			count = 0;
		} else{
			count++;
		}
		putDb("count", count);
	} 
	timer++;
	putDb("timer", timer);
	
} 

if (getValue('A0') == "0") {
	setValue('C7', 0);
	putDb("timer", "0");
}