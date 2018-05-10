<?php if(!osc_is_web_user_logged_in()){ header("location:".osc_user_login_url());die; }
/*/* 
Plugin Name: Subscription Management
Plugin URI:  http://osclassplugin.com
Version: 1.0  
File Description : This page will display subscribed packages by any user.
*/
$conn = getConnection() ;

$packages = $conn->osc_dbFetchResults("SELECT * FROM %st_user_subscription  where user_id=%d order by expiry_date asc",DB_TABLE_PREFIX,osc_logged_user_id());

$reCount = $conn->get_affected_rows();

if(Params::getParam('orderid') && osc_logged_user_id()){	 

    $orderId = Params::getParam('orderid');

	if($orderId){

   		 $conn->osc_dbExec("DELETE FROM %st_user_subscription where subscription_id=%d AND user_id=%d",DB_TABLE_PREFIX,$orderId,osc_logged_user_id());		 

		 osc_add_flash_ok_message( _m('Package has been deleted successfully.'));

		 $conn->autocommit(true);

		 header("location:".osc_render_file_url(osc_plugin_folder(__FILE__) . 'subscribedpkg.php'));die;
		 
 	 }

}

//echo "<pre>";print_r($packages);

?>
<style>
.table{    
    font-size: 12px;
    width: 100%;
}
.table th{ text-align:left;}
</style>
<h2 class="render-title "><?php echo __('Subscribed Packages','subscription')?></h2>    

	 <div class="content user_account">        

		<?php osc_show_flash_message(); ?>		

		<div id="sidebar"> <?php echo osc_private_user_menu() ; ?> </div>

		  <div id="main">

            <table class="table" cellpadding="0" cellspacing="0" border="2">

                <thead>

                    <tr>                        

                        <th><?php echo __('Package Name','subscription');?></th>

                        <th><?php echo __('Package Cost','subscription');?></th>

						<th><?php echo __('Post Allow','subscription');?></th>

						<th><?php echo __('Remaining Post','subscription');?></th>

						<?php /*?><th><?php echo __('Transaction Id','subscription');?></th><?php */?>

						<th><?php echo __('Subscribed Date','subscription');?></th>

                        <th><?php echo __('Expiry Date','subscription');?></th>

						<th><?php echo __('Payment Status','subscription');?></th>

						<?php /*?><th width="5%"><?php echo __('Action','subscription');?></th><?php */?>

                    </tr>

                </thead>

                <tbody>

				<?php if($reCount>0){foreach($packages as $package){?>

				<tr>
					 <td><?php echo $package['package_name'];?></td>					

					 <td><?php echo $package['package_cost'].' '.end(explode(':',$package['currency_code']));?></td>

					 <td><?php echo $package['post_allow'];?></td>

					 <td><?php echo $package['remaining_post'];?></td>

					 <?php /*?><td><?php echo $package['transaction_id'];?></td><?php */?>

					 <td><?php echo   date(dateFormat,strtotime($package['transaction_date']));?></td>

					 <td><?php echo (date('Y-m-d')< $package['expiry_date'])?date(dateFormat,strtotime($package['expiry_date'])):'Expired';?></td>

					 <td><?php echo $package['payment_status'];?></td>

					 <?php /*?><td><a href="javascript:;" onclick="if(confirm('Are you sure want to this order?')){location.href='<?php echo osc_render_file_url(osc_plugin_folder(__FILE__) . 'subscribedpkg.php')?>?orderid=<?php echo $package['subscription_id'];?>'}">Delete</a> 					</td>

				</tr><?php */?>

				<?php }} else{?>

                <tr>

                <td colspan="7" class="text-center">

                	 <p><?php echo __('No data available in table','subscription');?></p>

                 </td>

                 </tr>

			 <?php }?>

              </tbody>

            </table>

            <div id="table-row-actions"></div> <!-- used for table actions -->

			</div>

        </div>