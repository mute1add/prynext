<?php

class Madhouse_Utils_Plugins
{
    /**
     * Guess the current plugin name.
     * @param  boolean $includeIndex true if we want the function to return the "/index.php".
     * @return String                the plugin name
     *                               (including "/index.php" if $includeIndex is set to true)
     * @throws Exception if the calling file is not located in a plugin at all.
     * @since  1.18
     */
    public static function getCurrentPluginName($includeIndex=false)
    {
        $callee = Madhouse_Utils::getCalleeFile();
        if(! preg_match("#^.*/oc-content/plugins/.*$#", $callee)) {
            throw new Exception("Current file '" . $callee . "' does not belong to a plugin!");
        }

        $name = preg_replace('#^([^/]+)/.*$#', '$1', osc_plugin_folder($callee));
        if($includeIndex) {
            return sprintf("%s/index.php", $name);
        } else {
            return $name;
        }
    }

    /**
     * Get the absolute path to the file in the current plugin folder.
     *     Ex. mdh_current_plugin_path("assets/img/logo.png") in madhouse_hello
     *     plugin will produce :
     *     '{ABS_PATH}/oc-content/plugins/madhouse_hello/assets/img/logo.png'
     * @param  String  $file    relative path to the file within the plugin.
     * @param  boolean $include set to false if you just want to get the file path.
     *                          set to true (default), the file is included/required.
     * @return String|void      the absolute file path or void (if $inclue==true).
     * @since  1.18
     */
    public static function getCurrentPluginPath($file=null, $include=true)
    {
        // Get
        $path = osc_plugin_path(mdh_current_plugin_name());
        if($file != null) {
            $path = $path . DIRECTORY_SEPARATOR . $file;
        }
        if(! file_exists($path)) {
            throw new Exception(sprintf("'%s' not found!", $path));
        }

        if($include) {
            require_once($path);
        } else {
            return $path;
        }
    }

    /**
     * Gets the URL of a file - within a plugin - for a path.
     * @param  String $path path of the plugin file.
     * @return String       absolute URL for the plugin file.
     */
    public static function getURL($path)
    {
        $path = preg_replace('|/+|','/', str_replace('\\','/', $path));
        return sprintf("%s/%s", preg_replace("#^(.+)/$#", '$1', osc_base_url()), preg_replace("#^.*/(oc-content/[^/]+)/(.+)$#", '$1/$2', $path));
    }

    /**
     * Returns the relative path to the view $file within the theme.
     * @param  String $file a view filename (inbox.php, search.php, etc.)
     * @return        the relative path to the view file, within a theme.
     * @since  1.17
     */
    public static function themePath($file)
    {
        return sprintf("plugins/%s/%s", mdh_current_plugin_name(), $file);
    }

    /**
     * Forbid to delete or disable a plugin if some other plugins requires it.
     *  TODO: Do the same with themes.
     * @since 1.18
     */
    public static function beforeUninstallOrDesactivate($plugin_name) {
        $refs = self::listReferences($plugin_name);
        mdh_error_log($refs);
        if(count($refs)) {
            mdh_handle_error(
                sprintf("Can't disable/uninstall plugin '%s'. Some plugins still requires it : %s", $plugin_name, implode(", ", $refs)),
                osc_admin_base_url(true) .'?page=plugins'
            );
        }
    }

    /**
     * Returns the list of dependencies of the plugin .
     *  Reads the $plugin_name/index.php file to get the list of dependencies (Depends:).
     *  TODO: Handle themes dependencies.
     * @param $plugin_name the name of the plugin.
     * @since 1.18
     */
    public static function listDependencies($plugin_name) {
        // Find the plugin path whether $plugin_name contains "/index.php" or not.
        if(preg_match('#^.+index.php$#', $plugin_name)) {
            $plugin_path = osc_plugin_path($plugin_name);
        } else {
            $plugin_path = osc_plugin_path($plugin_name) . DIRECTORY_SEPARATOR . "index.php";
        }

        // Plugin is installed/enabled but is missing on the filesystem.
        if(! file_exists($plugin_path)) {
            return NULL;
        }

        // Retrieve the list of dependencies.
        $s_info = file_get_contents($plugin_path);
        if(preg_match('#Depends:([^\\r\\t\\n]*)#i', $s_info, $match)) {
            return array_map(function($v) use ($plugin_name) {
                if($v !== "" && $v !== "-") {
                    if(preg_match('#^([a-zA-Z0-9_]+):(.+)$#', $v, $m)) {
                        return array(
                            "name" => $m[1],
                            "version" => $m[2]
                        );
                    } else {
                        throw new Exception(sprintf("Malformed dependency declaration '%s' in %s", $v, $plugin_name));
                    }
                }
            }, explode(", ", trim($match[1])));
        } else {
            // No 'Depends:' line on the header of the plugin.
            return NULL;
        }
    }


    /**
     * Returns the list of dependencies of the plugin .
     *  Reads the $plugin_name/index.php file to get the list of dependencies (Depends:).
     *  TODO: Handle themes dependencies.
     * @param $plugin_name the name of the plugin.
     * @since 1.18
     */
    public static function listReferences($plugin_name) {
        return array_filter(Plugins::listEnabled(), function($v) use ($plugin_name) {
            $dependencies = Madhouse_Utils_Plugins::listDependencies($v);
            if ($dependencies == NULL) {
                return false;
            } else {
                return (in_array($plugin_name, array_map(function($v) {
                    return $v["name"];
                }, $dependencies)));
            }
        });
    }

    /**
     * Let a theme override the default theme file (from a plugin).
     *     Loads the file $file if it exists in the theme.
     *     Do nothing otherwise and let the default theme file be loaded by the
     *     route mechanism.
     * @return void.
     * @since  1.17
     */
    public static function overrideView()
    {
        // Get the relative path of the view (the calling file).
        $callee = self::themePath(basename(Madhouse_Utils::getCalleeFile()));

        if(file_exists(WebThemes::newInstance()->getCurrentThemePath() . $callee)) {
            osc_current_web_theme_path($callee);
            exit;
        }
    }

    /**
     * Create a loop for $collection.
     * @param  String $collection key (of the View object) of the collection.
     * @param  String $var        key (of the View object) of the current object to be used.
     * @return boolean            true if there's still elements in the loop.
     *                            false if it's the end of the loop.
     * @since  1.18
     */
    public static function doHelperLoop($collection, $var)
    {
        $loop = $collection . "_loop";
        if(self::countHelperLoop($collection) == 0) {
            return false;
        } else {
            if(View::newInstance()->_get($loop) !== true) {
                // First time in the loop.

                // Reset the loop just to make sure.
                self::resetHelperLoop($collection);

                // Starting the loop.
                View::newInstance()->_exportVariableToView($loop, true);
                osc_run_hook($loop . "_start");

                // Set the current item and return true.
                View::newInstance()->_exportVariableToView($var, View::newInstance()->_current($collection));

                osc_run_hook($var . "_exported");

                return true;
            } else {
                // Not the first time in the loop.
                View::newInstance()->_next($collection);
                $e = View::newInstance()->_current($collection);
                if($e) {
                    // Replace current item and return true.
                    View::newInstance()->_exportVariableToView($var, $e);

                    osc_run_hook($var . "_exported");

                    return true;
                } else {
                    // Erase and return false.
                    View::newInstance()->_exportVariableToView($loop, false);
                    osc_run_hook($loop . "_end");

                    return false;
                }
            }
        }
    }

    /**
     * Reset a loop (for $collection)
     * @param  String $collection key (of the View object) of the collection.
     * @return void
     * @since  1.18
     */
    public static function resetHelperLoop($collection)
    {
        View::newInstance()->_exportVariableToView($collection . "_loop", false);
        return View::newInstance()->_reset($collection);
    }

    /**
     * Count the number of elements (total) in the loop.
     * @param  String $collection key (of the View object) of the collection.
     * @return Int                the number of element in the loop.
     * @since  1.18
     */
    public static function countHelperLoop($collection)
    {
        View::newInstance()->_get($collection);
        return count(View::newInstance()->_get($collection));
    }
}

?>