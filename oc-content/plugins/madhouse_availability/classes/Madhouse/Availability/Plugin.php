<?php

/**
 * @since  1.1.0
 */
class Madhouse_Availability_Plugin
{
    public static function install()
    {
        // Import struct SQL and data.
        mdh_import_sql(mdh_current_plugin_path('assets/model/install.sql', false));

        // Set default preferences.
        osc_set_preference('end_date',                 '1', "plugin_madhouse_availability", 'INTEGER');
        osc_set_preference('search_include_past_item', '0', "plugin_madhouse_availability", 'INTEGER');
        osc_set_preference('detail_position',          '5', "plugin_madhouse_availability", 'INTEGER');
        osc_set_preference('form_post_position',       '5', "plugin_madhouse_availability", 'INTEGER');
        osc_set_preference('form_edit_position',       '5', "plugin_madhouse_availability", 'INTEGER');
        osc_set_preference('form_search_position',     '5', "plugin_madhouse_availability", 'INTEGER');

        // Set the version to the current installed one.
        osc_set_preference('version', '1.01', mdh_current_preferences_section(), 'INTEGER');
        osc_reset_preferences();

        // Upgrade.
        self::upgrade();
    }

    public static function uninstall()
    {
        // Detroy struct SQL.
        mdh_import_sql(mdh_current_plugin_path('assets/model/uninstall.sql', false));

        // Cleanup preferences.
        mdh_delete_preferences(mdh_current_preferences_section());
        osc_reset_preferences();
    }

    public static function init()
    {
        if(osc_plugin_is_installed(mdh_current_plugin_name(true)) && osc_get_preference("version", mdh_current_preferences_section()) === "") {
            // We bumped.
            self::install();
        } elseif (osc_plugin_is_enabled(mdh_current_plugin_name(true))) {
            // Check for updates.
            self::upgrade();
        }
    }

    public static function upgrade()
    {
        // Upgrade to 1.01
        if(strnatcmp(osc_get_preference('version', mdh_current_preferences_section()), "1.0.2") < 0) {
            // Upgrade if necessary.
            osc_set_preference('version', '1.0.2', mdh_current_preferences_section());
            osc_reset_preferences();
        }

        // Upgrade to 1.1.0
        if(strnatcmp(osc_get_preference('version', mdh_current_preferences_section()), "1.1.0") < 0) {
            osc_set_preference('version', '1.1.0', mdh_current_preferences_section());
            osc_reset_preferences();
        }
    }
}