<?php

/**
 * Logs an error message into PHP error log.
 * @param $mixed a PHP variable to log
 * @since 1.10
 */
function mdh_error_log($mixed, $stacktrace=true) {
	Madhouse_Utils::errorLog($mixed, $stacktrace);
}

?>