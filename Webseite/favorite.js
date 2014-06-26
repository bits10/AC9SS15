//List of Objects which will be saved
var favoritelist = new Object();
// function which will be used when something has changed
var onFavoritesChanged = function() {};
//Standard function for digital ports
var defaultFunctionDigital = 'if(x==1) {\n    return "True";\n} else {\n    return "False";\n}';
//Standard function for analog ports
var defaultFunctionAnalog = 'var num = x/204.8;\nreturn num.toFixed(2) + " V";';

/*
 * Initialises Favorites (reads form database)
 */
function initFavorites(){
	//getDb gets the favoriteList (If existing) of the last use 
    favoritelist = JSON.parse(getDb('pinConfig', '{}')) ;
	onFavoritesChanged();
}

/*
 * checks if Port(ID) is in list, if not it will get in favoritelist
 */
function initPin(id){
	if (!favoritelist[id]) {
		addPin(id);	
	}
}

/*
 * changes the 'isFavorite' value of a Pin in FavoriteList (depends if value is 'true' or 'false')
 */
function setFavorite(id, value) {
	//Checks if id is in list
	initPin(id);
	//changes the value
	favoritelist[id].isFavorite = value;
	//saves
	save();
	onFavoritesChanged();
}

/*
 * Pin is added in FavoriteList
 */
function addPin(id){
	//Checkes if ID is not null
	if(!id)
		return;
	//when Pin(id) is digital,it gets digitalFunction, else AnalogFunction
	var func = isDigital(id) ? defaultFunctionDigital : defaultFunctionAnalog;
	//Pin is added with id, empty description, defaultFunction, isFavorite = 'false'
	favoritelist[id] = {id:id,des:"",func:func, isFavorite: false};
	//saves
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
 * return if isFavorite is true or false (if object is set as an favorite)
 */
function isFavorite(id){
	//checkes if Pin(id) is in list
	initPin(id);
	// returns true or false
	return favoritelist[id].isFavorite;
}

/*
 * Resets Pin (delets it from array and adds it again)
 */
function resetPin(id){
	//checkes if id is a number
	if(!(typeof id ===  'string')) {
		throw 'invalid key';
	}
	//deletes Pin
	delete favoritelist[id];
	//adds Pin again
	addPin(id);
	onFavoritesChanged();
}

/*
 * saves the FavoriteList
 */
function save(){
	//favorite list is saved in database
	putDb('pinConfig', JSON.stringify(favoritelist));
} 

/*
 * Writes Description in Favoriteliste
 */
function setDescription(id, des){
	//Checkes if Pin(id) in list
	initPin(id);
	//Writes description
	favoritelist[id].des=des;
	//saves
	save();
	onFavoritesChanged();
}

/*
 * Returns Descprition or DefaultDescription of Pin
 */
function getDescription(id){
	//Checkes if Pin(id) in list
	initPin(id);
	//Returns either Description, if existing or DefaultDescription
	return favoritelist[id].des != ''?favoritelist[id].des:getDefaultDescription(id);
}

/*
 * Writes Function in the FavoriteList
 */
function setFunction(id, func){
	initPin(id);
	//Will throw a exception is the func text contains a syntax error
	if(!new Function(func)(1.324))
            throw 'Function has no return value!';
    //Writes new function in List
    favoritelist[id].func=func;
    //saves
	save();
	onFavoritesChanged();
}

/*
 * Returns Function of Pin
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
 * Changes isFavoriteValue
 */
function toggleFavorite(id){
	setFavorite(id, !isFavorite(id));
}

/*
 * changes OnFavoritesChanges (Writes new function)
 */
function setOnFavoritesChanged(func) {
	onFavoritesChanged = func;
}