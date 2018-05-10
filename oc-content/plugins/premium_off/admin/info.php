<?php
if ((!defined('ABS_PATH')))
    exit('ABS_PATH is not loaded. Direct access is not allowed.');
if (!OC_ADMIN)
    exit('User access is not allowed.');
/* 
 * Copyright (C) 2016 Puiu Calin
 * This program is a commercial software: is forbidden to use this software without licence, 
 * on multiple installations, and by purchasing from other source than those authorized for the sale of software.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

?>
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
        <img src="<?php echo osc_base_url() ?>oc-content/plugins/premium_off/admin/calinbehtuk.png" title="premium theme and plugins for oslcass"/></a>
    <span style="float:right;line-height:40px;font-weight:700;"><?php _e('Follow:', 'premium_off'); ?><a target="blank" style="text-decoration:none;" href="https://www.facebook.com/Calinbehtuk-themes-1086739291344584/"> <img style="margin-bottom:-5px;margin-left:5px;" src="<?php echo osc_base_url() ?>oc-content/plugins/premium_off/admin/facebook.png" title="facebook"/></a></span>
    <div class="like">
        <div class="fb-like" data-href="https://www.facebook.com/Calinbehtuk-themes-1086739291344584/" data-layout="standard" data-action="like" data-show-faces="false" data-share="false"></div>
    </div>
</div>
<?php premium_off_content(); ?>
<h2><?php _e('Info', 'premium_off'); ?></h2>
<pre>
<?php _e('This plugin remove the status of premium from inactive ads, so the expired premium ads will not be displayed on your site. Please take notes that this plugin need cron to be active and run on your site to remove the premium status from inactive ads.', 'premium_off'); ?>
<br/>
<?php _e('The plugin will perform a check one a day after the premium expired ads and will mark premium expired ads as normal ads.', 'premium_off'); ?>
<br />
<?php _e('More about osclass cron you can read here', 'premium_off'); ?>: <a href="https://doc.osclass.org/Cron" target="_blank">Osclass Cron</a>
</pre>