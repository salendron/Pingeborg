<?php
/*
Copyright 2012 Bruno Hautzenberger

This file is part of Pingeborg.

Pingeborg is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published 
by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Pingeborg is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of 
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Pingeborg. If not, see http://www.gnu.org/licenses/.
*/ 

//Add custom html block Page
add_action("admin_menu", "register_pingeb_custom_html");

//Registers the custom html block Page
//Author: Bruno Hautzenberger
//Date: 09.2013
function register_pingeb_custom_html() {
   add_submenu_page("pingeb_tag_admin", "Custom HTML", "Custom HTML", "add_users", "pingeb_custom_html", "pingeb_custom_html"); 
}

//Renders the custom html block Page
//Author: Bruno Hautzenberger
//Date: 09.2013
function pingeb_custom_html(){
	wp_enqueue_script( 'pingeb_custom_html', plugins_url('/js/pingeb_admin_custom_html.js', __FILE__) , array('jquery'), null, true ); 

	//Page Header   
	?> 
	<div id="pingeb-admin-box" style="min-height:100px;">
		<img src="<?php echo plugins_url("pingeborg/img/logo.png"); ?>" style="height:100px;float:left;margin-right:5px;margin-bottom:5px;">
		<h1 class="pingeb_headline">Custom HTML</h1>
		<p>Here you can create custom HTML Blocks which will be added to pingeb.org content pages right above the "squares". <br />You can create as many blocks as you want. These blocks can than be assigned to one or more tag on the tag admin page. So each tag can create a different block on the same content page.</p>
	</div>
	<?php 
	//END Page Header 

	//new block
	?> 
	<div id="pingeb-admin-box">
		<h2 class="pingeb_headline">New custom HTML block</h2>
		<p>
		     <b>Block name:</b><br />
		     <input type="text" id="pingeb_custom_html_name" size="25" value=""><br /><br />
			
		     <b>Custom HTML:</b><br />
		     <textarea id="pingeb_custom_html_html" rows="8" cols="200"></textarea><br />
		     <input onclick="pingeb_custom_html_create(0)" type="button" id="pingeb_custom_html_create" value="create block">
		</p>
	</div>
	<?php 
	//END new block

	//Custom HTML blocks
	?> 
	<div class="pingeb-table" id="pingeb_custom_html_list">
		
	</div>
	<?php 
	//END custom HTML blocks
}	

?>
