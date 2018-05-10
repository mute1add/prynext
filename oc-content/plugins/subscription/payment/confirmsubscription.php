<?php if(!osc_is_web_user_logged_in()){ header("location:".osc_user_login_url());die; }
/* 
Plugin Name: Subscription Management
Plugin URI:  http://osclassplugin.com
Version: 1.0  
File Description : This file has the code for second step of subscription.
*/
$conn = getConnection() ;

if(Params::getParam('pkgid')){  

  $pkgId = Params::getParam('pkgid');

  if($pkgId){
   $packageIfo = $conn->osc_dbFetchResult("select * from %st_packages where package_id=%d AND status='1' ",DB_TABLE_PREFIX,$pkgId);
   //print_r($packageIfo);
   $paymentMode = explode(',',osc_get_preference('subcription_payment_mode','payment_mode'));     

  }

} 

   

?>

<div class="content user_account">

<?php osc_show_flash_message(); ?>

  <h1> <strong><?php _e('Subscription Details', 'subscription') ; ?></strong> </h1>

	<div id="sidebar"> <?php echo osc_private_user_menu() ; ?> </div>

     <div id="main">

	 <form action="<?php echo osc_render_file_url(osc_plugin_folder(__FILE__) . 'subscription.php');?>" method="post">

	 <ul>

	   <li>

	    <div><h2><?php echo $packageIfo['package_name'];?></h2></div>	    

	    <div>Package Cost : <?php echo $packageIfo['package_cost'].' '.end(explode(':',$packageIfo['currency_code']));?></div>

		<div>Allow Post : <?php echo $packageIfo['post_allow'];?> </div> <br />

		<div><?php echo $packageIfo['package_description'];?> </div><br />

		<h3>Select your prefered payment method:</h3><br />

		<?php foreach($paymentMode as $mode=>$val){?>

		<div><input type="radio" name="payment_mode" value="<?php echo $val;?>" /> <strong><?php echo ucwords(str_replace('_',' ',$val));?></strong>

		<?php }?>

		<input type="hidden" name="pkgId" value="<?php echo $pkgId;?>"/>

		<div style="float:right;"><input type="submit" value="Continue" name="confirm" /></div>

	   </li>

	 </ul>	

	</form>

     </div>

</div>

 