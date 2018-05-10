<?php
/*
  Plugin Name: Phone
  Plugin URI: https://osclass.pro
  Description: This plugin allow include a phone field on item
  Version: 1.0.0
  Author: https://osclass.pro
  Author URI: https://osclass.pro
  Short Name: phone
  Plugin update URI: https://osclass.pro/phone-update.php
 */

require_once 'Modelphone.php';

function phone_install() {
    osc_set_preference('hide_digits', '0', 'phone');
    Modelphone::newInstance()->install_db_phone();
	if (!file_exists(osc_base_path() . 'oc-content/uploads/phone')) {
        mkdir(osc_base_path() . 'oc-content/uploads/phone', 0755, true);
    }
}

function phone_uninstall() {
    Modelphone::newInstance()->uninstall_db_phone();
	Modelphone::newInstance()->delete_folder();

}

function osc_set_phone_number() {
    $detail = '';
    if (osc_is_publish_page()) {
        if (Session::newInstance()->_getForm('phone') != '') {
            $detail = Session::newInstance()->_getForm('phone');
        }
    } else if (osc_is_edit_page()) {
        if (Session::newInstance()->_getForm('phone') != '') {
            $detail = Session::newInstance()->_getForm('phone');
        } else {
            $value = Modelphone::newInstance()->t_check_value(osc_item_id());
            if (!empty($value)) {
                $detail = $value['s_phone'];
            }
        }
    }
    ?>
    <div class="box control-group">
        <div class="row">
            <label for="phone"><?php _e('Phone', 'phone'); ?></label>
            <div class="controls">
                <input id="phone" type="text" value="<?php echo $detail; ?>" name="phone"></input>
            </div>
        </div>
    </div>
    <?php
}

function phone_insert_number($item) {
    $id = $item['pk_i_id'];
    $number = Params::getParam("phone");
    if ($number != '') {
        Modelphone::newInstance()->t_insert_number($id, $number);
    }
}

osc_add_hook('posted_item', 'phone_insert_number');

function phone_edited_number($item) {
    $id = $item['pk_i_id'];
    $number = Params::getParam("phone");
    if ($number != '') {
        Modelphone::newInstance()->t_insert_number($id, $number);
    }
}

osc_add_hook('edited_item', 'phone_edited_number');

function phone_deleted_number($id) {
    Modelphone::newInstance()->delete_number($id);
}

osc_add_hook('delete_item', 'phone_deleted_number');

function pre_post_store_value() {
    Session::newInstance()->_setForm('phone', Params::getParam("phone"));
    //Session::newInstance()->_keepForm('phone');
}

function osc_phone_number() {
    if (osc_item_id()) {
        $detail = Modelphone::newInstance()->t_check_value(osc_item_id());
        if (isset($detail['s_phone'])) {
            if (osc_get_preference('hide_digits', 'phone') == '1') {
                ?>
               <script type="text/javascript">
$(document).ready(function(){
$("#showPhone").click(function () {
$("#showPhone").hide();
$("#hidePhone").show();
});
$("#hidePhone").click(function () {
$("#showPhone").show();
$("#hidePhone").hide();
});
});
</script>
<div style="display:block;margin-bottom:5px;cursor:pointer;vertical-align: middle;"><?php _e('Phone', 'phone'); ?>:<span><a href="#"  id="hidePhone" style="display: none;"><?php
$phone_mobile = $detail['s_phone'];
$userid=osc_item_id();
$fname = 'oc-content/uploads/phone/phone_img-'.$userid.'.png';
$img_mobile = imagecreate(200, 13); 
imagecolorallocatealpha( $img_mobile, 0, 0, 0, 127 ); 
$textcolor=imagecolorallocate($img_mobile, 0, 0, 0);
imagestring($img_mobile, 4, 2, 0, $phone_mobile, $textcolor);
imagepng($img_mobile, $fname);
 ?>
<img src="/<?php echo $fname;?>">			 
</a></span>
<span><a href="#"  id="showPhone"><?php echo 'XXXXXXXXXX'; ?></a></span>
					</li>
</div>
                <?php
            } else {
                ?>
                <div style="display:block;margin-bottom:5px;" class="phone_phone"><?php _e('Phone', 'phone'); ?>: <?php echo $detail['s_phone']; ?></div>
                <?php
            }
        }
    }
}

function phone_scritp_settings() {
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.t_selector span').click(function () {
                var value = $(this).attr('class');
                var url = '<?php echo osc_ajax_plugin_url('phone/ajax/ajax.php') . '&case=digits&option='; ?>' + value;
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

function phone_replace_number_to_x($number) {
    return $short_phone = substr($number, 0, -4) . 'XXXX';
}

function thelephone_help() {
    osc_admin_menu_plugins('' . __('Phone Help', 'phone'), osc_admin_render_plugin_url('phone/help.php'), 'phone_submenu');
}

function phone_config() {
    osc_admin_render_plugin('phone/help.php');
}

function phone_admin_feild_edit($catgeory_id, $item_id) {
    if (OC_ADMIN) {
        $detail = '';
        $value = Modelphone::newInstance()->t_check_value($item_id);
        if (!empty($value)) {
            $detail = $value['s_phone'];
        }
        ?>
        <div class="box control-group">
            <div class="row">
                <label for="phone"><?php _e('Phone', 'phone'); ?></label>
                <div class="controls">
                    <input id="phone" type="text" value="<?php echo $detail; ?>" name="phone"></input>
                </div>
            </div>
        </div>
        <?php
    }
}
osc_add_hook('item_edit', 'phone_admin_feild_edit');
osc_add_hook('pre_item_post', 'pre_post_store_value');
osc_add_hook('admin_menu_init', 'thelephone_help');
osc_register_plugin(osc_plugin_path(__FILE__), 'phone_install');
osc_add_hook(osc_plugin_path(__FILE__) . "_uninstall", 'phone_uninstall');
osc_add_hook(osc_plugin_path(__FILE__) . "_configure", 'phone_config');