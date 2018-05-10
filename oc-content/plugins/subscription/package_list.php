<?php 
/*
Plugin Name: Subscription Management
Plugin URI:  http://osclassplugin.com
Version: 1.0  
File Description : This page will list all packages for users.
*/
$conn = getConnection();

$packages = $conn->osc_dbFetchResults("SELECT * FROM %st_packages where status='1' order by package_name asc", DB_TABLE_PREFIX);

$reCount = $conn->get_affected_rows();



// Check user don't have remaning post or package has been expired.

$checkSubscriberHavePostOrNot = $conn->osc_dbFetchResult("SELECT * FROM %st_user_subscription where (expiry_date > CURDATE() and remaining_post >0) AND payment_status='confirm' AND user_id=%d order by transaction_date desc limit 1",DB_TABLE_PREFIX,osc_logged_user_id());

$countResubscribe = $conn->get_affected_rows();


?>  

<div class="content user_account">

<?php osc_show_flash_message(true); ?>

  <h1> <strong><?php _e('Packages', 'subscription') ; ?></strong> </h1>
  <?php if(osc_is_web_user_logged_in()){ ?>
  <div id="sidebar"> <?php echo osc_private_user_menu() ; ?> </div>
  <?php }?>

  <div id="main<?php if(!osc_is_web_user_logged_in()) echo 'Full';?>">

    <ul>

	   <?php if($reCount>0){	    

	     

	    foreach($packages as $package){?>

		<li>

			<div><h2><?php echo $package['package_name'];?></h2></div>

			<div style="float:right;">

			<input type="button" name="subscribe" value="<?php echo __('Subscribe','subscription');?>" onClick="<?php if($countResubscribe == 0){?>location.href='<?php echo osc_render_file_url(osc_plugin_folder(__FILE__) . 'payment/confirmsubscription.php').'?pkgid='.$package['package_id'];?>'<?php }else{?>alert('You have already remaining post please use it first.');<?php }?>"/>			

			</div>

			<div><strong><?php echo __('Price','subscription');?>:</strong><?php echo $package['package_cost'].' '.end(explode(':',$package['currency_code']));?> <strong><?php echo __('Post Allow','subscritpion');?>:</strong> <?php echo $package['post_allow'];?></div>

			<div><strong><?php echo __('Validity','subsciption');?>:</strong> <?php echo $package['expiry_days'].' '.$package['period_type'];?></div><br />

			<div> <?php echo $package['package_description'];?> </div><br />

			

		</li>

	   <?php }} else{?>
	   <li><div>Package Not Available.</div></li>
	   <?php }?>

	</ul>

  </div>

</div>