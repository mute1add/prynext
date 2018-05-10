<?php
/*
Plugin Name: Russian Interactive Map
Plugin URI: https://osclass-pro.com
Plugin update URI:  russian-interactive-map
Description: Russian Interactive Map
Version: 1.0.1
Author: DIS
Author URI: https://osclass-pro.com
Short Name: russian_i_map
*/

	function russian_i_map() {
		require 'map_ru.php';
	}
	

	function map_configure_link() {
        osc_redirect_to(osc_route_admin_url('map-admin-home'));
    }
	function map_admin_menu() {
        osc_add_admin_submenu_divider('plugins', 'Russian map', 'russian_i_map_divider', 'administrator');
        osc_add_admin_submenu_page('plugins', __('Russian map help', 'russian_i_map'), osc_route_admin_url('map-admin-home'), 'russian_i_map_settings', 'administrator');
    }
	
	osc_add_route('map-admin-home', 'russian_i_map/admin/home', 'russian_i_map/admin/home', osc_plugin_folder(__FILE__).'/help.php');
	
	osc_add_hook(osc_plugin_path(__FILE__)."_configure", 'map_configure_link');
	
// This is needed in order to be able to activate the plugin
osc_register_plugin(osc_plugin_path(__FILE__), '');
// This is a hack to show a Uninstall link at plugins table (you could also use some other hook to show a custom option panel)
osc_add_hook(osc_plugin_path(__FILE__) . "_uninstall", '');


osc_add_hook('admin_menu_init', 'map_admin_menu');
?>