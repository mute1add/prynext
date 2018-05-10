<?php

/*
 * Copyright (C) 2016 Puiu Calin
 * This program is a commercial software: is forbidden to use this software without licence, 
 * on multiple installations, and by purchasing from other source than those authorized for the sale of software.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

/*
  Plugin Name: Debug Read
  Plugin URI: http://theme.calinbehtuk.ro/
  Description: Read your debug file from osclass admin.
  Version: 1.0.1
  Author: Puiu Calin
  Author URI: http://theme.calinbehtuk.ro/
  Plugin update URI: debug-read
  Short Name: debug-read
 */

require_once 'functions.php';

function debug_read_install() {
    // Something in the future
}

function debug_read_uninstall() {
    //Something in the future
}

function debug_read_admin_menu() {
    osc_add_admin_menu_page(__('Debug', 'debug'), osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/admin/settings.php'), 'debug');
}

function debug_read_admin_style() {
    osc_enqueue_style('debug', osc_base_url() . 'oc-content/plugins/debug/css/style.css');
}

osc_add_hook('admin_header', 'debug_read_admin_style');
osc_add_hook('admin_menu_init', 'debug_read_admin_menu');
osc_register_plugin(osc_plugin_path(__FILE__), 'debug_read_install');
osc_add_hook(osc_plugin_path(__FILE__) . "_uninstall", 'debug_read_uninstall');
