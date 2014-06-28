//The id of the pin displayed in the sidebar.
var sidebarId;
var configureId;
var reloadFuncText = '';
var reloadFunc = new Function(reloadFuncText);

/**
 * Shortcut for document.getElementById(id).
 * @return the found element or null, if no element was found.
 */
function getEl(id) {
	return document.getElementById(id);
}

/**
 * Initialises the UI. Should only be called once at the startup.
 */
function initUi() {
    
    reloadFuncText = getDb('reloadFuncText', '');
    reloadFunc = new Function(reloadFuncText);

    
	//Turn the screws
	//This Block may be delelted wehen more disk space is required, but don't forget to also remove the CSS classed and divs!
	var v=document.getElementsByClassName('head');
	for(var i=0; i<v.length; i++) {
		//Generate a constant "random" number to ensure the srews are positioned always identical
		var r="rotate("+i*42*i+"deg)";
    	v[i].style.transform=r;
    	v[i].style.setProperty('-webkit-transform', r);
    	v[i].style.setProperty('transform', r);
    	v[i].style.setProperty('-moz-transform', r);
	}
	
	v=getPinInfo();

	
	//Init Settings for rest.js
	setOnValuesChanged(updateUI);
	
	//Display REST information
	getEl('setting_version').innerHTML=getInfo().version;

	setOnFavoritesChanged(onFavoritesChanged);
}

function opcmoId(id) {
	setSidebarId(id);
}

function opcmo(elem) {
	setSidebarId(elem.htmlFor);
}

function updateUI(pinInfo, values, time) {
	//Update frequency in settings panel
	getEl('setting_freq').innerHTML=time+"ms";
	
	//Update all values
	for(var i=0; i<pinInfo.length; i++) {
		var id=pinInfo[i].id;
		var el = getEl(id);
		if(el) {
			//Update value
			if(el.type === 'checkbox') {
				el.checked=values[i].v=='1';
			} else {
				el.innerHTML=getFunction(id)(getValue(id));
			}
			//Update onclick function (depending on input or output)
			if(values[i].dd=='o') {
				el.setAttribute('onchange', 'setValue("' + id + '", this.checked, true);');
				el.setAttribute('onclick', '');
			} else {
				el.setAttribute('onchange', 'return false;');
				el.setAttribute('onclick', 'return false;');
			}
		}
		
		//Skip the currently displayed pin
		if(sidebarId==id)
			continue;
			
		//Update input output display
		el=getHighlightElem(id);
		if(el) {
			el.setAttribute('oncontextmenu', 'startConfigurePin("'+id+'");return false;');
			removeClass('pinCheckOutput', el);
			if(values[i].dd=='o') {
				addClass('pinCheckOutput', el);
			}
		}
	}
	
	updateSidebarValues();
	updateFavoritesTableValues();
    
    try {
        if(reloadFunc)
            reloadFunc();
    } catch(e) {
        showErrorOverlay("Beim ausführen des Reload-Skriptes ist ein Fehhler aufgetreten: <br>" + e + "<br><br><input type='button' value='Reload-Funktion zurücksetzen' onclick='resetReloadFunc();hideOverlay();'/>");
    }
}

/**
 * Sets the main component of the frame.
 * @param {Object} the id of the div to set as main component (status, favorite or settings).
 */
function setMain(div) {
	var v=document.getElementsByClassName('mainDiv');
	var bg=window.getComputedStyle(getEl('header')).backgroundColor;
	var fg=window.getComputedStyle(getEl('header')).borderBottomColor;
	
	for(var i=0;i<v.length;i++) {
    	getEl(v[i].id).style.display="none";
    	getEl(v[i].id+"_bt").style.borderColor=bg;
	}
	
	getEl(div).style.display="block";
	getEl(div+'_bt').style.borderColor=fg;
}

/**
 * Adds a CSS class to the given item.
 * @param {Object} the name of the CSS-class to add
 * @param {Object} the HTML-item to which the class should be added
 */
function addClass(clazz, item) {
	item.className += ' '+clazz;
}

/**
 * Removed a CSS class from the given item
 * @param {Object} the name of the CSS-class to remove
 * @param {Object} the HTML-item from which the class should be removed
 */
function removeClass(clazz, item) {
	item.className = item.className.replace(' '+clazz, '');
}

/**
 * Returns the highlight element for the given pin id.
 * The highlight component is either the HTML-element with the id (if the pin is digital)
 * or the checkbox if the pin is analog
 * @param {Object} The pin id of the pin which highlight elements is wanted
 * @return the requested highlight component or null, if no component was found
 */
function getHighlightElem(id) {
	var el;
	if(isDigital(id)) {
		el = getEl(id);
        
	} else {
		el = getEl(id+'_check');
        
	}
	
	return el ? el.parentNode:null;
}

/*
 * Displays all information of the pin with the given id in the sidebar.
 * @param {Object} The id of the pin which information should be displayed in the sidebar
 */
function setSidebarId(id) {
    //If id is undefined -> cancel
    if(id == undefined)
		return;
    
    //If already a pin is displayed -> remove the highlight
	if(sidebarId) {
		var el = getHighlightElem(sidebarId);
		removeClass('selected', el);
	}

    //Save the new id
	sidebarId=id;

	//Add the highlight to show the user that this pin is displayed in the sidebar
	var el=getHighlightElem(id);
	addClass('selected', el);

    //Set allinfos
	getEl('detail_fav').checked=isFavorite(id);
	getEl('detail_title').innerHTML=getName(id);
	getEl('detail_pin').innerHTML=getPosition(id);
	getEl('detail_conf').innerHTML=getDDDescription(getDD(id));
	getEl('detail_type').innerHTML=isDigital(id)?"Digital":"Analog";
	getEl('detail_desc').innerHTML=getDescription(id);
	getEl('detail_func').innerHTML=getFunctionText(id).replace(/\n/g, '<br/>').replace(/ /g, '&nbsp;');

    //Let a other function refersh the displayed values
	updateSidebarValues();
}

/*
 * Updates the values displayed in the sidebar
 */
function updateSidebarValues() {
    //If the sidebarId is undefined, no pin is displayed -> return
	var id = sidebarId;
	if(id==undefined)
		return;
		
    //Get the element to display the value
	var el = getEl('detail_val');
	
    //If the pin is digial
	if(isDigital(id)) {
        //put a checkbox in dateil_val, set its value and disable it if the pin is a input (so the user cant't click it)
		el.innerHTML = "<p class='checkbox'><input type='checkbox'id='detail_val_cb'value='None'/><label for='detail_val_cb'></label></p>";
		el = getEl('detail_val_cb');
		el.checked = getValue(sidebarId)=='1';
		el.disabled = getDD(id) != 'o';
        
	} else {
        //Just set a p element with the value in it in detail_val
		el.innerHTML='<p class="detail">' + getFunction(id)(getValue(id)) + '</p>';
        
	}
    
    //Display if the pin is input or output
	getEl('detail_conf').value = getDDDescription(getDD(id));
}

/*
 * Rebuilds the favorite table in favorite tab
 */
function updateFavoritesTable() {
    //get table body and delete its content
	var tb = getEl('favoritesTbody');
	tb.innerHTML = "";
    
    //get the current favoritelist
	var favs = getFavoritelist();
    
    //Iterate over all favorites
	for(var k in favs) {
        //If the pin is not marked as a favorit, skip it
		if(!isFavorite(k))
			continue;

        //Add a new table row
        tb.innerHTML += 
            '<tr><td class="row_title">' + getName(k) + '</td><td>' + getDescription(k) + '</td><td>' + getPosition(k) + '</td><td>' + getDDDescription(getDD(k)) + '</td><td>' +getTypeName(k) + '</td><td id="fav_table_'+k+'_v"></td><td id="fav_table_'+k+'_cv"></td><td class="row_trailer"><input type="image" src="ic_edit.svg" height="16px" alt="Anpassen" title="Pin anpassen" onclick="startConfigurePin(\''+k+'\')"/><input type="image" src="ic_trash.svg" height="16px" alt="Entfernen" title="Als Favorit entfernen" onClick="setFavorite(\'' + k + '\', false);"/></td></tr>';
	}
	
	updateFavoritesTableValues();
}

/*
 * Updates the values displayed in the favorite table in favorite tab
 */
function updateFavoritesTableValues() {
    //get the current favoritelist
	var favs = getFavoritelist();
    
    //Iterate over all favorites
	for(var k in favs) {
        //Get the value element in the table for this pin
		var el=getEl('fav_table_' + k + '_v');
        
        //If a element was found set its value
		if(el)
			el.innerHTML=getValue(k);
        
        //Get the custom value element in the table for this pin
		el=getEl('fav_table_' + k + '_cv');
        
        //If a element was found set its value
		if(el)
			el.innerHTML=getFunction(k)(getValue(k));
	}
}

/*
 * Shows a configure dialog for the pin with the given id
 * @param {Object} the id of the pin for wich the dialog should be shown 
 */
function startConfigurePin(id) {
    //If id is not defined -> show a error dialog and return
	if(!id) {
		showErrorOverlay("Bitte wählen Sie zuerst einen Pin aus.");
		return;
	}
	
	//Set all values
	getEl('conf_title').innerHTML=getName(id);
	getEl('conf_fav').checked=isFavorite(id);
	getEl('conf_pin').innerHTML=getPosition(id);
    getEl('conf_type').innerHTML=isDigital(id)?"Digital":"Analog";
	getEl('conf_desc').value=getDescription(id);
	getEl('conf_func').value=getFunctionText(id);
	getEl('conf_func_error').innerHTML='';
    
    //Get the select for the data direction, clear its content
	var s=getEl('conf_conf');
	s.innerHTML="";
    
    //Get all dd options for the pin
	var opts=getDDOptions(id);
    
    //Iterate over all options
	for (var i=0;i<opts.length; i++) {
        //create aoption element, set its value and innerHTML
    	var opt = document.createElement('option');
    	opt.value = opts[i];
    	opt.innerHTML = getDDDescription(opts[i]);
        
        //apend the option element to the select
    	s.appendChild(opt);
        
	}

    //Set the selects value to the current dd
	s.value=getDD(id);
    
	//Save the id and show the overlay
	configureId=id;
	showOverlay('configurePin');
 
}

/*
 * A function called when the user clicks "OK" or "Zurücksetzen" on the configure dialog.
 * @param {Object} a flag indicating if the values from the dialogshould be saved or if the pin should be reset to default values
 */
function endConfigurePin(reset) {
    //Get the id
	var id=configureId;
      
	try {
        //If reset flag is set, reset the pin
        if(reset) {
           resetPin(id);
        
        //If values should be saved
        } else {
            //Set the values
            setFavorite(id, getEl('conf_fav').checked);
		    setDescription(id, getEl('conf_desc').value);
            setFunction(id, getEl('conf_func').value);   
            
            //Get the selected dd and only set it if it was changed (will cause a reload)
            var dd= getEl('conf_conf').value;
            if(dd != getDD(id))
                setDD(id,dd);

        }

        //Hide the overlay and update the sidebar values
		hideOverlay();
		updateSidebarValues();
		
		var el=getHighlightElem(id);
        
	} catch(e) {
        //A error occured (normally in evalueating the function), display it
		getEl('conf_func_error').innerHTML="Ein Fehler ist aufgetreten:<br>"+e;
        
	}
}

/*
 * Shows a configure dialog for the reload fucntion
 */
function startConfigureReloadFunc() {
    //Reset error and set function text, then show dialog
    getEl('conf_reload_func_error').innerHTML='';
    getEl('conf_reload_func').value=reloadFuncText;
    showOverlay('configureReloadFunc');
    
}

/*
 * A function called when the user clicks "OK" on the configure dialog.
 */
function endConfigureReloadFunc() {
    //Get the enetered text
    var funcText = getEl('conf_reload_func').value;

    try {
        //Create a new function object and run it (just to test)
        new Function(funcText)();
        
        //Save the function if it is ok (otherwise we are noch catch)
        reloadFunc=new Function(funcText);
        reloadFuncText=funcText;
        putDb('reloadFuncText', reloadFuncText);
        
        //Hide the overlay
        hideOverlay();
        
    } catch(e) {
        //A error occured (normally in evalueating the function), display it
		getEl('conf_reload_func_error').innerHTML="Ein Fehler ist aufgetreten:<br>"+e;
        
	}
}

/*
 * Resets the reload function to a empty function
 */
function resetReloadFunc() {
    reloadFuncText='';
    reloadFunc=new Function(reloadFuncText);
    putDb('reloadFuncText', reloadFuncText);
    
}

/*
 * Shows a configure dialogfor the reload frequency
 */
function startConfigureFreq() {
    getEl('changeFreqInput').value=getPollingFreq();
    showOverlay('configureFreq');
    
}

/*
 * A function called when the user clicks "OK" on the configure dialog.
 */
function endConfigureFreq() {
    //hide overlay
    hideOverlay();
    
    //Set polling frequency, will return false if it was not successfull (no number e.g.), show a error
    if(!setPollingFreq(getEl('changeFreqInput').value))
        showErrorOverlay('Die eingegebene Frequenz war nicht korrekt. Der Wert mus zwischen 0 und 60000 liegen. Bitte versuchen Sie es erneut.');
    
}

/*
 * Shows the overlay with the given (HTML) id
 */
function showOverlay(id) {
	hideOverlay();
	getEl(id).style.display='block';
    
}

/*
 * Hides all overlays
 */
function hideOverlay() {
	var v=document.getElementsByClassName('overlay');
	for(var i=0;i<v.length;i++)
		v[i].style.display='none';
    
}

/*
 * Called when a favorit was changed
 */
function onFavoritesChanged() {
	setSidebarId(sidebarId);
	updateFavoritesTable();
    
}

/*
 * Shows a error dialog displaying the given message.
 * @param {Object} the message to display
 */
function showErrorOverlay(message) {
	getEl('errorText').innerHTML=message;
	showOverlay('showError');
    
}

/*
 * shows a dialog to import the settings
 */
function showImportOverlay() {
    getEl('importSettingsText').innerHTML="";
    showOverlay('importSettings');
}

/*
 * shows a dialog providing the text to export the settings
 */
function showExportOverlay() {
   getEl('exportSettingsText').innerHTML = JSON.stringify(getFavoritelist());
   showOverlay('exportSettings');


}

/*
 * Called when the OK button on the import dialog is pressed.
 * Imports the settings in the textarea of the dialog.
 */
function importSettings() {
    try {
        importFavoritList(getEl('importSettingsText').value);
        hideOverlay();
        
    } catch(e) {
        hideOverlay();
        showErrorOverlay("Beim Importieren ist ein Fehler aufgetreten:<br>" + e)
        
    }
}

/*
 * From here all fucntions are used to shorten the HTML code and save disk space
 */

function write(count, string, ids) {
	for(var i=0;i<count;i++) {
		var s = ids==undefined?string:string.replace(/%id/g,ids[i]);
		document.write(s);
        
	}
}

function writePinCheck(ids) {
	write(ids.length, '<div class="pinCheck pinCheckSelectable"><input type="checkbox" name="OUT" id="%id"/><label for="%id" onmouseover="opcmo(this)"></label></div>', ids);
    
}

function writePinCheckMinus(count) {
	write(count, '<div class="pinCheck pinCheckMinus"><input type="checkbox" name="OUT" id="pinMinus"/><label for="pinMinus"></label></div>');

}

function writePinCheckPlus(count) {
	write(count, '<div class="pinCheck pinCheckPlus"><input type="checkbox" name="OUT" id="pinPlus"/><label for="pinPlus"></label></div>');

}

function writePinCheckNone(count) {
	write(count, '<div class="pinCheck pinCheckNone"><input type="checkbox" name="OUT" id="pinNone"/><label for="pinNone"></label></div>');

}

function writePinCheckAnalog(ids) {
	write(ids.length, '<div><div class="pinCheck pinCheckAnalog"><input type="checkbox" name="OUT" id="%id_check" onclick="return false;"/><label for="%id_check" onmouseover="opcmoId(\'%id\')"> </label></div></div><p class="analogValue" id="%id">--</p>', ids);

}

function writePlusMinusBox(count) {
	write(count, '<div class="a_box blue"> <div class="circle screw silver"> <div class="screw head"> </div></div><div class="a_box_bar"> </div><script>writePinCheckPlus(1);</script></div><div class="a_box blue"> <div class="circle screw silver"> <div class="screw head"> </div></div><div class="a_box_bar"> </div><script>writePinCheckMinus(1);</script></div>');

}

function writeAnalogBox(ids) {
	write(ids.length, '<div class="a_box blue"> <div class="circle screw silver"> <div class="screw head"> </div></div><div class="a_box_bar %id"> </div><div class="tBox"><script>writePinCheckAnalog([\'%id\']);</script></div>	</div>', ids);

}
