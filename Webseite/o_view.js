function draw(){
	var canvas = document.getElementById('canvas');
	if (canvas.getContext){
		canvas = canvas.getContext('2d');
		canvas.fillStyle = "rgb(50,205,50)";
		canvas.fillRect(0,0,100,75);
		canvas.fillStyle = "rgb(0,0,0)";
		canvas.fillRect(30,24,25, 8);
		canvas.fillRect(30,45,40,15);
		canvas.fillStyle = "rgb(211,211,211)";
		canvas.fillRect(0,20,17,15)
	}
}