<?php
/*
Plugin Name: Russian Interactive Map
Plugin URI: https://osclass-pro.com
Description: Russian Interactive Map
Version: 1.0
Author: DIS
Author URI: https://osclass-pro.com
Short Name: russian_i_map
*/
?>
  <link href="<?php echo osc_base_url()?>oc-content/plugins/russian_i_map/jqvmap/css/jqvmap.css" media="screen" rel="stylesheet" type="text/css" />   
  <script src="<?php echo osc_base_url()?>oc-content/plugins/russian_i_map/jqvmap/js/jquery.vmap.js" charset="utf-8" ></script>
  <script src="<?php echo osc_base_url()?>oc-content/plugins/russian_i_map/jqvmap/js/maps/jquery.vmap.russia.js" charset="utf-8" ></script>
  <script type="text/javascript">
	var data_obj = {
	};
	
	colorRegion = '#14A7D1'; // Цвет всех регионов
	focusRegion = '#FF9900'; // Цвет подсветки регионов при наведении на объекты из списка
	selectRegion = '#0A4C82'; // Цвет изначально подсвеченных регионов
	
	highlighted_states = {};
	
	for(iso in data_obj){
		highlighted_states[iso] = selectRegion;
	}
	
	$(document).ready(function() {
		$('#vmap').vectorMap({
		    map: 'russia',
		    backgroundColor: '#ffffff',
			borderColor: '#ffffff',
			borderWidth: 2,
		    color: colorRegion,
			colors: highlighted_states,			
		    hoverOpacity: 0.7,		    
		    enableZoom: true,
		    showTooltip: true,			
			
			onLabelShow: function(event, label, code){
				name = '<strong>'+label.text()+'</strong><br>';				
				if(data_obj[code]){
					list_obj = '<ul>';
					for(ob in data_obj[code]){					
						list_obj += '<li>'+data_obj[code][ob]+'</li>';
					}
					list_obj += '</ul>';
				}else{
					list_obj = '';
				}				
				label.html(name + list_obj);				
				list_obj = '';				
			},			
			onRegionClick: function(element, region, name){
				window.location.replace('index.php?page=search&sRegion=' + name);
			}			
		});		
		
	});
	$(document).ready(function() {
		for(region in data_obj){
			for(obj in data_obj[region]){
				$('.list-object').append('<li><a href="'+selectRegion+'" id="'+region+'" class="focus-region">'+data_obj[region][obj]+' ('+region+')</a></li>');
			}
		}
	});
	
	$(function(){
		$('.focus-region').mouseover(function(){			
			iso = $(this).prop('id');
			fregion = {};
			fregion[iso] = focusRegion;
			$('#vmap').vectorMap('set', 'colors', fregion);			
		});
		$('.focus-region').mouseout(function(){
			c = $(this).attr('href');			
			cl = (c === '#')?colorRegion:c;
			iso = $(this).prop('id');
			fregion = {};
			fregion[iso] = cl;
			$('#vmap').vectorMap('set', 'colors', fregion);
		});
	});	
	</script>
	<div id="vmap" style="width: 960px; height: 500px;"></div>


