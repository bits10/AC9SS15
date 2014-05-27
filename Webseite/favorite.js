var favoritelist=new Object();
var onFavoritesChanged = function(){};
var defaultFunctionDigital='if(x==1) {\n    return "True";\n} else {\n    return "False";\n}';
var defaultFunctionAnalog='var num = x/204.8;\nreturn num.toFixed(2) + " V";';

function initFavorites(){

    favoritelist = JSON.parse(getDb('pinConfig', '{}'));
    
	onFavoritesChanged();
}

function replaceAll(find, replace, str) {
  return str.replace(new RegExp(find, 'g'), replace);
}
	/*
	 * adds the ID to the Favoritelist
	 */
function setFavorite(id, value) {
	initPin(id);
	favoritelist[id].isFavorite=value;
	save();
	onFavoritesChanged();
}
/*
 * checks if ID is in list, if not ID will get in favoritelist
 */
function initPin(id){
	if(!favoritelist[id]){
		addPin(id);	
	}
}
function addPin(id){
	if(!id)
		return;
	
	var func = isDigital(id) ? defaultFunctionDigital : defaultFunctionAnalog;
	favoritelist[id]={id:id,des:"",func:func, isFavorite: false};
//	console.log( favoritelist[id].func +' favorit hinzugefügt');
	save();
	onFavoritesChanged();

}
/*
 * returns Favoritelist
 */
function getFavoritelist(){
	return favoritelist;
}
/*
 * return true or false, if isFavorite is true or false
 */
function isFavorite(id){
	initPin(id);
	return favoritelist[id].isFavorite;
}
/*
 * Resets Pin (delets it from array and adds it again)
 */
function resetPin(id){
	console.log("resetPin");
	
	if(!(typeof id ===  'string')) {
		throw 'invalid key';
		//console.log('invalid key');
	}
		
	delete favoritelist[id];
	addPin(id);
	onFavoritesChanged();
}
function save(){
	putDb('pinConfig', JSON.stringify(favoritelist));
	} 
	/*
	 * Writes Description in the Favoriteliste
	 */
function setDescription(id, des){
	initPin(id);
	favoritelist[id].des=des;
	//console.log("Beschreibung geändert");
	save();
	onFavoritesChanged();
	}

	/*
	 * Reads Description out of the Favoritelist
	 */
function getDescription(id){
	initPin(id);
	return favoritelist[id].des!=''?favoritelist[id].des:getDefaultDescription(id);
}
	/*
	 * Writes Function in the FavoriteList
	 */
function setFunction(id, func){
	initPin(id);
		//Will throw a exception is the func text contains a syntax error
		if(!new Function(func)(1.324))
            throw 'Function has no return value!';
    
		favoritelist[id].func=func;
	//console.log("Beschreibung geändert");
		save();

		onFavoritesChanged();
	}
	/*
	 * Reads Function out of the Favoritelist
	 */
function getFunction(id){
	initPin(id);
	return new Function('x', getFunctionText(id));
	}
	/*
	 * Returns the Function in clean text
	 */
function getFunctionText(id){
	initPin(id);
	return favoritelist[id].func;
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
	setFavorite(id, !isFavorite(id));
	//addFavorite(id);
}

function setOnFavoritesChanged(func) {
	onFavoritesChanged=func;
}
