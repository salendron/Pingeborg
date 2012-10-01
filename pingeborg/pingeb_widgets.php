<?php

function pingeb_counter_widget( $args ) { 
	global $wpdb; 
	
	$downloads = $wpdb->get_var( $wpdb->prepare( "select count(*) from " . $wpdb->prefix . "pingeb_statistik" ) );
	$downloadsToday = $wpdb->get_var( $wpdb->prepare( "select count(*) from " . $wpdb->prefix . "pingeb_statistik where curdate() = substr(visit_time,1,10)" ) );

	//Render Widget
	echo $args['before_widget'];
	echo $args['before_title'].'Content Counter'.$args['after_title'];
	echo "<p><font size='16'>" . $downloadsToday . "</font> Downloads heute</p>";
	echo "<p><font size='16'>" . $downloads . "</font> Downloads insgesamt</p>";
	echo "<br>";
	echo $args['after_widget'];
}


function pingeb_counter_widget_register() {
  register_sidebar_widget( // Widget registrieren
    'Pingeborg Counter Widget', // Name des Widgets
    'pingeb_counter_widget'
  );
}
add_action( 'plugins_loaded', 'pingeb_counter_widget_register' );

?>
