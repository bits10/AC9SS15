var urlValues="rest/values";
var urlPinInfo="rest/pininfo";
var urlModify="rest/modify?";
var urlInfo="rest/info";
var urlModifyIP="rest/setip?";
var onValuesChanged;
var onNetworkError;
var cachedInfo;
var cachedValues;
var cachedPinInfo;
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
			el.innerHTML+="<br>"+(getName(i)+": "+getValue(i));
	});
	
	setOnNetworkError(function(state, response){
		alert("A Network error occured. Please Reload the site.\nState: " + state);
		console.log(response);
	});
	
	getInfo();
	isEditable(0);
	refreshValues();
	isInitialised=1;
}

/*
 * Internally used function to refresh the values in an given interval.
 */
function refreshValues(){
	loadURLAsync(urlValues, function(state, response) {
		cachedValues=JSON.parse(response);
	
		realPollingFreq=Date.now()-lastPollingTime;
		lastPollingTime=Date.now();
	
		if(state==200) {
			onValuesChanged(cachedValues, realPollingFreq);	
	
			if(!isPaused)
				window.setTimeout("refreshValues()",pollingFreq);
				
		} else {
			onNetworkError(state, response);
		}
		
	});
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
 * Loads the contents of the given URL and returns the laoded content. 
 * This funtion is incompatible with IE 6 and older.
 * @param {Object} the URL of the requested resource.
 * @return The loaded content.
 */
function loadURLAsync(url, func){
  	x=new XMLHttpRequest();
  	x.onreadystatechange=function() {
  		if(x.readyState==4)
  			func(x.status,x.responseText);
  	};
	x.open("GET",url,true);
	x.send();
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
 * Sets the function invoked when a nnetwork error occured. 
 * The function should have two parameters. The first is the HTTP state returned by the XMLHttpRequest,
 * the second is the loaded text.
 * @param {Object} The function to be invoked when a network error occured.
 */
function setOnNetworkError(func){
	onNetworkError=func;
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
	if(!cachedPinInfo)
		cachedPinInfo=JSON.parse(loadURL(urlPinInfo));
		
	return cachedPinInfo[id].editable;
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
 * Returns the default name of the pin with the given id.
 * @param {Object} The name of the pin.
 */
function getName(id){
	if(!cachedPinInfo)
		cachedPinInfo=JSON.parse(loadURL(urlPinInfo));
		
	return cachedPinInfo[id].name;
}

/**
 * Sets the ip of the board and causes the side to reload.
 * @param {Object} the new ip of the board
 */
function setIP(ip){
	loadUrl(urlModifyIP+ip);
	window.location=ip+"/index.html";
}

/**
 * Toggles the isPaused field and starts the refreshing process again if 
 * the pause mode was disabled. Returns true if the pause mode was entered and
 * false if the pause mode was disabled.
 * @return Returns true if it is now paused and false otherwise
 */
function togglePause() {
	isPaused=!isPaused;
	
	if(!isPaused)
		refreshValues();
		
	return isPaused;
}

