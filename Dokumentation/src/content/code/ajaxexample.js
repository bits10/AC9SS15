function loadValues() {
    var x=new XMLHttpRequest();
    x.open("GET", "/rest/values", false);
    x.send();
	
    return JSON.parse(x.responseText);
}