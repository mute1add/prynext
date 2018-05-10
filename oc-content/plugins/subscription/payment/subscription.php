<?php if(!osc_is_web_user_logged_in()){ header("location:".osc_user_login_url());die; }
/* 
Plugin Name: Subscription Management
Plugin URI:  http://osclassplugin.com
Version: 1.0  
File Description :This page is implemented to check for witch payment method user prefered.
*/
require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'paypal.class.php';
$conn = getConnection() ;
////////////////////////////////////////////////////////////////////////////////////////////////////////////

if(Params::getParam('pkgId')!=''){  
  $pkgId = Params::getParam('pkgId');
  if($pkgId){
   $packageIfo = $conn->osc_dbFetchResult("select * from %st_packages where package_id=%d AND status='1' ",DB_TABLE_PREFIX,$pkgId);
   //print_r($packageIfo);
    Session::newInstance()->_set('PKGINFO',$packageIfo);	
	//echo osc_logged_user_id();die;
  }
} 


 

$paymentMode = (Params::getParam('payment_mode')!='')?Params::getParam('payment_mode'):'';//osc_get_preference('subcription_payment_mode','payment_mode');
if($paymentMode=='offline_payment'){
header("location:".osc_render_file_url(osc_plugin_folder(__FILE__) . 'offlinesubscription.php'));die;
 

}
else if($paymentMode=='paypal_payment')
{ 
 
$p = new paypal_class;    // initiate an instance of the class
if(osc_get_preference('paypal_sandbox_mode','payment_mode')==1){
	$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
}else{
	$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url
 }           
// setup a variable for this script (ie: 'http://www.micahcarrick.com/paypal.php')
$this_script = osc_render_file_url(osc_plugin_folder(__FILE__) . 'subscription.php');

// if there is not action variable, set the default action of 'process'
if (empty($_GET['action'])) $_GET['action'] = 'process';  

switch ($_GET['action']) {
    
   case 'process':       
   
   
      // $p->add_field('first_name', $_POST['first_name']);
      // $p->add_field('last_name', $_POST['last_name']);
      
      $p->add_field('business', osc_get_preference('paypal_api_email','payment_mode'));
      $p->add_field('return', $this_script.'?action=success&payment_mode=paypal_payment');
      $p->add_field('cancel_return', $this_script.'?action=cancel');
      $p->add_field('notify_url', $this_script.'?action=ipn');
      $p->add_field('item_name',$packageIfo['package_name']);
      $p->add_field('amount', $packageIfo['package_cost']);
	  $p->add_field('currency_code',current(explode(':',$packageIfo['currency_code'])));

      $p->submit_paypal_post(); // submit the fields to paypal
      //$p->dump_fields();      // for debugging, output a table of all the fields
      break;
      
   case 'success':      // Order was successful...
   
      // This is where you would probably want to thank the user for their order
      // or what have you.  The order information at this point is in POST 
      // variables.  However, you don't want to "process" the order until you
      // get validation from the IPN.  That's where you would have the code to
      // email an admin, update the database with payment status, activate a
      // membership, etc.  
 
       //echo "<html><head><title>Success</title></head><body><h3>Thank you for your order.</h3>";
	   //print_r($_POST);die;
	   if(Session::newInstance()->_get('PKGINFO')){
	    $pkgInfo = Session::newInstance()->_get('PKGINFO'); 
		
		////////////////////Calculate Expiry date of a package///////////////
		if($pkgInfo['period_type']=='days'){
			$expiryDate = date('Y-m-d',time()+(60*60*24*$pkgInfo['expiry_days']));
		}else if($pkgInfo['period_type']=='month'){
		    $expiryDate = date('Y-m-d',time()+(60*60*24*30*$pkgInfo['expiry_days']));
		}else if($pkgInfo['period_type']=='year'){
			$expiryDate = date('Y-m-d',time()+(60*60*24*365*$pkgInfo['expiry_days']));  
		}
	   /////////////////////////////////////////////////////////////////////	
		 	    
	   $sql = $conn->osc_dbExec("insert into %st_user_subscription set user_id=%d, package_id=%d,package_name='%s',package_cost='%s',currency_code='%s', post_allow=%d, remaining_post=%d, transaction_id='%s',expiry_date='%s', transaction_type='paypal'",DB_TABLE_PREFIX,osc_logged_user_id(),$pkgInfo['package_id'],$pkgInfo['package_name'],$pkgInfo['package_cost'],$pkgInfo['currency_code'], $pkgInfo['post_allow'],  $pkgInfo['post_allow'],$_POST['txn_id'],$expiryDate);	   
	
	//////////////////////////Send Mail///////////////////////////////// 
	 	$mPages = new Page() ;
		$aPage = $mPages->findByInternalName('subscription_email') ;
		$locale = osc_current_user_locale() ;
		$content = array();
		if(isset($aPage['locale'][$locale]['s_title'])) {
			$content = $aPage['locale'][$locale];
		} else {
			$content = current($aPage['locale']);
		}
		$words   = array();
	    $words[] = array('{WEB_TITLE}','{PKG_TITLE}','{CONTACT_NAME}','{PKG_COST}','{POST_ALLOW}','{TXC_ID}','{EXPIRY_DATE}');	
		$words[] = array(osc_page_title(), $pkgInfo['package_name'], osc_logged_user_name(), $pkgInfo['package_cost'].' '.end(explode(':',$pkgInfo['currency_code'])),$pkgInfo['post_allow'],$_POST['txn_id'],$expiryDate);
		
		$title = osc_mailBeauty($content['s_title'], $words) ;
        $body  = osc_mailBeauty($content['s_text'], $words) ;
		//echo $title."<br><br>".$body;die;
    
        $emailParams =  array('subject'  => $title
							 ,'from'     => osc_contact_email()
                             ,'to'       => osc_logged_user_email()
                             ,'to_name'  => osc_logged_user_name()
                             ,'body'     => $body
                             ,'alt_body' => $body);

        osc_sendMail($emailParams);
			
	///////////////////////////////////////////////////////////////////////////////////////
	
	 ////////////Drop Session //////////////////////  
	 Session::newInstance()->_drop('PKGINFO');  
	 
	 osc_add_flash_ok_message( _m('Thank you, Your order has been successfull.'));
	 header("location:".osc_render_file_url("subscription/subscribedpkg.php"));die;     
	 
	 }else{
	    osc_add_flash_warning_message( _m('Something went wrong.'));
		header("location:".osc_render_file_url("subscription/package_list.php"));die;        
	 }
	     
      // You could also simply re-direct them to another page, or your own 
      // order status page which presents the user with the status of their
      // order based on a database (which can be modified with the IPN code 
      // below).
      
      break;
      
   case 'cancel':       // Order was canceled...

      // The order was canceled before being completed.
 
      echo "<html><head><title>Canceled</title></head><body><h3>The order was canceled.</h3>";
      echo "</body></html>";
      
      break;
      
   case 'ipn':          // Paypal is calling page for IPN validation...
   
      // It's important to remember that paypal calling this script.  There
      // is no output here.  This is where you validate the IPN data and if it's
      // valid, update your database to signify that the user has payed.  If
      // you try and use an echo or printf function here it's not going to do you
      // a bit of good.  This is on the "backend".  That is why, by default, the
      // class logs all IPN data to a text file.
      
      if ($p->validate_ipn()) {
          
         // Payment has been recieved and IPN is verified.  This is where you
         // update your database to activate or process the order, or setup
         // the database with the user's order details, email an administrator,
         // etc.  You can access a slew of information via the ipn_data() array.
  
         // Check the paypal documentation for specifics on what information
         // is available in the IPN POST variables.  Basically, all the POST vars
         // which paypal sends, which we send back for validation, are now stored
         // in the ipn_data() array.
  
         // For this example, we'll just email ourselves ALL the data.
         $subject = 'Instant Payment Notification - Recieved Payment';
         $to = osc_contact_email();    //  your email
         $body =  "An instant payment notification was successfully recieved\n";
         $body .= "from ".$p->ipn_data['payer_email']." on ".date('m/d/Y');
         $body .= " at ".date('g:i A')."\n\nDetails:\n";
         
         foreach ($p->ipn_data as $key => $value) { $body .= "\n$key: $value"; }
         mail($to, $subject, $body);
      }
      break;
 }
 
 
 }
 
?>