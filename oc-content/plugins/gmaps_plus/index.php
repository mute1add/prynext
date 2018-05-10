<?php
/*
Plugin Name: Google Maps Plus
Plugin URI: https://market.osclass.org/plugins/maps/google-maps-plus_858
Description: This plugin shows a Google Map on the location space of every item.
Version: 1.2.1
Author: jmsdan
Author URI: google-maps-plus
Plugin update URI: google-maps-plus_2
*/

    function gmaps_plus_install() {
            // Default values
        $s_width = '100';
        $s_width_by = '1'; 
        $s_height = '240';
        $s_height_by = '2'; 
        $s_zoom = '13';
        $s_map_type = 'ROADMAP';
        
        $b_zoom_control = '1';
        $b_map_type_control = '0';
        $b_scale_control = '1';
        $b_street_view_control = '0';
        $b_full_screen_control = '0';
        
        osc_set_preference('gmaps_plus_width', $s_width, 'gmaps_plus', 'STRING');
        osc_set_preference('gmaps_plus_width_in', $s_width_by, 'gmaps_plus', 'STRING');
        osc_set_preference('gmaps_plus_height', $s_height, 'gmaps_plus', 'STRING');
        osc_set_preference('gmaps_plus_height_in', $s_height_by, 'gmaps_plus', 'STRING');
        osc_set_preference('gmaps_plus_zoom', $s_zoom, 'gmaps_plus', 'STRING');
        osc_set_preference('gmaps_plus_map_type', $s_map_type, 'gmaps_plus','STRING');
        
        osc_set_preference('gmaps_plus_zoom_control', $b_zoom_control, 'gmaps_plus','BOOLEAN');
        osc_set_preference('gmaps_plus_map_type_control',$b_map_type_control,'gmaps_plus','BOOLEAN');
        osc_set_preference('gmaps_plus_scale_control',$b_scale_control,'gmaps_plus','BOOLEAN');
        osc_set_preference('gmaps_plus_street_view_control',$b_street_view_control,'gmaps_plus','BOOLEAN');
        osc_set_preference('gmaps_plus_full_screen_control',$b_full_screen_control,'gmaps_plus','BOOLEAN');             
    }    
    osc_register_plugin(osc_plugin_path(__FILE__), 'gmaps_plus_install');
    
    function gmaps_plus_uninstall(){
        osc_delete_preference('gmaps_plus_width', 'gmaps_plus');
        osc_delete_preference('gmaps_plus_width_in', 'gmaps_plus');
        osc_delete_preference('gmaps_plus_height', 'gmaps_plus');
        osc_delete_preference('gmaps_plus_height_in', 'gmaps_plus');
        osc_delete_preference('gmaps_plus_zoom', 'gmaps_plus');
        osc_delete_preference('gmaps_plus_map_type', 'gmaps_plus');
    }
    osc_add_hook(osc_plugin_path(__FILE__)."_uninstall", 'gmaps_plus_uninstall');            
       
    osc_add_hook( osc_plugin_path( __FILE__ ) . '_configure', function() {
         osc_admin_render_plugin(osc_plugin_path(dirname(__FILE__)) . '/admin/settings.php') ;
    });



    function gmaps_plus_location() {        
        $item = osc_item();        
        require 'map.php';
        gmaps_plus_header();
    }

    osc_add_hook('init',function() {
        if (osc_get_preference('include_maps_js', 'gmaps_plus') != '0') {
            osc_add_hook('location', 'gmaps_plus_location');
        }
    } );

    
    function gmaps_plus_header() {
        echo '<script src="https://maps.google.com/maps/api/js?key='.osc_get_preference('maps_key', 'gmaps_plus').'&callback=initMap" type="text/javascript" async defer></script>';
   }

    function gmaps_plus_insert_geo_location($item) {
        $itemId = $item['pk_i_id'];
        $aItem = Item::newInstance()->findByPrimaryKey($itemId);
        $sAddress = (isset($aItem['s_address']) ? $aItem['s_address'] : '');
        $sCity = (isset($aItem['s_city']) ? $aItem['s_city'] : '');
        $sRegion = (isset($aItem['s_region']) ? $aItem['s_region'] : '');
        $sCountry = (isset($aItem['s_country']) ? $aItem['s_country'] : '');
        $address = sprintf('%s, %s, %s, %s', $sAddress, $sCity, $sRegion, $sCountry);
        $response = osc_file_get_contents(sprintf('http://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false', urlencode($address)));
        $jsonResponse = json_decode($response);
        
        if (isset($jsonResponse->results[0]->geometry->location) && count($jsonResponse->results[0]->geometry->location) > 0) 		{
        	$location = $jsonResponse->results[0]->geometry->location;
        	$lat = $location->lat;
        	$lng = $location->lng;
        
            ItemLocation::newInstance()->update (array('d_coord_lat' => $lat
                                                      ,'d_coord_long' => $lng)
                                                ,array('fk_i_item_id' => $itemId));
        }
    }    

    osc_add_hook('posted_item', 'gmaps_plus_insert_geo_location');
    osc_add_hook('edited_item', 'gmaps_plus_insert_geo_location');    
       
    
    osc_add_route('gmaps_plus_settings', 'gmaps_plus_settings', 'gmaps_plus_settings', 'gmaps_plus/admin/settings.php');
    osc_add_hook('admin_menu_init', function() {
        osc_add_admin_submenu_divider('plugins', 'Google Maps Plus Plugin', 'google_maps_divider', 'administrator');
        osc_add_admin_submenu_page('plugins', __('Settings', 'gmaps_plus'), osc_route_admin_url('gmaps_plus_settings'), 'gmaps_plus_settings', 'administrator');
    });


    osc_add_hook('admin_header',  function() {
        if(Params::getParam('route')=='gmaps_plus_settings') osc_remove_hook('admin_page_header', 'customPageHeader');
    });
    osc_add_hook('admin_page_header',  function() {
        if (Params::getParam('route') == 'gmaps_plus_settings') {
            ?>
            <h1><?php _e('Google Maps Plus Plugin', 'gmaps_plus'); ?></h1>
            <?php
        }
    });
    
    

    
    
