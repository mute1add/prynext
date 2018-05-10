<?php
/*
  Plugin Name: Telephone
  Plugin URI: http://theme.calinbehtuk.ro
  Description: This plugin allow you to include a phone field on each item
  Version: 1.0.4
  Author: Puiu Calin
  Author URI: http://theme.calinbehtuk.ro
  Short Name: telephone
  Plugin update URI: telephone-plugin
 */

require_once 'Modelphone.php';

function telephone_install() {
    osc_set_preference('hide_digits', '0', 'telephone');
    Modelphone::newInstance()->install_db_phone();
}

function telephone_uninstall() {
    Modelphone::newInstance()->uninstall_db_phone();
}

function osc_set_telephone_number() {
    $detail = '';
    if (osc_is_publish_page()) {
        if (Session::newInstance()->_getForm('telephone') != '') {
            $detail = Session::newInstance()->_getForm('telephone');
        }
    } else if (osc_is_edit_page()) {
        if (Session::newInstance()->_getForm('telephone') != '') {
            $detail = Session::newInstance()->_getForm('telephone');
        } else {
            $value = Modelphone::newInstance()->t_check_value(osc_item_id());
            if (!empty($value)) {
                $detail = $value['s_telephone'];
            }
        }
    }
    ?>
    <div class="box control-group">
        <div class="row">
            <label for="telephone"><?php _e('Phone', 'telephone'); ?></label>
            <div class="controls">
                <input id="telephone" type="text" value="<?php echo $detail; ?>" name="telephone"></input>
            </div>
        </div>
    </div>
    <?php
}

function telephone_insert_number($item) {
    $id = $item['pk_i_id'];
    $number = Params::getParam("telephone");
    if ($number != '') {
        Modelphone::newInstance()->t_insert_number($id, $number);
    }
}

osc_add_hook('posted_item', 'telephone_insert_number');

function telephone_edited_number($item) {
    $id = $item['pk_i_id'];
    $number = Params::getParam("telephone");
    if ($number != '') {
        Modelphone::newInstance()->t_insert_number($id, $number);
    }
}

osc_add_hook('edited_item', 'telephone_edited_number');

function telephone_deleted_number($id) {
    Modelphone::newInstance()->delete_number($id);
}

osc_add_hook('delete_item', 'telephone_deleted_number');

function pre_post_store_value() {
    Session::newInstance()->_setForm('telephone', Params::getParam("telephone"));
    //Session::newInstance()->_keepForm('telephone');
}

function osc_telephone_number() {
    if (osc_item_id()) {
        $detail = Modelphone::newInstance()->t_check_value(osc_item_id());
        if (isset($detail['s_telephone'])) {
            if (osc_get_preference('hide_digits', 'telephone') == '1') {
                ?>
                <script type="text/javascript">
                    $(document).ready(function () {
                        var number = '<?php echo $detail['s_telephone']; ?>';
                        $('.phone_telephone .set_<?php echo osc_item_id(); ?>').click(function () {
                            $(this).html(number);
                        });
                    });
                </script>
                <div style="display:block;margin-bottom:5px;cursor:pointer;" class="phone_telephone" ><?php _e('Phone', 'telephone'); ?>: <span class="set_<?php echo osc_item_id(); ?>" title="<?php _e('Click to show the number', 'telephone'); ?>"><?php echo telephone_replace_number_to_x($detail['s_telephone']); ?></span></div>
                <?php
            } else {
                ?>
                <div style="display:block;margin-bottom:5px;" class="phone_telephone"><?php _e('Phone', 'telephone'); ?>: <?php echo $detail['s_telephone']; ?></div>
                <?php
            }
        }
    }
}

function telephone_other_products() {
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
    <link rel="stylesheet" href="<?php echo osc_base_url(); ?>oc-content/plugins/telephone/images/products/other.css" type="text/css">
    <div class="other_products">
        <h4><?php _e('You may like from our products', 'telephone'); ?></h4>
        <ul>
            <?php foreach ($products as $product) { ?>
                <li><a target="blank" href="<?php echo $product['link']; ?>"><img title="<?php echo $product['title']; ?>" src="<?php echo osc_base_url(); ?>oc-content/plugins/telephone/images/products/<?php echo $product['image']; ?>.png" /><span><?php echo $product['title']; ?></span></a></li>
            <?php } ?>
        </ul>
    </div>
    <?php
}

function telephone_scritp_settings() {
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.t_selector span').click(function () {
                var value = $(this).attr('class');
                var url = '<?php echo osc_ajax_plugin_url('telephone/ajax/ajax.php') . '&case=digits&option='; ?>' + value;
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: 'json',
                    success: function (data) {
                        var success = data.success;
                        var message = data.msg;
                        var option = data.option;
                        if (success == 'true') {
                            if (option == '1') {
                                $('.t_selector').removeClass('off');
                                $('.t_selector').addClass('on');
                            } else {
                                $('.t_selector').removeClass('on');
                                $('.t_selector').addClass('off');
                            }
                        }
                        if (success == 'false') {
                            alert(message);
                        }
                    }
                });
            });
        });
    </script>  
    <?php
}

function telephone_replace_number_to_x($number) {
    return $short_phone = substr($number, 0, -4) . 'XXXX';
}

function thelephone_help() {
    osc_admin_menu_plugins('' . __('Telephone Help', 'telephone'), osc_admin_render_plugin_url('telephone/help.php'), 'telephone_submenu');
}

function telephone_config() {
    osc_admin_render_plugin('telephone/help.php');
}

//admin include
function telephone_admin_feild_edit($catgeory_id, $item_id) {
    if (OC_ADMIN) {
        $detail = '';
        $value = Modelphone::newInstance()->t_check_value($item_id);
        if (!empty($value)) {
            $detail = $value['s_telephone'];
        }
        ?>
        <div class="box control-group">
            <div class="row">
                <label for="telephone"><?php _e('Phone', 'telephone'); ?></label>
                <div class="controls">
                    <input id="telephone" type="text" value="<?php echo $detail; ?>" name="telephone"></input>
                </div>
            </div>
        </div>
        <?php
    }
}
osc_add_hook('item_edit', 'telephone_admin_feild_edit');
osc_add_hook('pre_item_post', 'pre_post_store_value');
osc_add_hook('admin_menu_init', 'thelephone_help');
osc_register_plugin(osc_plugin_path(__FILE__), 'telephone_install');
osc_add_hook(osc_plugin_path(__FILE__) . "_uninstall", 'telephone_uninstall');
osc_add_hook(osc_plugin_path(__FILE__) . "_configure", 'telephone_config');

