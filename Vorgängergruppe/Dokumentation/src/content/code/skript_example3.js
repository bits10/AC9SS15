var count = getDb("count", "0");

switch (count) {
case "0":
	setValue('D7',0);
	setValue('D2',1);
	console.log(count);
	count = 1;
	break;
case "1":
	setValue('D2',0);
	setValue('D3',1);
	count = 2;
	break;
case "2":
	setValue('D3',0);
	setValue('D4',1);
	count = 3;
	break;
case "3":
	setValue('D4',0);
	setValue('D5',1);
	count = 4;
	break;
case "4":
	setValue('D5',0);
	setValue('D6',1);
	count = 5;
	break;
case "5":
	setValue('D6',0);
	setValue('D7',1);
	count = 0;
	break;
} 

putDb("count", count);