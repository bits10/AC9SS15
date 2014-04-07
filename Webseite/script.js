/**
 * Shortcut for document.getElementById(id).
 * @return the found element or null, if no element was found.
 */
function getEl(id) {
	return document.getElementById(id);
}

/**
 * Sets the main component of the frame.
 * @param {Object} the id of the div to set as main component (status, favorite or settings).
 */
function setMain(div) {
	getEl('favorite').style.display="none";
	getEl('favorite_bt').style.borderColor="#2B2B2B";

	getEl('status').style.display="none";
	getEl('status_bt').style.borderColor="#2B2B2B";

	getEl('settings').style.display="none";
	getEl('settings_bt').style.borderColor="#2B2B2B";

	getEl(div).style.display="block";
	getEl(div+'_bt').style.borderColor=getEl('header').style.borderColor;
	
	alert("Color:" + getEl('header').style.backgroundColor);


}
