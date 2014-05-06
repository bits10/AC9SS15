var favoritelist=new Array();
var onFavoritesChanged = function(){};
function initFavorites(){
	// addFavorite(1);
	// addFavorite(10);
	// console.log(isFavorite(10));
	// setDescription(1, "test");
	// console.log(getDescription(1));
	// console.log(getFavoritelist());
	// console.log(getFavoritelist());
	// saveCookie();
	
	// if(document.cookie){
		// console.log("Cookie: " + document.cookie);
		// var cook=JSON.parse(document.cookie);
		 //favoritelist =encodeURI(cook);
		/*
		 * favoritelist=cook.replace('#',';');
		 */		
	// }
	// console.log(getFavoritelist());
	// console.log(isFavorite(10));
	onFavoritesChanged();
// 
}

	/*
	 * adds the ID to the Favoritelist
	 */
function addFavorite(id){
	if(!isFavorite()){
		favoritelist[id]={id:id,des:"",func:null};
		setFunction(id, function(x){return x;}+"");
		console.log( favoritelist[id].func +' favorit hinzugefügt');
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
	console.log(favoritelist.hasOwnProperty(id));
	return favoritelist.hasOwnProperty(id);
}
/*
 * Removes Favorites from the list
 */
function removeFavorite(id){
	var index = favoritelist.indexOf(id);
	favoritelist.splice(index, 1);
	//schneidet aus Array raus
	console.log(id + " aus Array gelöscht");
	onFavoritesChanged();
}
function saveCookie(){
	var a=new Date();
	a=new Date(a.getTime() +1000*60*60*24*365*10);
	//cookie ist ein Jahr gültig?
	document.cookie= changeFunctionToCookie+";expires="+a.toGMTString();
	//jeder neue Wert wird hinten angehängt
	console.log(document.cookie+'cookie gespeichert');
	} 
	/*
	 * Writes Description in the Favoriteliste
	 */
function setDescription(id, des){
		favoritelist[id].des=des;
	console.log("Beschreibung geändert");
	onFavoritesChanged();
	}
	/*
	 * Reads Description out of the Favoritelist
	 */
function getDescription(id){
	return "--";//favoritelist[id].des;
	}
	/*
	 * Writes Function in the FavoriteList
	 */
	function setFunction(id, func){
		favoritelist[id].func=func;
	console.log("Beschreibung geändert");
	onFavoritesChanged();
	}
	/*
	 * Reads Function out of the Favoritelist
	 */
function getFunction(id){
	return "--";//new Function(favoritelist[id].func);
	}
 	/*
 	 * Changes the ; in Function to # , because of cut offs while saving
 	 * (cookie)
 	 */
function changeFunctionToCookie(){
	var cook=JSON.stringify(favoritelist);
	//var newString=cook.replace(';','#');
	var newString=decodeURI(cook);
	return newString;
	
}

function toggleFavorite(id){
	isFavorite(id)?removeFavorite(id):addFavorite(id);
	}

function setOnFavoritesChanged(func) {
	onFavoritesChanged=func;
}
