<?php

/**
 * Returns the name of the current plugin (plugin in which the calling file is located)
 * @returns a string (name of the plugin).
 * @throws Exception if the calling file is not located in a plugin at all.
 * @since 1.10
 */
function mdh_current_plugin_name($includeIndex=false)
{
    return Madhouse_Utils_Plugins::getCurrentPluginName($includeIndex);
}

/**
 * Requires or returns the file of the current plugin.
 * 	Ex. mdh_current_plugin_path("assets/img/logo.png") in madhouse_hello plugin will produce :
 *	'{ABS_PATH}/oc-content/plugins/madhouse_hello/assets/img/logo.png'
 *
 * @param $file relative path to the file from the root of the current plugin.
 * @param $include tells if the file should be imported (require_once). Default: true.
 * @throws Exception if the file does not exists.
 * @since 1.10
 */
function mdh_current_plugin_path($file=null, $include=true)
{
	return Madhouse_Utils_Plugins::getCurrentPluginPath($file, $include);
}

/**
 * Returns the URL of the file.
 *
 * Works the same as mdh_current_plugin_path for URL.
 *
 * @param $file relative path to the file from the root of the current plugin.
 * @return a string.
 * @since 1.12
 */
function mdh_current_plugin_url($file=null)
{
    $url = sprintf("%s/oc-content/plugins/%s", osc_base_url(), mdh_current_plugin_name());
    if($file != null) {
        $url = $url . "/" . $file;
    }
    return $url;
}

/**
 * Returns the preferences section of the current plugin.
 * @return a string.
 * @since 1.12
 */
function mdh_current_preferences_section()
{
    return "plugin_" . mdh_current_plugin_name();
}

/**
 * Deletes the whole section of preferences.
 * @return void.
 * @since 1.12
 */
function mdh_delete_preferences($section)
{
    Preference::newInstance()->delete(array("s_section" => $section));
}

/**
 * Gets a preference from the current plugin preferences set.
 * @param  String $pref name of the preference.
 * @return Any          the value for the preference $pref.
 * @since  1.18
 */
function mdh_get_preference($pref)
{
    return osc_get_preference($pref, mdh_current_preferences_section());
}

/**
 * Imports SQL file into Osclass database. Use it when installing or removing your plugin.
 * @param $path path to the SQL file.
 * @return void.
 * @throws Exception if import fails.
 * @since 1.10
 */
function mdh_import_sql($path)
{
    Madhouse_Utils_Models::import($path);
}

/**
 * Tells if the specified plugin is ready to be used (installed and enabled).
 * @param $pluginId the identifier of the plugin (name of the folder, '/' or '/index.php' is not needed).
 * @returns true if installed and enabled, false otherwise.
 * @since 1.10
 */
function mdh_plugin_is_ready($pluginId=null)
{
    if($pluginId === null) {
        $pluginId = mdh_current_plugin_name();
    }

    // Is the plugin 'pluginId' installed && enabled && exists in the plugins directory.
	return (
        file_exists(osc_plugins_path() . $pluginId)
        &&
        osc_plugin_is_installed($pluginId . "/index.php") && osc_plugin_is_enabled($pluginId . "/index.php")
    );
}

/**
 * Implements a "loop" helper.
 *
 * This function helps you create "loop" helpers like those provided
 * by osclass like osc_has_items. It iterates elements contained in
 * a collection and exports the next for each call.
 *
 * @param $var the key of the exported collection to loop around.
 * @return true if there has remaining elements, false otherwise.
 * @since 1.12
 */
function mdh_helpers_loop($collection, $var)
{
    return Madhouse_Utils_Plugins::doHelperLoop($collection, $var);
}

function mdh_helpers_reset($collection)
{
    return Madhouse_Utils_Plugins::resetHelperLoop($collection);
}

function mdh_helpers_count($collection)
{
    return Madhouse_Utils_Plugins::countHelperLoop($collection);
}

?>