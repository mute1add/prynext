<?php

/**
 * Utilities related to text manipulations.
 *
 * Just simplify text manipulation for both plugins and themes.
 *
 * @package Madhouse
 * @subpackage Utils
 * @since 1.10
 */
class Madhouse_Utils_Text
{
    public static function truncate($string, $max, $sep = "...", $oneline = true) {
    	if($oneline && strpos($string, "\n") != 0 && strpos($string, "\n") < $max) {
    		$max = strpos($string, "\n");
    	}

    	if($string[strlen($string)-1] == ".") {
    		$max = $max - 1;
    	}

    	if(strlen($string) > $max) {
    		return (mb_substr($string, 0, $max, 'utf-8') . $sep);
    	} else {
    		return($string);
    	}
    }
}

?>