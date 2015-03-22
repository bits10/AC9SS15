function getDb(key, defaultValue) {
    var value = localStorage.getItem(key);   
    return value == null ? defaultValue : value;
}

function putDb(key, value) {
    localStorage.setItem(key, value); 
}