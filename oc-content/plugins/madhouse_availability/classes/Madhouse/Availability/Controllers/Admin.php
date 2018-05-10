<?php

class Madhouse_Availability_Controllers_Admin extends AdminSecBaseModel
{
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Load the settings view.
	 * @since 1.0
	 */
	private function doSettings()
	{
	}

	/**
	 * Save the settings and redirect to settings view.
	 * @since 1.0
	 */
	private function doSettingsPost()
	{
		// Saves the settings.
		Madhouse_Utils_Controllers::doSettingsPost(
		    array(
		        "end_date",
		        "search_include_past_item",
		        "form_post_position",
		        "form_edit_position",
		        "form_search_position",
		        "detail_position"
		    ),
		    Params::getParamsAsArray("post"),
		    mdh_availability_url()
		);
	}

	public function doModel()
	{
		parent::doModel();

		switch (Params::getParam("route")) {
			case mdh_current_plugin_name() . "_do":
			$this->doSettingsPost();
			break;
			case mdh_current_plugin_name() . "_settings":
			default:
			$this->doSettings();
			break;
		}
	}
}

?>