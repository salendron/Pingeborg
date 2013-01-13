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

function pingeb_counter_widget( $args ) { 
	global $wpdb; 
	
	$downloads = $wpdb->get_var( $wpdb->prepare( "select count(*) from " . $wpdb->prefix . "pingeb_statistik",null ) );
	$downloadsToday = $wpdb->get_var( $wpdb->prepare( "select count(*) from " . $wpdb->prefix . "pingeb_statistik where curdate() = substr(visit_time,1,10)",null ) );
	$tags = $wpdb->get_var( $wpdb->prepare( "select count(*) from " . $wpdb->prefix . "pingeb_tag", null ) );
	
	//Render Widget
	echo $args['before_widget'];
	echo $args['before_title'].'Content Counter'.$args['after_title'];
	echo "<p><font size='16'>" . $downloadsToday . "</font> Downloads heute</p>";
	echo "<p><font size='16'>" . $downloads . "</font> Downloads insgesamt</p>";
	echo "<p><font size='16'>" . $tags . "</font> Orte</p>";
	echo "<br>";
	echo $args['after_widget'];
}


function pingeb_counter_widget_register() {
	wp_register_sidebar_widget(
		'pingeb_counter_widget',        // your unique widget id
		'Pingeborg Counter Widget',          // widget name
		'',  // callback function
		array(                  // options
			'description' => 'Showing pingeb.org statistics'
		)
	);
}
add_action( 'plugins_loaded', 'pingeb_counter_widget_register' );
?>
