<?php
/*
This work is licensed under the Creative Commons Namensnennung-Nicht-kommerziell 3.0 Unported License. 
To view a copy of this license, visit http://creativecommons.org/licenses/by-nc/3.0/.
*/

//Redirects the user to a content page if a pingeborg url is called and counts it
//Author: Bruno Hautzenberger
//Date: 09.2012
function pingeb_redirect(){
	global $wpdb; 
	
	$req = trim($_SERVER["REQUEST_URI"], '/');

	//check if it is an api call
	if(startsWith($req,'api/')){
		header('content-type: application/json; charset=utf-8');
		echo pingeb_api($req); ;
		exit();
	}

	//check if it is an api jsonp call
	if(startsWith($req,'apip/')){
		header('content-type: application/json; charset=utf-8');
		echo $_GET['callback'] . '('.pingeb_api($req).')'; ;
		exit();
	}
	
	//check if it is a tag url
	$sql = "select pt.page_id as page, pu.url_type_id as urltype, pt.marker_id tag from " . $wpdb->prefix . "pingeb_tag pt, " . $wpdb->prefix . "pingeb_url pu ";
	$sql .=  "where pu.url = '" . $wpdb->escape($req) . "' and pu.tag_id = pt.id";
	
	$urlType = -1;
	$pageId = -1;
	$tagId = -1;
	
	$results = $wpdb->get_results($sql);
	foreach( $results as $result ) {
		$urlType = $result->urltype;
		$pageId = $result->page;
		$tagId = $result->tag;
	}
	
	//redirect if it is a tag url
	if($pageId != -1){
		//insert statistik
		$wpdb->insert( 
			$wpdb->prefix . "pingeb_statistik", 
				array( 
					'tag_id' => $tagId, 
					'url_type' => $urlType,
					'visitor_os' => $_SERVER['HTTP_USER_AGENT']
				), 
				array( 
					'%d', 
					'%d',
					'%s' 
				) 
		);
		
		//send tweet
		sendTweet($tagId);


		echo "<meta http-equiv='refresh' content='0;url=" . get_permalink($pageId) . "' />";
		exit();
	}	
}

add_action('init','pingeb_redirect');

//Sends a Tweet via OAuth API
//Author: Bruno Hautzenberger
//Date: 10.2012
function sendTweet($tagId){
	global $wpdb;
	
	//load config
	$use_twitter = get_option('use_twitter');
	$consumer_key =  get_option('consumer_key');
	$consumer_secret =  get_option('consumer_secret');
	$user_token =  get_option('user_token');
	$user_secret =  get_option('user_secret');
	$tweet_text =  get_option('tweet_text');
	
	if($use_twitter == 1){
		//select tag name
		$sql = "select mm.markername tagname from " . $wpdb->prefix . "leafletmapsmarker_markers mm where mm.id = " . $wpdb->escape($tagId);
		
		$tagName = "-";		
		
		$results = $wpdb->get_results($sql);
		foreach( $results as $result ) {
			$tagName = $result->tagname;
		}
		
		$tweet_text = str_replace("%TagName%", $tagName, $tweet_text);
		$result = post_tweet($tweet_text, $consumer_key, $consumer_secret, $user_token, $user_secret);
		//print "Response code: " . $result . "\n";
	}

}

//Simple PHP String StartsWith Function
//Author: Bruno Hautzenberger
//Date: 10.2012
function startsWith($haystack, $needle)
{
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

/**
* post_tweet
* Posting a tweet with OAuth
* Latest copy of this code: 
* http://140dev.com/twitter-api-programming-tutorials/hello-twitter-oauth-php/
* @author Adam Green <140dev@gmail.com>
* @license GNU Public License
*/
function post_tweet($tweet_text, $consumer_key, $consumer_secret, $user_token, $user_secret) {

  // Use Matt Harris' OAuth library to make the connection
  // This lives at: https://github.com/themattharris/tmhOAuth
  require_once('tmhoauth/tmhOAuth.php');
      
  // Set the authorization values
  // In keeping with the OAuth tradition of maximum confusion, 
  // the names of some of these values are different from the Twitter Dev interface
  // user_token is called Access Token on the Dev site
  // user_secret is called Access Token Secret on the Dev site
  // The values here have asterisks to hide the true contents 
  // You need to use the actual values from Twitter
  $connection = new tmhOAuth(array(
    'consumer_key' => $consumer_key,
    'consumer_secret' => $consumer_secret,
    'user_token' => $user_token,
    'user_secret' => $user_secret
  )); 
  
  // Make the API call
  $connection->request('POST', 
    $connection->url('1/statuses/update'), 
    array('status' => $tweet_text));
  
  return $connection->response['code'];
}

?>
