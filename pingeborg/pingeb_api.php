<?php
/*
Copyright 2012 Bruno Hautzenberger

This file is part of Pingeborg.

Pingeborg is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published 
by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Pingeborg is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of 
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Pingeborg. If not, see http://www.gnu.org/licenses/.
*/ 

//init function to call any api function
//Author: Bruno Hautzenberger
//Date: 09.2012
function pingeb_api($url){
	$urlParts = pingeb_getUrlParts($url);
	$params = pingeb_getParams($url);
	
	//set return value
	$data = array();
	
	if(count($urlParts) == 2){
		if($urlParts[1] === "tags"){
			$data = pingeb_api_get_tags($params);
		} elseif($urlParts[1] === "downloads") {
			$data = pingeb_api_get_downloads($params);
		}  elseif($urlParts[1] === "tagsGeoJSON") {
			$data = pingeb_geojson_api_get_tags($params);
		}  elseif($urlParts[1] === "downloadsGeoJSON") {
			$data = pingeb_geojson_api_get_downloads($params);
		}  elseif($urlParts[1] === "systemStatistics") {
			$data = pingeb_api_get_stats();
		} else {
			pingeb_send_404();
		}
	} else { 
		pingeb_send_404();
	}
	
	return json_encode($data);
}

function pingeb_api_get_stats() {
	global $wpdb; 
	
	$downloads = $wpdb->get_var( $wpdb->prepare( "select count(*) from " . $wpdb->prefix . "pingeb_statistik" ) );
	$downloadsToday = $wpdb->get_var( $wpdb->prepare( "select count(*) from " . $wpdb->prefix . "pingeb_statistik where curdate() = substr(visit_time,1,10)" ) );

	//geet nfc qr relation
	$sql = "select url_type as type, (count(url_type) / ((select count(*) from " . $wpdb->prefix . "pingeb_statistik where url_type in(1,2)) / 100)) as count
		from " . $wpdb->prefix . "pingeb_statistik 
		where url_type in(1,2) group by url_type"; 
	$nfc = 0;
	$qr = 0;
	
	$results = $wpdb->get_results($sql);
	foreach ( $results as $result ) {
		if($result->type == 1){
			$nfc = $result->count;
		}
		
		if($result->type == 2){
			$qr = $result->count;
		}
	}
	
	return array("downloads" => $downloads, "downloadsToday" => $downloadsToday, "percentageQr" => $qr, "percentageNfc" => $nfc);
}

//gets all downloads
//accetps params layer, from (yyyy-mm-dd hh24:mi:ss), to (yyyy-mm-dd hh24:mi:ss), page (default = 1), pageSize (default = 100, max = 1000) 
//Author: Bruno Hautzenberger
//Date: 09.2012
function pingeb_api_get_downloads($params){
	global $wpdb; 
	
	//load params
	$from = "-1";
	$to = "-1";
	$page = 1;
	$pageSize = 100;
	
	foreach ( $params as $param ) {
			if($param[0] === "from"){
				$from = str_replace("%20", " ",$param[1]);
			} elseif($param[0] === "to") {
				$to = str_replace("%20", " ",$param[1]);
			} elseif($param[0] === "page") {
				$page = $param[1];
			} elseif($param[0] === "pageSize") {
				$pageSize = $param[1];
			}
	}

	//build statement
	$sql = "select DATE_FORMAT(stat.visit_time,'%Y-%m-%d %k:%i:%S') as time,
		ut.name as type, mm.markername tagname, mm.lat as lat, mm.lon as lon, 
		if(upper(visitor_os) like '%WINDOWS PHONE%', 'Windows Phone', 
		if(upper(visitor_os) like '%IPHONE%', 'iOS',
		if(upper(visitor_os) like '%IPAD%', 'iOS',
		if(upper(visitor_os) like '%ANDROID%', 'Android',
		if(upper(visitor_os) like '%SYMBIAN%', 'Symbian',
		if(upper(visitor_os) like '%BADA%', 'Bada',
		if(upper(visitor_os) like '%BLACKBERRY%', 'BlackBerry',
		'other'))))))) as os
		from " . $wpdb->prefix . "pingeb_statistik stat, " . $wpdb->prefix . "pingeb_url_type ut, " . $wpdb->prefix . "leafletmapsmarker_markers mm
		where ut.id = stat.url_type and mm.id = stat.tag_id ";
	
	if($from != '-1'){	
		$sql .= "and stat.visit_time >= '" . $wpdb->escape($from) . "' ";
	}
	
	if($to != '-1'){	
		$sql .= "and stat.visit_time <= '" . $wpdb->escape($to) . "' ";
	}
		
	$sql .= "order by stat.visit_time desc 
		LIMIT " . ((($wpdb->escape($page) - 1) * $wpdb->escape($pageSize)) + 1) . "," . $wpdb->escape($pageSize);
	
	//select tags
	$arr = array ();
	$i = 0;
	$downloads = $wpdb->get_results($sql);
	foreach ( $downloads as $download ) {
		$arr[$i] = array(
		'time'=>$download->time,
		'type'=>$download->type,
		'tagname'=>$download->tagname,
		'lat'=>$download->lat,
		'lon'=>$download->lon,
		'os'=>$download->os
		); 
		$i++;
	}
	
	return $arr;
}

//gets all tags
//accetps params layer, box (lat1,lon1,lat2,lon2), order (name,clicks,layer), orderAsc (true, false)
//Author: Bruno Hautzenberger
//Date: 09.2012
function pingeb_api_get_tags($params){
	global $wpdb; 
	
	//load params
	$layer = "-1";
	$box = "-1";
	$order = "name";
	$orderDirection = "ASC";
	
	foreach ( $params as $param ) {
			if($param[0] === "layer"){
				$layer = $param[1];
			} elseif($param[0] === "box") {
				$box = explode(",",$param[1]);
				if(count($box) != 4){
					$box = "-1";
				}
			} elseif($param[0] === "order") {
				if($param[1] === 'name' || $param[1] === 'clicks' || $param[1] === 'layer'){
					$order = $param[1];
				}
			} elseif($param[0] === "orderAsc") {
				if($param[1] === 'false'){
					$orderDirection = "DESC";
				}
			}
	}

	//build statement
	$sql = "select stat.tag_id as id, mm.markername as name, mm.lat as lat, mm.lon as lon, ml.name layer, IFNULL(stat.count,0) as clicks from " . $wpdb->prefix . "leafletmapsmarker_markers mm
		left outer join (select tag_id, count(*) as count from " . $wpdb->prefix . "pingeb_statistik group by tag_id) stat on mm.id = stat.tag_id
		join  " . $wpdb->prefix . "leafletmapsmarker_layers ml on ml.id = mm.layer where stat.tag_id is not null ";
		
	if($box != "-1"){
		$sql .= "and mm.lat <= " . $wpdb->escape($box[0]) . " and mm.lon >= " . $wpdb->escape($box[1]) . " and mm.lat >= " . $wpdb->escape($box[2]) . " and mm.lon <= " . $wpdb->escape($box[3]) . " ";
	}
	
	if($layer != "-1"){
		$sql .= "and ml.name = '" . $wpdb->escape($layer) . "' ";
	}
	
	$sql .= "order by " . $wpdb->escape($order) . " " . $orderDirection; 
	
	//select tags
	$arr = array ();
	$i = 0;
	$tags = $wpdb->get_results($sql);
	foreach ( $tags as $tag ) {
		$arr[$i] = array(
		'id'=>$tag->id,
		'name'=>$tag->name,
		'layer'=>$tag->layer,
		'clicks'=>$tag->clicks,
		'lat'=>$tag->lat,
		'lon'=>$tag->lon
		); 
		$i++;
	}

	return $arr;
}

//gets all tags as GeoJSON
//accetps params layer, box (lat1,lon1,lat2,lon2), order (name,clicks,layer), orderAsc (true, false)
//Author: Bruno Hautzenberger
//Date: 11.2012
function pingeb_geojson_api_get_tags($params){
	$tags = pingeb_api_get_tags($params);
	
	$features = array ();
	
	$i = 0;
	foreach ( $tags as $tag ) {
		$features[$i] = array(
					"type" => "Feature",
					"geometry" => array(
							"type" => "Point",
							"coordinates" => array($tag['lon'],$tag['lat'])
							),
					"properties" => array(
								"id" => $tag['id'],
								"name" => $tag['name'],
								"layer" => $tag['layer'],
								"clicks" => $tag['clicks'],
								"lat" => $tag['lat'],
								"lon" => $tag['lon']
							)
				);
		$i++;
	}
	
	return array("type" => "FeatureCollection", "features" => $features);
}

//gets all downloads as GeoJSON
//accetps params layer, from (yyyy-mm-dd hh24:mi:ss), to (yyyy-mm-dd hh24:mi:ss), page (default = 1), pageSize (default = 100, max = 1000) 
//Author: Bruno Hautzenberger
//Date: 11.2012
function pingeb_geojson_api_get_downloads($params){
	$downloads = pingeb_api_get_downloads($params);
	
	$features = array ();
	
	$i = 0;
	foreach ( $downloads as $download ) {
		$features[$i] = array(
					"type" => "Feature",
					"geometry" => array(
							"type" => "Point",
							"coordinates" => array($download['lon'],$download['lat'])
							),
					"properties" => array(
								"time" => $download['time'],
								"type" => $download['type'],
								"tagname" => $download['tagname'],
								"os" => $download['os'],
								"lat" => $download['lat'],
								"lon" => $download['lon']
							)
				);
		$i++;
	}
	
	return array("type" => "FeatureCollection", "features" => $features);
}

//gets the query string url parts
//Author: Bruno Hautzenberger
//Date: 09.2012
function pingeb_getUrlParts($url){
	$urlparts = explode("?",$url);
	return explode("/",$urlparts[0]);
}

//gets the query string paramters
//Author: Bruno Hautzenberger
//Date: 09.2012
function pingeb_getParams($url){
	$rawparams = explode("?",$url);
	$rawparams = explode("&",$rawparams[1]);
	
	$params = array ();
	$i = 0;
	foreach ( $rawparams as $param ) {
		$params[$i] = explode("=",$param);
		$i++;
	}
	
	return $params;
}

//404!!!!
//Author: Bruno Hautzenberger
//Date: 09.2012
function pingeb_send_404(){
	//api call not found
	status_header(404);
	nocache_headers();
	include( get_404_template() );
	exit;
}

?>
