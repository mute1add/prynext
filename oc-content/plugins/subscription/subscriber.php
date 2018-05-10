<?php if (!defined('OC_ADMIN')) exit('Direct access is not allowed.');
/* /* 
Plugin Name: Subscription Management
Plugin URI:  http://osclassplugin.com
Version: 1.0  
File Description : This page will list all subscribers.
*/
$conn = getConnection() ;
$orders = $conn->osc_dbFetchResults("SELECT tus.*, tu.s_name,tu.s_email,s_phone_mobile FROM %st_user_subscription as tus left join %st_user as tu on tus.user_id=tu.pk_i_id order by expiry_date asc", DB_TABLE_PREFIX,DB_TABLE_PREFIX);

$reCount = $conn->get_affected_rows();

if(Params::getParam('pkgid')){

	$status = Params::getParam('status');

    $pkgId = Params::getParam('pkgid');

	if($pkgId){

   		 $conn->osc_dbExec("UPDATE %st_packages set status='%d' where package_id=%d",DB_TABLE_PREFIX,$status,$pkgId);

		 $conn->autocommit(true);

		 osc_add_flash_ok_message( _m('Status has been changed successfully.') ) ;

		 header("location:".osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'packages.php'));die;
		 
 	 }

}



if($_SERVER['REQUEST_METHOD']=='POST' && Params::getParam('bulk_apply'))

   {

      $extract = extract($_POST);

	   $ordIds=implode(',',$ids);//die;

      if($bulk_actions=='confirm_all'){

	   		 $conn->osc_dbExec("UPDATE %st_user_subscription set payment_status='%s' where subscription_id IN(%s)",DB_TABLE_PREFIX,'confirm',$ordIds);

	   }else if($bulk_actions=='pending_all')

	    {

	    	 $conn->osc_dbExec("UPDATE %st_user_subscription set payment_status='%s' where subscription_id IN(%s)",DB_TABLE_PREFIX,'pending',$ordIds);	  

	    }	   

         osc_add_flash_ok_message( _m('Operation has been done successfully.') ) ;

		 header("location:".osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'subscriber.php'));die;

		 //echo "<script>window.location.href='".osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'subscriber.php')."';< /script>";

   }



//echo "<pre>";print_r($packages);

?>

<script type="text/javascript">

$(document).ready(function(){

	$('#bulk_apply').click(function(){

	if($('.package_id:checked').length){

	   if($("#bulk_actions").val()){

	   	 var diaContent = $("#bulk_actions option:selected").attr('data-dialog-content');

	    	if(confirm(diaContent)){

		  	return true;

			}else{return false;}

	   	}else{

		    alert('Please select action to perform.');return false; 

		}

		

	  }else{

	    alert('Please select atleast one from list.');return false; 

	  }

	});

	

//////////////////////////Contact details dilog box //////////////////////////////////////////	

$('.contactDetail').click(function(){

 $("#dialog" ).dialog({resizable: false,width:450,height:215});

 $('#dialog').dialog('option', 'title', 'Contact Details of '+$(this).html());

 $('#dialog').html($(this).attr('contact-details'));

});	

});

</script>
<h2 class="render-title "><?php echo __('Subscribed Users','subscription')?></h2>

    <fieldset>

	<form action="" method="post">

        <div class="table-contains-actions">

		<?php osc_show_flash_message(); ?>

		<div id="bulk-actions">

            <label>

                <div class="select-box select-box-extra">

				<select id="bulk_actions" name="bulk_actions" class="select-box-extra" style="opacity: 0;">

                    <option value=""><?php echo __('Bulk actions','subscription');?></option>                    

                    <option value="confirm_all" data-dialog-content="Are you sure you want to confirm the selected order?"><?php echo __('Confirm','subscription');?></option> 

					<option value="pending_all" data-dialog-content="Are you sure you want to pending the selected order?"><?php echo __('Pending','subscription');?></option>

				 </select>

				</div> 

				<input type="submit" id="bulk_apply" name="bulk_apply" class="btn" value="<?php echo __('Apply','subscription');?>">

            </label>

        </div><br />

            <table class="table" cellpadding="0" cellspacing="0">

                <thead>

                    <tr>

                        <th class="col-bulkactions"><input id="check_all" type="checkbox" onclick="if($(this).is(':checked')){ $('.package_id').attr('checked','checked');}else{ $('.package_id').filter(':enabled').attr('checked',false);}"/></th>

						<th><?php echo __('User Name','subscription');?></th>

                        <th><?php echo __('Package Name','subscription');?></th>                        

						<th><?php echo __('Package Cost','subscription');?></th>

						<th><?php echo __('Post Allow','subscription');?></th>

						<th><?php echo __('Transaction Id ','subscription');?></th>

						<th><?php echo __('Subscribed Date ','subscription');?></th>

                        <th><?php echo __('Expiry Date','subscription');?></th>

						<th><?php echo __('Payment Mode','subscription');?></th>

						<th><?php echo __('Payment Status','subscription');?></th>                         

                    </tr>

                </thead>

                <tbody>

				<?php if($reCount>0){foreach($orders as $order){?>

				<tr>

					 <th><input type="checkbox" value="<?php echo $order['subscription_id'];?>" name="ids[]" class="package_id"></th>

					 <td><a href="javascript:;" class="contactDetail" contact-details ="<div>Email Address : <?php echo $order['s_email'];?></div><div>Mobile Number : <?php echo $order['s_phone_mobile'];?></div>"><?php echo $order['s_name'];?></a></td>

					 <td><?php echo $order['package_name'];?></td>					 

					 <td>$<?php echo $order['package_cost'];?></td>

					 <td><?php echo $order['post_allow'];?></td>

					 <td><?php echo $order['transaction_id'];?></td>

					 <td><?php echo date(dateFormat,strtotime($order['transaction_date']));?></td>

					 <td><?php echo date(dateFormat,strtotime($order['expiry_date']));?></td>

					 <td><?php echo $order['transaction_type'];?></td>

					 <td><?php echo ($order['payment_status']=='confirm')?'Confirm':'Pending';?></td>

				</tr>

				<?php }} else{?>

                <tr>

                <td colspan="10" class="text-center">

                	 <p><?php echo __('No data available in table','subscription');?></p>

                 </td>

                 </tr>

			 <?php }?>

              </tbody>

            </table>

            <div id="table-row-actions"></div> <!-- used for table actions -->

        </div>

		</form>		

<div id="dialog" title="Contact Details" style="display:none; width:450px;"></div>
</form>

</div>	
</fieldset>