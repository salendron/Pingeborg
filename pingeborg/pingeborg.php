<?php
//-----------------------------------------------------------------------------
/*
Plugin Name: Pingeborg
Plugin URI: http://pingeb.org
Description: Pingeb.org und zwar gesamt!
Version: 0.0.3.1
Author: Bruno Hautzenberger
Author URI: http://the-engine.at
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
 
   add_option("pingeb_db_version", $pingeb_db_version);
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
}

//redirect script
require_once(dirname(__FILE__) . '/pingeb_links.php');

//widgets
require_once(dirname(__FILE__) . '/pingeb_widgets.php');
?>