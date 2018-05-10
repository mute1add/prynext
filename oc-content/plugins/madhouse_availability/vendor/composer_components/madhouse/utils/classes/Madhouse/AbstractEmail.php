<?php

/**
 * Base class for emails sent by Osclass.
 *
 * Use it as a base class (extend it) for your e-mail notifications. There's pre-defined
 * words that will be automatically replaced (available in each and every e-mails).
 * Those words are :
 *     {WEB_URL}, {WEB_TITLE}, {WEB_LINK}, {CURRENT_DATE}, {HOUR}, {IP_ADDRESS}
 *
 * @author Madhouse Design Co.
 * @package Madhouse
 * @since 1.00
 * @see oc-includes/osclass/utils.php#osc_mailBeauty
 * @deprecated Use Madhouse_EmailBase now.
 */
abstract class Madhouse_AbstractEmail {

	/**
	 * (Array<Any>) Associative array of e-mail information.
	 * 	That's the array returned by the Page DAO object.
	 */
	private $_page;

	/**
	 * (Array<String>) Words that will be replaced on sending this e-mail.
	 * 	Words are like variables that will be replaced by value when sending the e-mail.
	 *	For example, if '{WEB_TITLE}' is found in the body of this e-mail:
	 * 		It will be replace by the title of the Osclass website.
	 */
	private $_words;

	/**
	 * Construct.
	 * @param $internalName name of the e-mail to create.
	 * @since 1.00
	 */
	function __construct($internalName) {
		$this->_page = Page::newInstance()->findByInternalName($internalName);
		$this->_words = array();
	}

	/**
	 * Adds a list of words to be replaced.
	 * 	Order of those words matters because it will define the one of the values given when sending.
	 *	Ex. first word of this array is replaced by first value of the array given to send() method.
	 * @param $words (Array<String>).
	 * @see $this->_send.
 	 * @since 1.00
	 */
	public function withWords($words) {
		$this->_words = $words;
	}

	/**
	 * Gets the internal name of the implementation.
	 * @returns a string
	 * @since 1.10
	 */
	abstract public static function getName();

	/**
	 * Gets the default title of this e-mail.
	 *
	 * Useful for install (insert) the implementation in database.
	 *
	 * @returns a string
	 * @since 1.10
	 */
	abstract public static function getDefaultTitle();

    /**
     * Gets the default content (body) of this e-mail.
     *
     * Useful for install (insert) the implementation in database.
     *
     * @returns a string
     * @since 1.10
     */
	abstract public static function getDefaultContent();

	/**
	 * Sends the e-mail.
	 *
	 *
	 */
//	abstract public function send();

	/**
	 * Gets the title of this e-mail (depending on $locale).
	 * @param $locale the locale we want the title in (default: osc_current_user_locale())
	 * @return a string.
	 * @see osc_current_user_locale
	 */
	public function getTitle($locale=NULL) {
		if($locale == NULL) {
			$locale = osc_current_user_locale();
		}
		return (string) osc_field($this->_page, "s_title", $locale);
	}

	/**
	 * Gets the body of this e-mail (depending on $locale).
	 * @param $locale the locale we want the body in (default: osc_current_user_locale())
	 * @return a string.
	 * @see osc_current_user_locale
	 */
	public function getContent($locale=NULL) {
		if($locale == NULL) {
			$locale = osc_current_user_locale();
		}
		return (string) osc_field($this->_page, "s_text", $locale);
	}

	/**
	 * Gets the words of this e-mail.
	 * @return an array of strings (Array<String>).
	 */
	public function getWords() {
		return $this->_words;
	}

	/**
	 * Sends this email to each $recipients, beautyfying it with $values.
	 * @param $sender (MadhouseUser)
	 * @param $recipient (Array<MadhouseUser>)
	 * @param $values (Array<Scalar>), values that will replace words (@see $this->withWords)
	 * @return void
	 */
	protected function _send($sender, $recipients, $values) {
		$title = osc_mailBeauty($this->getTitle(), array($this->getWords(), $values));
		$body = osc_mailBeauty($this->getContent(), array($this->getWords(), $values));

		foreach($recipients as $r) {
			osc_sendMail(array(
				"subject" => $title,
				"to" => $r->getEmail(),
				"to_name" => $r->getName(),
				"body" => $body,
				"alt_body" => $body
			));
		}
	}

	protected function _sendAdmin($values)
	{
	    $title = osc_mailBeauty($this->getTitle(), array($this->getWords(), $values));
	    $body = osc_mailBeauty($this->getContent(), array($this->getWords(), $values));

    	osc_sendMail(array(
    		"subject" => $title,
    		"from" => osc_contact_email(),
    		"to" => osc_contact_email(),
            "to_name" => __('Admin mail system'),
    		"body" => $body,
    		"alt_body" => $body
    	));
	}
}

?>