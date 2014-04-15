var urlValues="rest/values";
var urlPinInfo="rest/pininfo";
var urlModify="rest/setpin?";
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
		var el=getEl('rest');
		el.innerHTML="";
		for(var i=0;i<getPinCount();i++)
			el.innerHTML+="<br>"+(getId(i)+": "+values[i]);
	});
	
	setOnNetworkError(function(state, response){
		alert("A Network error occured. Please Reload the site.\nState: " + state);
		console.log(response);
	});
	
	cachedInfo=JSON.parse(loadURL(urlInfo));
	cachedPinInfo=JSON.parse(loadURL(urlPinInfo));
	cachedValues=JSON.parse(loadURL(urlValues));
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
	return cachedValues[getIndex(id)];
}

/**
 * Sets the value for the pin with the id. Has no effect if the pin is not editable.
 * Returns the answer of the server.
 * @param {Object} the id of the pin to modify
 * @param {Object} the new value. Should be 1 or 0.
 */
function setValue(id,value){
	return loadURLAsync(urlModify+id+'='+value, function(){});
}

/**
 * Returns if the pin with the given id is editable.
 * @param {Object} The id of the pin.
 */
function isEditable(id){
	return cachedPinInfo[getIndex(id)].e;
}

/**
 * Returns if the pin with the given id is a digital one.
 * @param {Object} The id of the pin.
 */
function isDigital(id){
	return cachedPinInfo[getIndex(id)].t=='d';
}

/**
 * Returns the name of the pin with the given id.
 * @param {Object} The name of the pin.
 */
function getName(id){
	return cachedPinInfo[getIndex(id)].n;
}

/**
 * Returns the info array, including 'ip', 'def_ip', 'mac' and 'version'.
 * Usage: getInfo().ip
 */
function getInfo(){		
	return cachedInfo;
}

/**
 * Returns the pin info array, including 'e' (editable, 0 or 1), 'id' (the pin's id),'n' (the pin's name) and 't' (the pin's type, d or a).
 * Usage: getPinInfo()[0].id
 */
function getPinInfo(){		
	return cachedPinInfo;
}

/**
 * Returns the count of the available pins.
 * @return the count of the available pins.
 */
function getPinCount(){
	return this.cachedPinInfo.length;
}

/**
 * Returns the default name of the pin with the given id.
 * @param {Object} The name of the pin.
 */
function getId(index){
	return cachedPinInfo[getIndex(id)].id;
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
 * Returns the id of the pin stored at the given index.
 */
function getId(index){
	return cachedPinInfo[index].id;
}

/*
 * Returns the index in all data structures of the pin with the given id.
 * @param {Object} the id of the requested pin
 * @return The index of the pin with the given id or -1 if no entry was found
 */
function getIndex(id){
	for(var i=0;i<cachedPinInfo.length;i++)
		if(cachedPinInfo[i].id==id)
			return i;
			
	return -1;
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

