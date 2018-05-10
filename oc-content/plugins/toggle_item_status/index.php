<?php
/*
Plugin Name: Toggle Item Status
Plugin URI: http://amfearliath.tk/osclass-toggle-item-status
Description: User can mark items as sold or make them available again
Version: 1.0.2
Author: Liath
Author URI: http://amfearliath.tk
Short Name: toggle_item_status
Plugin update URI: toggle-item-status
*/

require_once('classes/tis.class.php');

if (Params::getParam('ti_status')) {
    t_i_s::newInstance()->tis_change_status(Params::getParamsAsArray());        
}

function tis_install() {
    t_i_s::newInstance()->tis_install();
}

function tis_uninstall() {
    t_i_s::newInstance()->tis_uninstall();
}

function tis_show($id, $item) {
    t_i_s::newInstance()->tis_show_status($id, $item);
}

function tis_status_box($id) {
    t_i_s::tis_status_box($id);
}

function tis_style() {
    osc_enqueue_style('tis-styles', osc_plugin_url('toggle_item_status/assets/css/tis.css').'tis.css');
}

function tis_script() {
    echo '<script type="text/javascript" src="'.osc_plugin_url('toggle_item_status/assets/js/tis.js').'tis.js"></script>';
}

function tis_configuration() {
    osc_admin_render_plugin(osc_plugin_path(dirname(__FILE__)) . '/help.php');
}

function tis_item_title($title) {
    $id = osc_item_id();
    return t_i_s::newInstance()->tis_item_title($title, $id);
}

if (osc_version() < 311) {
    osc_add_hook('footer', 'tis_script');
} else {
    osc_register_script('tis-script', osc_plugin_url('toggle_item_status/assets/js/tis.js') . 'tis.js', array('jquery'));
    osc_enqueue_script('tis-script');
}
    
osc_register_plugin(osc_plugin_path(__FILE__), 'tis_install') ;
osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', 'tis_uninstall') ;

osc_add_hook('header', 'tis_style');
osc_add_hook(osc_plugin_path(__FILE__) . '_configure', 'tis_configuration');                        
?>