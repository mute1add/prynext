<?php

/**
 * Nevermind the bollocks, madhouse/utils is not loaded yet.
 * mdh_utils() is available since 1.18, so we also test mdh_current_plugin_name() for utils < 1.18.
 */
if(! function_exists("mdh_utils") && ! function_exists("mdh_current_plugin_name")) {

    /*
     * ==========================================================================
     *  LOADING
     * ==========================================================================
     */

    require_once(__DIR__ . "/helpers/hPlugins.php");
    require_once(__DIR__ . "/helpers/hDebug.php");
    require_once(__DIR__ . "/helpers/hEmails.php");
    require_once(__DIR__ . "/helpers/hMustaches.php");
    require_once(__DIR__ . "/helpers/hSearch.php");
    require_once(__DIR__ . "/helpers/hUtils.php");

    /*
     * ==========================================================================
     *  ROUTES
     * ==========================================================================
     */

     /*
     * ==========================================================================
     *  LOCALES
     * ==========================================================================
     */
    if( OC_ADMIN ) {
        $locale = osc_current_admin_locale();
    } else {
        $locale = osc_current_user_locale();
    }

    Translation::newInstance()->_load(__DIR__ . "/languages/" . $locale . "/messages.mo", "madhouse_utils");

    /*
     * ==========================================================================
     *  REGISTER & ENQUEUE
     * ==========================================================================
     */

    /**
     * (hook: init) Registers scripts and styles (and mustache.js templates).
     * @returns void.
     */
    function mdh_utils_admin_init() {
        // osc_enqueue_style("codemirror", osc_plugin_url("madhouse_utils/index.php") . "assets/codemirror/codemirror.css");
        osc_enqueue_style("font-sourcesanspro-admin", "http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,500,700,400italic");
        osc_enqueue_style("madhouse-utils-admin", Madhouse_Utils_Plugins::getURL(__DIR__ . "/assets/css/admin.css"));
    }
    osc_add_hook('init_admin', 'mdh_utils_admin_init');

    function madhouse_load_mustaches() {
        Madhouse_Utils_Mustaches::newInstance()->printMustaches();
    }
    osc_add_hook('header', 'madhouse_load_mustaches', 10);

    /*
     * ==========================================================================
     *  USER AND ADMIN MENU
     * ==========================================================================
     */

    function mdh_utils_admin_menu_init() {
        // Create a Madhouse main admin menu to hold other Madhouse settings.
        osc_add_admin_menu_page("Madhouse", '#', "madhouse", "moderator");
        //osc_add_admin_submenu_page('madhouse', "&mdash;&nbsp;". __('About', "madhouse_utils"), osc_route_admin_url("madhouse_utils_about"), "madhouse_utils", 'moderator');
    }
    osc_add_hook("admin_menu_init", "mdh_utils_admin_menu_init");
}

?>