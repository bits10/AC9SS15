function getEl(id) {
	return document.getElementById(id);
}

function setMain(div) {
	getEl('favorite').style.display="none";
	getEl('favorite_bt').style.borderColor="#2B2B2B";

	getEl('status').style.display="none";
	getEl('status_bt').style.borderColor="#2B2B2B";

	getEl('settings').style.display="none";
	getEl('settings_bt').style.borderColor="#2B2B2B";

	getEl(div).style.display="block";
	getEl(div+'_bt').style.borderBottomColor="#33b5e5";

}
