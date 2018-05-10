<?php if ((!defined('ABS_PATH'))) exit('ABS_PATH is not loaded. Direct access is not allowed.'); ?>
<?php if (!OC_ADMIN) exit('User access is not allowed.'); ?>
<link rel="stylesheet" href="<?php echo osc_base_url(); ?>oc-content/plugins/telephone/css/css.css" type="text/css">
<div id="fb-root"></div>
<script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id))
            return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=651333508343077";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
<div class="rights" >
    <a style="float:left;" href="http://theme.calinbehtuk.ro" title="Premium theme and plugins for oslcass">
        <img src="<?php echo osc_base_url() ?>oc-content/plugins/telephone/images/calinbehtuk.png" title="premium theme and plugins for oslcass"/></a>
    <span style="float:right;line-height:40px;font-weight:700;"><?php _e('Follow:', 'debug'); ?><a target="blank" style="text-decoration:none;" href="https://www.facebook.com/Calinbehtuk-themes-1086739291344584/"> <img style="margin-bottom:-5px;margin-left:5px;" src="<?php echo osc_base_url() ?>oc-content/plugins/telephone/images/facebook.png" title="facebook"/></a></span>
    <div class="like">
        <div class="fb-like" data-href="https://www.facebook.com/Calinbehtuk-themes-1086739291344584/" data-layout="standard" data-action="like" data-show-faces="false" data-share="false"></div>
    </div>
</div>
<div class="prem_p">
    <a href="https://market.osclass.org/plugins/attributes/premium-phone_633" target="blank"><img src="<?php echo osc_base_url(); ?>oc-content/plugins/telephone/images/p_p.gif"/></a>
</div>
<div id="settings_form" style="border: 1px solid #ccc; background: #F3F3F3;margin-top:20px; ">
    <div style="padding: 0 20px 20px;">
        <div>
            <fieldset>
                <legend style="width:100%;display:inline-block;float:left;margin-bottom:10px;">
                    <div style="float:right;">
                        <p style="display:block;color:#f80;font-weight:700;"><?php _e('Help us to keep this free', 'telephone'); ?></p>
                        <form style="display:block;margin-top:10px;" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                            <input type="hidden" name="cmd" value="_s-xclick">
                            <input type="hidden" name="hosted_button_id" value="TL5PLDQHJB3XA">
                            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                        </form>
                    </div>
                    <h1><?php _e('Telephone Help', 'telephone'); ?></h1>
                </legend> 
                <p><?php _e('This plugin allow you to display a phone field in publish/edit page, in what area you want on this page. Custom fields are displayed all in the same area, but this plugin give you the option to display the phone field in different part of the page.', 'telephone'); ?></p>
                <p><?php _e('For using this plugin you have to insert this line in item post page and item edit page, in the area you want this field to appear', 'telephone'); ?>:
                </p>
                <pre>
                    &lt;?php if(function_exists('osc_set_telephone_number')){ osc_set_telephone_number();} ?&gt;
                </pre>
                <p><?php _e('To display the number of telephone you have to insert this line in the item page in the are you want to show the number', 'telephone'); ?>:
                </p>
                <pre>
                    &lt;?php if(function_exists('osc_telephone_number')){ osc_telephone_number();} ?&gt;
                </pre>
                <center>
                    <iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/LkbM_ZYpkDI?rel=0&amp;controls=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>
                </center>
                <h1><?php _e('Settings', 'telephone'); ?></h1>
                <div class="form-horizontal">
                    <div class="form-row">
                        <div class="form-label"><?php _e('Hide last digits from number on item page', 'telephone'); ?></div>
                        <div class="form-controls">
                            <div class="t_selector <?php if(osc_get_preference('hide_digits', 'telephone') == '1'){ echo 'on';} else { echo 'off';} ?>">
                                <span class="1" id="t_on"><?php _e('Yes', 'telephone'); ?></span>
                                <span class="0" id="t_off"><?php _e('No', 'telephone'); ?></span>
                            </div>
                            <?php telephone_scritp_settings(); ?>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
</div>
<?php telephone_other_products(); ?>
<div class="author">
    <div>
        <span class="text"><?php _e('2016 All rights reserved Debug Read File Plugin by Puiu Calin', 'debug'); ?></span>
        <span class="logo"><a href="http://theme.calinbehtuk.ro/"><img src="<?php echo osc_base_url() . 'oc-content/plugins/telephone/images/calinbehtuk.png'; ?>" /></a></span>
    </div>
</div>