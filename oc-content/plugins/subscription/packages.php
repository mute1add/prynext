<?php if (!defined('OC_ADMIN')) exit('Direct access is not allowed.');
/* 
Plugin Name: Subscription Management
Plugin URI:  http://osclassplugin.com
Version: 1.0  
File Description : This page list all packages for admin user. 
*/
$conn = getConnection() ;

$packages = $conn->osc_dbFetchResults("SELECT * FROM %st_packages order by package_id desc", DB_TABLE_PREFIX);

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

	   $pkgIds=implode(',',$ids);//die;

      if($bulk_actions=='activate_all'){

	   		 $conn->osc_dbExec("UPDATE %st_packages set status='%d' where package_id IN(%s)",DB_TABLE_PREFIX,1,$pkgIds);

	   }else if($bulk_actions=='inactivate_all')

	    {

	    	 $conn->osc_dbExec("UPDATE %st_packages set status='%d' where package_id IN(%s)",DB_TABLE_PREFIX,0,$pkgIds);	  

	    }

	  else if($bulk_actions=='delete_all')

	    {	  

	    	$conn->osc_dbExec("DELETE from %st_packages where package_id IN(%s)",DB_TABLE_PREFIX,$pkgIds); 

	    }

         osc_add_flash_ok_message( _m('Operation has been perform successfully.') ) ;

		 header("location:".osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'packages.php'));die;

		 //echo "<script>window.location.href='".osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'packages.php')."';< /script>";

   }

if(Params::getParam('delpkgid')!=''){

   $pkgId = Params::getParam('delpkgid');

  if((int)$pkgId)

  {

    $conn->osc_dbExec("DELETE FROM %st_packages where package_id=%d",DB_TABLE_PREFIX,$pkgId);

	osc_add_flash_ok_message( _m('Operation has been perform successfully.') ) ;

	header("location:".osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'packages.php'));die;

  }

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

});

</script>

 

<h2 class="render-title "><?php echo __('Package Management','subscription')?> 

<a href="<?php echo osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'addpackage.php')?>" class="btn btn-mini"><?php echo __('Add new','subscription');?></a></h2>

    <fieldset>

	<form action="" method="post">

        <div class="table-contains-actions">

		<?php osc_show_flash_message(); ?>

		<div id="bulk-actions">

            <label>

                <div class="select-box select-box-extra">

				<select id="bulk_actions" name="bulk_actions" class="select-box-extra" style="opacity: 0;">

                    <option value=""><?php echo __('Bulk actions','subscription');?></option>                    

                    <option value="activate_all" data-dialog-content="Are you sure you want to activate the selected packages?"><?php echo __('Activate','subscription');?></option> 

					<option value="inactivate_all" data-dialog-content="Are you sure you want to inactivate the selected packages?"><?php echo __('Inactivate','subscription');?></option> 

					<option value="delete_all" data-dialog-content="Are you sure you want to delete the selected packages?"><?php echo __('Delete','subscription');?></option>                   

                  </select>

				</div> 

				<input type="submit" id="bulk_apply" name="bulk_apply" class="btn" value="<?php echo __('Apply','subscription');?>">

            </label>

        </div><br />

            <table class="table" cellpadding="0" cellspacing="0">

                <thead>

                    <tr>

                        <th class="col-bulkactions"><input id="check_all" type="checkbox" onclick="if($(this).is(':checked')){ $('.package_id').attr('checked','checked');}else{ $('.package_id').filter(':enabled').attr('checked',false);}"/></th>

                        <th><?php echo __('Package Name','subscription');?></th>

                        <th><?php echo __('Package Description','subscription');?></th>

						<th><?php echo __('Package Cost','subscription');?></th>

						<th><?php echo __('Post Allow','subscription');?></th>

                        <th><?php echo __('Validity','subscription');?></th>

						<th><?php echo __('Status','subscription');?></th>

                        <th><?php echo __('Action','subscription');?></th>

                    </tr>

                </thead>

                <tbody>

				<?php if($reCount>0){foreach($packages as $package){?>

				<tr>

					 <th><input type="checkbox" value="<?php echo $package['package_id'];?>" name="ids[]" class="package_id"></th>

					 <td><?php echo $package['package_name'];?></td>

					 <td width="30%"><?php echo substr($package['package_description'],0,100);?>...</td>

					 <td><?php echo $package['package_cost'].' '.end(explode(':',$package['currency_code']));?></td>

					 <td><?php echo $package['post_allow'];?></td>

					 <td><?php echo $package['expiry_days']." ".$package['period_type'];?></td>

					 <td>

					 <a href="<?php echo osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'packages.php')?>?status=<?php echo ($package['status'])?0:1;?>&pkgid=<?php echo $package['package_id'];?>"><?php echo ($package['status'])?'Active':'Inactive';?></a>

					  

					 </td>

					<td>

						<a href="<?php echo osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'packages.php')?>?delpkgid=<?php echo $package['package_id'];?>" onclick="if(confirm('Are you sure want to delete this?'))return true; else return false;">Delete</a> /

						<a href="<?php echo osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'editpackage.php')?>?editpkgid=<?php echo $package['package_id'];?>">Edit</a>

					</td>

				</tr>

				<?php }} else{?>

                <tr>

                <td colspan="8" class="text-center">

                	 <p><?php echo __('No data available in table','subscription');?></p>

                 </td>

                 </tr>

			 <?php }?>

              </tbody>

            </table>

            <div id="table-row-actions"></div> <!-- used for table actions -->

        </div>

		</form>

    </fieldset>