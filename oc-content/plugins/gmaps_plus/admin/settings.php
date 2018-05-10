
<?php

echo '<link href="' . osc_plugin_url(__FILE__) . 'css/bootstrap-switch.min.css" rel="stylesheet" type="text/css">' . PHP_EOL;  
echo '<link href="' . osc_plugin_url(__FILE__) . 'css/gmaps-plus-admin.css" rel="stylesheet" type="text/css">' . PHP_EOL;  

if (Params::getParam('plugin_action') == 'done') {
        
    osc_set_preference('maps_key', Params::getParam('maps_key'), 'gmaps_plus');
    osc_set_preference('include_maps_js', (Params::getParam('include_maps_js') ? '1' : '0'), 'gmaps_plus');    
    osc_set_preference('gmaps_plus_width', Params::getParam('gmaps_plus_width'), 'gmaps_plus');
    osc_set_preference('gmaps_plus_width_in', Params::getParam('gmaps_plus_width_in'), 'gmaps_plus');
    osc_set_preference('gmaps_plus_height', Params::getParam('gmaps_plus_height'), 'gmaps_plus');
    osc_set_preference('gmaps_plus_height_in', Params::getParam('gmaps_plus_height_in'), 'gmaps_plus');    
    osc_set_preference('gmaps_plus_zoom', Params::getParam('gmaps_plus_zoom'), 'gmaps_plus');
    osc_set_preference('gmaps_plus_map_type', Params::getParam('gmaps_plus_map_type'), 'gmaps_plus');
    osc_set_preference('gmaps_plus_zoom_control', (Params::getParam('gmaps_plus_zoom_control') ? '1' : '0'), 'gmaps_plus');
    osc_set_preference('gmaps_plus_map_type_control', (Params::getParam('gmaps_plus_map_type_control') ? '1' : '0'), 'gmaps_plus');
    osc_set_preference('gmaps_plus_scale_control', (Params::getParam('gmaps_plus_scale_control') ? '1' : '0'), 'gmaps_plus');
    osc_set_preference('gmaps_plus_street_view_control', (Params::getParam('gmaps_plus_street_view_control') ? '1' : '0'), 'gmaps_plus');
    osc_set_preference('gmaps_plus_full_screen_control', (Params::getParam('gmaps_plus_full_screen_control') ? '1' : '0'), 'gmaps_plus');
    
    ob_get_clean();
    osc_add_flash_ok_message(__('Settings updated correctly', 'gmaps_plus'), 'admin');

    osc_redirect_to( osc_route_admin_url('gmaps_plus_settings') );
}

  $s_width_by = osc_get_preference('gmaps_plus_width_in','gmaps_plus');
  $s_height_by = osc_get_preference('gmaps_plus_height_in','gmaps_plus');
  $s_map_type = osc_get_preference('gmaps_plus_map_type','gmaps_plus');

?>

<form action="<?php echo osc_route_admin_url('gmaps_plus_settings') ?>" method="post" class="nocsrf">
    <input type="hidden" name="plugin_action" value="done" />
    <h2 class="render-title"><?php _e('Javascript', 'gmaps_plus'); ?></h2>
    <div class="form-horizontal">
        <div class="form-label"><?php _e('Load Google Maps javascript library.', 'gmaps_plus'); ?></div>
        <div class="form-controls">
            <div class="form-label-checkbox">
                <label><input type="checkbox" <?php echo ( (osc_get_preference('include_maps_js', 'gmaps_plus') != '0') ? 'checked="checked"' : ''); ?> name="include_maps_js" value="1" /> <?php _e('Load', 'gmaps_plus'); ?></label>
                <br>
                <p><span class="help-box"><?php _e('Some plugins already load the Google Maps library and can be loaded twice or more. Disable this option if you don\'t want to load google maps library.', 'gmaps_plus'); ?></span></p>
            </div>
        </div>
 
        <div class="form-label"><?php _e('Google Maps API key.', 'gmaps_plus'); ?></div>
        <div class="form-controls">
            <div class="form-label-checkbox">
                <input type="text" value="<?php echo osc_esc_html(osc_get_preference('maps_key', 'gmaps_plus')); ?>" name="maps_key"/>
                <br>
                <p><span class="help-box"><?php _e('Create an API key as per instructions here: <a href="https://developers.google.com/maps/documentation/javascript/get-api-key">Get api key</a>', 'gmaps_plus'); ?></span></p>
            </div>
        </div>
    
    <h2 class="render-title"><?php _e('Custom Map', 'gmaps_plus'); ?></h2>
    <div class="form-row">
        <div class="form-label"><?php _e('Map Width', 'gmaps_plus'); ?></div>
        <div class="form-controls">
            <div class="form-control">
                <input type="number" class="input-text input-medium float-left" min="10" max="100" name="gmaps_plus_width" id="gmaps_plus_width"  value="<?php echo osc_esc_html(osc_get_preference('gmaps_plus_width', 'gmaps_plus')); ?>"></imput>
            </div>

            <div class="form-control">
                <select class="select-box-input" name="gmaps_plus_width_in" id="gmaps_plus_width_in" value="<?php echo $s_width_by; ?>" onchange="value_limit(this.id,'gmaps_plus_width')">
                    <option value="1" <?php if($s_width_by == '1') echo ' selected'; ?>>%</option>
                    <option value="2" <?php if($s_width_by == '2') echo ' selected'; ?>>Pixels</option>
                </select>
            </div>
        </div>        
    </div>
    
    <div class="form-row">
        <div class="form-label"><?php _e('Map Height', 'gmaps_plus'); ?></div>
        <div class="form-controls">
            <div class="form-control">
                <input type="number" class="input-text input-medium float-left" name="gmaps_plus_height" min="100" max="800" value="<?php echo osc_esc_html(osc_get_preference('gmaps_plus_height', 'gmaps_plus')); ?>"></imput>
            </div>
            <div class="form-control">
                <select class="select-box-input" name="gmaps_plus_height_in" value="<?php echo $s_height_by; ?>">
                    <option value="1" <?php if($s_height_by == '1') echo ' selected'; ?>>%</option>
                    <option value="2" <?php if($s_height_by == '2') echo ' selected'; ?>>Pixels</option>
                </select>
            </div>
        </div>        
    </div>
        
    <div class="form-row">
        <div class="form-label"><?php _e('Zoom Level', 'gmaps_plus'); ?></div>
        <div class="form-controls">
            <div class="form-control">
                <input type="number" class="input-text input-medium" name="gmaps_plus_zoom" min="1" max="20" value="<?php echo osc_esc_html(osc_get_preference('gmaps_plus_zoom', 'gmaps_plus')); ?>"></imput>
            </div>            
        </div>        
    </div>
    
    <div class="form-row">
        <div class="form-label"><?php _e('Map Type', 'gmaps_plus'); ?></div>
        <div class="form-controls">           
            <div class="form-control">
                <select class="select-box-input" id="gmaps_plus_map_type" name="gmaps_plus_map_type" value="<?php echo $s_map_type; ?>">
                    <option value="ROADMAP"  <?php if($s_map_type == 'ROADMAP') echo ' selected'; ?>>ROADMAP</option>
                    <option value="HYBRID" <?php if($s_map_type == 'HYBRID') echo ' selected'; ?>>HYBRID</option>
                    <option value="TERRAIN" <?php if($s_map_type == 'TERRAIN') echo ' selected'; ?>>TERRAIN</option>
                </select>
            </div>
        </div>
    </div>

        
        <h3 class="render-title"><?php _e('Map Controls','gmaps_plus'); ?></h3>
        
                <div class="form-label"><?php _e('Show Zoom Control', 'gmaps_plus'); ?></div>
                <div class="form-controls">
                    <div class="form-label-checkbox">
                        <input type="checkbox" class="switch" name="gmaps_plus_zoom_control" value="1" <?php echo (osc_esc_html( osc_get_preference('gmaps_plus_zoom_control', 'gmaps_plus') ) == "1")? "checked": ""; ?>>
                    </div>
	</div>
           
                <div class="form-label"><?php _e('Show Map Type Control', 'gmaps_plus'); ?></div>
                <div class="form-controls">
                    <div class="form-label-checkbox">
                        <input type="checkbox" class="switch" name="gmaps_plus_map_type_control" value="1" <?php echo (osc_esc_html( osc_get_preference('gmaps_plus_map_type_control', 'gmaps_plus') ) == "1")? "checked": ""; ?>>
                    </div>
	</div>
                 <div class="form-label"><?php _e('Show Scale Control', 'gmaps_plus'); ?></div>
                <div class="form-controls">
                    <div class="form-label-checkbox">
                        <input type="checkbox" class="switch" name="gmaps_plus_scale_control" value="1" <?php echo (osc_esc_html( osc_get_preference('gmaps_plus_scale_control', 'gmaps_plus') ) == "1")? "checked": ""; ?>>
                    </div>
	</div>
                  <div class="form-label"><?php _e('Show Street View Control', 'gmaps_plus'); ?></div>
                <div class="form-controls">
                    <div class="form-label-checkbox">
                        <input type="checkbox" class="switch" name="gmaps_plus_street_view_control" value="1" <?php echo (osc_esc_html( osc_get_preference('gmaps_plus_street_view_control', 'gmaps_plus') ) == "1")? "checked": ""; ?>>
                    </div>
	</div>
                    
               
                  <div class="form-label"><?php _e('Show Full Screen Control', 'gmaps_plus'); ?></div>
                    <div class="form-controls">
                        <div class="form-label-checkbox">                       
                            <input type="checkbox" class="switch" name="gmaps_plus_full_screen_control" value="1" <?php echo (osc_esc_html( osc_get_preference('gmaps_plus_full_screen_control', 'gmaps_plus') ) == "1")? "checked": ""; ?>>
                        </div>
                    </div> 
                               
                
                  
	         
                 
                 
                 
         </div>
    
        
    </div>
    
    <div class="form-actions">
        <input type="submit" value="<?php _e('Save changes', 'gmaps_plus'); ?>" class="btn btn-submit">
    </div>
</form>


<script type="text/javascript">
    function value_limit(id_control, id_control_to_limit) {   
        var id_object = document.getElementById(id_control);
        var id_object_to_limit = document.getElementById(id_control_to_limit);
        
        if(id_object.value == '1') {
            id_object_to_limit.max = '100';  
        } else {id_object_to_limit.max='800';}      
    }       
   
</script>
<script src="<?php echo osc_plugin_url('admin/js/bootstrap-switch.min.js');?>"></script> 