<?php if (!defined('OC_ADMIN')) exit('Direct access is not allowed.');
/* 
Plugin Name: Subscription Management
Plugin URI:  http://osclassplugin.com
Version: 1.0  
File Description : This page is for configure the payment gateways.
*/
if($_SERVER['REQUEST_METHOD']=='POST' && Params::getParam('submit')){
   if(Params::getParam('payment_mode'))
   {
    	$payment_mode = Params::getParam('payment_mode');		
		 
		osc_set_preference('subcription_payment_mode', implode(',',$payment_mode), 'payment_mode', 'STRING');
		if(in_array('paypal_payment',$payment_mode)){
		
		   $apiInfo = Params::getParam('paypal_payment');
		   		    
		   osc_set_preference('paypal_sandbox_mode', ($apiInfo['paypal_sandbox_mode'])?$apiInfo['paypal_sandbox_mode']:0, 'payment_mode', 'STRING');
		   osc_set_preference('paypal_api_username', $apiInfo['paypal_api_username'], 'payment_mode', 'STRING');
		   osc_set_preference('paypal_api_password', $apiInfo['paypal_api_password'], 'payment_mode', 'STRING');
		   osc_set_preference('paypal_api_signature', $apiInfo['paypal_api_signature'], 'payment_mode', 'STRING');
		   osc_set_preference('paypal_api_email', $apiInfo['paypal_api_email'], 'payment_mode', 'STRING');		    
		   		
		 }
		 if(in_array('offline_payment',$payment_mode))
		 {		 
		 	$apiInfo = Params::getParam('offline_payment'); 
		    osc_set_preference('offline_payment_tc', $apiInfo['offline_payment_tc'], 'payment_mode', 'STRING');
		 } 
		    
		osc_add_flash_ok_message( _m('Payment mode configuration has been changed successfully.')) ;
		osc_reset_preferences();
   }
 
}

?>
<script type="text/javascript">
$(document).ready(function(){
	$('.payment_type').click(function(){
	  if($(this).attr('id')=='paypal_api'){
	    if($(this).is(":checked"))
	    $('#paypal_config').slideDown();
		else
		$('#paypal_config').slideUp();
	   }else if($(this).attr('id')=='offline_api'){
	     if($(this).is(":checked"))
	      $('#offline_config').slideDown();
		  else
		  $('#offline_config').slideUp();
	   }
	});
});
</script>
<h2 class="render-title "><?php echo __('Payment Management','subscription')?> </h2>
<fieldset>
<?php osc_show_flash_message();?>
       <form action="" method="post">
        <div class="form-horizontal">
         <ul id="error_list" style="display: none;">
		    <li><label for="catId" generated="true" class="error"></label></li>
		 </ul>    
        <h3><?php echo __('Select Payment Options:','subscription')?></h3>
            <div class="form-rowdiv">
                <div class="form-label">
				 <input type="checkbox" class="payment_type" name="payment_mode[]" value="paypal_payment" <?php echo (in_array('paypal_payment',explode(',',osc_get_preference('subcription_payment_mode','payment_mode'))))?'checked="checked"':'';?> id="paypal_api"/></div>
                <div class="form-controls"><strong><?php echo __('Paypal','subscription');?></strong></div>
            </div> <br /> 
			<div id="paypal_config" style="display:<?php echo (in_array('paypal_payment',explode(',',osc_get_preference('subcription_payment_mode','payment_mode'))))?'block':'none';?>;">
				<div class="form-rowdiv">
					<div class="form-label"><?php echo __('Enable Sandbox Mode','subscription');?></div>
					<div class="form-controls">
					<input type="checkbox" name="paypal_payment[paypal_sandbox_mode]" value="1" <?php echo (osc_get_preference('paypal_sandbox_mode','payment_mode')==1)?'checked="checked"':'';?> /></div>
				</div><br />
				<div class="form-rowdiv">
					<div class="form-label"><?php echo __('Api UserName','subscription');?></div>
					<div class="form-controls">
					<input type="text" class="xlarge" name="paypal_payment[paypal_api_username]" value="<?php echo osc_get_preference('paypal_api_username','payment_mode');?>" /></div>
				</div>
				<br />
				<div class="form-rowdiv">
					<div class="form-label"><?php echo __('Api Password','subscription');?></div>
					<div class="form-controls">
					<input type="text" class="xlarge" name="paypal_payment[paypal_api_password]" value="<?php echo osc_get_preference('paypal_api_password','payment_mode');?>" /></div>
				</div>
				<br />
				<div class="form-rowdiv">
					<div class="form-label"><?php echo __('Api Signature','subscription');?></div>
					<div class="form-controls">
					<input type="text" class="xlarge" name="paypal_payment[paypal_api_signature]" value="<?php echo osc_get_preference('paypal_api_signature','payment_mode');?>" /></div>
				</div>
				<br />
				<div class="form-rowdiv">
					<div class="form-label"><?php echo __('Paypal Seller Email','subscription');?></div>
					<div class="form-controls">
					<input type="text" class="xlarge" name="paypal_payment[paypal_api_email]" value="<?php echo osc_get_preference('paypal_api_email','payment_mode');?>" /></div>
				</div>
				<br />
			</div>
			
			 
			<div class="form-rowdiv">
                <div class="form-label"><input type="checkbox" class="payment_type" name="payment_mode[]" value="offline_payment"  <?php echo (in_array('offline_payment',explode(',',osc_get_preference('subcription_payment_mode','payment_mode'))))?'checked="checked"':'';?> id="offline_api"/> </div>
                <div class="form-controls"><strong><?php echo __('Offline Payment','subscription');?></strong></div>
            </div>
			<br /> 
			<div id="offline_config" style="display:<?php echo (in_array('offline_payment',explode(',',osc_get_preference('subcription_payment_mode','payment_mode'))))?'block':'none';?>;">
			<div class="form-rowdiv input-description-wide">
                <div class="form-label"><?php echo __('Offline Payment T&C','subscription');?></div>
                <div class="form-controls">
				<textarea name="offline_payment[offline_payment_tc]" rows="10"><?php echo osc_get_preference('offline_payment_tc','payment_mode');?></textarea>
                </div>
            </div> 
			</div>
			<br />
			 
             
             
            <div class="form-actions">
                <input type="submit" value="<?php echo __('Save Change','subscription');?>" name="submit" class="btn btn-submit">
            </div>
        </div>
		</form>
    </fieldset>