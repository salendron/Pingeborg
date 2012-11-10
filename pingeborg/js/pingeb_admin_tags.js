/*
Copyright 2012 Bruno Hautzenberger

This file is part of Pingeborg.

Pingeborg is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published 
by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Pingeborg is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of 
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Pingeborg. If not, see http://www.gnu.org/licenses/.
*/ 

//Global Members
var pages = [];
var url_types = [];
var tags = [];
var layers = [];

jQuery(document).ready(function($) {
	pingeb_show_loading("");

	//load pages
	var data = {
		action: 'pingeb_get_pages'
	};

	jQuery.post(ajaxurl, data, function(response) {
		pages = JSON.parse(response);

		//load url types
		data = {
			action: 'pingeb_get_url_type'
		};

		jQuery.post(ajaxurl, data, function(response) {
			url_types = JSON.parse(response);

			//load layers
			data = {
				action: 'pingeb_get_layers'
			};

			jQuery.post(ajaxurl, data, function(response) {
				layers = JSON.parse(response);

				//load tags
				data = {
					action: 'pingeb_get_tags',
					layer_id: -1,
					marker_name: '-1',
					page_id: -1
				};

				jQuery.post(ajaxurl, data, function(response) {
					tags = JSON.parse(response);
					prepareUI();
					pingeb_build_tag_list();
					pingeb_hide_loading();
				});
			});
		});
	});	
});

function prepareUI(){
	//fill comboxes with pages
	var cmbBatchTagPage = document.getElementById('pingeb_batch_tag_page');
	var cmbFilterPage = document.getElementById('pingeb_filter_page');
	var pagesOptions = "";

	for(var i = 0; i < pages.length; i++){
		pagesOptions += "<option value='" + pages[i]['id'] + "'>" + pages[i]['title'] + "</option>"
	}

	cmbBatchTagPage.innerHTML += pagesOptions;
	cmbFilterPage.innerHTML += pagesOptions;

	//fill comboxes with layers
	var cmbFilterLayer = document.getElementById('pingeb_filter_layer');
	var layerOptions = "";

	for(var i = 0; i < layers.length; i++){
		layerOptions += "<option value='" + layers[i]['id'] + "'>" + layers[i]['name'] + "</option>"
	}

	cmbFilterLayer.innerHTML += layerOptions;
}

function pingeb_get_tags(){
	pingeb_show_loading("");
	
	//get filters
	var page = document.getElementById('pingeb_filter_page').value;
	var marker_name = jQuery.trim(document.getElementById('pingeb_filter_marker_name').value);
	var layer = document.getElementById('pingeb_filter_layer').value;
	
	if(marker_name === ""){
		marker_name = '-1';
	}
	
	//load tags
	data = {
		action: 'pingeb_get_tags',
		layer_id: layer,
		marker_name: marker_name,
		page_id: page
	};

	jQuery.post(ajaxurl, data, function(response) {
		tags = JSON.parse(response);
		pingeb_build_tag_list();
		pingeb_hide_loading();
	});
}

function pingeb_clear_filter(){
	document.getElementById('pingeb_filter_page').selectedIndex = 0;
	document.getElementById('pingeb_filter_marker_name').value = "";
	document.getElementById('pingeb_filter_layer').selectedIndex = 0;
	
	pingeb_get_tags();
}

function pingeb_save_tag(id){
	pingeb_show_loading("saving tag...");
	
	//get filters
	var radius = document.getElementById('pingeb_tag_radius_' + id).value;
	//var radius = 20;
	
	var page = document.getElementById('pingeb_tag_page_' + id).value;
	
	if(!isNumber(radius)){
		pingeb_hide_loading();
		alert("Geofence radius has to be a number!");
		return;
	}
	
	//save tag
	data = {
		action: 'pingeb_save_tag',
		geofence_radius: radius,
		page_id: page,
		tag_id: id
	};

	jQuery.post(ajaxurl, data, function(response) {
		pingeb_hide_loading();
	});
}

function pingeb_build_tag_list(){
	var html = "";
	var tagList = document.getElementById('pingeb_admin_tag_list');

	//header
	html +="<div class='pingeb-table-header'>";
	//html +="<div class='pingeb-table-header-col' style='border-right:1px solid #a4a4a4;'>Id</div>";
	html +="<div class='pingeb-table-header-col' style='border-right:1px solid #a4a4a4;border-left:1px solid #a4a4a4;'>Maker Id</div>";
	html +="<div class='pingeb-table-header-col' style='border-right:1px solid #a4a4a4;border-left:1px solid #a4a4a4;'>Marker Name</div>";
	html +="<div class='pingeb-table-header-col' style='border-right:1px solid #a4a4a4;border-left:1px solid #a4a4a4;'>Layer</div>";
	//html +="<div class='pingeb-table-header-col' style='border-right:1px solid #a4a4a4;border-left:1px solid #a4a4a4;'>Position</div>";
	html +="<div class='pingeb-table-header-col' style='border-right:1px solid #a4a4a4;border-left:1px solid #a4a4a4;'>Geofence Radius</div>";
	html +="<div class='pingeb-table-header-col' style='border-right:1px solid #a4a4a4;border-left:1px solid #a4a4a4;'>Url-Suffixes</div>";
	html +="<div class='pingeb-table-header-col' style='border-left:1px solid #a4a4a4;'>Redirect to</div>";
	html +="</div>";

	//tags
	for(var i = 0; i < tags.length; i++){
		html +="<div class='pingeb-table-row'>";
		//html +="<div class='pingeb-table-col-mm'><center>" + tags[i]['id'] + "</center></div>";
		html +="<div class='pingeb-table-col-mm'><center>" + tags[i]['marker_id'] + "</center></div>";
		html +="<div class='pingeb-table-col-mm'>" + tags[i]['name'] + "</div>";
		html +="<div class='pingeb-table-col-mm'>" + tags[i]['layer_name'] + "</div>";
		
		//html +="<div class='pingeb-table-col-mm'>" + tags[i]['lat'] + " / " + tags[i]['lng'] + "</div>";

		html +="<div class='pingeb-table-col'><nobr>";
		html +="<input type='number' id='pingeb_tag_radius_" + tags[i]['id'] + "' size='3' min='20' max='1000' value='" + tags[i]['geofence_radius'] + "'>m&nbsp;";
		html +="<input onclick='pingeb_save_tag(" + tags[i]['id'] + ")' type='button' id='pingeb_set_radius_btn_" + tags[i]['id'] + "' value='set'>";
		html +="</nobr></div>";

		html +="<div class='pingeb-table-col'>";
		html +="<nobr>";
		html +="<select id='pingeb_tag_url_add_type_" + tags[i]['id'] + "' size='1'>";
		for(var k = 0; k < url_types.length; k++){
			html += "<option value='" + url_types[k]['id'] + "'>" + url_types[k]['name'] + "</option>";
		}
		html +="</select>";
		html +="&nbsp;<input type='text' size='12'  id='pingeb_tag_new_url_" + tags[i]['id'] + "'>";
		html +="&nbsp;<input onclick='pingeb_random_url(" + tags[i]['id'] + ")'type='button' id='pingeb_tag_new_url_btn_" + tags[i]['id'] + "' value='random url'>";
		html +="&nbsp;<input onclick='pingeb_add_url(" + tags[i]['id'] + "," + tags[i]['marker_id'] + ")' type='button' id='pingeb_tag_url_add_" + tags[i]['id'] + "' value='add'>";
		html +="</nobr>";
		
		html +="<ul id='pingeb_tag_urls_" + tags[i]['id'] + "' style='list-style-type:none;'>";
		for(var k = 0; k < tags[i]['urls'].length; k++){
			html +="<li id='pingeb_tag_url_" + tags[i]['urls'][k]['id'] + "'>";
			
			if(tags[i]['urls'][k]['type'] === "QR"){
				html +="<div style='width:30%;float:left;text-align:left;'>";
				html +="<a href=javascript:pingeb_show_qr('" + baseUrl + "/" + tags[i]['urls'][k]['url'] + "');>" +  tags[i]['urls'][k]['type'] + "</a>";
				html +="</div>";
			} else {
				html +="<div style='width:30%;float:left;text-align:left;'>" + tags[i]['urls'][k]['type'] + "</div>";
			}
			
			html +="<div style='width:40%;float:left;text-align:left;'><b>/" + tags[i]['urls'][k]['url'] + "</b></div>";
			html +="<div style='width:30%;float:left;text-align:left;'><a href='javascript:pingeb_remove_url(" + tags[i]['urls'][k]['id'] + "," + tags[i]['id'] + ");'><b>remove</b></a></div>";
			html +="</li>";
		}
		html +="</ul>";
		
		html +="</div>";

		html +="<div class='pingeb-table-col'>";
		html +="<select id='pingeb_tag_page_" + tags[i]['id'] + "' onchange='pingeb_save_tag(" + tags[i]['id'] + ")' size='1'>";
		html +="<option value='-1'>Select a page</option>";
		for(var j = 0; j < pages.length; j++){
			if(tags[i]['page_id'] == pages[j]['id']){
				html += "<option value='" + pages[j]['id'] + "' selected>" + pages[j]['title'] + "</option>";
			} else {
				html += "<option value='" + pages[j]['id'] + "'>" + pages[j]['title'] + "</option>";
			}
		}
		html +="</select>";
		html +="</div>";

		html +="</div>";
	}

	//table footer
	html +="<div class='pingeb-table-header'>";
	html +="<div class='pingeb-table-header-col'>&nbsp;</div>";
	html +="<div class='pingeb-table-header-col'>&nbsp;</div>";
	html +="<div class='pingeb-table-header-col'>&nbsp;</div>";
	html +="<div class='pingeb-table-header-col'>&nbsp;</div>";
	html +="<div class='pingeb-table-header-col'>&nbsp;</div>";
	html +="<div class='pingeb-table-header-col'>&nbsp;</div>";
	//html +="<div class='pingeb-table-header-col'>&nbsp;</div>";
	//html +="<div class='pingeb-table-header-col'>&nbsp;</div>";
	html +="</div>";

	tagList.innerHTML = html;
}

function pingeb_add_url(id, tagid){
	pingeb_show_loading("saving url...");
	
	var urlSuffix = jQuery.trim(document.getElementById("pingeb_tag_new_url_" + id).value);
	var typeName = document.getElementById('pingeb_tag_url_add_type_' + id).options[document.getElementById('pingeb_tag_url_add_type_' + id).selectedIndex].text;
	var type = document.getElementById('pingeb_tag_url_add_type_' + id).value;
	
	var urlList = document.getElementById('pingeb_tag_urls_' + id);
	
	if(urlSuffix === ""){
		pingeb_hide_loading();
		alert("Please enter a valid Url-Suffix!");
		return;
	}		

	data = {
		action: 'pingeb_insert_url',
		url_type_id: type,
		tag_id: id,
		url: urlSuffix
	};

	jQuery.post(ajaxurl, data, function(response) {
		var newUrlId = response;
		
		//add new element to ui
		html ="<li id='pingeb_tag_url_" + newUrlId + "'>";
		
		if(typeName === "QR"){
			html +="<div style='width:30%;float:left;text-align:left;'>";
			html +="<a href=javascript:pingeb_show_qr('" + baseUrl + "/" + urlSuffix + "');>" +  typeName + "</a>";
			html +="</div>";
		} else {
			html +="<div style='width:30%;float:left;text-align:left;'>" + typeName + "</div>";
		}
			
		html +="<div style='width:40%;float:left;text-align:left;'><b>/" + urlSuffix + "</b></div>";
		html +="<div style='width:30%;float:left;text-align:left;'><a href='javascript:pingeb_remove_url(" + newUrlId + "," + id + ");'><b>remove</b></a></div>";
		html +="</li>";
		
		urlList.innerHTML += html;
		
		document.getElementById("pingeb_tag_new_url_" + id).value = "";
		
		pingeb_hide_loading();
	});
}

function pingeb_remove_url(id,tag_id){
	pingeb_show_loading("removing url...");	

	data = {
		action: 'pingeb_delete_url',
		url_id: id
	};

	jQuery.post(ajaxurl, data, function(response) {
		//remove url from ui
		var urlItem = document.getElementById('pingeb_tag_url_' + id);
		document.getElementById('pingeb_tag_urls_' + tag_id).removeChild(urlItem);
		
		pingeb_hide_loading();
	});
}

function pingeb_random_url(id){
	var urlBox = document.getElementById("pingeb_tag_new_url_" + id);
	var ulrLetters = "abcdefghijklmnopqrstuvwxyz1234567890";
	var length = 8;
	var newUrl = "";			

	for(var i = 0; i < length; i++){
		newUrl += ulrLetters[randomInt(0, ulrLetters.length - 1)];
	}

	urlBox.value = newUrl;
}

function pingeb_batch_set_radius(i){
	if(tags.length == 0){
		return;
	}
	if(i == 0){
		var answer = confirm ("Do you really want to assign this geofence radius to all selected tags?")
		if (!answer){
			return;
		}
		pingeb_show_loading("setting geofence radius [" + (i+1) + " of " + tags.length +"]...");
	} else {
		pingeb_hide_loading();
		pingeb_show_loading("setting geofence radius [" + (i+1) + " of " + tags.length +"]...");
	}
	
	var radius = document.getElementById('pingeb_batch_tag_radius').value;
	
	if(!isNumber(radius)){
		pingeb_hide_loading();
		alert("Geofence radius has to be a number!");
		return;
	}
	
	//save tags
	data = {
		action: 'pingeb_save_tag',
		geofence_radius: radius,
		page_id: tags[i]['page_id'],
		tag_id: tags[i]['id']
	};

	jQuery.post(ajaxurl, data, function(response) {
		if(i < tags.length -1){
			i++;
			pingeb_batch_set_radius(i)
		} else {
			pingeb_hide_loading();
			pingeb_get_tags();
		}
	});
}

function pingeb_batch_set_page(i){
	if(tags.length == 0){
		return;
	}
	if(i == 0){
		var answer = confirm ("Do you really want to assign this page to all selected tags?")
		if (!answer){
			return;
		}
		pingeb_show_loading("setting page [" + (i+1) + " of " + tags.length +"]...");
	} else {
		pingeb_hide_loading();
		pingeb_show_loading("setting page [" + (i+1) + " of " + tags.length +"]...");
	}
	
	var page = document.getElementById('pingeb_batch_tag_page').value;
	
	//save tags
	data = {
		action: 'pingeb_save_tag',
		geofence_radius: tags[i]['geofence_radius'],
		page_id: page,
		tag_id: tags[i]['id']
	};

	jQuery.post(ajaxurl, data, function(response) {
		if(i < tags.length -1){
			i++;
			pingeb_batch_set_page(i)
		} else {
			pingeb_hide_loading();
			pingeb_get_tags();
		}
	});
}

function pingeb_show_qr(url){
	var overlay = document.createElement('div');
	overlay.setAttribute('id', 'qroverlay');

	document.body.appendChild(overlay);
	
	//set offset
	overlay.style.top = window.pageYOffset + 'px';

	html = "<p>";
	html += "<a href='javascript:pingeb_hide_qr();' target='_blank'>close</a><br><br>";
	html += "<img src='http://api.qrserver.com/v1/create-qr-code/?size=200x200&amp;data=" + url + "' alt='" + url + "'/><br>";
	html += "<b>" + url + "</b><br><br>";
	html += "<i>QR Code generated using QR Code API from <a href='http://qrserver.com/' target='_blank'>http://qrserver.com/</a></i><br>";
	html += "</p>";
	overlay.innerHTML = html;
}

function pingeb_hide_qr() {
	var overlay = document.getElementById('qroverlay');
 	document.body.removeChild(overlay);
}
