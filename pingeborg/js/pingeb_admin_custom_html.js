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
var blocks = [];

jQuery(document).ready(function($) {
	pingeb_custom_html_reload();
});

function pingeb_custom_html_create(){
	pingeb_show_loading("creating block...");
	
	var new_name = document.getElementById('pingeb_custom_html_name').value;
	var new_html = document.getElementById('pingeb_custom_html_html').value;
	
	//create block
	data = {
		action: 'pingeb_insert_cutom_html_block',
		name: new_name,
		html: new_html
	};

	jQuery.post(ajaxurl, data, function(response) {
		pingeb_hide_loading();
		pingeb_custom_html_reload();
	});
}

function pingeb_custom_html_reload(){
	pingeb_show_loading("");

	//load pages
	var data = {
		action: 'pingeb_get_custom_html_blocks'
	};

	jQuery.post(ajaxurl, data, function(response) {
		blocks = JSON.parse(response);
		pingeb_build_block_list();
		pingeb_hide_loading();
	});
}

function pingeb_build_block_list(){
	var html = "";
	var blocklist = document.getElementById('pingeb_custom_html_list');

	if(blocks.length > 0){
		//header
		html +="<div class='pingeb-table-header'>";
		html +="<div class='pingeb-table-header-col' style='border-right:1px solid #a4a4a4;border-left:1px solid #a4a4a4;'>Block Name</div>";
		html +="<div class='pingeb-table-header-col' style='border-right:1px solid #a4a4a4;border-left:1px solid #a4a4a4;'>HTML</div>";
		html +="</div>";
	
		//tags
		for(var i = 0; i < blocks.length; i++){
			html +="<div class='pingeb-table-row'>";
			
			html +="<div class='pingeb-table-col-mm'>" + blocks[i]['name'] + "</div>";
			html +="<div class='pingeb-table-col-mm' style='vertical-align:top;'><textarea id='pingeb_custom_html_html_" + blocks[i]['id'] + "' rows='8' cols='120'>" + blocks[i]['html'] + "</textarea></div>";
	
			html +="<div class='pingeb-table-col-mm' style='vertical-align:top;'>";
			html +="<input style='width:100%' onclick='pingeb_save_block(" + blocks[i]['id'] + ")' type='button' id='pingeb_save_block_" + blocks[i]['id'] + "' value='save'><br />";
			html +="<input style='width:100%' onclick='pingeb_delete_block(" + blocks[i]['id'] + ")' type='button' id='pingeb_delete_block_" + blocks[i]['id'] + "' value='delete'>";
			html +="</div>";
	
			html +="</div>";
		
		}
	
		//table footer
		html +="<div class='pingeb-table-header'>";
		html +="<div class='pingeb-table-header-col'>&nbsp;</div>";
		html +="<div class='pingeb-table-header-col'>&nbsp;</div>";
		html +="</div>";
	
		blocklist.innerHTML = html;
	} else {
		blocklist.innerHTML = "";
	}
}

function pingeb_save_block(block_id){
	pingeb_show_loading("saving block...");

	var new_html = document.getElementById('pingeb_custom_html_html_' + block_id).value;
	
	//save block
	data = {
		action: 'pingeb_save_block',
		id: block_id,
		html: new_html
	};

	jQuery.post(ajaxurl, data, function(response) {
		pingeb_hide_loading();
		pingeb_custom_html_reload();
	});
}

function pingeb_delete_block(block_id){
	var answer = confirm ("Do you really want to delete this custom HTML block?")
	if (!answer){
		return;
	}
	
	pingeb_show_loading("deleting block...");
	
	//delete block
	data = {
		action: 'pingeb_delete_block',
		id: block_id
	};

	jQuery.post(ajaxurl, data, function(response) {
		pingeb_hide_loading();
		pingeb_custom_html_reload();
	});
}
