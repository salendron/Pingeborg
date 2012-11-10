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

//Add Tag Admin Page
add_action("admin_menu", "register_pingeb_admin_tag_maintenance");

//Registers the Tag Admin Page
//Author: Bruno Hautzenberger
//Date: 09.2012
function register_pingeb_admin_tag_maintenance() {
   add_submenu_page("pingeb_tag_admin", "Tag Maintenance", "Tag Maintenance", "add_users", "pingeb_admin_tag_maintenance", "pingeb_admin_tag_maintenance"); 
}

//Renders the Tag Maintenance Admin Page
//Author: Bruno Hautzenberger
//Date: 10.2012
function pingeb_admin_tag_maintenance(){
	wp_enqueue_script( 'pingeb_admin_tag_maintenance', plugins_url('/js/pingeb_admin_tag_maintenance.js', __FILE__) , array('jquery'), null, true );

	//Page Header   
	?> 
	<div id="pingeb-admin-box" style="min-height:50px;">
		<img src="<?php echo plugins_url("pingeborg/img/logo.png"); ?>" style="height:50px;float:left;margin-right:5px;margin-bottom:5px;">
		<h1 class="pingeb_headline">Tag Maintenance</h1>
		<p>If you delete a tag in Maps Marker it will still exist as a Pingeorg tag, because otherwise you would loose all downloads of this tag.
		On this page you can merge this tag into an other existing tag or really delete it (downloads of this tag will also be deleted).<br>
		Another function of this page is it to notify you about tags which haven't been used within the last 4 weeks, 
		because these tags (stickers) have probably been removed and need to be replaced. If your system is new this list will show all tags without downloads.</p>
	</div>
	<?php 
	
	//Show tags
	?> 
	<div id="pingeb-admin-box">
		<p>
			<select id="pingeb_tag_maintenance_mode" size="1">
				<option value="1">Tags without a download in the last 4 weeks</option>
				<option value="2">Tags without Markers</option>
			</select>&nbsp;
			<input onclick="pingeb_show_maintenance_list()" type="button" value="show">
		</p>
	</div>
	<?php 
	//show tags
	
	//tag list
	?> 
	<div class="pingeb-table" id="pingeb_tag_maintenance_list"></div>
	<?php 
	//tag list
}	

?>
