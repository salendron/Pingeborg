<?php
/*
This work is licensed under the Creative Commons Namensnennung-Nicht-kommerziell 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by-nc/3.0/.
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

	//Tag list
	?> 
	<div class="pingeb-table" id="pingeb_admin_tag_list">
		
	</div>
	<?php 
	//END Tag list
}	

?>
