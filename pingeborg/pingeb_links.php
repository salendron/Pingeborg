<?php
/*
This work is licensed under the Creative Commons Namensnennung-Nicht-kommerziell 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by-nc/3.0/.
*/

//Redirects the user to a content page if a pingeborg url is called and counts it
//Author: Bruno Hautzenberger
//Date: 09.2012
function pingeb_redirect(){
	global $wpdb; 
	
	$req = trim($_SERVER["REQUEST_URI"], '/');

	//check if it is an api call
	if(startsWith($req,'api')){
		echo pingeb_api($req);
		exit();
	}
	
	//check if it is a tag url
	$sql = "select pt.page_id as page, pu.url_type_id as urltype, pt.marker_id tag from " . $wpdb->prefix . "pingeb_tag pt, " . $wpdb->prefix . "pingeb_url pu ";
	$sql .=  "where pu.url = '" . $req . "' and pu.tag_id = pt.id";
	
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


		echo "<meta http-equiv='refresh' content='0;url=" . get_permalink($pageId) . "' />";
		exit();
	}	
}

function startsWith($haystack, $needle)
{
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

add_action('init','pingeb_redirect');
?>
