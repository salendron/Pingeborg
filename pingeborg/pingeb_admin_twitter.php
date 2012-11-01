<?php
/*
This work is licensed under the Creative Commons Namensnennung-Nicht-kommerziell 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by-nc/3.0/.
*/

//Add Tag Admin Page
add_action("admin_menu", "register_pingeb_twitter_admin");

//Registers the Tag Admin Page
//Author: Bruno Hautzenberger
//Date: 09.2012
function register_pingeb_twitter_admin() {
   add_submenu_page("pingeb_tag_admin", "Settings", "Settings", "add_users", "pingeb_twitter_admin", "pingeb_twitter_admin"); 

   add_action( 'admin_init', 'pingeb_register_twitter_settings' );
}

function pingeb_register_twitter_settings() {
	//register twitter settings
	register_setting( 'pingeb_twitter_settings_group', 'use_twitter' );
	register_setting( 'pingeb_twitter_settings_group', 'consumer_key' );
	register_setting( 'pingeb_twitter_settings_group', 'consumer_secret' );
	register_setting( 'pingeb_twitter_settings_group', 'user_token' );
	register_setting( 'pingeb_twitter_settings_group', 'user_secret' );
	register_setting( 'pingeb_twitter_settings_group', 'tweet_text' );
	
	//register geofence settings
	register_setting( 'pingeb_twitter_settings_group', 'use_geofence' );
	register_setting( 'pingeb_twitter_settings_group', 'geofence_url' );
	register_setting( 'pingeb_twitter_settings_group', 'no_tag_found_url' );
	register_setting( 'pingeb_twitter_settings_group', 'google_api_key' );
}

//Renders the Twitter Admin Page
//Author: Bruno Hautzenberger
//Date: 10.2012
function pingeb_twitter_admin(){
	//wp_enqueue_script( 'pingeb_admin_tags', plugins_url('/js/pingeb_admin_tags.js', __FILE__) , array('jquery'), null, true );

	//Page Header   
	?> 
	<div id="pingeb-admin-box" style="min-height:100px;">
		<img src="<?php echo plugins_url("pingeborg/img/logo.png"); ?>" style="height:100px;float:left;margin-right:5px;margin-bottom:5px;">
		<h1 class="pingeb_headline">Settings</h1>
		<p><b>Geofences</b> The system can also work with geofences. In this case your user navigate to a special url on their phone and if they are near a tag they will be directly redirected to the content. This only works with tags which have a gefence url assigned to them.</p>
		<p><b>Twitter</b> The system can automaticaly send a tweet everytime a tags is used. If you want to use this feature you have to register an application for your twitter account on <a href="https://dev.twitter.com/">https://dev.twitter.com/</a>.</p>
	</div>
	<?php 
	
	//Setting
	$checked = "";
	if(get_option('use_twitter') == 1) {
		$checked = "checked";
	}
	
	$geo_checked = "";
	if(get_option('use_geofence') == 1) {
		$geo_checked = "checked";
	}
	?> 
	<form method="post" action="options.php">
		<?php settings_fields( 'pingeb_twitter_settings_group' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row" colspan="2"><h2>Geofences</h2></th>
			</tr>
			<tr valign="top">
				<th scope="row"><b>Activate</b></th>
				<td><input name="use_geofence" type="checkbox" value="1" <?php echo $geo_checked; ?> /> </td>
			</tr>
			<tr valign="top">
				<th scope="row">Google Maps API Key</th>
				<td><input name="google_api_key" type="text" size="70" value="<?php echo get_option('google_api_key'); ?>" /> </td>
			</tr>
			<tr valign="top">
				<th scope="row">Geofence Url</th>
				<td><?php get_bloginfo('url'); ?>/ <input name="geofence_url" type="text" size="70" value="<?php echo get_option('geofence_url'); ?>" /> </td>
			</tr>
			<tr valign="top">
				<th scope="row">No tag found here Url</th>
				<td><input name="no_tag_found_url" type="text" size="70" value="<?php echo get_option('no_tag_found_url'); ?>" /> This should be the full url (with http://) to a page where you tell your users that there is no tag nearby.</td>
			</tr>
			<tr valign="top">
				<th scope="row" colspan="2"><h2>Twitter</h2></th>
			</tr>
			<tr valign="top">
				<th scope="row"><b>Activate</b></th>
				<td><input name="use_twitter" type="checkbox" value="1" <?php echo $checked; ?> /> </td>
			</tr>
			<tr valign="top">
				<th scope="row">Consumer Key</th>
				<td><input name="consumer_key" type="text" size="70" value="<?php echo get_option('consumer_key'); ?>" /> </td>
			</tr>
			<tr valign="top">
				<th scope="row">Consumer Secret</th>
				<td><input name="consumer_secret" type="text" size="70" value="<?php echo get_option('consumer_secret'); ?>" />  </td>
			</tr>
			<tr valign="top">
				<th scope="row">User Token</th>
				<td><input name="user_token" type="text" size="70" value="<?php echo get_option('user_token'); ?>" /> </td>
			</tr>
			<tr valign="top">
				<th scope="row">User Secret</th>
				<td><input name="user_secret" type="text" size="70" value="<?php echo get_option('user_secret'); ?>" />  </td>
			</tr>
			<tr valign="top">
				<th scope="row"><b>Tweet Text</b></th>
				<td><input name="tweet_text" type="text" size="70" maxSize="100" value="<?php echo get_option('tweet_text'); ?>" />  use %TagName% to include tag name into tweet.</td>
			</tr>
		</table>		
		
		<?php submit_button(); ?>
	<?php
}	

?>
