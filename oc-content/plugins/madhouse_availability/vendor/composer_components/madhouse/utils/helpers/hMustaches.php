<?php 

/**
 * Register a mustache template.
 * @param $key unique identifier for the mustache partial.
 * @param $file file that contains the mustache template.
 * @since 1.00
 */
function madhouse_register_mustache($key, $file) {
	Madhouse_Utils_Mustaches::newInstance()->registerMustache($key, $file, NULL);
}

/**
 * Enqueue the mustache template to be used.
 * @param $key the unique identifier of the mustache partial.
 * @since 1.00
 */
function madhouse_enqueue_mustache($key) {
	Madhouse_Utils_Mustaches::newInstance()->enqueueMustache($key);
}

/**
 * Render a particular template with the values.
 * @param $key the unique identifier of the mustache.
 * @param $values the values to render the template (associative array).
 * @since 1.00
 */
function madhouse_render_mustache($key, $values) {
	echo Madhouse_Utils_Mustaches::newInstance()->render($key, $values);
}

?>