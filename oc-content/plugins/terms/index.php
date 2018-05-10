<?php
/*
 * Copyright (C) 2016 Puiu Calin
 * This program is a commercial software: is forbidden to use this software without licence, 
 * on multiple installations, and by purchasing from other source than those authorized for the sale of software.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

/*
  Plugin Name: Terms
  Plugin URI: http://theme.calinbehtuk.ro/
  Description: Show a checkbox with therms on published page.
  Version: 1.0.3
  Author: Puiu Calin
  Author URI: http://theme.calinbehtuk.ro/
  Plugin update URI: terms-and-conditions
 */

define('TERMS', '103');

function terms_install() {
    osc_set_preference('include', '1', 'terms');
    osc_set_preference('version', TERMS, 'terms');
}

function terms_uninstall() {
    
}

//update version
if (OC_ADMIN) {
    if (TERMS > osc_get_preference('version', 'terms')) {
        osc_set_preference('version', TERMS, 'terms');
    }
}

//auto include
function terms_include() {
    $value_sesion = '';
    if (Session::newInstance()->_getForm('terms') != '') {
        $value_sesion = Session::newInstance()->_getForm('terms');
    }
    ?>
    <div class="row">
        <label>&nbsp;</label>
        <input style="width:30px !important;" type="checkbox" name="terms_box" id="terms_box" <?php
        if ($value_sesion != '') {
            echo 'checked="yes"';
        }
        ?> value="1"/>
        <span style="margin-left:5px;"><?php printf(__('I agree with these <a target="_blank" href="%s">terms and conditions</a>', 'terms'), osc_get_preference('terms_page', 'terms')); ?></span>
    </div>
    <?php
}

if (osc_get_preference('include', 'terms') == '1') {
    osc_add_hook('item_form', 'terms_include');
    osc_add_hook('item_edit', 'terms_include');
}
if (osc_get_preference('include', 'terms') == '0') {

    //manual include
    function terms_manual_include() {
        $value_sesion = '';
        if (Session::newInstance()->_getForm('terms') != '') {
            $value_sesion = Session::newInstance()->_getForm('terms');
        }
        ?>
        <div class="row">
            <label></label>
            <input type="checkbox" name="terms_box" id="terms_box" <?php
            if ($value_sesion != '') {
                echo 'checked="yes"';
            }
            ?> value="1"/>
            <span style="margin-left:5px;"><?php printf(__('I agree with these <a target="_blank" href="%s">terms and conditions</a>', 'terms'), osc_get_preference('terms_page', 'terms')); ?></span>
        </div>
        <?php
    }

}

function terms_pre_post() {
    $terms = Params::getParam('terms_box');
    if (empty($terms)) {
        osc_add_flash_error_message(__('Please read and accept the terms and conditions.', 'terms'));
        $redirect_url = osc_item_post_url();
        osc_redirect_to($redirect_url);
    }
}

osc_add_hook('pre_item_add', 'terms_pre_post');

function terms_pre_edit() {
    $terms = Params::getParam('terms_box');
    if (empty($terms)) {
        osc_add_flash_error_message(__('Please read and accept the terms and conditions.', 'terms'));
        $redirect_url = osc_item_edit_url();
        osc_redirect_to($redirect_url);
    }
}

if (!OC_ADMIN) {
    osc_add_hook('pre_item_edit', 'terms_pre_edit');
}

function terms_keep_data_post() {
    Session::newInstance()->_setForm('terms', Params::getParam("terms_box"));
    Session::newInstance()->_keepForm('terms');
}

osc_add_hook('pre_item_post', 'terms_keep_data_post');

function terms_settings() {
    switch (Params::getParam('action_specific')) {
        case('terms_settings'):
            $include = Params::getParam('include');
            osc_set_preference('include', ($include ? '1' : '0'), 'terms');
            osc_set_preference('terms_page', trim(Params::getParam('terms_page', false, false, false)), 'terms');
            osc_add_flash_ok_message(__('Plugin settings updated correctly', 'terms'), 'admin');
            osc_redirect_to(osc_admin_render_plugin_url('terms/settings.php'));
            break;
    }
}

osc_add_hook('init_admin', 'terms_settings');

function terms_admin() {
    osc_admin_render_plugin('terms/settings.php');
}

osc_add_hook(osc_plugin_path(__FILE__) . "_configure", 'terms_admin');

function terms_admin_menu() {
    osc_admin_menu_plugins(__('Terms Settings', 'firs_one'), osc_admin_render_plugin_url('terms/settings.php'), 'terms_submenu');
}

osc_add_hook('admin_menu_init', 'terms_admin_menu');

function terms_products() {
    $one = array('title' => 'One theme', 'link' => 'http://market.osclass.org/themes/general/responsive-one-theme_196', 'image' => 'one');
    $rita = array('title' => 'Rita theme', 'link' => 'http://market.osclass.org/themes/general/premium-theme-rita_443', 'image' => 'rita');
    $ema = array('title' => 'Ema theme', 'link' => 'http://market.osclass.org/themes/premium-osclass-theme-ema_347', 'image' => 'ema');
    $message = array('title' => 'S Message Plugin', 'link' => 'http://market.osclass.org/plugins/messaging/s-message_532', 'image' => 'message');
    $first = array('title' => 'First One Plugin', 'link' => 'http://market.osclass.org/plugins/ad-management/first-one-plugin_426', 'image' => 'first');
    $wishlist = array('title' => 'Wishlist Plugin', 'link' => 'http://market.osclass.org/plugins/ad-management/wishlist-plugin_473', 'image' => 'wishlist');
    $eighteen = array('title' => 'Eighteen Plugin', 'link' => 'http://market.osclass.org/plugins/eighteen-18_281', 'image' => 'eighteen');
    $floating = array('title' => 'Floating Social Share', 'link' => 'http://market.osclass.org/plugins/social-networks/floating-social-share_475', 'image' => 'floating');
    $paygol = array('title' => 'Sms Payment', 'link' => 'http://market.osclass.org/plugins/payments/sms-payment-with-paygol-plugin_481', 'image' => 'sms');
    $calinbehtuk = array('title' => 'Calinbehtuk theme', 'link' => 'http://market.osclass.org/themes/general/premium-theme-calinbehtuk_493', 'image' => 'calinbehtuk');
    $poup = array('title' => 'Facebook pop-up', 'link' => 'http://market.osclass.org/plugins/social-networks/facebook-pop-up_574', 'image' => 'facebook');
    $premium_phone = array('title' => 'Premium Phone', 'link' => 'http://theme.calinbehtuk.ro/?product=premium-phone', 'image' => 'premium_phone');
    //products
    $products = array('0' => $one, '1' => $rita, '2' => $ema, '3' => $message, '4' => $first, '5' => $wishlist, '6' => $eighteen, '7' => $floating, '8' => $paygol, '9' => $calinbehtuk, '10' => $poup, '11' => $premium_phone);
    shuffle($products);
    $products = array_slice($products, 0, 4);
    ?>
    <link rel="stylesheet" href="<?php echo osc_base_url() . 'oc-content/plugins/terms/images/products/other.css'; ?>" type="text/css">
    <div class="other_products">
        <h4><?php _e('You may like from our products', 'terms'); ?></h4>
        <ul>
            <?php foreach ($products as $product) { ?>
                <li><a target="blank" href="<?php echo $product['link']; ?>"><img title="<?php echo $product['title']; ?>" src="<?php echo osc_base_url() . 'oc-content/plugins/terms/images/products/'; ?><?php echo $product['image']; ?>.png" /><span><?php echo $product['title']; ?></span></a></li>
            <?php } ?>
        </ul>
    </div>
    <?php
}

function terms_content() {
    $url = 'http://theme.calinbehtuk.ro/content/data.php?data=true';
    $json = file_get_contents($url);
    $obj = json_decode($json, true);

    $title = $obj['info_data']['title'];
    $image_url = $obj['info_data']['image_url'];
    $style = $obj['info_data']['style'];
    if (!empty($obj)) {
        ?>
        <link rel="stylesheet" href="<?php echo $style; ?>">
        <div class="p_content">
            <h2 class="h_title"><?php echo $title; ?></h2>
            <?php
            unset($obj['info_data']);
            shuffle($obj);
            $obj = array_slice($obj, 0, 5);
            foreach ($obj as $value) {
                ?> 
                <a href="<?php echo $value['link']; ?>" target="blank">
                    <div class="individual_content">
                        <div class="p_image">
                            <img src="<?php echo $image_url; ?>/<?php echo $value['image']; ?>/<?php echo $value['image_id']; ?>.png" title="<?php echo $value['title']; ?>"/>
                        </div>
                        <div class="p_title">
                            <?php echo $value['title']; ?>
                        </div>
                        <div class="p_description">
                            <?php echo $value['description']; ?>
                        </div>
                    </div>
                </a>
                <script type="text/javascript">
                    $(document).ready(function () {
                        $('.individual_content').hover(function () {
                            $(this).find('.p_description').toggle();
                        });
                    });
                </script>
                <?php
            }
            ?></div><?php
    }
}

osc_register_plugin(osc_plugin_path(__FILE__), 'terms_install');
osc_add_hook(osc_plugin_path(__FILE__) . "_uninstall", 'terms_uninstall');
