<?php
//-----------------------------------------------------------------------------
/*
Plugin Name: pingeb.org
Plugin URI: http://pingeb.org
Description: A plugin that connects the real world with your great content on WordPress using NFC, QR and geofences.
Version: 2.0.3.1
Author: pingeb.org
Author URI: http://pingeb.org
License: 
This file is part of pingeb.org.
Pingeborg is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published 
by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Pingeborg is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of 
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Pingeborg. If not, see http://www.gnu.org/licenses/.
*/
//-----------------------------------------------------------------------------

global $pingeb_db_version;
$pingeb_db_version= "1.4";

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
   custom_html_id int,
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
   
   $table_name = $wpdb->prefix . "pingeb_custom_html";

   $sql .=  "CREATE TABLE $table_name (
   id mediumint(9) NOT NULL AUTO_INCREMENT,
   name text NOT NULL,
   html text NOT NULL,
   UNIQUE KEY id (id)
   );";

   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   dbDelta($sql);
   
   pingeb_insert_initial_data();
 
   add_option("pingeb_db_version", $pingeb_db_version);
}

//Installs Pingeborg
//Author: Bruno Hautzenberger
//Date: 10.2013
function pingeb_update_db() {
   global $wpdb;
   global $pingeb_db_version;

   $table_name = $wpdb->prefix . "pingeb_tag";
      
   $sql = "CREATE TABLE $table_name (
   id mediumint(9) NOT NULL AUTO_INCREMENT,
   marker_id mediumint(9) NOT NULL,
   geofence_radius tinyint NOT NULL,
   page_id int NOT NULL,
   custom_html_id int,
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
   
   $table_name = $wpdb->prefix . "pingeb_custom_html";

   $sql .=  "CREATE TABLE $table_name (
   id mediumint(9) NOT NULL AUTO_INCREMENT,
   name text NOT NULL,
   html text NOT NULL,
   UNIQUE KEY id (id)
   );";

   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   dbDelta($sql);
   
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
	
	//Custom HTML
	require_once(dirname(__FILE__) . '/pingeb_admin_custom_html.php');
	require_once(dirname(__FILE__) . '/pingeb_admin_custom_html_callbacks.php');
	
	//Twitter Settings
	require_once(dirname(__FILE__) . '/pingeb_admin_twitter.php');
	
	//Tag Maintenance
	require_once(dirname(__FILE__) . '/pingeb_admin_tag_maintenance.php');
	require_once(dirname(__FILE__) . '/pingeb_admin_tag_maintenance_callbacks.php');
	
	require_once(dirname(__FILE__) . '/pingeb_info.php');
}

//page meta
require_once(dirname(__FILE__) . '/pingeb_post_data.php');

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

//admin bar
add_action('admin_bar_menu', 'pingeb_add_toolbar', 100);
function pingeb_add_toolbar($admin_bar){
   global $wp_version;
   if ( version_compare( $wp_version, '3.1', '>=' ) )
   {
      global $wp_admin_bar;
      $menu_items = array(
	 array(
	    'id' => 'pingeb_adminbar_pingeborg',
	    'title' => '<img style="float:left;margin:5px 5px 0 0;" src="' . plugins_url("pingeborg/img/icon.png") . '"/></span> pingeb.org',
	    'href' => admin_url('admin.php?page=pingeb_tag_admin'),
	    'meta' => array( 'title' => 'pingeb.org' )
	 ),
	 array(
	    'id' => 'pingeb_adminbar_tags',
	    'parent' => 'pingeb_adminbar_pingeborg',
	    'title' => 'Tags',
	    'href' => admin_url('admin.php?page=pingeb_tag_admin')
	 ),
	 array(
	    'id' => 'pingeb_adminbar_settings',
	    'parent' => 'pingeb_adminbar_pingeborg',
	    'title' => 'Settings',
	    'href' => admin_url('admin.php?page=pingeb_twitter_admin')
	 ),
	 array(
	    'id' => 'pingeb_adminbar_tag_maintenance',
	    'parent' => 'pingeb_adminbar_pingeborg',
	    'title' => 'Tag Maintenance',
	    'href' => admin_url('admin.php?page=pingeb_admin_tag_maintenance')
	 ),
	 array(
	    'id' => 'pingeb_adminbar_info',
	    'parent' => 'pingeb_adminbar_pingeborg',
	    'title' => 'Info',
	    'href' => admin_url('admin.php?page=pingeb_info')
	 )
      );

      foreach ($menu_items as $menu_item) {
	 $wp_admin_bar->add_menu($menu_item);
      }
   }
}

//add feature to feed
function pingeb_addFeatureImageToFeed($content) {
        $width = "625px";	
	global $post;
	if (has_post_thumbnail($post->ID)){
		$content = '' . get_the_post_thumbnail( $post->ID, $width, array( 'style' => 'float:left; margin:0 10px 10px 0;' ) ) . '<br />' . $content;

	}
	return $content;
}
add_filter('the_content_feed', 'pingeb_addFeatureImageToFeed');

//push new article
add_action( 'save_post', 'pingeb_send_article_push' );

function pingeb_send_article_push( $post_id ) {

   $use_push = get_option('push_enabled');
   $push_url =  get_option('push_url');
   $push_key =  get_option('push_key');

   if($use_push == 1){ // push enabled
      if ( !wp_is_post_revision( $post_id ) ) {
	 $post_title = get_the_title( $post_id );
	 $post_url = get_permalink( $post_id );
	 
	 $qry_str = "?pushKey=" . $push_key . "&content=NEWARTICLE";
	 
	 $ch = curl_init();
	 
	 curl_setopt($ch, CURLOPT_URL, $push_url . $qry_str); 
	 
	 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	 curl_setopt($ch, CURLOPT_TIMEOUT, '3');
	 $content = trim(curl_exec($ch));
	 curl_close($ch);
      }
   }
}

function pingeb_addUploadMimes($mimes) {
    $mimes = array_merge($mimes, array(
        'epub|mobi' => 'application/octet-stream'
    ));
    return $mimes;
}
add_filter('upload_mimes', 'pingeb_addUploadMimes');

//update db allways if needed
pingeb_update_db();

?>
