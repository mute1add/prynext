<?php

class Madhouse_Utils_Controllers
{

    /**
     * Redirects after error/warning to $target with a flash message $message.
     * @param $registerMessage an anonymous function reference that registers the message.
     * @param $target URL where to redirect. Default: osc_base_url();
     * @param $message the message to display as an error/warning. Default: "Oops!..."
     * @param $doRedirect an anonymous function reference to make the redirection. Default: osc_redirect_to($t).
     * @return void
     * @since 1.12
     */
    public static function handleRedirect($message, $registerMessage, $target=null, $doRedirect=null)
    {
        if(is_null($target)) {
            // Redirect to homepage.
        	$target = osc_base_url();
        }

        // Registers the message using an anonymous function.
        $registerMessage($message);

        // Redirect to the $target URL.
        if(is_null($doRedirect)) {
            osc_redirect_to($target);
        } else {
            $doRedirect($target);
        }
    }

    /**
     * Redirects to $target with a warning message $message.
     * @param $target URL where to redirect. Default: osc_base_url();
     * @param $message the message to display as an error/warning. Default: "Oops!..."
     * @return void.
     * @since 1.12
     */
    public static function handleOk($message, $target=null)
    {
        self::handleRedirect(
            $message,
            function($m) {
                if(OC_ADMIN) {
                    osc_add_flash_ok_message($m, "admin");
                } else {
                    osc_add_flash_ok_message($m);
                }
            },
            $target
        );
    }

    /**
     * Redirects to $target with a warning message $message.
     * @param $target URL where to redirect. Default: osc_base_url();
     * @param $message the message to display as an error/warning. Default: "Oops!..."
     * @return void.
     * @since 1.12
     */
    public static function handleWarning($message, $target=null)
    {
        self::handleRedirect(
            $message,
            function($m) {
                if(OC_ADMIN) {
                    osc_add_flash_warning_message($m, "admin");
                } else {
                    osc_add_flash_warning_message($m);
                }
            },
            $target
        );
    }

    /**
     * Redirects to $target with an error message $message.
     * @param $target URL where to redirect. Default: osc_base_url();
     * @param $message the message to display as an error/warning. Default: "Oops!..."
     * @return void.
     * @since 1.12
     */
    public static function handleError($message=null, $target=null)
    {
        if(is_null($message)) {
            $message = __("Oops! We got confused at some point. Try again later and/or contact us.", "madhouse_utils");
        }

        self::handleRedirect(
            $message,
            function($m) {
                if(OC_ADMIN) {
                    osc_add_flash_error_message($m, "admin");
                } else {
                    osc_add_flash_error_message($m);
                }
            },
            $target
        );
    }

    /**
     * Redirects using Javascript to $target with an error message $message.
     * @param $target URL where to redirect. Default: osc_base_url();
     * @param $message the message to display as an error/warning. Default: "Oops!..."
     * @return void.
     * @since 1.12
     */
    public static function handleErrorUgly($message=null, $target=null)
    {
        if(is_null($message)) {
            $message = __("Oops! We got confused at some point. Try again later and/or contact us.", "madhouse_utils");
        }

        self::handleRedirect(
            $message,
            function($m) {
                if(OC_ADMIN) {
                    osc_add_flash_error_message($m, "admin");
                } else {
                    osc_add_flash_error_message($m);
                }
            },
            $target,
            function($t) {
                /*
                 * THIS IS A UGLY HACK. It uses Javacript to redirect and not get a
                 * "header already sent" error.
                 */
                echo sprintf("<script type='text/javascript'>window.location='%s'</script>", $t);
            }
        );
    }

    /**
     * Loads the proper web or admin file.
     * @param $file the file name (without path).
     * @return void
     * @since 1.12
     */
    public static function doView($file) {
        if(OC_ADMIN) {
            // We are in the admin (back-end).
            self::doAdminView($file);
        } else {
            // We are in the web (front-end).
            self::doWebView($file);
        }
    }

    /**
     * Utility function to load the theme file or fallback to default plugin file.
     * @param $file name of the view file to load.
     * @return void.
     * @since 1.12
     */
    public static function doWebView($file)
    {
        $pluginName = mdh_current_plugin_name();
        $relativePath = sprintf("plugins/%s/%s", $pluginName, $file);

        osc_run_hook("before_html");

        // Check if a file named $file exists in the current theme folder in plugins/${plugin_name}/ folder.
        if(file_exists(WebThemes::newInstance()->getCurrentThemePath() . $relativePath)) {
            // Loads the requested view in the current theme.
        	osc_current_web_theme_path($relativePath);
        } else {
            // Loads the default view shipped with the plugin.
            osc_run_hook($plugin_name . "_init_default");
            self::doCustom(sprintf("%s/views/web/%s", $pluginName, $file));
        }

        Session::newInstance()->_clearVariables();
        osc_run_hook("after_html");
    }

    /**
     * Loads a plugin view file in the admin.
     * @param $file the name of the view file.
     * @return void
     * @since 1.12
     */
    public static function doAdminView($file)
    {
        osc_run_hook("before_admin_html");

        self::doCustom(mdh_current_plugin_path("views/admin/" . $file, false));

        Session::newInstance()->_clearVariables();
        osc_run_hook("after_admin_html");
    }

    /**
     * Loads a file using the custom view.
     * @param $file the path of the file to include within the custom file.
     * @return void
     * @since 1.12
     */
    public static function doCustom($file)
    {
        // Exports the file to the view.
        View::newInstance()->_exportVariableToView("file", $file);
        if(OC_ADMIN) {
            // We are in the admin (back-end).
            osc_run_hook(mdh_current_plugin_name() . "_init_admin_custom");
            osc_current_admin_theme_path("plugins/view.php");
        } else {
            // We are in the web (front-end).
            osc_run_hook(mdh_current_plugin_name() . "_init_custom");
            osc_current_web_theme_path("custom.php");
        }
    }

    /**
     * Includes a view part (chunk of a view) from the theme or fallback to the default.
     * @param $file the name of the view part file.
     * @return void.
     * @since 1.12
     */
    public static function doViewPart($file)
    {
        // Gets the name of the caller plugin.
        $pluginName = mdh_current_plugin_name();

        // Loads the theme file or fallback to default if not exists such file.
        if(file_exists(WebThemes::newInstance()->getCurrentThemePath() . "plugins/" . $pluginName . "/" . $file)) {
        	osc_current_web_theme_path("plugins/" . $pluginName . "/" . $file);
        } else {
            // Includes the default widget.
            mdh_current_plugin_path("views/web/" . $file);
        }
    }

    /**
     * Common case for updating settings for a plugin.
     * @param  Array<String> $settings List of preferences names.
     * @param  String $success  URL to redirect to when update is successful.
     * @param  String $error    URL to redirect to when update is NOT successful.
     * @return void.
     */
    public static function doSettingsPost($keys, $values, $successURL, $errorURL=null, $successMsg=null, $errorMsg=null)
    {
        if(is_null($errorURL)) {
            $errorURL = $successURL;
        }

        if(is_null($successMsg)) {
            $successMsg = __("Successfully updated settings!", mdh_current_plugin_name());
        }

        if(is_null($errorMsg)) {
            $errorMsg = __("Error while updating settings!", mdh_current_plugin_name());
        }

        // Each settings is getting updated with the value passed as params.
        foreach ($keys as $p) {
            if(! array_key_exists($p, $values)) {
                $v = 0;
            } else {
                $v = $values[$p];
            }

            $res = osc_set_preference($p, $v, mdh_current_preferences_section());
            if(! $res) {
                mdh_handle_error(
                    $errorMsg,
                    $errorURL
                );
            }
        }

        mdh_handle_ok($successMsg, $successURL);
    }
}

?>