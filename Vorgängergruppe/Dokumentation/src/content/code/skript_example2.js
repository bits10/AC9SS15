setValue('D2',0);
setValue('D3',0);
setValue('D4',0);
setValue('D5',0);
setValue('D6',0);
setValue('D7',0);


var value = getValue('A5');

if (value > 869) {
	setValue('D2', 1);
}
if (value > 719) {
	setValue('D3', 1);
}
if (value > 569) {
	setValue('D4', 1);
}
if (value > 419) {
	setValue('D5', 1);
}
if (value > 269) {
	setValue('D6', 1);
}
if (value > 119) {
	setValue('D7', 1);
}