<?php

class Madhouse_Utils_Dates
{
	public static function smartDate($date) {
		if(!isset($date) || empty($date)) {
	        throw new Exception(__("No date provided !", "madhouse_utils"));
	    }

		$timestamp = strtotime($date);

		$diff = time() - $timestamp;
	 
		if ($diff <= 60) {
			return __('just now', "madhouse_utils");
		}
		else if ($diff < 60) {
			return self::grammarDate(floor($diff), __('%d second(s) ago', "madhouse_utils"));
		}
		else if ($diff < 60*60) {
			return self::grammarDate(floor($diff/60), __('%d minute(s) ago', "madhouse_utils"));
		}
		else if ($diff < 60*60*24) {
			return self::grammarDate(floor($diff/(60*60)), __('%d hour(s) ago', "madhouse_utils"));
		}
		else if ($diff < 60*60*24*30) {
			return self::grammarDate(floor($diff/(60*60*24)), __('%d day(s) ago', "madhouse_utils"));
		}
		else if ($diff < 60*60*24*30*12) {
			return self::grammarDate(floor($diff/(60*60*24*30)), __('%d month(s) ago', "madhouse_utils"));
		}
		else {
			return self::grammarDate(floor($diff/(60*60*24*30*12)), __('%d year(s) ago', "madhouse_utils"));
		}
	}
 
	private static function grammarDate($val, $sentence) {
		if ($val > 1) {
			return sprintf(str_replace('(s)', 's', $sentence), $val);
		} else {
			return sprintf(str_replace('(s)', '', $sentence), $val);
		}
	}
}

?>