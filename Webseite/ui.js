//The id of the pin displayed in the sidebar.
var displayDetailId;

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
	
	//Init Settings for rest.js
	setOnValuesChanged(updateUI);
	
	//Display REST information
	getEl('setting_version').innerHTML=getInfo().version;
	getEl('changeFreqInput').value=getPollingFreq();

	setOnFavoritesChanged(onFavoritesChanged);
}

function opcmoId(id) {
	displayDetails(id);
}

function opcmo(elem) {
	displayDetails(elem.htmlFor);
}

function updateUI(pinInfo, values, time){
	//Update frequency in settings panel
	getEl('setting_freq').innerHTML=time+"ms";
	
	//Update all values
	for(var i=0; i<pinInfo.length; i++) {
		var el = getEl(pinInfo[i].id);
		if(el) {
			if(el.type === 'checkbox') {
				el.checked=values[i].v=='1';
			} else {
				el.innerHTML=values[i].v;
			}
		}
	}
	
	updateDetailsValue();
	
	//Hier nur Werte updaten!! TODO
	updateFavoritesTable();
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

function displayDetails(id){
	displayDetailId=id;
	if(id==undefined)
		return;
		
	getEl('detail_fav').checked=isFavorite(id);
	getEl("detail_title").innerHTML=getName(id);
	getEl('detail_pin').innerHTML=getPosition(id);
	var s=getEl('detail_conf');
	s.innerHTML="";
	var opts=getDataDirectionOptions(id);
	for (var i=0;i<opts.length;i++){
    	var opt = document.createElement('option');
    	opt.value = opts[i];
    	opt.innerHTML = getDataDirectionDescription(opts[i]);
    	s.appendChild(opt);
	}
	getEl('detail_type').innerHTML=isDigital(id)?"Digital":"Analog";
	getEl('detail_desc').innerHTML=getDescription(id);
	getEl('detail_func').innerHTML=getFunction(id)+"";
	
	updateDetailsValue();
}

function updateDetailsValue(){
	var id = displayDetailId;
	if(id==undefined)
		return;
		
	var el=getEl('detail_val');
	var dd=getDataDirection(id);
	
	if(isDigital(id)) {
		el.innerHTML="<div class='checkbox'><input type='checkbox'id='detail_val_cb'value='None'/><label for='detail_val_cb'></label></div>";
		el=getEl('detail_val_cb');
		el.checked=getValue(displayDetailId)=='1';
		el.disabled=dd!='o'
	} else {
		el.innerHTML=getValue(id);
	}
	getEl('detail_conf').value=dd;
}

function updateFavoritesTable() {
	var tb = getEl('favoritesTbody');
	tb.innerHTML = "";
	var favs = getFavoritelist();
	for(var k in favs) {
		var id = favs[k].id;
		tb.innerHTML+='<tr><td>' + getName(id) + '</td><td>' + getDescription(id) + '</td><td>' + getPosition(id) + '</td><td>' + getDataDirectionDescription(getDataDirection(id)) + '</td><td>' +getTypeName(id) + '</td><td>' + getValue(id) + '</td><td>' + getFunction(id)(getValue(id)) + '</td><td><input type="button" value="Anpassen" onclick="editFavorit(\'' + id + '\')"/><input type="button" value="Entfernen" onClick="removeFavorite(\'' + id + '\');"/></td></tr>';

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
	displayDetails(displayDetailId);
	updateFavoritesTable();
}

function showErrorOverlay(message) {
	getEl('errorText').innerHTML=message;
	showOverlay('showError');
}

function editFavorit() {
	showOverlay('configureFav');
}


function write(count, string, ids) {
	for(var i=0;i<count;i++) {
		var s = ids==undefined?string:string.replace(/%id/g,ids[i]);
		console.log(s);
		document.write(s);
	}
}

function writePinCheck(ids) {
	write(ids.length, '<div class="pinCheck"><input type="checkbox" name="OUT" id="%id"/><label for="%id" onmouseover="opcmo(this)"></label></div>', ids);
}

function writePinCheckMinus(count) {
	write(count, '<div class="pinCheck pinCheckUnclickable pinCheckMinus"><input type="checkbox" name="OUT" id="pinMinus"/><label for="pinMinus"></label></div>');
}

function writePinCheckPlus(count) {
	write(count, '<div class="pinCheck pinCheckUnclickable pinCheckPlus"><input type="checkbox" name="OUT" id="pinPlus"/><label for="pinPlus"></label></div>');
}

function writePinCheckNone(count) {
	write(count, '<div class="pinCheck pinCheckUnclickable pinCheckNone"><input type="checkbox" name="OUT" id="pinNone"/><label for="pinNone"></label></div>');
}

function writePinCheckAnalog(ids) {
	write(ids.length, '<div class="pinCheck pinCheckUnclickable pinCheckAnalog"><input type="checkbox" name="OUT" id="%id_check" /><label for="%id_check" onmouseover="opcmoId(\'%id\')"> </label></div><p class="analogValue" id="%id">--</p>', ids);
}
