<?php if (!defined('OC_ADMIN')) exit('Direct access is not allowed.');
/* 
Plugin Name: Subscription Management
Plugin URI:  http://osclassplugin.com
Version: 1.0  
File Description : This page will allow to admin to add the new package from the backend.
*/

$conn = getConnection(); 

if($_SERVER['REQUEST_METHOD']=='POST' && Params::getParam('submit'))

   {

      $extratct = extract($_POST);

	  $exist = $conn->osc_dbFetchResults("SELECT * FROM %st_packages where package_name='%s' AND status='1'",DB_TABLE_PREFIX,$package_name);

	  $reCount = $conn->get_affected_rows();

	  if($reCount >0){

	   osc_add_flash_warning_message( _m('This package name is already exist.')) ;

	    

	  }else{	 

	  $conn->osc_dbExec("INSERT INTO %st_packages set package_name='%s', package_description='%s',package_cost=%d,currency_code='%s', post_allow=%d, expiry_days='%s',period_type='%s', status='%d'",DB_TABLE_PREFIX, $package_name,$package_description,$package_cost,$currency_code,$package_post_allow,$package_expiry_date,$period_type,$status);

	  osc_add_flash_ok_message( _m('Data has been added successfully') ) ;

      $conn->autocommit(true);

	  header("location:".osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'packages.php'));die; 

	 }

   }   

 
   osc_enqueue_script('tiny_mce');
?>
<script type="text/javascript" src="<?php echo osc_base_url(); ?>/oc-includes/osclass/assets/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">

 $(document).ready(function(){

   $('input[type="submit"]').click(function(){

     if($.trim($('input[name="package_name"]').val())==''){

	    showerror('Please enter package name.');

	    return false;

	 }else if($.trim($('input[name="package_cost"]').val())==''){

		 showerror('Please enter package cost.');

	     return false;

	 }

	 else if(!IsNumeric($.trim($('input[name="package_cost"]').val()))){

		 showerror('Please enter package cost numeric.');

	     return false;

	 }

	 else if($.trim($('input[name="package_post_allow"]').val())==''){

		  showerror('Please enter number to allow the post of package.');

	      return false;

	 }

	 else if(!IsNumeric($.trim($('input[name="package_post_allow"]').val()))){

		  showerror('Please enter number to allow the post of package numeric.');

	      return false;

	 }

	 else if($.trim($('input[name="package_expiry_date"]').val())==''){

		  showerror('Please enter package expiry date.');

	      return false;

	 }
	else if(!IsNumeric($.trim($('input[name="package_expiry_date"]').val()))){

		  showerror('Please enter numeric value for package validity.');

	      return false;

	 }

	});

   

	$(".xlarge").keypress(function(event) {

	  if ( event.which == 45 || event.which == 189 ) {

		  event.preventDefault();

	   }

	});   

	   $( "#package_expiry_date" ).datepicker({dateFormat: 'yy-mm-dd',minDate:1});

});

 

function showerror(msg)

{

  //$('#error_list').fadeIn('slow');$('.error').html(msg);

  $('#flashmessage').fadeIn('slow');$('.flashmessage').html('<a class="btn ico btn-mini ico-close">x</a>'+msg); 

}

function IsNumeric(input)

{

    return (input - 0) == input && (input+'').replace(/^\s+|\s+$/g, "").length > 0;

}

function isYYYYMMDD(str){

  return /^\d{4}\-\d{2}\-\d{2}$/.test(str);

         

}
 
tinyMCE.init({
	mode : "textareas",
	theme : "advanced",
	skin: "cirkuit",
	width: "50%",
	height: "240px",
	language: 'en',
	theme_advanced_toolbar_align : "left",
	theme_advanced_toolbar_location : "top",
	plugins : "adimage,advlink,media,contextmenu",
	entity_encoding : "raw",
	theme_advanced_buttons1_add : "forecolorpicker,fontsizeselect",
	theme_advanced_buttons2_add: "media",
	theme_advanced_buttons3: "",
	theme_advanced_disable : "styleselect,anchor",
	file_browser_callback : "ajaxfilemanager",
	relative_urls : false,
	remove_script_host : false,
	convert_urls : false
});

function ajaxfilemanager(field_name, url, type, win) {
	var ajaxfilemanagerurl = "<?php echo osc_base_url(); ?>/oc-includes/osclass/assets/js/tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php";
	var view = 'detail';
	switch (type) {
		case "image":
			view = 'thumbnail';
			break;
		case "media":
			break;
		case "flash":
			break;
		case "file":
			break;
		default:
			return false;
	}
	tinyMCE.activeEditor.windowManager.open({
		url: "<?php echo osc_base_url(); ?>/oc-includes/osclass/assets/js/tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php?view=" + view,
		width: 782,
		height: 440,
		inline : "yes",
		close_previous : "no"
	},{
		window : win,
		input : field_name
	});
}

       
</script>

<h2 class="render-title "><?php echo __('Package Management','subscription')?></h2>
<form action="" method="post">
  <!--<input type="hidden" name="action_specific" value="seo_options" />-->
  <fieldset>
  <div class="form-horizontal">
    <ul id="error_list" style="display: none;">
      <li>
        <label for="catId" generated="true" class="error"></label>
      </li>
    </ul>
    <?php osc_show_flash_message(); ?>
    <h3><?php echo __('Add New Package','subscription');?>:</h3>
    <div class="form-rowdiv">
      <div class="form-label"><?php echo __('Package Name','subscription');?> *</div>
      <div class="form-controls">
        <input type="text" class="xlarge" name="package_name" value="<?php echo Params::getParam('package_name');?>">
      </div>
    </div>
    <br />
    <div class="form-rowdiv">
      <div class="form-label"><?php echo __('Package Cost','subscription');?> *</div>
      <div class="form-controls">
        <input type="text" class="xlarge" name="package_cost" value="<?php echo Params::getParam('package_cost');?>" maxlength="5">
		 <select name="currency_code">
			<?php foreach((array)osc_get_currencies() as $currency){?>
			<option value="<?php echo $currency['pk_c_code'].':'.$currency['s_description'];?>"><?php echo $currency['s_description'];?></option>
			<?php }?>
		</select>	
		
      </div>
    </div>
    <br />
    <div class="form-rowdiv">
      <div class="form-label"><?php echo __('Item Post Allow','subscription');?> *</div>
      <div class="form-controls">
        <input type="text" class="xlarge" name="package_post_allow" value="<?php echo Params::getParam('package_post_allow');?>">
      </div>
    </div>
    <br />
    <div class="form-rowdiv">
      <div class="form-label"><?php echo __('Package Validity','subscription');?> *</div>
      <div class="form-controls">
        <input type="text" class="xlarge" name="package_expiry_date" id="package_expiry_date1" value="<?php echo Params::getParam('package_expiry_date');?>" maxlength="5">
		 <select name="period_type">
			<option value="days">Days</option>
			<option value="month">Month</option>
	    	<option value="year">Year</option>
	      </select>
	   </div>
    </div>
    <br />
    <div class="form-rowdiv input-description-wide">
      <div class="form-label"><?php echo __('Package Description','subscription');?></div>
      <div class="form-controls">
        <textarea name="package_description" rows="5" class="mceEditor"><?php echo Params::getParam('package_description');?></textarea>
      </div>
    </div>
    <br />
    <div class="form-rowdiv">
      <div class="form-label"><?php echo __('Status','subscription');?></div>
      <div class="form-controls">
        <input type="radio" class="xlarge" name="status" value="1" checked="checked">
        <?php echo __('Active','subscription');?>
        <input type="radio" class="xlarge" name="status" value="0">
        <?php echo __('Inactive','subscription');?> </div>
    </div>
    <br />
    <div class="form-actions">
      <input type="submit" value="<?php echo __('Add Package','subscription');?>" name="submit" class="btn btn-submit">
    </div>
  </div>
  </fieldset>
</form>