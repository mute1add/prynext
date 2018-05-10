<?php if(!osc_is_web_user_logged_in()){ header("location:".osc_user_login_url());die; }
/* 
Plugin Name: Subscription Management
Plugin URI:  http://osclassplugin.com
Version: 1.0  
File Description : This page is implemented for offline subscription.
*/
$conn = getConnection() ;

$offlinetc   = osc_get_preference('offline_payment_tc','payment_mode');

$pkgInfo = Session::newInstance()->_get('PKGINFO'); 


if($_SERVER['REQUEST_METHOD']=='POST' && Params::getParam('confirm')){

if(count(Session::newInstance()->_get('PKGINFO')>0)){
  

	   $txn_id = strtoupper(substr(md5(rand()),5,16));
	   ////////////////////Calculate Expiry date of a package///////////////
		if($pkgInfo['period_type']=='days'){
			$expiryDate = date('Y-m-d',time()+(60*60*24*$pkgInfo['expiry_days']));
		}else if($pkgInfo['period_type']=='month'){
		    $expiryDate = date('Y-m-d',time()+(60*60*24*30*$pkgInfo['expiry_days']));
		}else if($pkgInfo['period_type']=='year'){
			$expiryDate = date('Y-m-d',time()+(60*60*24*365*$pkgInfo['expiry_days']));  
		}
	   /////////////////////////////////////////////////////////////////////		   

	   $sql = $conn->osc_dbExec("insert into %st_user_subscription set user_id=%d, package_id=%d,package_name='%s',package_cost='%s',currency_code='%s', post_allow=%d, remaining_post=%d, transaction_id='%s',expiry_date='%s', transaction_type='offline',payment_status='pending'",DB_TABLE_PREFIX,osc_logged_user_id(),$pkgInfo['package_id'],$pkgInfo['package_name'],$pkgInfo['package_cost'],$pkgInfo['currency_code'], $pkgInfo['post_allow'], $pkgInfo['post_allow'],$txn_id,$expiryDate);	   

	

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

		$words[] = array(osc_page_title(), $pkgInfo['package_name'], osc_logged_user_name(),$pkgInfo['package_cost'].' '.end(explode(':',$pkgInfo['currency_code'])),$pkgInfo['post_allow'],$txn_id,$pkgInfo['expiry_date']);		

		$title = osc_mailBeauty($content['s_title'], $words) ;

        $body  = osc_mailBeauty($content['s_text'], $words) ;

		//echo osc_logged_user_email();

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

   }   

  

?>

<div class="content user_account">

<?php osc_show_flash_message(); ?>

  <h1> <strong><?php _e('Terms And Conditions For Offline Payment', 'subscription') ; ?></strong> </h1>

	<div id="sidebar"> <?php echo osc_private_user_menu() ; ?> </div>

     <div id="main">

	 <form action="" method="post">

	 <ul>

	    <li>

			<div><h2><?php echo $pkgInfo['package_name'];?></h2></div>	    

			<div>Package Cost :  <?php echo $pkgInfo['package_cost'].' '.end(explode(':',$pkgInfo['currency_code']));?></div>

			<div>Allow Post : <?php echo $pkgInfo['post_allow'];?> </div> <br />

			<div><?php echo $pkgInfo['package_description'];?> </div><br />

			<h2>Terms And Conditions</h2>			 

			<?php echo nl2br($offlinetc); ?> <br /><br />

			<input type="submit" value="Confirm" name="confirm" />

	    </li>

	 </ul>	

	 </form>

     </div>

</div>