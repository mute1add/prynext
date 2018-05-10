<?php
/*
 * Copyright (C) 2016 Puiu Calin
 * This program is a commercial software: is forbidden to use this software without licence, 
 * on multiple installations, and by purchasing from other source than those authorized for the sale of software.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */
?>
<style>
    .rights {display: block;
             background: #F9F9F9;
             padding: 10px;
             border: 1px solid #D6FFFF;
             line-height:20px;
             margin-bottom:10px;
             overflow: hidden;
             box-shadow: 2px 3px 6px rgba(0, 0, 0, 0.1);
    }
    .rights .author {
        display: inline-block;
        background: #EBF6F6;
        border: 1px solid #D6FFFF;
        width:100%;
        margin-top:10px;
    }
    .rights .like{
        width:320px;
        display:inline-block;
        float:left;
        line-height:30px;
        margin-left:15px;
    }
</style>
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
<div class="rights">
    <a style="float:left;" href="http://theme.calinbehtuk.ro" title="Premium theme and plugins for oslcass">
        <img src="<?php echo osc_base_url() ?>oc-content/plugins/terms/images/calinbehtuk.png" title="premium theme and plugins for oslcass"/></a>
    <span style="float:right;line-height:40px;font-weight:700;"><?php _e('Follow:', 'terms'); ?><a target="blank" style="text-decoration:none;" href="https://www.facebook.com/Calinbehtuk-themes-1086739291344584/"> <img style="margin-bottom:-5px;margin-left:5px;" src="<?php echo osc_base_url() ?>oc-content/plugins/terms/images/facebook.png" title="facebook"/></a></span>
    <form style="display:inline-block;margin-left:10px;" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="TL5PLDQHJB3XA">
        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
    </form>
    <div class="like">
        <div class="fb-like" data-href="https://www.facebook.com/Calinbehtuk-themes-1086739291344584/" data-layout="standard" data-action="like" data-show-faces="false" data-share="false"></div>
    </div>
</div>
<?php terms_content(); ?>
<h2 class="render-title"><?php _e('Settings', 'terms'); ?></h2>
<form action="<?php echo osc_admin_render_plugin_url('terms/settings.php'); ?>" method="post">  
    <input type="hidden" name="action_specific" value="terms_settings" />
    <fieldset>
        <div class="form-horizontal">
            <div class="form-row">
                <div class="form-label"><?php _e('Include the checkbox', 'terms'); ?></div>
                <div class="form-controls">
                    <select name="include" id="include">
                        <option name="include" value="1" <?php
                        if (osc_get_preference('include', 'terms') !== '0') {
                            echo 'selected="selected"';
                        }
                        ?>><?php _e('Automat', 'terms'); ?></option> 
                        <option name="include" value="0"<?php
                        if (osc_get_preference('include', 'terms') !== '1') {
                            echo 'selected="selected"';
                        }
                        ?>><?php _e('Manual', 'terms'); ?></option>
                    </select>
                    <p>	<?php _e('Please note that if you choose to include manual, you have to edit and include the below line manual in your theme files.', 'terms'); ?></p>		
                </div>		
            </div>
            <div class="form-row">
                <div class="form-label"><?php _e('Terms page', 'terms'); ?></div>
                <div class="form-controls"><input type="text" class="terms_page" name="terms_page" value="<?php echo osc_esc_html(osc_get_preference('terms_page', 'terms')); ?>">
                    <div class="help-box"><?php _e('Include the entire link to your terms page in the above field. Ex. http://my_site.com/page/terms', 'terms'); ?></div>
                </div>			
            </div>
            <input type="submit" value="<?php _e('Save changes', 'terms'); ?>" class="btn btn-submit">           
        </div>
    </fieldset>
</form>
<div id="settings_form" style="border: 1px solid #ccc; background: #eee;margin-top:30px;padding:10px; ">
    <h2 class="render-title" style="margin-top:10px;"><?php _e('Info', 'terms'); ?></h2>
    <p>
        <?php _e('Insert this line in item-post.php, item-edit.php in your theme if you choose to not auto include the line.', 'terms'); ?>
    </p>
    <pre>
		&lt;?php if(function_exists('terms_manual_include')){ terms_manual_include();} ?&gt;
    </pre>
    <p>
        <?php _e('You have option to auto include the line, but if you do not like in which area the terms box is displayed you can choose to include de line manual. Just include the line in item-post.php and item-edit.php from your theme in which area you want the checkbox to appear.', 'terms'); ?>
    </p>
</div>
<br />
<center><iframe width="560" height="315" src="https://www.youtube.com/embed/tHsvDcxeVj8" frameborder="0" allowfullscreen></iframe></center>
<?php terms_products(); ?>