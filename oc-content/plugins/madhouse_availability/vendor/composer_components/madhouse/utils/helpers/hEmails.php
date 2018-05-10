<?php

/**
 * Install an email.
 * @see Madhouse_Utils_Emails::install($name, [...]);
 */
function mdh_install_email($name, $defaultTitle=null, $defaultText=null) {
	Madhouse_Utils_Emails::install($name, $defaultTitle, $defaultText);
}

/**
 * Uninstall an email.
 * @see  Madhouse_Utils_Emails::uninstall($name)
 */
function mdh_uninstall_email($name) {
    Madhouse_Utils_Emails::uninstall($name);
}

?>