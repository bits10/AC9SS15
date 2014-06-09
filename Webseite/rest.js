//The URL to query the pin values from
var urlValues = "rest/values";

//The URL to query the pininfos from
var urlPinInfo = "rest/pininfo";

//The URL to query the server Information from
var urlInfo = "rest/info";

//The function to trigger after every value reload
var onValuesChanged;

//The function to trigger when a network error occurs
var onNetworkError;

//The last loaded server info (from urlInfo)
var cachedInfo;

//The last loaded values (from urlValues)
var cachedValues;

//A real copy of cachedValues to check, if last values was changed
var cachedValuesReference;

//The last loaded pin infos (from urlPinInfo)
var cachedPinInfo;

//The currently used polling frequency (should-be value)
var pollingFreq = 1000;

//The currently measured polling frequency
var realPollingFreq = 0;

//The time of the last reload
var lastPollingTime = Date.now();

//A flag indicating if the REST interface is already initialised 
var isInitialised;

//A counter to count the refresh tasks
var taskIdCounter = 0;

/**
 * Inits the REST interface. Should only be called once at the startup of the page!
 */
function initRest() {
    //Check if the REST interface is already inititalised and cancel if true
	if (isInitialised) {
		return;
    }
	
    //Init the onValuesChanged variable with an empty function
	setOnValuesChanged(function(pinInfo, values, time){});
	
    //Init the onNetworkError variable with an empty function
	setOnNetworkError(function(state, response){});
	
    //Load the server info, this must only happen once
	cachedInfo = JSON.parse(loadURL(urlInfo));
    
    //Load the pin info, this must only happen once
	cachedPinInfo = JSON.parse(loadURL(urlPinInfo));
    
    //Load the values for the first time
	cachedValues = JSON.parse(loadURL(urlValues));
    
    //Load the pollingFrequncy to be used from the db module
    pollingFreq = getDb('pollingFreq', 1000);

    //Start a refresh task which will update the values after a delay (pollingFrequency)
	startNewRefershTask();
    
    //Set initialised to true to prevent reinitialisation
	isInitialised = 1;
    
}

/**
 * Cancels the currently running refersh task.
 */
function cancelRefreshTask() {
    //Increment taskIdCounter to signal the refesh task after waking up from setTimeout() that is not anymore used
    ++taskIdCounter;
    
}

/**
 * Cancels the currently running refresh task and starts a new one.
 * This will cause the values to be refreshed immediately.
 * @param {Object} The post parameters to send with the first refresh
 */
function startNewRefershTask(postParams) {
	 refreshValues(++taskIdCounter, postParams);
}

/*
 * Internally used function to refresh the values in an given interval.
 */
function refreshValues(taskId, postParams){
    //Check if this task is still in use or if the taskIdCounter was raised
	if(taskIdCounter != taskId) {
		return;
        
	}
    
    //Check if values or dd was changed, if true call sendValues() and return (send values will start a new refresh task)
    //If postParams are supplied, just ignore changes becuase the post params will already contain the info to send to the server
    if(!postParams && JSON.stringify(cachedValues) !== JSON.stringify(cachedValuesReference)) {
        console.log("Values or DD was changed! (Call sendValues)");
        sendValues();
        return;
    
    }
	
    //Call loadURLAsync to reload the values in background
	loadURLAsync(urlValues, postParams, function(state, response) {
        //Measure the real polling frequency and save
		realPollingFreq = Date.now()-lastPollingTime;
        
        //update the last polling time
		lastPollingTime = Date.now();
	
        //If everything is OK (HTTP state 200)
		if(state == 200) {
            //Parse the response to a object and save in cachedValues
		    cachedValues = JSON.parse(response);
	
            //Create a identically copy
            cachedValuesReference = JSON.parse(response);
            
            //Call onValuesChanged() to notify about the new values
			onValuesChanged(cachedPinInfo, cachedValues, realPollingFreq);
            
            //Set new Timeout to reload the values again
            window.setTimeout("refreshValues(" + taskId + ", null)", pollingFreq);

        //If something went wrong, call onNetworkError()
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
    //Create a new XMLHttpRequest
  	x=new XMLHttpRequest();
    
    //Open a connection to the url using HTTP-GET Method.
    //false means the loading is synchronosly in the foreground (will block the programm!)
	x.open("GET", url, false);
    
    //Send the request
	x.send();
	
    //return the response
	return x.responseText;
    
}

/**
 * Loads the contents of the given URL and returns the laoded content. 
 * This funtion is incompatible with IE 6 and older.
 * @param {Object} the URL of the requested resource.
 * @param {Object} the post parameters to send along with the request or null
 * @param {Function} the function to call after the transmission is done. Should have two params: the http status (200, 404...) and the laoded content
 * @return The loaded content.
 */
function loadURLAsync(url, postParams, func){
    //Create a new XMLHttpRequest
  	x = new XMLHttpRequest();
    
    //Set a onreadystatechanged function to be notified about status updates (connection openened, transmission done...)
  	x.onreadystatechange=function() {
        //If the new state is done call the given function
  		if(x.readyState == 4)
  			func(x.status,x.responseText);
        
  	};
	
    //If post parameters where given
	if(postParams) {
        //Open a connection to the url using HTTP-POST Method.
        //true means the connection is in a background thread
        x.open("POST", url, true);
        
        //Set content type for POST-Parameters
		x.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');	
        
        //Send the request
		x.send(postParams);
        
	} else {
        //Open a connection to the url using HTTP-GET Method.
        //true means the connection is in a background thread
        x.open("GET",url,true);
        
        //Send the request
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
	onValuesChanged = func;
}

/**
 * Sets the function invoked when a nnetwork error occured. 
 * The function should have two parameters. The first is the HTTP state returned by the XMLHttpRequest,
 * the second is the loaded text.
 * @param {Object} The function to be invoked when a network error occured.
 */
function setOnNetworkError(func){
	onNetworkError = func;
}

/**
 * Returns the value for the pin or analog input with the id.
 * @param {Object} the value of the pin or analog input.
 */
function getValue(id){
	return cachedValues[getIndex(id)].v;
}

/**
 * Sets the value of the pin with the given id.
 * @param {Object} the id of the pin which value should be set ('C0' etc.)
 * @param {Object} the new value 
 * @param {Object} a flag indicating if the values should be applied immediately or with the next reload
 */
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
		var num = id.charAt(1);
		var port = id.charAt(0);
		
		if(!newValues[port]) {
			newValues[port] = new Array(8);
			for (var i=0; i<newValues[port].length; i++) 
				newValues[port][i] = 0; 
		}
			
		if(getDD(id) == 'o') {
			newValues[port][num] = cachedValues[getIndex(id)].v;
		} 
	}
	
	var post = "";
	for(var k in newValues) {
		var hex = 0;
		for(var i=0; i<newValues[k].length; i++) {
            if(newValues[k][i] == true)
                newValues[k][i] = 1;
            else
                newValues[k][i] = 0;
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
 * Sets the data direction of the given pin.
 * @param {Object} The id of the pin.
 * @param {Object} The new data direction.
 */
function setDD(id, dd) {
    cancelRefreshTask();
	var newValues = new Object();
	for(var k in cachedValues) {
		var kid = getId(k);
		var num=kid.charAt(1);
		var port=kid.charAt(0);
		
		if(!newValues[port]) {
			newValues[port] = new Array(8);
			for(var i=0; i<newValues[port].length; i++) 
				newValues[port][i] = 0; 
		}
			
        newValues[port][num] = (cachedValues[k].dd == 'o') + 0;
        
        if(id == kid) {
            newValues[port][num] = (dd == 'o') + 0;
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
		case 'i': return"Eingang";
		case 'o': return"Ausgang";
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
    //If freq is a number
	if(!isNaN(parseFloat(freq)) && isFinite(freq)) {
        //Set polling freq
		pollingFreq = freq;
        //Save in db
        putDb('pollingFreq', pollingFreq);
        
        //return true (success)
		return true;
	}
	
    //return false (no success)
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
		if(cachedPinInfo[i].id == id)
			return i;
			
	return -1;
}
