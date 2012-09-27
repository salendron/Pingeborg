<?php
/*
This work is licensed under the Creative Commons Namensnennung-Nicht-kommerziell 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by-nc/3.0/.
*/

// [bartag foo="foo-value"]
function pingeb_statistic_nfc_qr( $atts ) {
	extract( shortcode_atts( array(
		'test' => 'something',
		'test2' => 'something else',
	), $atts ) );

	return "test = {$test}, test2 = {$test2}";
}
add_shortcode( 'pingeb_data_qr_nfc', 'pingeb_statistic_nfc_qr' );

?>
