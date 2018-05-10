<?php if ((!defined('ABS_PATH'))) exit('ABS_PATH is not loaded. Direct access is not allowed.'); ?>
<?php if (!OC_ADMIN) exit('User access is not allowed.'); ?>
<link rel="stylesheet" href="<?php echo osc_base_url(); ?>oc-content/plugins/phone/css/css.css" type="text/css">
<div id="fb-root"></div>
<div id="settings_form" style="border: 1px solid #ccc; background: #F3F3F3;margin-top:20px; ">
    <div style="padding: 0 20px 20px;">
        <div>
            <fieldset>
                <legend style="width:100%;display:inline-block;float:left;margin-bottom:10px;">
                    <h1><?php _e('Help', 'phone'); ?></h1>
                </legend> 
                <p><?php _e('This plugin allow you to display a phone field in publish/edit page, in what area you want on this page. Custom fields are displayed all in the same area, but this plugin give you the option to display the phone field in different part of the page.', 'phone'); ?></p>
                <p><?php _e('For using this plugin you have to insert this line in item post page and item edit page, in the area you want this field to appear', 'phone'); ?>:
                </p>
                <pre>
                    &lt;?php if(function_exists('osc_set_phone_number')){ osc_set_phone_number();} ?&gt;
                </pre>
                <p><?php _e('To display the number of phone you have to insert this line in the item page in the are you want to show the number', 'phone'); ?>:
                </p>
                <pre>
                    &lt;?php if(function_exists('osc_phone_number')){ osc_phone_number();} ?&gt;
                </pre>
                <h1><?php _e('Settings', 'phone'); ?></h1>
                <div class="form-horizontal">
                    <div class="form-row">
                        <div class="form-label"><?php _e('Hide last digits from number on item page', 'phone'); ?></div>
                        <div class="form-controls">
                            <div class="t_selector <?php if(osc_get_preference('hide_digits', 'phone') == '1'){ echo 'on';} else { echo 'off';} ?>">
                                <span class="1" id="t_on"><?php _e('Yes', 'phone'); ?></span>
                                <span class="0" id="t_off"><?php _e('No', 'phone'); ?></span>
                            </div>
                            <?php phone_scritp_settings(); ?>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
</div>
<div class="author">
    <div>
       <span class="logo"><?php _e('Plugin site:', 'phone'); ?> <a href="https://osclass.pro/" target="_blank">https://osclass.pro</a> | <?php _e('Forum Osclass:', 'phone'); ?> <a href="https://4osclass.net/" target="_blank">https://4osclass.net</a></span>
    </div>
</div>