<?php
//-----------------------------------------------------------------------------
/*
Plugin Name: Pingeborg
Plugin URI: http://pingeb.org
Description: Pingeb.org as a plugin.
Version: 1.0.0.2
Author: Bruno Hautzenberger
Author URI: http://the-engine.at
License: This work is licensed under the Creative Commons Namensnennung-Nicht-kommerziell 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by-nc/3.0/.
*/
//-----------------------------------------------------------------------------

global $pingeb_db_version;
$pingeb_db_version= "1.3";

//Installs Pingeborg
//Author: Bruno Hautzenberger
//Date: 09.2012
function pingeb_db_install() {
   global $wpdb;
   global $pingeb_db_version;

   $table_name = $wpdb->prefix . "pingeb_tag";
      
   $sql = "CREATE TABLE $table_name (
   id mediumint(9) NOT NULL AUTO_INCREMENT,
   marker_id mediumint(9) NOT NULL,
   geofence_radius tinyint NOT NULL,
   page_id tinyint NOT NULL,
   UNIQUE KEY id (id)
   );";
   
   $table_name = $wpdb->prefix . "pingeb_url";
   
   $sql .= "CREATE TABLE $table_name (
   id mediumint(9) NOT NULL AUTO_INCREMENT,
   tag_id mediumint(9) NOT NULL,
   url text NOT NULL,
   url_type_id tinyint NOT NULL,
   UNIQUE KEY id (id)
   );";

   $table_name = $wpdb->prefix . "pingeb_url_type";

   $sql .=  "CREATE TABLE $table_name (
   id mediumint(9) NOT NULL AUTO_INCREMENT,
   name tinytext NOT NULL,
   description text NOT NULL,
   UNIQUE KEY id (id)
   );";

   $table_name = $wpdb->prefix . "pingeb_statistik";

   $sql .=  "CREATE TABLE $table_name (
   id mediumint(9) NOT NULL AUTO_INCREMENT,
   tag_id mediumint(9) NOT NULL,
   url_type mediumint(9) NOT NULL,
   visitor_os text NOT NULL,
   visit_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   UNIQUE KEY id (id)
   );";

   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   dbDelta($sql);
   
   pingeb_insert_initial_data();
 
   add_option("pingeb_db_version", $pingeb_db_version);
}

function pingeb_insert_initial_data()
{
    global $wpdb;

    $table_name = $wpdb->prefix.'pingeb_url_type';
    
    $nfc = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE id = 1;" ) );
    $qr = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE id = 2;" ) );
    $geofence = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE id = 3;" ) );

	if($nfc == 0){
		$wpdb->insert($table_name, array('id' => 1, 'name' => 'NFC'));
	}
    
    if($qr == 0){
		$wpdb->insert($table_name, array('id' => 2, 'name' => 'QR'));
	}
	
	if($geofence == 0){
		$wpdb->insert($table_name, array('id' => 3, 'name' => 'Geofence'));
	}
}

register_activation_hook(__FILE__,'pingeb_db_install');

//Register Scripts and reference other files
if(is_admin()){
	//-----------------------------------------------------------------------------

	function pingeb_admin_style() {
		echo "<link rel='stylesheet' href='" . plugins_url('styles/admin.css', __FILE__) . "' media='all' />";

		//script global values
		echo "<script language='javascript'>\n";
		echo "var loadingImg = '" . plugins_url('/img/loading51.gif', __FILE__) . "';\n";
		echo "var baseUrl = '" . get_bloginfo('url') . "';\n";
		echo "</script>\n";
	}

	add_action('admin_head', 'pingeb_admin_style');
	
	wp_enqueue_script( 'pingeb_common', plugins_url('/js/common.js', __FILE__) , array('jquery'), null, true );

	//-----------------------------------------------------------------------------
	//Tag Admin
	require_once(dirname(__FILE__) . '/pingeb_admin_tags.php');
	require_once(dirname(__FILE__) . '/pingeb_admin_tags_callbacks.php');
	
	//Twitter Settings
	require_once(dirname(__FILE__) . '/pingeb_admin_twitter.php');
	
	//Tag Maintenance
	require_once(dirname(__FILE__) . '/pingeb_admin_tag_maintenance.php');
	require_once(dirname(__FILE__) . '/pingeb_admin_tag_maintenance_callbacks.php');
}
//common js
function pingeb_scripts() {
	echo "<script type='text/javascript' src='" . plugins_url('/js/common.js', __FILE__) . "'></script>";
}
add_action('wp_head', 'pingeb_scripts');

//api
require_once(dirname(__FILE__) . '/pingeb_api.php');

//redirect script
require_once(dirname(__FILE__) . '/pingeb_links.php');

//statistics
require_once(dirname(__FILE__) . '/pingeb_statistics.php');

wp_enqueue_script( 'pingeb_heatmap', plugins_url('/js/heatmap.js', __FILE__) , array('jquery'), null, true );
wp_enqueue_script( 'raphael', plugins_url('/js/raphael-min.js', __FILE__) , array('jquery'), null, true );
wp_enqueue_script( 'graphael', plugins_url('/js/g.raphael.js', __FILE__) , array('raphael'), null, true );
wp_enqueue_script( 'raphael_line', plugins_url('/js/g.line.js', __FILE__) , array('graphael'), null, true );
wp_enqueue_script( 'raphael_bar', plugins_url('/js/g.bar.js', __FILE__) , array('graphael'), null, true );
wp_enqueue_script( 'raphael_pie', plugins_url('/js/g.pie.js', __FILE__) , array('graphael'), null, true );


//widgets
require_once(dirname(__FILE__) . '/pingeb_widgets.php');
?>
