<?php

//Shows the Pingeborg Counter Widget
//Author: Bruno Hautzenberger
//Date: 09.2012
function pingeb_counter_widget( $args ) { 
	global $wpdb; 
	
	$downloads = $wpdb->get_var( $wpdb->prepare( "select count(*) from " . $wpdb->prefix . "pingeb_statistik" ) );
	$downloadsToday = $wpdb->get_var( $wpdb->prepare( "select count(*) from " . $wpdb->prefix . "pingeb_statistik where curdate() = substr(visit_time,1,10)" ) );

	//Render Widget
	echo $args['before_widget'];
	echo $args['before_title'].'Content Counter'.$args['after_title'];
	echo "<p class='pingeb_counter_widget'>Downloads: " . $downloads . "<br>";
	echo "Downloads today: " . $downloadsToday;
	echo "<br>";
	echo $args['after_widget'];
}

//Registers the Pingeborg Counter Widget
//Author: Bruno Hautzenberger
//Date: 09.2012
function pingeb_counter_widget_register() {
  register_sidebar_widget( // Widget registrieren
    'Pingeborg Counter Widget', // Name des Widgets
    'pingeb_counter_widget'
  );
}
add_action( 'plugins_loaded', 'pingeb_counter_widget_register' );

?>
