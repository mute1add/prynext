<?php
/*
 * Copyright (C) 2017 Puiu Calin
 * This program is a commercial software: is forbidden to use this software without licence, 
 * on multiple installations, and by purchasing from other source than those authorized for the sale of software.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

/*
  Plugin Name: Currency search
  Plugin URI: http://theme.calinbehtuk.ro/
  Description: Filter ads on search based on currency.
  Version: 1.0.1
  Author: Puiu Calin
  Author URI: http://theme.calinbehtuk.ro/
  Plugin update URI: currency-search
  Short Name: currency-search
 */

function currency_c_install() {
    //nothing for now
}

function currency_c_uninstall() {
    //nothing for now 
}

function currency_c_filter() {
    if (Params::getParam('curr_currency') != '') {
        $value = Params::getParam('curr_currency');
        $no_price = Params::getParam('curr_no_price');
        if ($no_price == '1') {
            Search::newInstance()->addConditions(sprintf("%st_item.fk_c_currency_code IS NULL OR %st_item.fk_c_currency_code = '%s'", DB_TABLE_PREFIX, DB_TABLE_PREFIX, $value));
        } else {
            Search::newInstance()->addConditions(sprintf("%st_item.fk_c_currency_code = '%s'", DB_TABLE_PREFIX, $value));
        }
    }
}

osc_add_hook('search_conditions', 'currency_c_filter');

function currency_c_selector() {
    ?>
    <?php
    $currency_check = osc_get_currencies();
    $number_of_currency = count($currency_check);
    if ($number_of_currency > 1) {
        ?>
        <fieldset>
            <div class="row one_input">
                <h3><?php _e('Select currency', 'currency'); ?></h3>
                <select id="curr_currency" name="curr_currency">
                    <option value=""><?php _e('All currency', 'currency'); ?></option>
                    <?php foreach ($currency_check as $curr) { ?>
                        <option value="<?php echo $curr['pk_c_code']; ?>" <?php
                        if (Params::getParam('curr_currency') == $curr['pk_c_code']) {
                            echo 'selected';
                        }
                        ?>><?php echo $curr['pk_c_code']; ?></option>
                            <?php } ?>
                </select>
            </div>
            <div class="row one_input">
                <input id="curr_no_price" type="checkbox" value="1" name="curr_no_price" <?php if (Params::getParam('curr_no_price') == '1' && Params::getParam('curr_currency') != '') { ?>checked="checked"<?php } ?> />
                <label for="curr_no_price"><?php _e('include non price ads', 'currency'); ?></label>
            </div>
        </fieldset>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#curr_currency").change(function () {
                    var curr_value = $(this).val();
                    if(!curr_value){
                        $('#curr_no_price').attr('checked', false);
                    }
                });
            });
        </script>
    <?php } ?>
    <?php
}

function currency_config() {
    osc_admin_render_plugin('currency/help.php');
}
osc_add_hook(osc_plugin_path(__FILE__) . "_configure", 'currency_config');
osc_add_hook('search_form', 'currency_c_selector');
osc_register_plugin(osc_plugin_path(__FILE__), 'currency_c_install');
osc_add_hook(osc_plugin_path(__FILE__) . "_uninstall", 'currency_c_uninstall');
