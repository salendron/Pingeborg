/*
* This work is licensed under the Creative Commons Attribution 3.0 Unported License. 
* To view a copy of this license, visit http://creativecommons.org/licenses/by/3.0/.
*/

//Shows the loading overlay
//Author: Bruno Hautzenberger
//Date: 09.2012
function pingeb_show_loading(msg){
	if(msg === ""){
		msg = "loading...";
	}

	var overlay = document.createElement('div');
	overlay.setAttribute('id', 'loadingoverlay');
	
	//set offset
	overlay.style.top = window.pageYOffset + 'px';

	document.body.appendChild(overlay);

	html = "<p>";
	html += "<img src='" + loadingImg + "' style='height:30pt;'><br><br>";
	html += msg;
	html += "</p>";
	overlay.innerHTML = html;
}

//Hides the loading overlay
//Author: Bruno Hautzenberger
//Date: 09.2012
function pingeb_hide_loading() {
	var overlay = document.getElementById('loadingoverlay');
 	document.body.removeChild(overlay);
}

//Checks if a value is a number
//Author: Bruno Hautzenberger
//Date: 09.2012
function isNumber(s){
	if(isNaN(s)){
		return false;
	} else {
		return true;
	}
}

//Returns a random number between low and high
//Author: Bruno Hautzenberger
//Date: 09.2012
function randomInt(low, high){ 
	return Math.floor(Math.random()*(high-low+1)) + low; 
} 

//Adds a function to the window.onLoad Function
//Author: Bruno Hautzenberger
//Date: 09.2012
function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
}
