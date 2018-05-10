<?php

/**
 * Deal with mails never been as easy as with this.
 *
 * This class contains common utilities to deal with Osclass email
 * notifications in your plugin : install, uninstall, etc.
 *
 * @package Madhouse
 * @subpackage Utils
 * @see Madhouse_AbstractEmails
 * @since 1.00
 */
class Madhouse_Utils_Emails
{
	/**
	 * Install a new e-mail into the Osclass database.
	 * @param  String|Madhouse_AbstractEmail $nameOrClass Since 1.18 - the internal name of the e-mail to install
	 *                                                    Before - Class name that defines the e-mail.
	 * @param  [type] $dtitle      Default title for this e-mail.
	 * @param  [type] $dcontent    Default content for this e-mail.
	 * @return void
	 * @since  1.00
	 */
	public static function install($nameOrClass, $dtitle=null, $dcontent=null) {
		if(class_exists($nameOrClass) && is_subclass_of($nameOrClass, "Madhouse_AbstractEmail")) {
			$name = $nameOrClass::getName();
			$dtitle = $nameOrClass::getDefaultTitle();
			$dcontent = $nameOrClass::getDefaultContent();
		} else {
			$name = $nameOrClass;
		}

		// Gets the Page data-access object (an emails is a special static page)
		$pageDAO = Page::newInstance();

		$descriptions = array();
		foreach (osc_listLanguageCodes() as $code) {
			$descriptions[$code] =
			array("s_title" => $dtitle, "s_text" => $dcontent);
		}

		$isInserted = $pageDAO->insert(
			array(
				"s_internal_name" => $name,
				"b_indelible" => true,
				"b_link" => true
				),
			$descriptions
		);

		if(! $isInserted) {
			throw new Exception($pageDAO->getErrorDesc());
		}
	}

	/**
	 * Uninstall the e-mail from the Osclass database.
	 * @param  String|Madhouse_AbstractEmail $nameOrClass Since 1.18 - the internal name of the e-mail to install
	 *                                                    Before - Class name that defines the e-mail.
	 * @return void.
	 * @since  1.00
	 */
	public static function uninstall($nameOrClass) {
		if(class_exists($nameOrClass) && is_subclass_of($nameOrClass, "Madhouse_AbstractEmail")) {
			$name = $nameOrClass::getName();
		} else {
			$name = $nameOrClass;
		}

	    // Gets the Page data-access object (an emails is a special static page)
		$pageDAO = Page::newInstance();

        // Get the e-mail.
		$pageInfo = $pageDAO->findByInternalName($name);
		if(! empty($pageInfo)) {
            // Delete descriptions (title and body) in every language.
			$pageDAO->dao->delete(
				DB_TABLE_PREFIX.'t_pages_description',
				array('fk_i_pages_id' => $pageInfo['pk_i_id']));

            // Delete the email itself.
			$pageDAO->dao->delete(
				DB_TABLE_PREFIX.'t_pages',
				array('pk_i_id' => $pageInfo['pk_i_id']));
		}
	}

	/**
	 * Send an e-mail and applies filters if necessary.
	 * @param Array<String, Any> $values Array of (word => value) couples.
	 *                           Words are '{WORD}' in text and values, the values to replace it with.
	 * @param Array<Madhouse_User> 	$recipients Array of recipients.
	 * @param Array<Any> $context   Array of elements used in the filters.
	 * @return void.
	 * @since  1.18
	 */
	public static function send($name, $wordsandvalues, $recipients=null, $context=null, $locale=null)
	{
		// Retrieve the e-mail from the database.
		$page = Page::newInstance()->findByInternalName($name);

		// Find the proper locale and corresponding title and content.
		if(is_null($locale)) {
			$locale = osc_current_user_locale();
		}
		$title = osc_field($page, "s_title", $locale);
		$content = osc_field($page, "s_text", $locale);

		// Split words and values as send mail / beauty mail requires it.
		$words = array_keys($wordsandvalues);
		$values = array_values($wordsandvalues);

		// Beautyfy title (replace words by values).
		$title = osc_apply_filter(
			$name . "_title_after",
			osc_mailBeauty(
				osc_apply_filter(
					"email_title",
					osc_apply_filter(
						$name . "_title_before",
						$title,
						$recipients,
						$context
					)
				),
				array(
					$words,
					$values
				)
			),
			$recipients,
			$context
		);

		// Beautyfy content (replace words by values).
		$body = osc_apply_filter(
			$name . "_description_after",
			osc_mailBeauty(
				osc_apply_filter(
					"email_description",
					osc_apply_filter(
						$name . "_description_before",
						$content,
						$recipients,
						$context
					)
				),
				array(
					$words,
					$values
				)
			),
			$recipients,
			$context
		);

		if(empty($recipients)) {
			// Send the email to admin.
			osc_sendMail(array(
				"subject" => $title,
				"from" => osc_contact_email(),
				"to" => osc_contact_email(),
				"to_name" => __('Admin mail system'),
				"body" => $body,
				"alt_body" => $body
				));
		} else {
			// Send the email to each recipients.
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
	}
}

?>