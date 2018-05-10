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

if (Params::getParam('debug_empty') == 'done') {
    $file_to_c = osc_base_path() . 'oc-content/debug.log';
    if (file_exists($file_to_c)) {
        $handle = fopen($file_to_c, "w+");
        if (!$handle) {
            $redirect_url = osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/settings.php');
            header("Location: $redirect_url");
            osc_add_flash_error_message(__('An error occurred when emptying file', 'debug'), 'admin');
            exit();
        }
        fclose($handle);
        osc_add_flash_ok_message(__('File successfully clear', 'debug'), 'admin');
    } else {
        osc_add_flash_error_message(__('File do not exists', 'debug'), 'admin');
    }
    $redirect_url = osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/settings.php');
    header("Location: $redirect_url");
}
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
<div class="rights" >
    <a style="float:left;" href="http://theme.calinbehtuk.ro" title="Premium theme and plugins for oslcass">
        <img src="<?php echo osc_base_url() ?>oc-content/plugins/debug/admin/images/calinbehtuk.png" title="premium theme and plugins for oslcass"/></a>
    <span style="float:right;line-height:40px;font-weight:700;"><?php _e('Follow:', 'debug'); ?><a target="blank" style="text-decoration:none;" href="https://www.facebook.com/Calinbehtuk-themes-1086739291344584/"> <img style="margin-bottom:-5px;margin-left:5px;" src="<?php echo osc_base_url() ?>oc-content/plugins/debug/admin/images/facebook.png" title="facebook"/></a></span>
    <div class="like">
        <div class="fb-like" data-href="https://www.facebook.com/Calinbehtuk-themes-1086739291344584/" data-layout="standard" data-action="like" data-show-faces="false" data-share="false"></div>
    </div>
</div>
<div class="debug_area">
    <div class="debug_icon_top"><img src="<?php echo osc_base_url(); ?>oc-content/plugins/debug/images/debug.png"/></div>
    <h2><?php _e('Read your php debug file', 'debug'); ?></h2>
    <div class="help_page"><a href="<?php echo osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/help.php'); ?>"><?php _e('Help', 'debug'); ?></a></div>
    <?php
//check to see if debug is active
    if (OSC_DEBUG) {
        //if erors are save in debug log file
        if (OSC_DEBUG_LOG) {
            $file = osc_base_path() . 'oc-content/debug.log';
            //check to see if the file exists
            if (file_exists($file)) {
                $file_size = filesize($file);
                ?>
                <h4><?php _e('File:', 'debug'); ?> <a href="<?php echo osc_base_url(); ?>oc-content/debug.log" target="blank"><?php echo osc_base_url(); ?>oc-content/debug.log</a></h4>
                <?php if ($file_size > 10) { ?>
                    <div class="debug-sidebar">
                        <h4><?php _e('FILE', 'debug'); ?></h4>
                        <span><?php _e('File name', 'debug'); ?>: <span>debug.log</span></span>
                        <span><?php _e('File size', 'debug'); ?>: <span><?php echo debug_read_transform($file_size); ?></span></span>
                        <span><?php _e('File lines', 'debug'); ?>: <span><?php echo debug_read_count_rows($file); ?></span></span>
                        <script type="text/javascript" src="<?php echo osc_base_url() . 'oc-content/plugins/debug/js/jscolor.js'; ?>"></script>
                        <div class="debug_option">
                            <h4><?php _e('COLORS', 'debug'); ?></h4>
                            <label><?php _e('Date color', 'debug'); ?></label>
                            <input type="text" class="jscolor {onFineChange:'updateColorDate(this)'}" name="debug_date_color" id="debug_date_color" value="<?php echo osc_esc_html(osc_get_preference('debug_date_color', 'debug')); ?>" onchange="updateDate(this.jscolor)" />
                            <label><?php _e('Text color', 'debug'); ?></label>
                            <input type="text" class="jscolor {onFineChange:'updateColorText(this)'}" name="debug_text_color" id="debug_text_color" value="<?php echo osc_esc_html(osc_get_preference('debug_text_color', 'debug')); ?>" onchange="updateText(this.jscolor)"/>
                            <?php debug_read_script(); ?>
                        </div>
                        <div class="debug_option">
                            <h4><?php _e('EMPTY FILE', 'debug'); ?></h4>
                            <a href="<?php echo osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/settings.php'); ?>?debug_empty=done"><?php _e('Clear the debug file', 'debug'); ?></a>
                        </div>
                    </div>
                    <div class="debug_read_text">
                        <?php debug_read_style(); ?>
                        <?php
                            //pagination
                            $tot_number = debug_read_count_rows($file);
                            $param_page = Params::getParam('s_page');
                            $total = $tot_number;
                            $perpage = 48;
                            $pages = ceil($total / $perpage);
                            if ($param_page == 0) {
                                            $param_page = '1';
                            }
                            $page = isset($param_page) && $param_page <= $pages ? (int) $param_page : 1;
                            $start = ($page > 1) ? ($page * $perpage) - $perpage : 0;
                        ?>
                        <?php debug_read_file($file, $start, $perpage); ?>
                    </div>
                    <div class="debug_p_pag">
                        <?php debug_read_pagination($pages, $page, osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/settings.php?')); ?>
                    </div>
                <?php } else { ?>
                    <div class="debug_info" style="text-align:center;">
                        <p class="fine"><?php _e('Your site has no bugs.', 'debug'); ?></p>
                        <p><?php _e('The debug file is empty.', 'debug'); ?></p>
                        <p><?php _e('Everything is fine and the debug settings are correct. You can read more about osclass debug on this link:', 'debug'); ?> <a href="https://doc.osclass.org/Debug_PHP_errors" target="blank">Debug PHP errors</a></p>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="debug_info">
                    <?php
                    //error generate
                    if (Params::getParam('debug_read_generate') == 'error_generated') {
                        echo this_is_a_generated_error_by_yourself_to_see_if_the_debug_file_can_be_created;
                        $file_to_c = osc_base_path() . 'oc-content/debug.log';
                        if (file_exists($file_to_c)) {
                            osc_add_flash_ok_message(__('Your debug file was created successfully. You will find in this file the current error generated by you, to check if the file can be created.', 'debug'), 'admin');
                        } else {
                            osc_add_flash_error_message(__('The debug file cannot be created, please visit osclass documentation for php debug.', 'debug'), 'admin');
                        }
                        $redirect_url = osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/settings.php');
                        header("Location: $redirect_url");
                    }
                    ?>
                    <p><?php _e('Your debugger is active and set , but the debug file do not exist. Is possible that this file is not created yet because you have no errors on your site or is an issue when creating this file.', 'debug'); ?></p>
                    <p><?php _e('You can simulate an error, to see if the debug file is created.', 'debug'); ?></p>
                    <form action="" method="post"> 
                        <input type="hidden" name="debug_read_generate" value="error_generated" />
                        <input type="submit" value="<?php _e('Generates an error', 'debug'); ?>" class="btn btn-submit">
                    </form>   
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="debug_info">  
                <p><?php _e('Your debugger is active but is displaying errors on your site please set debug to save errors in debug file in order to read the errors with this plugin.', 'debug'); ?></p>
                <p><?php _e('Osclass debug documentation:', 'debug'); ?> <a href="https://doc.osclass.org/Debug_PHP_errors" target="blank">Debug PHP errors</a></p>
                <p><?php _e('or follow this tutorial:', 'debug'); ?></p>
                <p><center><iframe width="560" height="315" src="https://www.youtube.com/embed/zmSSJs3hfVA" frameborder="0" allowfullscreen></iframe></center></p>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="debug_info">
            <p><?php _e('Please activate osclass php debug on your site in order to read your php errors.', 'debug'); ?></p>
            <p><?php _e('Osclass debug documentation:', 'debug'); ?> <a href="https://doc.osclass.org/Debug_PHP_errors" target="blank">Debug PHP errors</a></p>
            <p><?php _e('or follow this tutorial:', 'debug'); ?></p>
            <p><center><iframe width="560" height="315" src="https://www.youtube.com/embed/zmSSJs3hfVA" frameborder="0" allowfullscreen></iframe></center></p>
        </div>
    <?php } ?>
</div>
<?php debug_calinbehtuk_rights(); ?>
<?php debug_other_products(); ?>
