<?php
/*
This work is licensed under the Creative Commons Namensnennung-Nicht-kommerziell 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by-nc/3.0/.
*/

//Shortcode [pingeb_heatmap]
//Accepts Attr:  w=WIDTH h=HEIGHT zoom=ZOOMLEVEL downloads=DOWNLOADS TO SHOW
//Author: Bruno Hautzenberger
//Date: 09.2012
function pingeb_heatmap( $atts ) {	
	extract( shortcode_atts( array(
		'w' => '640',
		'h' => '480',
		'downloads' => '500',
		'lat' => '46.626425',
		'lon' => '14.304956',
		'zoom' => '12'
	), $atts ) );

	return "
	    <script type='text/javascript' src='http://www.mapquestapi.com/sdk/js/v7.0.s/mqa.toolkit.js?key=Fmjtd%7Clu6y2101nu%2Caw%3Do5-00b5g'></script></script>
		<script type='text/javascript'>
		//Heatmap.js by PATRICK WIED - http://www.patrick-wied.at/static/heatmapjs/
		var m,mdiv,hm,hmdiv,lltp,idx,sr; // map, map div, heatmap, heatmap div, llToPix, idx and data
		addLoadEvent( function(){
			mdiv=document.getElementById('mapdiv'); 
			m=new MQA.TileMap({elt:mdiv,zoom:{$zoom},latLng:{lat:{$lat},lng:{$lon}}}); // make a map
			MQA.withModule('largezoom','viewoptions',function(){ // add controls
				m.addControl(new MQA.LargeZoom(),new MQA.MapCornerPlacement(MQA.MapCorner.TOP_LEFT,new MQA.Size(10,10)));
			});
			getData(); // go get the data
			MQA.EventManager.addListener(m,'moveend',getData); 
			MQA.EventManager.addListener(m,'zoomend',getData); 
		} );
		
		function getData(){ 
			if(m.getZoomLevel()>7){
				var mb=m.getBounds();
				MQA.IO.doJSONP('http://pingelabs.beyond400nm.com/api/downloads?pageSize={$downloads}&callback=rendersearch');
			}else{ // or not
				if(hmdiv)hmdiv.innerHTML='';
			}
		};
		function rendersearch(data){
			sr=data;
			idx=0;
			if(hmdiv)hmdiv.innerHTML=''; 
			else hmdiv=document.createElement('div');
			hmdiv.style.position='absolute';
			hmdiv.style.height=mdiv.style.height;
			hmdiv.style.width=mdiv.style.width;
			hmdiv.style.left=(m.getDragOffset().x)+'px';
			hmdiv.style.top=(m.getDragOffset().y)+'px';
			if(MQA.browser.name=='msie')hmdiv.style.filter ='alpha(opacity=75)';
			else hmdiv.style.opacity='0.75';
			mdiv.childNodes[0].childNodes[0].childNodes[5].appendChild(hmdiv);
			if(data.length>0){
				hm=h337.create({'element':hmdiv,'radius':25-m.getZoomLevel(),'visible':true});
				var hmd={max:5,data:[]}
				doadddatapoint(0,data);
			}
		}
		
		function doadddatapoint(){
			x = 0;
			
			while(x < 10 && (idx + x) < sr.length){
				lltp=m.llToPix(new MQA.LatLng(sr[idx + x].lat,sr[idx + x].lon));
				hm.store.addDataPoint(lltp.x,lltp.y);
				x++;
			}
			
			idx = idx +x;
			
			if(++idx<sr.length)t=setTimeout('doadddatapoint()',100);
		}
	</script>
	<div id='mapdiv' style='width:{$w}px;height:{$h}px;'></div>
	";
}
add_shortcode( 'pingeb_heatmap', 'pingeb_heatmap' );

//Shortcode [pingeb_data_qr_nfc_by_month]
//Accepts Attr:  w=Width, h=Height, colorNFC=Color of NFC Area as Hex, colorQR=Color of QR Area as Hex
//Author: Bruno Hautzenberger
//Date: 09.2012
function pingeb_statistic_nfc_qr_by_month( $atts ) {
	global $wpdb;
	
	extract( shortcode_atts( array(
		'w' => '640',
		'h' => '480',
		'colorQR' => '#feed01',
		'colorNFC' => '#bdcc00'
	), $atts ) );

	$sql = "select month(visit_time) as month, url_type as type, count(url_type) as count from wp_pingeb_statistik group by month(visit_time), url_type order by month(visit_time)"; 
	
	//select tags
	$arr = array ();
	$i = 0;
	
	$cur_month = -1;
	$cur_nfc = 0;
	$cur_qr = 0;
	
	$results = $wpdb->get_results($sql);
	foreach ( $results as $result ) {
		if($cur_month == $result->month || $cur_month == -1){
			if($result->type == '1'){
				$cur_nfc = $result->count;
			}
			if($result->type == '2'){
				$cur_qr = $result->count;
			}
			
			$cur_month = $result->month;
		} else {
			$arr[$i] = array ('NFC' => round($cur_nfc / (($cur_nfc + $cur_qr) / 100),0),'QR' => round($cur_qr / (($cur_nfc + $cur_qr) / 100),0));
			$i++;
			$cur_nfc = 0;
			$cur_qr = 0;
			
			if($result->type == '1'){
				$cur_nfc = $result->count;
			}
			if($result->type == '2'){
				$cur_qr = $result->count;
			}
			
			$cur_month = $result->month;
		}
	}
	$arr[$i] = array ('NFC' => round($cur_nfc / (($cur_nfc + $cur_qr) / 100),0),'QR' => round($cur_qr / (($cur_nfc + $cur_qr) / 100),0));

	//build chart data
	$qrData = "[";
	$nfcData = "[";
	
	$j = 0;
	while($j < count($arr)){
		if($j < count($arr) - 1){
			$qrData .= $arr[$j]['QR'] . ",";
			$nfcData .= $arr[$j]['NFC'] . ",";
		} else {
			$qrData .= $arr[$j]['QR'] . "]";
			$nfcData .= $arr[$j]['NFC'] . "]";
		}
		
		$j++;
	}
	
	$ch = $h - 20;
	$cw = $w - 10;
	
	//show chart
	$chart = "<script>
			addLoadEvent( function(){
				loadNfcVsQrByDate();
				drawAxis();
			} );
			
			function loadNfcVsQrByDate(){
				var r = Raphael('chartNfcQrByDate'),
                    txtattr = { font: '25px sans-serif' };
                
                var x = [];

                for (var i = 0; i < $j; i++) {
                    x[i] = i;
                }

				r.linechart(0, 0, {$w}, {$h}-10, x, [$qrData, $nfcData],{ axis: '0 0 0 0',nostroke: false, shade: true,smooth: true,'colors':['{$colorQR}','{$colorNFC}'] });

			}
			
			function drawAxis() {
			 //AXIS
			 var canvas = document.getElementById('chartNfcQrByDateCanvas');
			 var ctx = canvas.getContext('2d');
			 
			 ctx.lineWidth = 1;
			 ctx.strokeStyle = '#a4a4a4';
			 
			 ctx.beginPath();
			 ctx.moveTo(20,0);
			 ctx.lineTo(20,{$h});
			 ctx.closePath();
			 ctx.stroke();
			 
			 ctx.beginPath();
			 ctx.moveTo(0,{$h}-20);
			 ctx.lineTo({$w},{$h}-20);
			 ctx.closePath();
			 ctx.stroke();
			 
			 ctx.font='9px Arial';
			 
			 for(var i = 0; i < 10; i++){
				ctx.fillText(((10 - i) * 10) + '%', 0, ({$h} / 10) * i);
			 }
			 
			 //Legend
			 //QR Label
			 ctx.fillStyle = '{$colorQR}';
			 ctx.beginPath();
			 ctx.arc(32, {$h} - 10, 5, Math.PI*2, 0, true);
			 ctx.closePath();
			 ctx.fill();
			 
			 ctx.font='12px Arial bold';
			 ctx.fillStyle = '#000000';
			 ctx.fillText('QR', 40, {$h} - 6);
			 
			 //NFC Label
			 ctx.fillStyle = '{$colorNFC}';
			 ctx.beginPath();
			 ctx.arc(72, {$h} - 10, 5, Math.PI*2, 0, true);
			 ctx.closePath();
			 ctx.fill();
			 
			 ctx.font='12px Arial bold';
			 ctx.fillStyle = '#000000';
			 ctx.fillText('NFC', 80, {$h} - 6);
			}
		</script>

		<div id='chartNfcQrByDateHolder' style='position:relative;width:{$w}px;height:{$h}px;'>
			<canvas id='chartNfcQrByDateCanvas' width='{$w}' height='{$h}' style='position:absolute;top:0px;left:0px;z-index:1;'></canvas>
			<div id='chartNfcQrByDate' style='overflow:hidden;position:absolute;top:0px;left:10px;width:" . $cw . "px;height:" . $ch . "px;z-index:0;'></div>
		</div>";

	return $chart;
}
add_shortcode( 'pingeb_data_qr_nfc_by_month', 'pingeb_statistic_nfc_qr_by_month' );

//Shortcode [pingeb_data_qr_nfc_by_month]
//Accepts Attr:  w=Width, h=Height, colorNFC=Color of NFC Area as Hex, colorQR=Color of QR Area as Hex
//Author: Bruno Hautzenberger
//Date: 09.2012
function pingeb_statistic_os( $atts ) {
	global $wpdb;
	
	extract( shortcode_atts( array(
		'w' => '640',
		'h' => '480',
		'colorQR' => '#feed01',
		'colorNFC' => '#bdcc00'
	), $atts ) );

	$sql = "select month(visit_time) as month, url_type as type, count(url_type) as count from wp_pingeb_statistik group by month(visit_time), url_type order by month(visit_time)"; 
	
	//select tags
	$arr = array ();
	$i = 0;
	
	$cur_month = -1;
	$cur_nfc = 0;
	$cur_qr = 0;
	
	$results = $wpdb->get_results($sql);
	foreach ( $results as $result ) {
		if($cur_month == $result->month || $cur_month == -1){
			if($result->type == '1'){
				$cur_nfc = $result->count;
			}
			if($result->type == '2'){
				$cur_qr = $result->count;
			}
			
			$cur_month = $result->month;
		} else {
			$arr[$i] = array ('NFC' => round($cur_nfc / (($cur_nfc + $cur_qr) / 100),0),'QR' => round($cur_qr / (($cur_nfc + $cur_qr) / 100),0));
			$i++;
			$cur_nfc = 0;
			$cur_qr = 0;
			
			if($result->type == '1'){
				$cur_nfc = $result->count;
			}
			if($result->type == '2'){
				$cur_qr = $result->count;
			}
			
			$cur_month = $result->month;
		}
	}
	$arr[$i] = array ('NFC' => round($cur_nfc / (($cur_nfc + $cur_qr) / 100),0),'QR' => round($cur_qr / (($cur_nfc + $cur_qr) / 100),0));

	//build chart data
	$qrData = "[";
	$nfcData = "[";
	
	$j = 0;
	while($j < count($arr)){
		if($j < count($arr) - 1){
			$qrData .= $arr[$j]['QR'] . ",";
			$nfcData .= $arr[$j]['NFC'] . ",";
		} else {
			$qrData .= $arr[$j]['QR'] . "]";
			$nfcData .= $arr[$j]['NFC'] . "]";
		}
		
		$j++;
	}
	
	$ch = $h - 20;
	$cw = $w - 10;
	
	//show chart
	$chart = "<script>
			addLoadEvent( function(){
				loadNfcVsQrByDate();
				drawAxis();
			} );
			
			function loadNfcVsQrByDate(){
				var r = Raphael('chartNfcQrByDate'),
                    txtattr = { font: '25px sans-serif' };
                
                var x = [];

                for (var i = 0; i < $j; i++) {
                    x[i] = i;
                }

				r.linechart(0, 0, {$w}, {$h}-10, x, [$qrData, $nfcData],{ axis: '0 0 0 0',nostroke: false, shade: true,smooth: true,'colors':['{$colorQR}','{$colorNFC}'] });

			}
			
			function drawAxis() {
			 //AXIS
			 var canvas = document.getElementById('chartNfcQrByDateCanvas');
			 var ctx = canvas.getContext('2d');
			 
			 ctx.lineWidth = 1;
			 ctx.strokeStyle = '#a4a4a4';
			 
			 ctx.beginPath();
			 ctx.moveTo(20,0);
			 ctx.lineTo(20,{$h});
			 ctx.closePath();
			 ctx.stroke();
			 
			 ctx.beginPath();
			 ctx.moveTo(0,{$h}-20);
			 ctx.lineTo({$w},{$h}-20);
			 ctx.closePath();
			 ctx.stroke();
			 
			 ctx.font='9px Arial';
			 
			 for(var i = 0; i < 10; i++){
				ctx.fillText(((10 - i) * 10) + '%', 0, ({$h} / 10) * i);
			 }
			 
			 //Legend
			 //QR Label
			 ctx.fillStyle = '{$colorQR}';
			 ctx.beginPath();
			 ctx.arc(32, {$h} - 10, 5, Math.PI*2, 0, true);
			 ctx.closePath();
			 ctx.fill();
			 
			 ctx.font='12px Arial bold';
			 ctx.fillStyle = '#000000';
			 ctx.fillText('QR', 40, {$h} - 6);
			 
			 //NFC Label
			 ctx.fillStyle = '{$colorNFC}';
			 ctx.beginPath();
			 ctx.arc(72, {$h} - 10, 5, Math.PI*2, 0, true);
			 ctx.closePath();
			 ctx.fill();
			 
			 ctx.font='12px Arial bold';
			 ctx.fillStyle = '#000000';
			 ctx.fillText('NFC', 80, {$h} - 6);
			}
		</script>

		<div id='chartNfcQrByDateHolder' style='position:relative;width:{$w}px;height:{$h}px;'>
			<canvas id='chartNfcQrByDateCanvas' width='{$w}' height='{$h}' style='position:absolute;top:0px;left:0px;z-index:1;'></canvas>
			<div id='chartNfcQrByDate' style='overflow:hidden;position:absolute;top:0px;left:10px;width:" . $cw . "px;height:" . $ch . "px;z-index:0;'></div>
		</div>";

	return $chart;
}
add_shortcode( 'pingeb_data_os', 'pingeb_statistic_os' );

// [bartag foo="foo-value"]
function pingeb_statistic_nfc_qr( $atts ) {
	extract( shortcode_atts( array(
		'test' => 'something',
		'test2' => 'something else',
	), $atts ) );

	return "test = {$test}, test2 = {$test2}";
}
add_shortcode( 'pingeb_data_qr_nfc', 'pingeb_statistic_nfc_qr' );


add_filter('widget_text', 'do_shortcode');
?>
