<?php
/*
Plugin Name: Madhouse Availability
Plugin URI: https://wearemadhouse.wordpress.com/portfolio/madhouse-availability/
Description: This plugin extends items with custom attributes to store dates of availability
Version: 1.1.0
Author: Madhouse
Author URI: http://wearemadhouse.wordpress.com
Short Name: madhouse_availability
Plugin update URI: madhouse-availability
*/

/*
 * ==========================================================================
 *  LOADING
 * ==========================================================================
 */

require_once __DIR__ . "/vendor/composer_components/madhouse/autoloader/autoload.php";

/**
 * Makes this plugin the first to be loaded.
 * - Bumps this plugin at the top of the active_plugins stack.
 */
function mdh_availability_bump_me()
{
    if(OC_ADMIN) {
        // @legacy : ALWAYS remove this if active.
        if(osc_plugin_is_enabled("madhouse_utils/index.php")) {
            Plugins::deactivate("madhouse_utils/index.php");
        }

        // Sanitize & get the {PLUGIN_NAME}/index.php.
        $path = str_replace(osc_plugins_path(), '', osc_plugin_path(__FILE__));

        if(osc_plugin_is_installed($path)) {
            // Get the active plugins.
            $plugins_list = unserialize(osc_active_plugins());
            if(!is_array($plugins_list)) {
                return false;
            }

            // Remove $path from the active plugins list
            foreach($plugins_list as $k => $v) {
                if($v == $path) {
                    unset($plugins_list[$k]);
                }
            }

            // Re-add the $path at the beginning of the active plugins.
            array_unshift($plugins_list, $path);

            // Serialize the new active_plugins list.
            osc_set_preference('active_plugins', serialize($plugins_list));

            if(Params::getParam("page") === "plugins" && Params::getParam("action") === "enable" && Params::getParam("plugin") === $path) {
                //osc_redirect_to(osc_admin_base_url(true) . "?page=plugins");
            } else {
                osc_redirect_to(osc_admin_base_url(true) . "?" . http_build_query(Params::getParamsAsArray("get")));
            }
        }
    }
}

if(!function_exists("mdh_utils") || (function_exists("mdh_utils") && (mdh_utils() === true || strnatcmp(mdh_utils(), "1.20") === -1))) {
	mdh_availability_bump_me();
} else {

	/*
	 * ==========================================================================
	 *  INSTALL / UNINSTALL
	 * ==========================================================================
	 */

	/**
	 * (hook:install)
	 *
	 * @return void
	 */
	function mdh_availability_install() {
		Madhouse_Availability_Plugin::install();
	}
	osc_register_plugin(osc_plugin_path(__FILE__), "mdh_availability_install");

	/**
	 * (hook:uninstall)
	 *
	 * @return void
	 */
	function mdh_availability_uninstall() {
		Madhouse_Availability_Plugin::uninstall();
	}
	osc_add_hook(osc_plugin_path(__FILE__)."_uninstall", "mdh_availability_uninstall");

	/*
	 * Init the plugin.
	 *
	 * Upgrade if needed.
	 */
	Madhouse_Availability_Plugin::init();

	/**
	 * (hook:configure)
	 *
	 * @return  void
	 */
	osc_add_hook(osc_plugin_path(__FILE__)."_configure", function() {
		// Standard configuration page for plugin which extend item's attributes
		osc_plugin_configure_view(osc_plugin_path(__FILE__));
	});

	/*
	 * ==========================================================================
	 *  SEARCH
	 * ==========================================================================
	 */

	// When searching, add some conditions
	osc_add_hook('search_conditions', function ($p) {
		$ok = 0;

		$cat  = Params::getParam('sCategory');
		if(!is_array($cat)) {
			if($cat == '') {
				$cat = array();
			} else {
				$cat = explode(",",$cat);
			}
		}

		if (count($cat) > 0) {
			foreach (mdh_search_category_id($cat) as $v) {
				if (osc_is_this_category(mdh_current_plugin_name(), $v)) {
					$ok = 1;
				}
			}
		}

		if($ok && (Params::getParam('availabilityStart') != "" || Params::getParam('availabilityEnd'))) {
			$mAvailability = Madhouse_Availability_Model::newInstance();

			$dAvailabilityStart = "";

			// Filter: availabilityStart
			if (Params::getParam('availabilityStart') != "") {
				if (osc_get_preference("search_include_past_item", "plugin_madhouse_availability") > 0) {
					// search_include_past_item lets you be more flexible.
					$dAvailabilityStart = date('Y-m-d', strtotime(Params::getParam('availabilityStart')."+".osc_get_preference("search_include_past_item", "plugin_madhouse_availability")." days"));
				} else {
					$dAvailabilityStart = Params::getParam('availabilityStart');
				}

				// Select all items where 'd_start <= $availabilityStart + search_include_past_item (if set)'
				$mAvailability->setVal('d_start', $dAvailabilityStart, '<=');
			}

			// Filter: availabilityEnd
			if (Params::getParam('availabilityEnd') != "") {
				// If 'availabilityEnd' is set:
				if (is_numeric(Params::getParam('availabilityEnd'))) {
					// 'availabilityEnd' is a duration (eg. X months), making computing to find
					// end date that corresponds.
					$mAvailability->setDurationMin(Params::getParam('availabilityEnd'));
				} else {
					// Select all items where 'd_end >= $availabilityEnd || d_end IS NULL'
					$mAvailability->setEnd(Params::getParam('availabilityEnd'));
				}
			} elseif ($dAvailabilityStart != "") {
					// If no 'availabilityEnd' and 'availabilityStart' exists:
					// Filter on 'd_end' to select items where 'd_end >= availabilityStart || d_end IS NULL'
					$mAvailability->setEnd($dAvailabilityStart);
			}

			// Add conditions to current search query.
			$mAvailability->addSQL();

			// Export variables to view.
			$view = View::newInstance();
			$view->_exportVariableToView('search_availability_start', Params::getParam('availabilityStart'));
			$view->_exportVariableToView('search_availability_end', Params::getParam('availabilityEnd'));
		}
	});

	// When searching, display an extra form with our plugin's fields
	osc_add_hook('search_form', function ($catId = null) {
		// We received the categoryID
		if(osc_get_preference('form_search_position', "plugin_madhouse_availability") != 0 && $catId!=null) {
			// We check if the category is the same as our plugin
			foreach($catId as $id) {
				if (!is_numeric($id)) {
					$cat = Category::newInstance()->findBySlug($id);
					$id = $cat["pk_i_id"];
				}
				if(osc_is_this_category(mdh_current_plugin_name(), $id)) {
					Madhouse_Utils_Controllers::doViewPart('search.php');
					break;
				}
			}
		}
	}, osc_get_preference('form_search_position', "plugin_madhouse_availability"));

	/*
	 * ==========================================================================
	 *  EXPORT VARIABLE / SHOW DETAIL
	 * ==========================================================================
	 */

	osc_add_hook("before_html", function() {
		if (osc_is_search_page() || osc_is_list_items()) {
			$catId = osc_search_category_id();
			if (is_array($catId)) {
				foreach ($catId as $value) {
					if(osc_is_this_category(mdh_current_plugin_name(), $value) && !View::newInstance()->_exists('availability')) {
						osc_reset_items();
						$data = array();
						while(osc_has_items()) {
							$detail = Madhouse_Availability_Model::newInstance()->findByPrimaryKey( osc_item_id() );
							if(isset($detail['fk_i_item_id'])) {
								$data[osc_item_id()] = $detail;
							}
						}
						View::newInstance()->_exportVariableToView('availability', $data);
					}
				}
			}
		}
	});

	osc_add_hook('show_item', function() {
		if(osc_is_this_category(mdh_current_plugin_name(), osc_item_category_id())) {
			$detail = Madhouse_Availability_Model::newInstance()->findByPrimaryKey( osc_item_id() );
			$data[osc_item_id()] = $detail;
			if(isset($detail['fk_i_item_id'])) {
				View::newInstance()->_exportVariableToView('availability', $data);
			}
		}
	});

	// Show an item special attributes
	osc_add_hook('item_detail', function() {
		if(osc_get_preference('detail_position', "plugin_madhouse_availability") != 0 && osc_is_this_category(mdh_current_plugin_name(), osc_item_category_id())) {
			$detail = Madhouse_Availability_Model::newInstance()->findByPrimaryKey( osc_item_id() );
			$data[osc_item_id()] = $detail;
			if(isset($detail['fk_i_item_id'])) {
				Madhouse_Utils_Controllers::doViewPart('detail.php');
			}
		}
	}, osc_get_preference('detail_position', "plugin_madhouse_availability"));

	/*
	 * ==========================================================================
	 *  POST FORM / CONTROLLER
	 * ==========================================================================
	 */

	// When publishing an item we show an extra form with more attributes
	osc_add_hook('item_form', function($cat_id = '') {
		if(
			osc_is_this_category(mdh_current_plugin_name(), $cat_id) &&
			osc_get_preference('form_post_position', "plugin_madhouse_availability") != 0
		) {
			if (OC_ADMIN) {
				mdh_current_plugin_path('views/admin/edit.php');
			} else {
				Madhouse_Utils_Controllers::doViewPart('edit.php');
			}
		}
	}, osc_get_preference('form_post_position', "plugin_madhouse_availability"));

	// To add that new information to our custom table
	osc_add_hook('posted_item', function($item) {
		$catId = $item['fk_i_category_id'];
		// We received the categoryID and the Item ID
		if($catId!=null) {
			// We check if the category is the same as our plugin
			if(osc_is_this_category(mdh_current_plugin_name(), $catId)) {
				$aParams = _getAvailabilityParameters();
				$aParams['fk_i_item_id'] = $item['fk_i_item_id'];
				// Insert the data in our plugin's table
				Madhouse_Availability_Model::newInstance()->insert($aParams);
			}
		}
	});

	/*
	 * ==========================================================================
	 *  EDIT FORM / CONTROLLER
	 * ==========================================================================
	 */

	// Edit an item special attributes
	osc_add_hook('item_edit', function($catId = null, $item_id = null) {
		if(
			osc_is_this_category(mdh_current_plugin_name(), $catId) &&
			osc_get_preference('form_edit_position', "plugin_madhouse_availability") != 0 &&
			!is_null($item_id)
		) {

				$data[$item_id] = Madhouse_Availability_Model::newInstance()->findByPrimaryKey( $item_id );
				View::newInstance()->_exportVariableToView('availability', $data);

				//export item to use osc_item_id
				$item     = Item::newInstance()->findByPrimaryKey($item_id);
				View::newInstance()->_exportVariableToView('item', $item);

				if (OC_ADMIN) {
					mdh_current_plugin_path('views/admin/edit.php');
				} else {
					Madhouse_Utils_Controllers::doViewPart('edit.php');
				}
		}
	}, osc_get_preference('form_edit_position', "plugin_madhouse_availability"));

	// Edit an item special attributes POST
	osc_add_hook('edited_item', 'mdh_update_availability');

	/*
	 * ==========================================================================
	 *  AFTER RENEW
	 * ==========================================================================
	 */

	// previous to insert item
	osc_add_hook('after_renew_item', 'mdh_update_availability');

	/*
	 * ==========================================================================
	 *  AFTER RESTART
	 * ==========================================================================
	 */

	// previous to insert item
	osc_add_hook('after_restart_item', 'mdh_update_availability');

	/*
	 * ==========================================================================
	 *  DELETE CONTROLLER
	 * ==========================================================================
	 */

	//Delete item
	osc_add_hook('delete_item', function($item_id) {
		Madhouse_Availability_Model::newInstance()->deleteByPrimaryKey($item_id);
	});

	/*
	 * ==========================================================================
	 *  PRE POST
	 * ==========================================================================
	 */

	function mdm_availability_pre_item($aItem) {
		if(osc_is_this_category(mdh_current_plugin_name(), Params::getParam('catId') )) {
			$aParams = _getAvailabilityParameters();
			foreach( $aParams as $key => $value ) {
				Session::newInstance()->_setForm('pp_'.$key, $value);
				Session::newInstance()->_keepForm('pp_'.$key);
			}
		}
	}

	// previous to insert item
	osc_add_hook('pre_item_add',  'mdm_availability_pre_item' );
	osc_add_hook('pre_item_edit', 'mdm_availability_pre_item' );

	/*
	 * ==========================================================================
	 *  POST, EDIT HELPER
	 * ==========================================================================
	 */

	function mdh_update_availability($item=null) {

		if(
			osc_is_this_category(mdh_current_plugin_name(), $item['fk_i_category_id']) &&
			!is_null($item)
		) {
			$result   = Madhouse_Availability_Model::newInstance()->findByPrimaryKey( $item['pk_i_id'] );

			if($result === false) {
				$aParams = _getAvailabilityParameters();
				$aParams['fk_i_item_id'] = $item['fk_i_item_id'];
				// Insert the data in our plugin's table
				Madhouse_Availability_Model::newInstance()->insert($aParams);
			} else {

				$aParams = _getAvailabilityParameters();

				// Test if nothing is passed
				$now =  date("Y-m-d");
				$dStart = date("Y-m-d", strtotime($result['d_start']));
				if ( $now > $dStart  && !Params::existParam("availabilityStart")) {
					$aParams['d_start'] =$now;
				}

				$dEnd = date("Y-m-d", strtotime($result['d_end']));
				if ( $now > $dEnd  && !Params::existParam("availabilityEnd")) {
					$aParams['d_end'] = null;
				}

				Madhouse_Availability_Model::newInstance()->updateByPrimaryKey($aParams, $item['fk_i_item_id']);
			}
		}
	}


	function _getAvailabilityParameters() {

		// aaaa-mm-dd
		$regex = "/^(19[0-9][0-9]|20[0-9][0-9])[\/\.\-](0[1-9]|1[0-2])[\/\.\-](0[1-9]|1[0-9]|2[0-9]|3[0-1])$/";
		$now   = date("Y-m-d");
		$array = array();

		if(Params::existParam("availabilityStart")) {
			$data = Params::getParam('availabilityStart');

			// If preg match and start > now
			if (preg_match($regex, $data) && date("Y-m-d", strtotime($data)) > $now) {
				$array['d_start'] = $data;
			} else {
				$array['d_start'] = $now;
			}
		}

		if(Params::existParam("availabilityEnd")) {
			$data = Params::getParam('availabilityEnd');
			// If preg match and end > start
			if (preg_match($regex, $data) && date("Y-m-d", strtotime($data)) > date("Y-m-d", strtotime(Params::getParam('availabilityStart')))) {
				$array['d_end'] =  $data;
			} elseif(is_numeric($data)) {
				$array['d_end'] =  date('Y-m-d', strtotime($array['d_start']."+".$data." days"));
			} else {
				$array['d_end'] = null;
			}
		}

		return $array;
	}


	/*
	 * ==========================================================================
	 *  USER AND ADMIN MENU
	 * ==========================================================================
	 */

	/**
	 * Adds a submenu to the Madhouse main admin menu.
	 * (hook: admin_menu_init)
	 */

	osc_add_hook('admin_menu_init', function () {
		osc_add_admin_submenu_divider('madhouse', __("Availability", mdh_current_plugin_name()), mdh_current_plugin_name(), 'administrator');
		osc_add_admin_submenu_page('madhouse', __('Categories', mdh_current_plugin_name()), osc_plugin_configure_url(mdh_current_plugin_name(true)), mdh_current_plugin_name() . '_configure', 'administrator');
		osc_add_admin_submenu_page('madhouse', __('Settings', mdh_current_plugin_name()), mdh_availability_url(), mdh_current_plugin_name() . '_settings', 'administrator');
	});

	/*
	 * ==========================================================================
	 *  ROUTES
	 * ==========================================================================
	 */

	osc_add_hook("renderplugin_controller", function()
	{
		if(preg_match('/^' . mdh_current_plugin_name() . '.*$/', Params::getParam("route"))) {

			// Enqueue style for admin only.
		    osc_add_hook("admin_header", function() {
	            osc_enqueue_style(mdh_current_plugin_name() . "_admin", mdh_current_plugin_url("assets/css/admin.css"));
	        });

			$filter =  function($string) {
				return __("Madhouse Availability", mdh_current_plugin_name());
			};

			// Page title (in <head />)
			osc_add_filter("admin_title", $filter);

			// Page title (in <h1 />)
			osc_add_filter("custom_plugin_title", $filter);

			osc_add_filter("admin_body_class", function($classes) {
				array_push($classes, "madhouse");
				return $classes;
			});

			// Add a .row-offset to wrapping <div /> element.
			osc_add_filter("render-wrapper", function($string) {
				return "row-offset";
			});

			mdh_current_plugin_path("classes/Madhouse/Availability/Controllers/Admin.php");
			$do = new Madhouse_Availability_Controllers_Admin();
			$do->doModel();
		}
	});

	osc_add_route(
		mdh_current_plugin_name(),
		'availability/?',
		'availability/',
		mdh_current_plugin_name() . '/views/admin/settings.php'
	);

	osc_add_route(
		mdh_current_plugin_name() . "_do",
		'availability/do/?',
		'availability/do/',
		mdh_current_plugin_name() . '/views/admin/settings.php'
	);
}

?>