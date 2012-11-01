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
				MQA.IO.doJSONP('http://pingeb.org/apip/downloads?pageSize={$downloads}&callback=rendersearch');
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

//Shortcode [pingeb_data_qr_nfc]
//Accepts Attr:  w=Width, h=Height, colorNFC=Color of NFC Area as Hex, colorQR=Color of QR Area as Hex
//Author: Bruno Hautzenberger
//Date: 10.2012
function pingeb_statistic_nfc_qr( $atts ) {
	global $wpdb;
	
	extract( shortcode_atts( array(
		'w' => '640',
		'h' => '480',
		'radius' => '180',
		'center' => '200,200',
		'legendpos' => 'east',
		'colors' => "'#feed01','#bdcc00'"
	), $atts ) );

	$sql = "select url_type as type, count(url_type) as count from " . $wpdb->prefix . "pingeb_statistik group by url_type"; 
	
	//select tags
	$arr = array ();
	$i = 0;
	
	$cur_month = -1;
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
	
	$data = "[" . $nfc . "," . $qr . "]"; 
	$labels = "['%%.%% - NFC','%%.%% - QR']"; 
	
	//show chart
	$chart = "<script>
			
			addLoadEvent( function(){
				loadNfcQr();
			} );
			
			function loadNfcQr(){
				var r = Raphael('chartNfcQr'),
                    pie = r.piechart({$center}, {$radius}, " . $data . ", { legend: " . $labels . ", legendpos: '{$legendpos}','colors':[{$colors}]});
				
                pie.hover(function () {
                    this.sector.stop();
                    this.sector.scale(1.1, 1.1, this.cx, this.cy);

                    if (this.label) {
                        this.label[0].stop();
                        this.label[0].attr({ r: 7.5 });
                        this.label[1].attr({ 'font-weight': 800 });
                    }
                }, function () {
                    this.sector.animate({ transform: 's1 1 ' + this.cx + ' ' + this.cy }, 500, 'bounce');

                    if (this.label) {
                        this.label[0].animate({ r: 5 }, 500, 'bounce');
                        this.label[1].attr({ 'font-weight': 400 });
                    }
                });
			}
		</script>

		<div id='chartNfcQr' style='width:{$w}px;height:{$h}px;'></div>";

	return $chart;
}
add_shortcode( 'pingeb_data_qr_nfc', 'pingeb_statistic_nfc_qr' );

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

	$sql = "select month(visit_time) as month, url_type as type, count(url_type) as count from " . $wpdb->prefix . "pingeb_statistik where month(visit_time) != month(now()) group by month(visit_time), url_type order by month(visit_time)"; 
	
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
	
	$ch = $h - 10;
	$cw = $w - 10;
	
	//show chart
	$chart = "<script>
			addLoadEvent( function(){
				loadNfcVsQrByDate();
				drawAxis();
			} );
			
			function loadNfcVsQrByDate(){
				//nfc
				var r = Raphael('chartNfcQrByDateNfc'),
                    txtattr = { font: '25px sans-serif' };
                
                var x = [];

                for (var i = 0; i < $j; i++) {
                    x[i] = i;
                }

				r.linechart(0, 0, {$w}, {$h}-10, x, [$nfcData,$qrData],{ axis: '0 0 0 0',nostroke: false, shade: true,smooth: true,'colors':['{$colorNFC}', 'transparent'] });

				//qr
				var r2 = Raphael('chartNfcQrByDateQr'),
                    txtattr = { font: '25px sans-serif' };
                
                var x = [];

                for (var i = 0; i < $j; i++) {
                    x[i] = i;
                }

				r2.linechart(0, 0, {$w}, {$h}-10, x, [$qrData,$nfcData],{ axis: '0 0 0 0',nostroke: false, shade: true,smooth: true,'colors':['{$colorQR}', 'transparent'] });
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
			<canvas id='chartNfcQrByDateCanvas' width='{$w}' height='{$h}' style='position:absolute;top:0px;left:0px;z-index:2;'></canvas>
			<div id='chartNfcQrByDateNfc' style='overflow:hidden;position:absolute;top:0px;left:10px;width:" . $cw . "px;height:" . $ch . "px;z-index:1;'></div>
			<div id='chartNfcQrByDateQr' style='-moz-transform: scaleY(-1);-webkit-transform: scaleY(-1);-ms-transform: scaleY(-1);overflow:hidden;position:absolute;top:0px;left:10px;width:" . $cw . "px;height:" . $ch . "px;z-index:0;'></div>
		</div>";

	return $chart;
}
add_shortcode( 'pingeb_data_qr_nfc_by_month', 'pingeb_statistic_nfc_qr_by_month' );

//Shortcode [pingeb_data_os]
//Accepts Attr:  w=Width, h=Height, colorNFC=Color of NFC Area as Hex, colorQR=Color of QR Area as Hex
//Author: Bruno Hautzenberger
//Date: 09.2012
function pingeb_statistic_os( $atts ) {
	global $wpdb;
	
	extract( shortcode_atts( array(
		'w' => '640',
		'h' => '480',
		'radius' => '180',
		'center' => '200,200',
		'legendpos' => 'east',
		'colors' => "'#feed01','#bdcc00','#a4a4a4','#e9e9e9', '#919191'"
	), $atts ) );

	$sql = "select os.name os, count(*) count from
			(
			select 
			if(upper(visitor_os) like '%WINDOWS PHONE%', 'Windows Phone', 
			if(upper(visitor_os) like '%IPHONE%', 'iOS',
			if(upper(visitor_os) like '%IPAD%', 'iOS',
			if(upper(visitor_os) like '%ANDROID%', 'Android',
			if(upper(visitor_os) like '%SYMBIAN%', 'Symbian',
			if(upper(visitor_os) like '%BADA%', 'Bada',
			if(upper(visitor_os) like '%BLACKBERRY%', 'BlackBerry',
			'other'))))))) as name
			from " . $wpdb->prefix . "pingeb_statistik stat, " . $wpdb->prefix . "pingeb_url_type ut, " . $wpdb->prefix . "leafletmapsmarker_markers mm
			where ut.id = stat.url_type and mm.id = stat.tag_id 
			) os where os.name != 'other' group by os.name"; 
	
	//select tags
	$arr = array ();
	$i = 0;
	$count = 0;
	
	$results = $wpdb->get_results($sql);
	foreach ( $results as $result ) {
		$arr[$i] = array(
			'os' => $result->os,
			'count' => $result->count
		);
		$i++;
		
		$count += $result->count;
	}
	
	$onePc = $count / 100;
	
	$i = 0;
	$values = '[';
	$labels = '[';
	foreach ( $arr as $os ) {
		if($i < count($arr) -1){
			$values .= $os['count'] / $onePc . ",";
			$labels .= "'%%.%% - " . $os['os'] . "',";
		} else {
			$values .= $os['count'] / $onePc . "]";
			$labels .= "'%%.%% - " . $os['os'] . "']";
		}
		$i++;
	}
	
	//show chart
	$chart = "<script>
			
			addLoadEvent( function(){
				loadiOsVsAndroid();
			} );
			
			function loadiOsVsAndroid(){
				var r = Raphael('chartMobileOs'),
                    pie = r.piechart({$center}, {$radius}, " . $values . ", { legend: " . $labels . ", legendpos: '{$legendpos}','colors':[{$colors}]});
				
                pie.hover(function () {
                    this.sector.stop();
                    this.sector.scale(1.1, 1.1, this.cx, this.cy);

                    if (this.label) {
                        this.label[0].stop();
                        this.label[0].attr({ r: 7.5 });
                        this.label[1].attr({ 'font-weight': 800 });
                    }
                }, function () {
                    this.sector.animate({ transform: 's1 1 ' + this.cx + ' ' + this.cy }, 500, 'bounce');

                    if (this.label) {
                        this.label[0].animate({ r: 5 }, 500, 'bounce');
                        this.label[1].attr({ 'font-weight': 400 });
                    }
                });
			}
		</script>

		<div id='chartMobileOs' style='width:{$w}px;height:{$h}px;'></div>";
	
	return $chart;
}
add_shortcode( 'pingeb_data_os', 'pingeb_statistic_os' );


add_filter('widget_text', 'do_shortcode');
?>
