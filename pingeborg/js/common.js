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
