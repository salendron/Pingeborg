<?php
//-----------------------------------------------------------------------------
/*
Plugin Name: Pingeborg
Plugin URI: http://pingeb.org
Description: A plugin that connects the real world with your great content on WordPress using NFC, QR and geofences.
Version: 1.0.0.91
Author: Bruno Hautzenberger
Author URI: http://the-engine.at
License: 
This file is part of Pingeborg.
Pingeborg is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published 
by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Pingeborg is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of 
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Pingeborg. If not, see http://www.gnu.org/licenses/.
*/
//-----------------------------------------------------------------------------

global $pingeb_db_version;
$pingeb_db_version= "1.3";

if ( ! defined( 'PINGEBORG_WP_ADMIN_URL' ) )
	define( 'PINGEBORG_WP_ADMIN_URL', get_admin_url() );
if ( ! defined( 'PINGEBORG_PLUGIN_URL' ) )
	define ("PINGEBORG_PLUGIN_URL", plugin_dir_url(__FILE__));
if ( ! defined( 'PINGEBORG_PLUGIN_DIR' ) )
	define ("PINGEBORG_PLUGIN_DIR", plugin_dir_path(__FILE__));

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
   page_id int NOT NULL,
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

	function pingeb_admin_styles() {
		global $wp_styles;
		wp_enqueue_style('pingeborg-admin', PINGEBORG_PLUGIN_URL . 'styles/admin.css' );		
		wp_enqueue_script( 'pingeb_common', PINGEBORG_PLUGIN_URL . 'js/common.js', array('jquery'), null, true );
		wp_localize_script('pingeb_common', 'pingeb', array(
			'loadingImg' => PINGEBORG_PLUGIN_URL . 'img/loading51.gif',
			'baseUrl' => get_bloginfo('url')
		) );
	}
	add_action('admin_init','pingeb_admin_styles');

	//-----------------------------------------------------------------------------
	//Tag Admin
	require_once(dirname(__FILE__) . '/pingeb_admin_tags.php');
	require_once(dirname(__FILE__) . '/pingeb_admin_tags_callbacks.php');
	
	//Twitter Settings
	require_once(dirname(__FILE__) . '/pingeb_admin_twitter.php');
	
	//Tag Maintenance
	require_once(dirname(__FILE__) . '/pingeb_admin_tag_maintenance.php');
	require_once(dirname(__FILE__) . '/pingeb_admin_tag_maintenance_callbacks.php'); 
	require_once(dirname(__FILE__) . '/pingeb_info.php');
}
//common js - enqueued in frontend and backend
function pingeb_scripts() {
    wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'pingeb_heatmap', PINGEBORG_PLUGIN_URL . 'js/heatmap.js', array('jquery'), null, true );
	wp_enqueue_script( 'raphael', PINGEBORG_PLUGIN_URL . 'js/raphael-min.js', array('jquery'), null, true );
	wp_enqueue_script( 'graphael', PINGEBORG_PLUGIN_URL . 'js/g.raphael.js', array('raphael'), null, true );
	wp_enqueue_script( 'raphael_line', PINGEBORG_PLUGIN_URL . 'js/g.line.js', array('graphael'), null, true );
	wp_enqueue_script( 'raphael_bar', PINGEBORG_PLUGIN_URL . 'js/g.bar.js', array('graphael'), null, true );
	wp_enqueue_script( 'raphael_pie', PINGEBORG_PLUGIN_URL . 'js/g.pie.js', array('graphael'), null, true );
}
add_action('wp_enqueue_scripts', 'pingeb_scripts');

//api
require_once(dirname(__FILE__) . '/pingeb_api.php');

//redirect script
require_once(dirname(__FILE__) . '/pingeb_links.php');

//statistics
require_once(dirname(__FILE__) . '/pingeb_statistics.php');

//widgets
require_once(dirname(__FILE__) . '/pingeb_widgets.php');
?>