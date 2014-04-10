var urlValues="rest/values";
var urlEditable="rest/editable";
var urlModify="rest/modify?";
var urlInfo="rest/info";
var urlModifyIP="rest/setip?ip=";
var onValuesChanged;
var cachedInfo;
var cachedValues;
var cachedEditable;
var pollingFreq=1000;
var realPollingFreq=0;
var lastPollingTime=Date.now();
var isInitialised;
var isPaused=0;
/*
 * Inits the REST interface. Should only be called once at the startup of the page!
 */
function initRest(){
	if(isInitialised)
		return;
	
	setOnValuesChange(function(values, time){
		document.getElementById('freq_ist').innerHTML=time;
		var el=document.getElementById('rest');
		el.innerHTML="";
		for(var i=0;i<cachedValues.length;i++)
			el.innerHTML+="<br>"+(isEditable(i)?"Eingang ":"Ausgang ")+i+": "+getValue(i);
	});
	refreshValues();
	isInitialised=1;
}

/*
 * Internally used function to refresh the values in an given interval.
 */
function refreshValues(){
	cachedValues=JSON.parse(loadURL(urlValues));
	
	realPollingFreq=Date.now()-lastPollingTime;
	lastPollingTime=Date.now();
	
	onValuesChanged(cachedValues, realPollingFreq);	
	
	if(!isPaused)
		window.setTimeout("refreshValues()",pollingFreq);
}

/**
 * Loads the contents of the given URL and returns the laoded content. 
 * This funtion is incompatible with IE 6 and older.
 * @param {Object} the URL of the requested resource.
 * @return The loaded content.
 */
function loadURL(url){
  	x=new XMLHttpRequest();
	x.open("GET",url,false);
	x.send();
	
	return x.responseText;
}

/**
 * Sets the function invoked when a new value set was recieved by the server. 
 * The function should have two parameters. The first is the value array recieved 
 * and the second is the current polling rate in milliseconds.
 * @param {Object} The function to be invoked after a new value set was recieved.
 */
function setOnValuesChange(func){
	onValuesChanged=func;
}

/**
 * Returns the value for the pin or analog input with the id.
 * @param {Object} the value of the pin or analog input.
 */
function getValue(id){
	return cachedValues[id];
}

/**
 * Sets the value for the pin with the id. Has no effect if the pin is not editable.
 * Returns the answer of the server.
 * @param {Object} the id of the pin to modify
 * @param {Object} the new value. Should be 1 or 0.
 */
function setValue(id,value){
	return loadURL(urlModify+id+'='+value);
}

/**
 * Returns if the pin with the given id is editable.
 * @param {Object} The id of the pin.
 */
function isEditable(id){
	if(!cachedEditable)
		cachedEditable=JSON.parse(loadURL(urlEditable));
		
	return cachedEditable[id];
}

/**
 * Returns the info array, including 'ip', 'def_ip', 'mac' and 'version'.
 * Usage: getInfo().ip
 */
function getInfo(){
	if(!cachedInfo)
		cachedInfo=JSON.parse(loadURL(urlInfo));
		
	return cachedInfo;
}

/**
 * Sets the ip of the board and causes the side to reload.
 * @param {Object} the new ip of the board
 */
function setIP(ip){
	loadUrl(urlModifyIP+ip);
	window.location = ip+"/index.html";
}

/**
 * Toggles the isPaused field and starts the refreshing process again if 
 * the pause mode was disabled. Returns true if the pause mode was entered and
 * false if the pause mode was disabled.
 */
function togglePause() {
	isPaused=!isPaused;
	
	if(!isPaused)
		refreshValues();
		
	return isPaused;
}

