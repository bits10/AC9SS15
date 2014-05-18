var favoritelist=new Object();
var onFavoritesChanged = function(){};
var defaultFunction='return x;';
function initFavorites(){
	// addFavorite(1);
	// addFavorite(10);
	// console.log(isFavorite(10));
	// setDescription(1, "test");
	// console.log(getDescription(1));
	// console.log(getFavoritelist());
	// console.log(getFavoritelist());
	// saveCookie();
	
	 if(document.cookie){
		 //console.log("Cookie: " + document.cookie);
		 var cook = replaceAll('#',';', document.cookie);
		 cook=cook.substring(cook.indexOf('=')+1, cook.length);
		 //console.log(cook);
		 favoritelist =	JSON.parse(cook);
		 	
		  //favoritelist=cook.replace('#',';');
		 	
	 }
	// console.log(getFavoritelist());
	// console.log(isFavorite(10));
	onFavoritesChanged();
// 
}

function replaceAll(find, replace, str) {
  return str.replace(new RegExp(find, 'g'), replace);
}

	/*
	 * adds the ID to the Favoritelist
	 */
function setFavorit(id, value) {
	if(value) {
		addFavorite(id);
	} else {
		removeFavorite(id);
	}
}
function addFavorite(id){
	if(!id)
		return;
	if(!isFavorite()){
		favoritelist[id]={id:id,des:"",func:'if(x==1) {\n    return "True";\n} else {\n    return "False";\n}'};
	//	console.log( favoritelist[id].func +' favorit hinzugefügt');
		saveCookie();
		onFavoritesChanged();
	}
}
/*
 * returns Favoritelist
 */
function getFavoritelist(){
	return favoritelist;
}
/*
 * return true or false, if id is in favoritelist or not
 */
function isFavorite(id){
	return favoritelist.hasOwnProperty(id)&&favoritelist[id]!=undefined;
}
/*
 * Removes Favorites from the list
 */
function removeFavorite(id){
	if(!(typeof id ===  'string')) {
		throw 'invalid key';
		//console.log('invalid key');
	}
		
	delete favoritelist[id];
	//schneidet aus Array raus
	console.log(id + " aus Array gelöscht");
	saveCookie();
	onFavoritesChanged();
}
function saveCookie(){
	var a=new Date();
	a=new Date(a.getTime() +1000*60*60*24*365*10);
	//cookie ist ein Jahr gültig?
	document.cookie="sa=" + changeFunctionToCookie()+";expires="+a.toGMTString();
	//jeder neue Wert wird hinten angehängt
	//console.log("favorites=" + changeFunctionToCookie()+";expires="+a.toGMTString());
	} 
	/*
	 * Writes Description in the Favoriteliste
	 */
function setDescription(id, des){
		favoritelist[id].des=des;
	//console.log("Beschreibung geändert");
	saveCookie();
	onFavoritesChanged();
	}
	/*
	 * Reads Description out of the Favoritelist
	 */
function getDescription(id){
	return isFavorite(id)&&favoritelist[id].des!=''?favoritelist[id].des:getDefaultDescription(id);
}
	/*
	 * Writes Function in the FavoriteList
	 */
function setFunction(id, func){
		//Will throw a exception is the func text contains a syntax error
		new Function(func)(1.324);
		favoritelist[id].func=func;
	//console.log("Beschreibung geändert");
		saveCookie();

		onFavoritesChanged();
	}
	/*
	 * Reads Function out of the Favoritelist
	 */
function getFunction(id){
	return new Function('x', getFunctionText(id));
	}
function getFunctionText(id){
	return isFavorite(id)&&favoritelist[id].func!=''?favoritelist[id].func:defaultFunction;
	}
 	/*
 	 * Changes the ; in Function to # , because of cut offs while saving
 	 * (cookie)
 	 */
function changeFunctionToCookie(){
	var cook=JSON.stringify(favoritelist);
	var newString=replaceAll(';','#', cook);
	return newString;
	
}

function toggleFavorite(id){
	isFavorite(id)?removeFavorite(id):addFavorite(id);
	//addFavorite(id);
}

function setOnFavoritesChanged(func) {
	onFavoritesChanged=func;
}
