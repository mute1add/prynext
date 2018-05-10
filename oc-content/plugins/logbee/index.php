<?php
/*
Plugin Name: Logbee
Plugin URI: http://www.logbee.com/
Description: Item detail page optimized for use with Logbee.com (meta-tags and 'log it' button)
Version: 1.1.2
Author: Pierre Moret, Logbee
Author URI: http://www.logbee.com/
Short Name: logbee
Plugin update URI: logbee
*/


function logit_btn()
{
    
    echo '
<script type="text/javascript">function logbee_wopen(url){ var win = window.open(url, \'logbee\', \'width=1200, height=1000, location=no, menubar=no, status=no, toolbar=no, scrollbars=yes, resizeable=yes\'); win.resizeTo(w, h); win.focus(); }</script>

<div class="logbee"> 
  <a href="" class="log-it-button" onclick="javascript:logbee_wopen(\'http://www.logbee.com/add?url=' . urlencode(osc_item_url()) . '\');return false;"><img src="http://www.logbee.com/img/affiliation/logbee_portal_button_logit_60x25.png" border="0" title="log it"></a>
  <div class="clear"></div>
</div>
  ';
    
}


function logbee_header()
{
    $location = Rewrite::newInstance()->get_location();
    $section  = Rewrite::newInstance()->get_section();
    
    if ($location == 'item' && $section == '') {
        echo '
<style type="text/css">
  .logbee ul { margin: 10px 0; list-style: none; }
  .logbee ul li { float: left; }
  .logbee .clear { clear:both; }
</style>';
        
        
        // meta-tags
        
        $address_array = array();
        if (osc_item_address() != "") {
            $address_array[] = osc_item_address();
        }
        if (osc_item_city_area() != "") {
            $address_array[] = osc_item_city_area();
        }
        if (osc_item_zip() != "") {
            $address_array[] = osc_item_zip();
        }
        if (osc_item_city() != "") {
            $address_array[] = osc_item_city();
        }
        if (osc_item_region() != "") {
            $address_array[] = osc_item_region();
        }
        if (osc_item_country() != "") {
            $address_array[] = osc_item_country();
        }
        $address = implode(", ", $address_array);
        
        $price = '';
        if (osc_item_formated_price() != "") {
            $price = osc_item_formated_price();
        }        

        $email = '';
        if (osc_item_show_email()) {
            $email = osc_item_contact_email();
        }

        echo '
 <meta property="logbee:title" content="' . osc_esc_html(osc_item_title()) . '"/>
 <meta property="logbee:url"   content="' . osc_esc_html(osc_item_url()) . '"/>
 <meta property="logbee:desc"  content="' . osc_esc_html(osc_item_description()) . '"/>
 <meta property="logbee:addr"  content="' . osc_esc_html($address) . '"/>
 <meta property="logbee:email" content="' . osc_esc_html($email) . '"/>
 <meta property="logbee:price" content="' . osc_esc_html($price) . '"/>';
        
        
        // do we have the cars_plugin enabled?
        
        if (osc_plugin_is_enabled('cars_attributes/index.php')) {
            
            require_once(osc_plugin_path('') . '/cars_attributes/ModelCars.php');
            
            if (osc_is_this_category('cars_plugin', osc_item_category_id())) {
                $detail = ModelCars::newInstance()->getCarAttr(osc_item_id());
                
                echo '
 <meta property="logbee:type" content="car"/>
 <meta property="logbee:mileage" content="' . osc_esc_html(@$detail['i_mileage']) . '"/>
 <meta property="logbee:firstreg" content="' . osc_esc_html(@$detail['i_year']) . '"/>';
            }
        }
        
        
        
        // do we have the realestate_plugin enabled?
        
        if (osc_plugin_is_enabled('realestate_attributes/index.php')) {
            
            require_once(osc_plugin_path('') . '/realestate_attributes/ModelRealEstate.php');
            
            if (osc_is_this_category('realestate_plugin', osc_item_category_id())) {
                $detail = ModelRealEstate::newInstance()->getAttributes(osc_item_id());
                
                echo '
 <meta property="logbee:type" content="realty"/>
 <meta property="logbee:rooms" content="' . osc_esc_html(@$detail['i_num_rooms']) . '"/>
 <meta property="logbee:size" content="' . osc_esc_html(@$detail['s_square_meters']) . ' sqm"/>';
            }
        }
        

 
        // images
        
        osc_reset_resources();
        $images_array = array();
        
        for ($i = 0; osc_has_item_resources(); $i++) {
            $images_array[] = osc_esc_html(osc_resource_url());
        }
        $images = implode("|", $images_array);
        
        echo '
 <meta property="logbee:imgurl" content="' . $images . '"/>
 ';
        
        osc_reset_resources();
                
        echo "\n";
        
    }
}


/**
 *  HOOKS
 */
osc_register_plugin(osc_plugin_path(__FILE__), '');
osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', '');

osc_add_hook('item_detail', 'logit_btn');
osc_add_hook('header', 'logbee_header');

?>
