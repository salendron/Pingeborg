<?php
/*
* This work is licensed under the Creative Commons Attribution 3.0 Unported License. 
* To view a copy of this license, visit http://creativecommons.org/licenses/by/3.0/.
*/

//Add Twitter Admin Page
add_action("admin_menu", "register_pingeb_twitter_admin");

//Registers the Twitter Admin Page
//Author: Bruno Hautzenberger
//Date: 10.2012
function register_pingeb_twitter_admin() {
   add_submenu_page("pingeb_tag_admin", "Twitter Settings", "Twitter Settings", "add_users", "pingeb_twitter_admin", "pingeb_twitter_admin"); 

   add_action( 'admin_init', 'pingeb_register_twitter_settings' );
}

//Registers the Twitter Settings
//Author: Bruno Hautzenberger
//Date: 10.2012
function pingeb_register_twitter_settings() {
	//register our settings
	register_setting( 'pingeb_twitter_settings_group', 'use_twitter' );
	register_setting( 'pingeb_twitter_settings_group', 'consumer_key' );
	register_setting( 'pingeb_twitter_settings_group', 'consumer_secret' );
	register_setting( 'pingeb_twitter_settings_group', 'user_token' );
	register_setting( 'pingeb_twitter_settings_group', 'user_secret' );
	register_setting( 'pingeb_twitter_settings_group', 'tweet_text' );
}

//Renders the Twitter Admin Page
//Author: Bruno Hautzenberger
//Date: 10.2012
function pingeb_twitter_admin(){
	//Page Header   
	?> 
	<div id="pingeb-admin-box" style="min-height:50px;">
		<img src="<?php echo plugins_url("pingeborg/img/logo.png"); ?>" style="height:50px;float:left;margin-right:5px;margin-bottom:5px;">
		<h1 class="pingeb_headline">Twitter Setting</h1>
		<p>The system can automaticaly send a tweet everytime a tags is used. If you want to use this feature you have to register an application for your twitter account on <a href="https://dev.twitter.com/">https://dev.twitter.com/</a>.</p>
	</div>
	<?php 
	
	//Setting
	$checked = "";
	if(get_option('use_twitter') == 1) {
		$checked = "checked";
	}
	?> 
	<form method="post" action="options.php">
		<?php settings_fields( 'pingeb_twitter_settings_group' ); ?>
		<table class="form-table">
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
