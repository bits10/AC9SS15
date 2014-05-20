//The id of the pin displayed in the sidebar.
var sidebarId;
var configureId;

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
	  document.captureEvents(Event.MOUSEMOVE);
	  
	//Turn the screws
	//This Block may be delelted wehen more disk space is required, but don't forget to also remove the CSS classed and divs!
	var v=document.getElementsByClassName('head');
	for(var i=0;i<v.length;i++){
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
	getEl('changeFreqInput').value=getPollingFreq();

	setOnFavoritesChanged(onFavoritesChanged);
}

function opcmoId(id) {
	setSidebarId(id);
}

function opcmo(elem) {
	setSidebarId(elem.htmlFor);
}

function updateUI(pinInfo, values, time){
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
				el.innerHTML=values[i].v;
			}
			//Update onclick function (depending on input or output)
			if(values[i].dd=='o') {
				el.setAttribute('onchange', 'sendValues()');
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
	
	//Hier nur Werte updaten!! TODO
	updateFavoritesTableValues();
}

/**
 * Sets the main component of the frame.
 * @param {Object} the id of the div to set as main component (status, favorite or settings).
 */
function setMain(div){
	var v=document.getElementsByClassName('mainDiv');
	var bg=window.getComputedStyle(getEl('header')).backgroundColor;
	var fg=window.getComputedStyle(getEl('header')).borderBottomColor;
	
	for(var i=0;i<v.length;i++){
    	getEl(v[i].id).style.display="none";
    	getEl(v[i].id+"_bt").style.borderColor=bg;
	}
	
	getEl(div).style.display="block";
	getEl(div+'_bt').style.borderColor=fg;
}
function addClass(clazz, item) {
	item.className+=' '+clazz;
}
function removeClass(clazz, item) {
	item.className = item.className.replace(' '+clazz, '');
}
function getHighlightElem(id) {
	var el;
	if(isDigital(id)) {
		el=getEl(id);
	} else {
		el=getEl(id+'_check');
	}
	
	return el?el.parentNode:null;
}
function setSidebarId(id){
	if(sidebarId) {
		var el=getHighlightElem(sidebarId);
		removeClass('selected', el);
	}

	sidebarId=id;
	if(id==undefined)
		return;
	
	var el=getHighlightElem(id);
	addClass('selected', el);

	getEl('detail_fav').checked=isFavorite(id);
	getEl('detail_title').innerHTML=getName(id);
	getEl('detail_pin').innerHTML=getPosition(id);
	getEl('detail_conf').innerHTML=getDDDescription(getDD(id));
	getEl('detail_type').innerHTML=isDigital(id)?"Digital":"Analog";
	getEl('detail_desc').innerHTML=getDescription(id);
	getEl('detail_func').innerHTML=getFunctionText(id).substring(0, 100).replace(/\n/g, '<br/>').replace(/ /g, '&nbsp;');

	
	updateSidebarValues();
}

function updateSidebarValues(){
	var id = sidebarId;
	if(id==undefined)
		return;
		
	var el=getEl('detail_val');
	var dd=getDD(id);
	
	if(isDigital(id)) {
		el.innerHTML="<p class='checkbox'><input type='checkbox'id='detail_val_cb'value='None'/><label for='detail_val_cb'></label></p>";
		el=getEl('detail_val_cb');
		el.checked=getValue(sidebarId)=='1';
		el.disabled=dd!='o'
	} else {
		el.innerHTML='<p class="detail">' + getValue(id) + '</p>';
	}
	getEl('detail_conf').value=dd;
}

function updateFavoritesTable() {
	var tb = getEl('favoritesTbody');
	tb.innerHTML = "";
	var favs = getFavoritelist();
	for(var k in favs) {
		if(!isFavorite(k))
			continue;

        tb.innerHTML+='<tr><td class="row_title">' + getName(k) + '</td><td>' + getDescription(k) + '</td><td>' + getPosition(k) + '</td><td>' + getDDDescription(getDD(k)) + '</td><td>' +getTypeName(k) + '</td><td id="'+k+'_v"></td><td id="'+k+'_cv"></td><td class="row_trailer"><input type="image" src="ic_edit.svg" height="16px" alt="Anpassen" title="Pin anpassen" onclick="startConfigurePin(\''+k+'\')"/><input type="image" src="ic_trash.svg" height="16px" alt="Entfernen" title="Als Favorit entfernen" onClick="setFavorite(\'' + k + '\', false);"/></td></tr>';
	}
	
	updateFavoritesTableValues();
}

function updateFavoritesTableValues() {
	var favs = getFavoritelist();
	for(var k in favs) {
		var el=getEl(k+'_v');
		if(el)
			el.innerHTML=getValue(k);
		el=getEl(k+'_cv');
		if(el)
			el.innerHTML=getFunction(k)(getValue(k));
	}
}

function startConfigurePin(id) {
	if(!id) {
		showErrorOverlay("Bitte wählen Sie zuerst einen Pin aus.");
		return;
	}
	
	console.log('id: ' +id);
	
	getEl('conf_title').innerHTML=getName(id);
	getEl('conf_fav').checked=isFavorite(id);
	getEl('conf_pin').innerHTML=getPosition(id);

	var s=getEl('conf_conf');
	s.innerHTML="";
	var opts=getDDOptions(id);
	for (var i=0;i<opts.length;i++){
    	var opt = document.createElement('option');
    	opt.value = opts[i];
    	opt.innerHTML = getDDDescription(opts[i]);
    	s.appendChild(opt);
	}
	s.value=getDD(id);
	getEl('conf_type').innerHTML=isDigital(id)?"Digital":"Analog";
	getEl('conf_desc').value=getDescription(id);
	getEl('conf_func').value=getFunctionText(id);
	getEl('conf_func_error').innerHTML='';
	
	configureId=id;
	showOverlay('configurePin');
 
}

function endConfigurePin() {
	var id=configureId;
	
	try {
		setFavorite(id, getEl('conf_fav').checked);
		setDD(id, getEl('conf_conf').value);
		setDescription(id, getEl('conf_desc').value);
		setFunction(id, getEl('conf_func').value);
		hideOverlay();
		updateSidebarValues();
		
		var el=getHighlightElem(id);
	} catch(e) {
		getEl('conf_func_error').innerHTML="Ein Fehler ist aufgetreten:<br>"+e;
	}


}

function getTypeName(id) {
	return isDigital(id)?"Digital":"Analog";
}
function getPosition(id){
	return isDigital(id)?"Port "+id.charAt(0)+" Pin "+id.charAt(1):getName(id);
}

function showOverlay(id) {
	hideOverlay();
	getEl(id).style.display='block';
}

function hideOverlay() {
	var v=document.getElementsByClassName('overlay');
	for(var i=0;i<v.length;i++)
		v[i].style.display='none';
}

function onFavoritesChanged() {
	setSidebarId(sidebarId);
	updateFavoritesTable();
}

function showErrorOverlay(message) {
	getEl('errorText').innerHTML=message;
	showOverlay('showError');
}

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
	write(ids.length, '<div><div class="pinCheck pinCheckAnalog"><input type="checkbox" name="OUT" id="%id_check" /><label for="%id_check" onmouseover="opcmoId(\'%id\')"> </label></div></div><p class="analogValue" id="%id">--</p>', ids);
}

function writePlusMinusBox(count) {
	write(count, '<div class="a_box blue"> <div class="circle screw silver"> <div class="screw head"> </div></div><div class="a_box_bar"> </div><script>writePinCheckPlus(1);</script></div><div class="a_box blue"> <div class="circle screw silver"> <div class="screw head"> </div></div><div class="a_box_bar"> </div><script>writePinCheckMinus(1);</script></div>');
}

function writeAnalogBox(ids) {
	write(ids.length, '<div class="a_box blue"> <div class="circle screw silver"> <div class="screw head"> </div></div><div class="a_box_bar %id"> </div><div class="tBox"><script>writePinCheckAnalog([\'%id\']);</script></div>	</div>', ids);
}
