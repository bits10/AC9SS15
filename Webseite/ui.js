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
	setOnValuesChange(function(values, time){
		getEl('setting_freq').innerHTML=time+"ms";
	});
	
	//Display REST information
	getEl('setting_ip').innerHTML=getInfo().ip;
	getEl('setting_def_ip').innerHTML=getInfo().def_ip;
	getEl('setting_mac').innerHTML=getInfo().mac;
	getEl('setting_version').innerHTML=getInfo().version;

	setOnFavoritesChanged(onFavoritesChanged);
}

/**
 * Turns the LED off or on, depending on the current state. 
 * This Block may be delelted wehen more disk space is required, but don't forget to also remove the CSS classed and divs!
 * #Easteregg
 */
function toggleLED(el){
	el.className=='circle led_on'?el.className='circle led_off':el.className='circle led_on';
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
		
	getEl('cb_fav').checked=isFavorite(id);
	getEl("detail_title").innerHTML=getName(id);
	getEl('p_pid').innerHTML=isDigital(id)?"Port "+id.charAt(0)+" Pin "+id.charAt(1):getName(id);
	var s=getEl('s_conf');
	s.innerHTML="";
	var opts=getDataDirectionOptions(id);
	for (var i=0;i<opts.length;i++){
    	var opt = document.createElement('option');
    	opt.value = opts[i];
    	opt.innerHTML = getDataDirectionDescription(opts[i]);
    	s.appendChild(opt);
	}
	getEl('p_type').innerHTML=isDigital(id)?"Digital":"Analog";
	getEl('p_desc').innerHTML=getDescription(id);
	getEl('p_func').innerHTML=getFunction(id);
	
	refershSidebarValue();
}

function refershSidebarValue(){
	var el=getEl('p_val');
	var dd=getDataDirection(displayDetailId);
	switch(dd){
		case 'i':el.innerHTML=getValue(displayDetailId);break;
		case 'o':el.innerHTML=getValue(displayDetailId) + "<br><input type='button' value='Wert &auml;ndern' onclick='changeDetailValue()'/>";break;
		default:el.innerHTML="undefined";
	}
	getEl('s_conf').value=dd;
}

function changeDetailValue(){		
	alert("Wert Šndern");
}

function onFavoritesChanged() {
	var tb = getEl('favoritesTbody');
	//tb.innerHTML = "";
}
