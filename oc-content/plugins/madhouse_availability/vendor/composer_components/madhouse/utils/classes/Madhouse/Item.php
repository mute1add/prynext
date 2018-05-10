<?php

/**
 * Represents an Item.
 * 	Simple wrapper around the associative array of an item.
 * @author Madhouse
 * @since 1.00
 */
class Madhouse_Item {

	/**
	 * (Associative Array) The informations about this item.
	 * 	Actually, this is the associative array returned by Item::findByPrimaryKey($id);
	 */
	private $_item;

	/**
	 * (Associative Array) The informations about the resources of this item.
	 * This is the associative array returned by ItemResource::findByPrimaryKey($id).
	 */
	private $_resource;

	/**
	 * Construct.
	 */
	function __construct($item, $resource) {
		$this->_item = $item;
		$this->_resource = $resource;
	}

	public static function create($item, $resource) {
		$instance = new self($item, $resource);
		return $instance;
	}

	/**
	 * Gets the id of this item.
	 * @return an int.
	 */
	public function getId() {
		return (int) osc_field($this->_item, "pk_i_id", "");
	}

	/**
	 * Gets the title of this item.
	 * @param $locale (opt.) the wanted locale.
	 * @return a string.
	 */
	public function getTitle($locale=NULL) {
		if($locale == NULL) {
			$locale = osc_current_user_locale();
		}
		return (string) osc_field($this->_item, "s_title", $locale);
	}

	public function getPrice() {
		return (string) osc_format_price(osc_field($this->_item, "i_price", ""), $this->getCurrencySymbol());
	}

	public function getCurrencySymbol() {
		$aCurrency = Currency::newInstance()->findByPrimaryKey(
			osc_field($this->_item, "fk_c_currency_code", "")
		);
		return $aCurrency['s_description'];
	}

	/**
	 * Gets the city of this item.
	 * @return a string.
	 */
	public function getCity() {
		return (string) osc_field($this->_item, "s_city", "");
	}

	/**
	 * Returns the id of the category of this item.
	 * @return an int.
	 */
	public function getCategoryId() {
		return (int) osc_field($this->_item, "fk_i_category_id", "");
	}

	/**
	 * Returns the owner of this item.
	 * @return an int.
	 */
	public function getUserId() {
		return (int) osc_field($this->_item, "fk_i_user_id", "");
	}

	public function isSpam() {
		return (osc_field($this->_item, "b_spam", "") == 1);
	}

	public function isEnabled() {
		return (osc_field($this->_item, "b_enabled", "") == 1);
	}

	public function isActive() {
		return (osc_field($this->_item, "b_active", "") == 1);
	}

	public function isPremium() {
		return (osc_field($this->_item, "b_premium", "") == 1);
	}

	public function isExpired() {
		if($this->isPremium()) {
			return false;
		}
		return osc_isExpired(osc_field($this->_item, "dt_expiration", ""));
	}

	/**
	 * Computes the URL of this item.
	 * @return a (url) string.
	 */
	public function getURL() {
		$locale = osc_current_user_locale();
		if(osc_rewrite_enabled()) {
			$url = osc_get_preference('rewrite_item_url');
		    if(preg_match('|{CATEGORIES}|', $url)) {
				$sanitized_categories = array();
				$cat = Category::newInstance()->hierarchy($this->getCategoryId());
				for ($i = (count($cat)); $i > 0; $i--) {
				    $sanitized_categories[] = $cat[$i - 1]['s_slug'];
				}
				$url = str_replace('{CATEGORIES}', implode("/", $sanitized_categories), $url);
		    }
		    $url = str_replace('{ITEM_ID}', osc_sanitizeString($this->getId()), $url);
		    $url = str_replace('{ITEM_CITY}', osc_sanitizeString($this->getCity()), $url);
		    $url = str_replace('{ITEM_TITLE}', osc_sanitizeString($this->getTitle()), $url);
		    $url = str_replace('?', '', $url);

		    if($locale!='') {
		        $path = osc_base_url() . $locale . "/" . $url;
		    } else {
		        $path = osc_base_url() . $url;
		    }
		} else {
		    $path = osc_item_url_ns($this->getId(), $locale);
		}
		return $path;
	}

	public function hasResource() {
		if($this->_resource == NULL) {
			return false;
		}
		return true;
	}

	public function getThumbnailURL() {
		return osc_base_url() .
			   osc_field($this->_resource, "s_path", "") .
			   osc_field($this->_resource, "pk_i_id", "") .
			   "_thumbnail." .
			   osc_field($this->_resource, "s_extension", "");
	}

	public function toArray()
	{
	    return array_merge(
	    	$this->_item,
	    	array(
	    		"s_url" => $this->getURL(),
	    		"b_expired" => $this->isExpired()
	    	)
	    );
	}
}

?>