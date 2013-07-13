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
add_action("admin_menu", "register_pingeb_tag_admin");

//Registers the Tag Admin Page
//Author: Bruno Hautzenberger
//Date: 09.2012
function register_pingeb_tag_admin() {
   add_menu_page("Pingeborg Tag Admin", "Pingeborg Tags", "add_users", "pingeb_tag_admin", "pingeb_tag_admin",plugins_url("pingeborg/img/icon.png")); 
}

//Renders the Tag Admin Page
//Author: Bruno Hautzenberger
//Date: 09.2012
function pingeb_tag_admin(){
	wp_enqueue_script( 'pingeb_admin_tags', plugins_url('/js/pingeb_admin_tags.js', __FILE__) , array('jquery'), null, true );

	//Page Header   
	?> 
	<div id="pingeb-admin-box" style="min-height:200px;">
	       <img src="<?php echo plugins_url("pingeborg/img/logo.png"); ?>" style="float:left;margin-right:5px;margin-bottom:5px;">
	       <h1>pingeb.org tags</h1>
	       <p><strong><a href="http://pingeb.org/" target="_blank">Project Ingeborg</a></strong> was founded by Georg Holzer and Bruno Hautzenberger to promote local artists using stickers or posters equipped with NFC-tags and QR-codes. But you can do a lot of crazy stuff with it and distribute all kinds of digital content in your village, town or city. Check out our project documentation at <a href="http://pep.pingeb.org" target="_blank">pep.pingeb.org</a>.</p>
	       <p><strong>Requirements:</strong> You have to change the <a href="/wp-admin/options-permalink.php">permalink structure</a> to anything but the default and you need to install another plugin: <a href="http://wordpress.org/extend/plugins/leaflet-maps-marker/">Leaflet Maps Marker</a> by <a href="http://www.harm.co.at" target="_blank">Robert Harm</a>. It lets you create the locations that will show up here to assign URLs for NFC-tags and QR-codes.</p>
	       <p><strong>That's it?</strong> Yes! Now get awesome and do amazing stuff. We’d love to hear from you: <a href="mailto:mail@pingeb.org">mail@pingeb.org</a>.</p>	
	 </div>
	<?php 
	//END Page Header

  If (!is_plugin_active('leaflet-maps-marker/leaflet-maps-marker.php')) {
  	  //Maps MArker Check   
				?>
				  <div id="pingeb-admin-box">
		      <p style="color:red;"><strong>WARNING!</strong><br>Leaflet Maps Marker is not installed or not activated! This plugin is necessary for pingeb.org to work! 
		      Leaflet Maps Marker is available here -><a href="http://wordpress.org/extend/plugins/leaflet-maps-marker/">http://wordpress.org/extend/plugins/leaflet-maps-marker/</a>.</p>
		    </div>
				<?php 
  }

	//Batch Actions
	?> 
	<div id="pingeb-admin-box">
		<h2 class="pingeb_headline">Batch Actions</h2>
		<p><i>Batch Actions will affect all currently visible Tags. Use filters to select tags to apply batch changes on.</i></p> 
		<p>
			<select id="pingeb_batch_tag_page" size="1">
				<option value="-1">Select a Page</option>
			</select>&nbsp;
			<input onclick="pingeb_batch_set_page(0)" type="button" id="pingeb_batch_assign_page" value="assign page">
			
			&nbsp;|&nbsp;
			<input type="number" id="pingeb_batch_tag_radius" size="3" min="20" max="250" value="20">
			<input onclick="pingeb_batch_set_radius(0)" type="button" id="pingeb_batch_assign_radius" value="assign geofence radius">
			
		</p>
		
		<h3 class="pingeb_headline">Generate Urls</h3>
		<p>
			<input onclick="pingeb_batch_generateUrls_withType(0,1)" type="button" id="pingeb_batch_urls" value="auto generate missing NFC urls">&nbsp;
			<input onclick="pingeb_batch_generateUrls_withType(0,2)" type="button" id="pingeb_batch_urls" value="auto generate missing QR urls">&nbsp;
			<input onclick="pingeb_batch_generateUrls_withType(0,3)" type="button" id="pingeb_batch_urls" value="auto generate missing Geofence urls">&nbsp;
		</p>
	</div>
	<?php 
	//END Batch Actions

	//Tag filter
	?> 
	<div id="pingeb-admin-box">
		<h2 class="pingeb_headline">Filter</h2>
		<p><i>Filters affect this list as well as batch actions.</i></p> 
		<p>
			Layer:
			<select id="pingeb_filter_layer" size="1">
				<option value="-1">all layers</option>
			</select>&nbsp;

			Marker Name:
			<input type="text" size="25"  id="pingeb_filter_marker_name">&nbsp;

			Assisgned page:
			<select id="pingeb_filter_page" size="1">
				<option value="-1">all pages</option>
			</select>&nbsp;

			&nbsp;<input onclick="pingeb_get_tags()"type="button" id="pingeb_filter_tags" value="apply filter">
			&nbsp;|&nbsp;<input onclick="pingeb_clear_filter()"type="button" id="pingeb_clear_filter" value="clear filter">
		</p>
	</div>
	<?php 
	//END Tag filter
	
	//Export
	?> 
	<div id="pingeb-admin-box">
		<h2 class="pingeb_headline">Export</h2>
		<p>
			<a href="#" id="pingeb_export_tags"  download="tags.csv">Export selected tags to csv</a>
		</p>
	</div>
	<?php 
	//END Export
	
	 //Push
	 $use_push = get_option('push_enabled');
      
	 if($use_push == 1){ // push enabled
	?> 
	<div id="pingeb-admin-box">
		<h2 class="pingeb_headline">Push to mobile devices</h2>
		<p><i>Every time you have edited tags you should use this button to push all changed and new tags to mobile devices. They will automatically update all tags in their cache. This will not trigger a notification on these devices.</i></p> 
		<p>
		  <input onclick="pingeb_send_push()" type="button" id="pingeb_send_push" value="send push">
		</p>
	</div>
	<?php
	 }
	//END Push

	//Tag list
	?> 
	<div class="pingeb-table" id="pingeb_admin_tag_list">
		
	</div>
	<?php 
	//END Tag list
}	

?>
