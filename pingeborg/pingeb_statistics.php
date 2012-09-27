<?php
/*
This work is licensed under the Creative Commons Namensnennung-Nicht-kommerziell 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by-nc/3.0/.
*/

// [bartag foo="foo-value"]
function pingeb_heatmap( $atts ) {	
	extract( shortcode_atts( array(
		'w' => '640',
		'h' => '480',
		'downloads' => '500',
		'lat' => '46.626425',
		'lon' => '14.304956',
		'zoom' => '12'
	), $atts ) );

	return "<script type='text/javascript' src='http://www.mapquestapi.com/sdk/js/v7.0.s/mqa.toolkit.js?key=Fmjtd%7Clu6y2101nu%2Caw%3Do5-00b5g'></script></script>
		<script type='text/javascript'>
		// THANKS PATRICK WIED FOR THE HEATMAP STUFF!!!
		// http://www.patrick-wied.at/static/heatmapjs/
		var m,mdiv,hm,hmdiv,lltp,idx,sr; // map, map div, heatmap, heatmap div, and llToPix
		window.onload = function(){
			mdiv=document.getElementById('mapdiv'); 
			m=new MQA.TileMap({elt:mdiv,zoom:{$zoom},latLng:{lat:{$lat},lng:{$lon}}}); // make a map
			MQA.withModule('largezoom','viewoptions',function(){ // add controls
				m.addControl(new MQA.LargeZoom(),new MQA.MapCornerPlacement(MQA.MapCorner.TOP_LEFT,new MQA.Size(10,10)));
			});
			getData(); // go get the data
			MQA.EventManager.addListener(m,'moveend',getData); 
			MQA.EventManager.addListener(m,'zoomend',getData); 
		}
		
		function getData(){ 
			if(m.getZoomLevel()>7){
				var mb=m.getBounds();
				MQA.IO.doJSONP('http://pingelabs.beyond400nm.com/api/downloads?pageSize={$downloads}&callback=rendersearch');
			}else{ // or not
				if(hmdiv)hmdiv.innerHTML='';
				alert('Zoom in for better details.');
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
			lltp=m.llToPix(new MQA.LatLng(sr[idx].lat,sr[idx].lon));
			hm.store.addDataPoint(lltp.x,lltp.y);
			if(++idx<sr.length)t=setTimeout('doadddatapoint()',10);
		}
	</script>
	<div id='mapdiv' style='width:{$w}px;height:{$h}px;'></div>
	";
}
add_shortcode( 'pingeb_heatmap', 'pingeb_heatmap' );

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
