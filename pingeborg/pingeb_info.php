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
add_action("admin_menu", "register_pingeb_info");

//Registers the Tag Admin Page
//Author: Bruno Hautzenberger
//Date: 09.2012
function register_pingeb_info() {
   add_submenu_page("pingeb_tag_admin", "Info", "Info", "add_users", "pingeb_info", "pingeb_info"); 
}

//Renders the Twitter Admin Page
//Author: Bruno Hautzenberger
//Date: 10.2012
function pingeb_info(){
	//Page Header   
	?> 
	<div id="pingeb-admin-box" style="min-height:100px;">
		<img src="<?php echo plugins_url("pingeborg/img/logo.png"); ?>" style="height:100px;float:left;margin-right:5px;margin-bottom:5px;">
		<h1 class="pingeb_headline">Info</h1>
		<p> <strong><a href="http://pingeb.org/" target="_blank">Project Ingeborg</a></strong> was founded by Georg Holzer and Bruno Hautzenberger to promote local artists using stickers or posters equipped with NFC-tags and QR-codes. But you can do a lot of crazy stuff with it and distribute all kinds of digital content in your village, town or city. Check out our project documentation at <a href="http://pep.pingeb.org" target="_blank">pep.pingeb.org</a>. </p>
	</div>
	
	 <h2>Thanks!</h2>
	 <p>Project Ingeborg is proudly supported by the <a href="http://www.nic.at/ipa" target="_blank">Austrian Internet Foundation IPA</a></p>
	 <img src="<?php echo plugins_url("pingeborg/img/netidee.jpg"); ?>">
	 
	 <p>Thanks a lot to <a href="http://wordpress.org/extend/plugins/leaflet-maps-marker/">Leaflet Maps Marker</a> by <a href="http://www.harm.co.at" target="_blank">Robert Harm</a>.</p>
	 <img src="http://cdn.mapsmarker.com/wp-content/themes/mapsmarker/inc/images/mapsmarker.png">
	
	<h2>3rd party libraries</h2>
	<h3>g.Raphael - Charting library, based on Raphaël</h3>
 <p>Copyright (c) 2009 Dmitry Baranovskiy <a href="http://g.raphaeljs.com" target="_blank">http://g.raphaeljs.com</a></p>
	
	<h3>heatmap.js -	JavaScript Heatmap Library </h3>
 <p>Copyright (c) 2011, Patrick Wied <a href="http://www.patrick-wied.at" target="_blank">http://www.patrick-wied.at</a><br>
    Dual-licensed under the MIT <a href="http://www.opensource.org/licenses/mit-license.php" target="_blank">http://www.opensource.org/licenses/mit-license.php</a><br>
    and the Beerware <a href=" http://en.wikipedia.org/wiki/Beerware " target="_blank">http://en.wikipedia.org/wiki/Beerware</a> license.</p>
	
	<h3>tmhOAuth - An OAuth 1.0A library written in PHP for use with the Twitter API.</h3>
 <p>By Matt Harris - Licensed under Apache license 2.0 <a href="http://www.apache.org/licenses/" target="_blank">http://www.apache.org/licenses/</a></p>
	
	<?php 
}	

?>
