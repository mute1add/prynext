<?php if (!defined('OC_ADMIN')) exit('Direct access is not allowed.');
/*
Plugin Name: Subscription Management
Plugin URI:  http://osclassplugin.com
Description: This plugin allows admin to make adding post management in OSclass as paid process. Users will purchase a package and add then add posts in the site
Version: 1.0  
Author:  Efusionsoft Technology Pvt. Ltd. 
Author URI: http://www.efusionsoft.com/
Short Name: subscription
Plugin update URI: subscription
*/
$dateFormat = osc_get_preference('dateFormat','osclass');
define('dateFormat',$dateFormat);
function subscription_after_install() {
        $conn = getConnection() ;
        $conn->autocommit(false);
        try {
            $path = osc_plugin_resource('subscription/struct.sql');
            $sql = file_get_contents($path);
            $conn->osc_dbImportSQL($sql);
            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            echo $e->getMessage();
        }
		osc_set_preference('subcription_payment_mode','offline_payment', 'payment_mode', 'STRING');
		$conn->osc_dbExec("INSERT INTO %st_pages (s_internal_name, b_indelible, dt_pub_date) VALUES ('subscription_email', 1,'%s' )", DB_TABLE_PREFIX, date('Y-m-d H:i:s'));
        $conn->osc_dbExec("INSERT INTO %st_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (%d, '%s', '{WEB_TITLE} -Subscribed Package: {PKG_TITLE}','<p>Hi {CONTACT_NAME}!</p>\r\n<p> </p>\r\n<p>Thanks for your subscription ({PKG_TITLE}) on {WEB_TITLE}.</p>\r\n<p>Your Subscription Details Is Given Below</p>\r\n<p> </p>\r\n<p>Package Name : {PKG_TITLE}</p>\r\n<p>Package Cost : {PKG_COST}</p>\r\n<p>Post Allow : {POST_ALLOW}</p>\r\n<p>Trasaction Id : {TXC_ID}</p>\r\n<p>Expiry Date : {EXPIRY_DATE}</p>\r\n<p> </p>\r\n<p>Thanks</p> ')", DB_TABLE_PREFIX, $conn->get_last_id(), osc_language());
		
		osc_set_preference('subcription_payment_mode', 'offline_payment', 'payment_mode', 'STRING');		
        $conn->autocommit(true);
    }
     
function subscription_call_after_uninstall() {
        $conn = getConnection() ;         
        try {
            $conn->osc_dbExec("DELETE FROM %st_plugin_category WHERE s_plugin_name = 'subscription'", DB_TABLE_PREFIX);
            $conn->osc_dbExec('DROP TABLE %st_packages', DB_TABLE_PREFIX);
			$conn->osc_dbExec('DROP TABLE %st_user_subscription', DB_TABLE_PREFIX); 
			$page_id = $conn->osc_dbFetchResult("SELECT * FROM %st_pages WHERE s_internal_name = 'subscription_email'", DB_TABLE_PREFIX);
			$conn->osc_dbExec("DELETE FROM %st_pages_description WHERE fk_i_pages_id = %d", DB_TABLE_PREFIX, $page_id['pk_i_id']);
			$conn->osc_dbExec("DELETE FROM %st_pages WHERE pk_i_id = %d", DB_TABLE_PREFIX, $page_id['pk_i_id']);
			            
        } catch (Exception $e) {
            $conn->rollback();
            echo $e->getMessage();
        }
		   osc_delete_preference('subcription_payment_mode', 'payment_mode');
		   osc_delete_preference('paypal_sandbox_mode', 'payment_mode');
		   osc_delete_preference('paypal_api_username', 'payment_mode');
		   osc_delete_preference('paypal_api_password', 'payment_mode');
		   osc_delete_preference('paypal_api_signature','payment_mode');
		   osc_delete_preference('paypal_api_email', 'payment_mode');
        $conn->autocommit(true);
    }
	
	
function subscription_admin_configuration() {
        osc_plugin_configure_view(osc_plugin_path(__FILE__));
    }
 
/**
* Create a menu on the admin panel
*/
function subscription_admin_menu()
{
echo '<h3><a href="javascript:;">' . __('Subscription', 'efst_subscription') . '</a></h3>
   <ul> 
        <li><a href="' . osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'packages.php') . '">&raquo; ' . __('Packages', 'subscription') . '</a></li>
     <li><a href="' . osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . '/payment/index.php') . '">&raquo; ' . __('Payment gateway', 'subscription') . '</a></li>
	 <li><a href="' . osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'subscriber.php') . '">&raquo; ' . __('Subscribed Users', 'subscription') . '</a></li>
   </ul>';

}

/**
* Create a new menu option on users dashboards
*/
function package_user_menu() {
echo '<li class="opt_paypal" ><a href="' . osc_render_file_url(osc_plugin_folder(__FILE__)."subscribedpkg.php") . '" >' . __("Subscribed Packages", "subscription") . '</a></li>' ;
echo '<li class="opt_paypal" ><a href="' . osc_render_file_url(osc_plugin_folder(__FILE__)."package_list.php") . '" >' . __("Go To Package Listing", "subscription") . '</a></li>' ;

} 
 
/**
* Check user have remaining post or not while add listing page call 
*/ 

function remaining_post_addlisting_page()
{
 // Check user have remaning post or package has been expired. osc_is_web_user_logged_in()
 if(!osc_is_admin_user_logged_in()){
	 $conn = getConnection() ;
	 $checkSubscriberHavePostOrNot = $conn->osc_dbFetchResult("SELECT * FROM %st_user_subscription where (expiry_date > CURDATE() and remaining_post >0) AND payment_status='confirm' AND user_id=%d order by   transaction_date desc limit 1",DB_TABLE_PREFIX,osc_logged_user_id());
	$countResubscribe = $conn->get_affected_rows();
	if($countResubscribe==0){
		osc_add_flash_warning_message( _m('You need to loggedin and must be subscribed to a package to add "Ads/Posts" in the website.'));	
		header("location:".osc_render_file_url(osc_plugin_folder(__FILE__)."package_list.php"));die;
	}
  }
 
} 

/**
* Decrease the user remaining post  
*/ 
function descrease_the_remaining_post($item)
{
	//print_r($item);
 if(!osc_is_admin_user_logged_in()){
	$conn = getConnection() ;
	$conn->osc_dbExec("UPDATE %st_user_subscription set remaining_post=remaining_post-1 where expiry_date > CURDATE() AND payment_status='confirm' AND user_id=%d",DB_TABLE_PREFIX,osc_logged_user_id());
	$conn->autocommit(true);
 }

}
 
/**
* Daily Cron to expired the package and user subscription  
*/ 

function makepackage_and_user_subscription_expired()
{
 $conn = getConnection() ;  
 $updatesubs = $conn->osc_dbExec("UPDATE %st_user_subscription set payment_status='expired' where expiry_date >= CURDATE()",DB_TABLE_PREFIX);
 $conn->autocommit(true);
}

 
    
	
	osc_register_plugin(osc_plugin_path(__FILE__), 'subscription_after_install');
    osc_add_hook(osc_plugin_path(__FILE__)."_configure", 'subscription_admin_configuration');
    osc_add_hook(osc_plugin_path(__FILE__)."_uninstall", 'subscription_call_after_uninstall');

////////////////////////Add Hooks ////////////////////////
	osc_add_hook('admin_menu', 'subscription_admin_menu');
	osc_add_hook('user_menu', 'package_user_menu');
	osc_add_hook('post_item', 'remaining_post_addlisting_page');
	osc_add_hook('posted_item', 'descrease_the_remaining_post'); 
	osc_add_hook('cron_daily', 'makepackage_and_user_subscription_expired'); 
	    

?>
