var urlValues="rest/values";
var urlPinInfo="rest/pininfo";
var urlModifyValue="rest/setpin?";
var urlInfo="rest/info";
var onValuesChanged;
var onNetworkError;
var cachedInfo;
var cachedValues;
var cachedPinInfo;
var pollingFreq=1000;
var realPollingFreq=0;
var lastPollingTime=Date.now();
var isInitialised;
var taskIdCounter=0;
/*
 * Inits the REST interface. Should only be called once at the startup of the page!
 */
function initRest(){
	if(isInitialised)
		return;
	
	setOnValuesChanged(function(pinInfo, values, time){});
	
	setOnNetworkError(function(state, response){});
	
	cachedInfo=JSON.parse(loadURL(urlInfo));
	cachedPinInfo=JSON.parse(loadURL(urlPinInfo));
	cachedValues=JSON.parse(loadURL(urlValues));
	startNewRefershTask();
    pollingFreq=getDb('pollingFreq', 1000);
	isInitialised=1;
}

function cancelRefreshTask() {
    ++taskIdCounter;
}
function startNewRefershTask(postParams) {
	 refreshValues(++taskIdCounter, postParams);
}
/*function cancelRefreshTask() {
	taskIdCounter++;
}*/
/*
 * Internally used function to refresh the values in an given interval.
 */
function refreshValues(taskId, postParams){
	if(taskIdCounter!=taskId) {
		console.log("Task canceled!");
		return;
	}
	
	loadURLAsync(urlValues, postParams, function(state, response) {
		cachedValues=JSON.parse(response);
	
		realPollingFreq=Date.now()-lastPollingTime;
		lastPollingTime=Date.now();
	
		if(state==200) {
			onValuesChanged(cachedPinInfo, cachedValues, realPollingFreq);	
            window.setTimeout("refreshValues("+taskId+", null)",pollingFreq);
				
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
function loadURLAsync(url, postParams, func){
  	x=new XMLHttpRequest();
  	x.onreadystatechange=function() {
  		if(x.readyState==4)
  			func(x.status,x.responseText);
  	};
	
	if(postParams) {
        x.open("POST",url,true);
		x.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');	
		x.send(postParams);
	} else {
        x.open("GET",url,true);
		x.send();
	}
}

/**
 * Sets the function invoked when a new value set was recieved by the server. 
 * The function should have two parameters. The first is the value array recieved 
 * and the second is the current polling rate in milliseconds.
 * @param {Object} The function to be invoked after a new value set was recieved.
 */
function setOnValuesChanged(func){
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
	return cachedValues[getIndex(id)].v;
}

function setValue(id, value, apply) {
    cachedValues[getIndex(id)].v = value;

    if(apply)
        sendValues();
}
    
/**
 * Sets the value for the pin with the id. Has no effect if the pin is not editable.
 * Returns the answer of the server.
 * @param {Object} the id of the pin to modify
 * @param {Object} the new value. Should be 1 or 0.
 */
function sendValues(){
	//return loadURLAsync(urlModify+id+'='+value, function(){});
	cancelRefreshTask();
	var newValues = new Object();
	for(var k in cachedValues) {
		var id = getId(k);
		var num=id.charAt(1);
		var port=id.charAt(0);
		
		if(!newValues[port]) {
			newValues[port]=new Array(8);
			for (var i=0; i<newValues[port].length; i++) 
				newValues[port][i] = 0; 
		}
			
		if(getDD(id) == 'o') {
			newValues[port][num]=cachedValues[getIndex(id)].v;
		} 
	}
	
	var post = "";
	for(var k in newValues) {
		var hex = 0;
		for(var i=0; i<newValues[k].length; i++) {
            if(newValues[k][i]==true)
                newValues[k][i]=1;
            else
                newValues[k][i]=0;
			hex += newValues[k][i] << i;
		}
		
		hex = hex.toString(16);
        
		while(hex.length<2)
			hex = "0" + hex;
			
		post += "SET=PORT" + k + hex + "&";

	}
    
    post = post.toUpperCase();
	post += "SUB=Senden";
	startNewRefershTask(post);
	
}

/**
 * Returns if the pin with the given id is a digital one.
 * @param {Object} The id of the pin.
 */
function isDigital(id){
	return cachedPinInfo[getIndex(id)].type=='d';
}

/**
 * Returns the name of the pin with the given id.
 * @param {Object} The name of the pin.
 */
function getName(id){
	return cachedPinInfo[getIndex(id)].name;
}

/**
 * Returns the info array, including 'ip', 'def_ip', 'mac' and 'version'.
 * Usage: getInfo().ip
 */
function getInfo(){		
	return cachedInfo;
}

/**
 * Returns a array with all possible data directions for the pi with the given id.
 * @param {Object} The name of the pin.
 * @return An array with all possible data directions.
 */
function getDDOptions(id){
	return cachedPinInfo[getIndex(id)].ddOptions;
}

/**
 * Returns a description string for the given data direction.
 * @param {Object} The data direction.
 * @return A String describing the given data description.
 */
function getDDDescription(dd) {
	switch(dd){
		case 'i':return"Eingang";
		case 'o':return"Ausgang";
	}
}

/**
 * Returns the data direction of the pin with the given id. 
 * The data direction is either 'i' (input) or 'o' (output).
 * @param {Object} The name of the pin.
 * @return The data direction of the pin with the given id.
 */
function getDD(id){
	return  cachedValues[getIndex(id)].dd;
}

/**
 * Sets the data direction of the given pin.
 * @param {Object} The id of the pin.
 * @param {Object} The new data direction.
 */
function setDD(id, dd) {
    cancelRefreshTask();
	var newValues = new Object();
	for(var k in cachedValues) {
		var kid = getId(k);
        console.log(i);
		var num=kid.charAt(1);
		var port=kid.charAt(0);
		
		if(!newValues[port]) {
            console.log("new:"+port);
			newValues[port]=new Array(8);
			for(var i=0; i<newValues[port].length; i++) 
				newValues[port][i] = 0; 
		}
			
        newValues[port][num]=(cachedValues[k].dd=='o')+0;
        
        if(id == kid) {
            newValues[port][num]=(dd=='o')+0;
        }
	}
	
    console.log(newValues);
	var post = "";
	for(var k in newValues) {
		var hex = 0;
		for(var i=0; i<newValues[k].length; i++) {
			hex += newValues[k][i] << i;
		}
		
		hex = hex.toString(16);
		
		while(hex.length<2)
            hex = "0" + hex;
			
		post += "SET=OUT" + k + hex + "&";

	}
	
    post = post.toUpperCase();
	post += "SUB=Senden";
    startNewRefershTask(post);

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
 * @return true if the operation was successfull, false otherwise
 */
function setPollingFreq(freq){
	if(!isNaN(parseFloat(freq)) && isFinite(freq)) {
		pollingFreq=freq;
        putDb('pollingFreq', pollingFreq);
		return true;
	}
	
	return false;
}

/**
 * Returns the polling freq.
 * @return polling freq
 */
function getPollingFreq(){
	return pollingFreq;
}

/**
 * Returns thedefault description for the pin with the given id.
 * @return the default description
 */
function getDefaultDescription(id){
	var desc=cachedPinInfo[getIndex(id)].desc;
	return desc?desc:"--";
}

/**
 * Returns the id of the pin stored at the given index.
 */
function getId(index){
	return cachedPinInfo[index].id;
}

/**
 * Returns the index in all data structures of the pin with the given id.
 * @param {Object} The name of the pin.
 * @return The index of the pin with the given id or -1 if no entry was found
 */
function getIndex(id){
	for(var i=0;i<cachedPinInfo.length;i++)
		if(cachedPinInfo[i].id==id)
			return i;
			
	return -1;
}
