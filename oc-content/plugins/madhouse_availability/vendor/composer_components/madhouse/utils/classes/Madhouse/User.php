<?php

/**
 * Represents a User as an object.
 * 	OSClass works with associative arrays.
 *	Therefore, this class is OO approach of a User.
 *	It also make use of the avatar (@see madhouse_user_resource plugin).
 * @since 1.00
 */
class Madhouse_User {
	private $_user;
	private $_avatar;

	/**
	 * Construct.
	 */
	function __construct($user) {
		$this->_user = $user;
		if(function_exists("madhouse_ur_nav_ur")) {
			$this->_avatar = madhouse_ur_nav_url($this->getId(), "_nav");
		}
		if(function_exists("mdh_avatar_nav_url")) {
			$this->_avatar = mdh_avatar_nav_url($this->getId(), "_nav");
		}
	}

	public static function create($user) {
		$instance = new self($user);
		return $instance;
	}

	/**
	 * Gets the id of this user.
	 * @return an int.
	 */
	public function getId() {
		return osc_field($this->_user, "pk_i_id", "");
	}

	/**
	 * Gets the name of this user.
	 * @return a string.
	 */
	public function getName() {
		return osc_field($this->_user, "s_name", "");
	}

	/**
	 * Gets the username of this user.
	 * @return an int.
	 */
	public function getUsername() {
		return strtolower(osc_field($this->_user, "s_username", ""));
	}

	/**
	 * Gets the username of this user.
	 * @return an int.
	 */
	public function getEmail() {
		return osc_field($this->_user, "s_email", "");
	}

	public function hasMobilePhone() {
		$mp = osc_field($this->_user, "s_phone_mobile", "");
		return (isset($mp) && !empty($mp));
	}

	public function getMobilePhone() {
		return osc_field($this->_user, "s_phone_mobile", "");
	}

	/**
	 * Gets the date of last access.
	 * @return a date.
	 */
	public function getLastAccess() {
		return osc_field($this->_user, "dt_access_date", "");
	}

	/**
	 * Gets the avatar path of this user.
	 * @return an int.
	 */
	public function getAvatar() {
		return $this->_avatar;
	}

	/**
	 * Returns the URL of the public profile of this user.
	 * @returns a string
	 */
	public function getURL() {
		return osc_user_public_profile_url($this->getId());
	}

    /**
     * Is this user a fake user (dead user).
     * @return true if fake, false otherwise.
     * @since 1.11
     */
    public function isFake() {
        return ($this->getId() === 0);
    }

	/**
	 * Serialize the object as an associative array.
	 * @return an array (with key => value pairs).
	 */
	public function toArray() {
		return array(
			"id" => $this->getId(),
			"name" => $this->getName(),
			"username" => $this->getUsername(),
			"avatar" => $this->getAvatar(),
			"url" => $this->getURL(),
			"last_access" => array(
				"raw" => $this->getLastAccess(),
				"formatted" => osc_format_date($this->getLastAccess()) . " " . date(osc_time_format(), strtotime($this->getLastAccess())),
			),
			"is_fake" => $this->isFake()
		);
	}
}

?>
