<?php
	/**
	 * Gets a specific field from current user
	 *
	 * @param string $field
	 * @param string $locale
	 * @return mixed
	 */
	function mdh_availability_field($field, $locale = "") {
		if (View::newInstance()->_exists('availability')) {
			$data = View::newInstance()->_get('availability');
			return osc_field($data[osc_item_id()], $field, $locale);
		} else {
			return null;
		}
	}

	/**
	 * Get route admin url
	 * @return string $url
	 */
	function mdh_availability_url() {
		return osc_route_admin_url(mdh_current_plugin_name());
	}

	/**
	 * Get route do admin url
	 * @return string $url
	 */
	function mdh_availability_do_url() {
		return osc_route_admin_url(mdh_current_plugin_name() . "_do");
	}

	/**
	 * Get start date availability of an item
	 * @return string $availability_start
	 */
	function mdh_availability_start() {
		return mdh_availability_field("d_start");
	}

	/**
	 * Return true if the item has an and date
	 * @return string $mdh_availability_has_end
	 */
	function mdh_availability_has_end() {
		return mdh_availability_end() !="";
	}

	/**
	 * Get end date availability of an item
	 * @return string $availability_end
	 */
	function mdh_availability_end() {
		return mdh_availability_field("d_end");
	}

	/**
	 * If the itme has an end, return the duration between start and end
	 * @return string $duration
	 */
	function mdh_availability_duration() {
		if (mdh_availability_end() != "") {
			return mdh_availability_calc_duration(mdh_availability_start(), mdh_availability_end());
		} else {
			return "";
		}
	}

	/**
	 * Return start availability value in search
	 * @return string $availability_start
	 */
	function mdh_search_availability_start() {
		return View::newInstance()->_get('search_availability_start');
	}

	/**
	 * Return end availability value in search
	 * @return string $availability_end
	 */
	function mdh_search_availability_end() {
		return View::newInstance()->_get('search_availability_end');
	}

	/**
	 * Calcul the duration in day between two dates
	 * @param  string $date1 start date
	 * @param  string $date2 end date
	 * @return string        duration in day
	 */
	function mdh_availability_calc_duration($date1, $date2) {
		$date1 = new DateTime($date1);
		$date2 = new DateTime($date2);
		$interval = $date1->diff($date2);
		return $interval->days;
	}

	/**
	 * Return end date settings
	 * @return [type] [description]
	 */
	function mdh_availability_end_date_setting() {
		return osc_get_preference('end_date', mdh_current_preferences_section());
	}
?>