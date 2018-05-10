<?php
        $s_width = osc_get_preference('gmaps_plus_width',  'gmaps_plus');
        $s_width_by = osc_get_preference('gmaps_plus_width_in',  'gmaps_plus');
        $s_height = osc_get_preference('gmaps_plus_height',  'gmaps_plus');     
        $s_height_by = osc_get_preference('gmaps_plus_height_in',  'gmaps_plus');        
        $s_zoom = osc_get_preference('gmaps_plus_zoom',  'gmaps_plus');
        $s_map_type = osc_get_preference('gmaps_plus_map_type','gmaps_plus');     
        $b_zoom_control = ( osc_get_preference('gmaps_plus_zoom_control', 'gmaps_plus')  == "1")? "true":"false";        
        $b_map_type_control = ( osc_get_preference('gmaps_plus_map_type_control','gmaps_plus') == "1")? "true":"false";
        $b_scale_control = ( osc_get_preference('gmaps_plus_scale_control','gmaps_plus') == "1")? "true":"false";
        $b_street_view_control = ( osc_get_preference('gmaps_plus_street_view_control','gmaps_plus') == "1")? "true":"false";
        $b_full_screen_control = ( osc_get_preference('gmaps_plus_full_screen_control','gmaps_plus') == "1")? "true":"false";
        
        $s_width_style = '';
        $s_height_style = '';
        $s_item_map_style = '';
       if($s_width_by=='1') {$s_width_style = '%';} else {$s_width_style='px';}
       if($s_height_by=='1') {$s_height_style = '%';} else {$s_height_style='px';}
       
       $s_item_map_style = 'width:' . $s_width . $s_width_style;
       $s_item_map_style .= ';height:' . $s_height . $s_height_style;  
    
?>


<style type="text/css">
        #itemMap {<?php echo $s_item_map_style; ?>}    
</style>

<div id="itemMap"></div>
<?php if($item['d_coord_lat'] != '' && $item['d_coord_long'] != '') {?>
    <script type="text/javascript">
        var map;
        
        function initMap(){
            var latlng = new google.maps.LatLng(<?php echo $item['d_coord_lat']; ?>, <?php echo $item['d_coord_long']; ?>);                        
            
            var gMapsOptions = {
                zoom: <?php echo $s_zoom; ?>,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.<?php echo $s_map_type; ?>,
                zoomControl: <?php echo $b_zoom_control;?>,
                mapTypeControl: <?php echo $b_map_type_control;?>,
                scaleControl: <?php echo $b_scale_control;?>,
                streetViewControl: <?php echo $b_street_view_control;?>,
                fullscreenControl: <?php echo $b_full_screen_control;?>
            }

            map = new google.maps.Map(document.getElementById("itemMap"), gMapsOptions);
            var marker = new google.maps.Marker({
                map: map,
                position: latlng
            });        
        }
   </script>
    
<?php } else { ?>
    <script type="text/javascript"> 
        var map = null;
        var geocoder = null;
        
        function initMap() {
            var gMapsOptions = {
                zoom: <?php echo $s_zoom; ?>,
                center: new google.maps.LatLng(37.4419, -122.1419),
                mapTypeId: google.maps.MapTypeId.<?php echo $s_map_type; ?>,
                zoomControl: <?php echo $b_zoom_control;?>,
                mapTypeControl: <?php echo $b_map_type_control;?>,
                scaleControl: <?php echo $b_scale_control;?>,
                streetViewControl: <?php echo $b_street_view_control;?>,
                fullscreenControl: <?php echo $b_full_screen_control;?>
            }

            map = new google.maps.Map(document.getElementById("itemMap"), gMapsOptions);
            geocoder = new google.maps.Geocoder();
        }
        
        function showAddress(address) {
            if (geocoder) {
                geocoder.geocode( { 'address': address}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        map.setCenter(results[0].geometry.location);
                        var marker = new google.maps.Marker({
                            map: map,
                            position: results[0].geometry.location
                        });
                        marker.setMap(map);  
                    } else {
                        $("#itemMap").remove();
                    }
                });
            }
        }

        <?php
            $addr = array();
            if( ( $item['s_address'] != '' ) && ( $item['s_address'] != null ) ) { $addr[] = $item['s_address']; }
            if( ( $item['s_city'] != '' ) && ( $item['s_city'] != null ) ) { $addr[] = $item['s_city']; }
            if( ( $item['s_zip'] != '' ) && ( $item['s_zip'] != null ) ) { $addr[] = $item['s_zip']; }
            if( ( $item['s_region'] != '' ) && ( $item['s_region'] != null ) ) { $addr[] = $item['s_region']; }
            if( ( $item['s_country'] != '' ) && ( $item['s_country'] != null ) ) { $addr[] = $item['s_country']; }
            $address = implode(", ", $addr);
        ?>

        $(document).ready(function(){
            showAddress('<?php echo osc_esc_js($address); ?>');
        });

    </script>
<?php } ?>