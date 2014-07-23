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

//Returns all pages as JSON Array
//Author: Bruno Hautzenberger
//Date: 09.2012
add_action('wp_ajax_pingeb_get_custom_html_blocks', 'pingeb_get_custom_html_blocks_callback');
function pingeb_get_custom_html_blocks_callback() {
	global $wpdb; 

	$sql = "select id, name, html from " . $wpdb->prefix . "pingeb_custom_html order by name";
	$arr = array ();
	$i = 0;
	$blocks = $wpdb->get_results($sql);
	foreach ( $blocks as $block ) {
		$arr[$i] = array(
		'id'=>$block->id,
		'name'=>$block->name,
		'html'=>$block->html
		); 
		$i++;
	}

	echo json_encode($arr);
	die();
}

//Inserts a new custom html block
//Author: Bruno Hautzenberger
//Date: 09.2013
add_action('wp_ajax_pingeb_insert_cutom_html_block', 'pingeb_insert_cutom_html_block_callback');
function pingeb_insert_cutom_html_block_callback() {
	global $wpdb;
	
	$name = $_POST['name'];
	$html = $_POST['html'];
	
	$wpdb->insert( 
	$wpdb->prefix . "pingeb_custom_html", 
		array( 
			'name' => $name,
			'html' => $html
		), 
		array( 
			'%s', 
			'%s'
		) 
	);
	
	echo "SUCCESS!";
	die();
}

//Saves a Block
//Author: Bruno Hautzenberger
//Date: 11.2013
add_action('wp_ajax_pingeb_save_block', 'pingeb_save_block_callback');
function pingeb_save_block_callback() {
	global $wpdb; 

	$id = $_POST['id'];
	$html = $_POST['html'];

	$wpdb->update( 
			$wpdb->prefix . "pingeb_custom_html", 
			array( 
				'html' => $html
			), 
			array( 'id' => $id ),
			array( 
				'%s'
			),
			array( '%d' )
		);

	echo "Saved block!";
	die();
}

//deletes a block
//Author: Bruno Hautzenberger
//Date: 11.2013
add_action('wp_ajax_pingeb_delete_block', 'pingeb_delete_block_callback');
function pingeb_delete_block_callback() {
	global $wpdb; 

	$id = $_POST['id'];

	$table = $wpdb->prefix . "pingeb_custom_html";
	$wpdb->query( 
	$wpdb->prepare( 
			"
		     DELETE FROM $table
			 WHERE id = %d
			",
			$id 
		)
	);

	echo "Url deleted!";
	die();
}

?>
