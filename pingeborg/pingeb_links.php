<?php

//Redirects the user to a content page if a pingeborg url is called and counts it
//Author: Bruno Hautzenberger
//Date: 09.2012
function pingeb_redirect(){
	global $wpdb; 
	
	$req = trim($_SERVER["REQUEST_URI"], '/');
	
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

add_action('init','pingeb_redirect');
?>
