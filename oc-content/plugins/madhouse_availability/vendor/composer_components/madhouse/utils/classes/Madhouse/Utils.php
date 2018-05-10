<?php

class Madhouse_Utils
{
    /**
     * Returns the absolute path to the callee (calling) file of a function.
     *
     * Uses backtrace to guess who's calling the function, *dirty* but works well.
     *
     * @return a string.
     * @see mdh_current_plugin_name().
     */
    public static function getCalleeFile()
    {
        // Filter all reference to madhouse_utils to allow internal call.
        $filteredBT = array_filter(debug_backtrace(), function($v) {
            if(! isset($v["file"])) {
                return false;
            } else {
                $file = preg_replace('|/+|','/', str_replace('\\','/', $v["file"]));
                return (preg_match("#^.*/oc-content/plugins/.*$#", $file) && !preg_match("#^.*/vendor/.*$#", $file));
            }
        });

        $files = array_map(
            function($v) {
                return preg_replace('|/+|','/', str_replace('\\','/', $v["file"]));
            },
            $filteredBT
        );

        return array_shift($files);
    }

    public static function getHttpResponseCode() {
        if(function_exists("http_response_code")) {
            // PHP >= 5.4
            return http_response_code();
        } else {
            // PHP < 5.4
            $headers = get_headers(osc_base_url());
            return substr($headers[0], 9, 3);
        }
    }

    public static function errorLog($mixed, $stacktrace=true) {
        $haystack = array(
            "message" => $mixed
        );
        if($stacktrace) {
            $dbacktrace = array_filter(debug_backtrace(), function($v) {
                return (isset($v["file"]));
            });

            $haystack["backtrace"] = array_map(function($v) {
                return $v["file"] . ":" . $v["line"];
            }, $dbacktrace);
        }

        error_log(print_r($haystack, true));
    }
}

?>