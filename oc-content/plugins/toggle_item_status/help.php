<?php
/*
Plugin Name: Toggle Item Status
Plugin URI: http://amfearliath.tk/osclass-toggle-item-status
Description: User can mark items as sold or make them available again
Version: 1.0.1
Author: Liath
Author URI: http://amfearliath.tk
Short Name: toggle_item_status
Plugin update URI: toggle-item-status
*/
 
if (!defined('OC_ADMIN') || OC_ADMIN!==true) exit('Access is not allowed.');
 
?>
<div class="tis_help">
    <div class="tis_header">
        <h1><?php _e('Toggle Item Status', 'toggle_item_status'); ?></h1>
        <p><?php _e('This plugin allows the user, to set the items to sold or available', 'toggle_item_status'); ?></p>
    </div>
    <br /><br />
    <div class="tis_content">
        <h3 class="tis_title"><?php _e('Toggle button', 'toggle_item_status'); ?></h3>
        <p><?php _e('To display the toggle button, place this code anywhere you want (e.g. in item.php of your theme)', 'toggle_item_status'); ?></p>
        <code>&lt;?php tis_show(osc_item_id(), osc_item_user_id()); ?&gt;</code>
        <br /><br /><br />
        <p><?php _e('I have placed it right next to the edit button, e.g.', 'toggle_item_status'); ?></p>
<pre>...
&lt;div&gt;
    &lt;a href="&lt;?php echo osc_item_edit_url(); ?&gt;" rel="nofollow"&gt;
        &lt;button class="btn btn-info btn-post"&gt;&lt;?php _e('Edit item', CLASSIC_LANGUAGE); ?&gt;&lt;/button&gt;
    &lt;/a&gt; 
&lt;/div&gt;          
&lt;?php tis_show(osc_item_id(), osc_item_user_id()); ?&gt;
    ...</pre>
        <br /><br /><br />
        <h3 class="tis_title"><?php _e('Status box', 'toggle_item_status'); ?></h3>
        <p><?php _e('You should insert a status box to the item page with this code (e.g. in item.php of your theme)', 'toggle_item_status'); ?></p>
        <code>&lt;?php tis_status_box(osc_item_id()); ?&gt;</code>
        <br /><br /><br />
        <p><?php _e('I have placed it right above the title, e.g.', 'toggle_item_status'); ?></p>
<pre>...
&lt;div class="inner-box ads-details-wrapper"&gt;
    &lt;?php echo tis_status_box(osc_item_id()); ?&gt;
    &lt;h2&gt;&lt;?php echo osc_item_title(); ?&gt;&lt;/h2&gt;
    &lt;p class="item-intro"&gt;
    ...</pre>
        <br /><br /><br />
        <h3 class="tis_title"><?php _e('Styling', 'toggle_item_status'); ?></h3>
        <p><?php _e('To style the button or the status box, edit the following file', 'toggle_item_status'); ?></p>
        <code>../oc-content/plugins/toggle_item_status/assets/css/tis.css</code>    
    </div>
    <br /><br /><br /><br />
    <div style="width: 48%; float: left; padding: 1%;">
        <p><?php _e('Item is sold, status box is shown', 'toggle_item_status'); ?></p>
        <img src="<?php echo osc_plugin_url(__FILE__).'/assets/img/screen_1.jpg'; ?>" />
    </div>
    <div style="width: 48%; float: left; padding: 1%;">
        <p><?php _e('Item is available, no status box is shown', 'toggle_item_status'); ?></p>
        <img src="<?php echo osc_plugin_url(__FILE__).'/assets/img/screen_2.jpg'; ?>" />
    </div>
    <div style="clear: both;"></div>
</div>