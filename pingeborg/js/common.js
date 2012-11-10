/*
Copyright 2012 Bruno Hautzenberger

This file is part of Pingeborg.

Pingeborg is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published 
by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Pingeborg is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of 
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Pingeborg. If not, see http://www.gnu.org/licenses/.
*/ 

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

function pingeb_hide_loading() {
	var overlay = document.getElementById('loadingoverlay');
 	document.body.removeChild(overlay);
}

function isNumber(s){
	if(isNaN(s)){
		return false;
	} else {
		return true;
	}
}

function randomInt(low, high){ 
	return Math.floor(Math.random()*(high-low+1)) + low; 
} 

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
