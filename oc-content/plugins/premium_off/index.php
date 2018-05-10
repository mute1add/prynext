<?php

/*
 * Copyright (C) 2016 Puiu Calin
 * This program is a commercial software: is forbidden to use this software without licence, 
 * on multiple installations, and by purchasing from other source than those authorized for the sale of software.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

/*
  Plugin Name: Premium Off
  Plugin URI: http://theme.calinbehtuk.ro
  Description: This plugin deactivate premium ads after expiration.
  Version: 1.0.1
  Author: Puiu Calin
  Author URI: http://theme.calinbehtuk.ro
  Short Name: premium-off
  Plugin update URI: premium-off
 */

define('PREMIUM_OFF', '100');

require_once 'model.php';
require_once 'data.php';

function premium_off_install() {
    osc_set_preference('premium_off_version', PREMIUM_OFF, 'premium_off');
}

function premium_off_uninstall() {
    
}

function premium_off_cron() {
    $item_data = Premium_OFF::newInstance()->get_expired_premium_items();
    if (!empty($item_data)) {
        foreach ($item_data as $item) {
            ///Premium_OFF::newInstance()->update_ad($item['pk_i_id']); removed
            //use osclass function to run premium hook
            $mItem = new ItemActions(true);
            $mItem->premium($item['pk_i_id'], $on = false);
        }
    }
}

osc_add_hook('cron_daily', 'premium_off_cron');

if (OC_ADMIN) {
    if (PREMIUM_OFF > osc_get_preference('premium_off_version', 'premium_off')) {
        osc_set_preference('premium_off_version', PREMIUM_OFF, 'premium_off');
    }
}


function premium_off_help() {
    osc_admin_menu_plugins('' . __('Premium Off', 'premium_off'), osc_admin_render_plugin_url('premium_off/admin/info.php'), 'premium_off_submenu');
}

osc_add_hook('admin_menu_init', 'premium_off_help');
osc_register_plugin(osc_plugin_path(__FILE__), 'premium_off_install');
osc_add_hook(osc_plugin_path(__FILE__) . "_uninstall", 'premium_off_uninstall');
