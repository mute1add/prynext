<?php

/**
 * Simple function to know if Madhouse/Utils has already been loaded.
 * @return Boolean  always true.
 */
function mdh_utils() {
    return "1.20";
}

/**
 * Returns the contact URL of an item.
 * @return a string.
 * @since 1.12
 */
if(! function_exists("osc_item_contact_url")) {
    function osc_item_contact_url() {
        if ( osc_rewrite_enabled() ) {
            $path = osc_base_url() . osc_get_preference('rewrite_item_contact') . "/" . osc_item_id();
        } else {
            $path = osc_base_url(true) . '?page=item&action=contact&id=' . osc_item_id();
        }
        return $path;
    }
}

/**
 * Registers a success message $message and redirect to $target.
 * @since 1.12
 */
function mdh_handle_ok($message, $target=null)
{
	Madhouse_Utils_Controllers::handleOk($message, $target);
}

/**
 * Registers a warning message $message and redirect to $target.
 * @since 1.12
 */
function mdh_handle_warning($message, $target=null)
{
	Madhouse_Utils_Controllers::handleWarning($message, $target);
}

/**
 * Registers an error message $message and redirect to $target.
 * @since 1.12
 */
function mdh_handle_error($message=null, $target=null)
{
	Madhouse_Utils_Controllers::handleError($message, $target);
}

/**
 * Registers an error message $message and redirect to $target using javascript.
 *     THIS IS A UGLY HACK. Use this helper if you can't use mdh_handle_error.
 * @since 1.12
 */
function mdh_handle_error_ugly($message=null, $target=null)
{
	Madhouse_Utils_Controllers::handleErrorUgly($message, $target);
}

/**
 * Returns the date as Facebook does.
 *
 * 13 minutes ago, 1 day, just now, etc... The delay since the date is
 * calculated and formated as seconds, minutes, days, months, years, just
 * as Facebook does on its page, wall, and messenger.
 *
 * @param $date the date string to format.
 * @return a string.
 * @throws Exception, if no date is provided.
 * @since 1.10
 */
function mdh_smart_date($date)
{
    return Madhouse_Utils_Dates::smartDate($date);
}

/**
 * Computes the 'from' of a pagination.
 * @param $page current page of the pagination.
 * @param $n number of element per page.
 * @return an int.
 */
function mdh_pagination_from($page, $n)
{
    return (($page - 1) * $n) + 1;
}

/**
 * Computes the 'to' of a pagination.
 * @param $page current page of the pagination.
 * @param $n number of element per page.
 * @param $total the total number of elements.
 * @return an int.
 */
function mdh_pagination_to($page, $n, $total)
{
    $to = (($page - 1) * $n) + $n;
    if($total > 0 && $to > $total) {
        return $total;
    }
    return $to;
}

/**
 * Shows the pagination on the admin.
 * @return void.
 */
function mdh_pagination_admin($total=null)
{
    if(is_null($total)) {
        $total = View::newInstance()->_get("count");
    }

    osc_add_hook("before_show_pagination_admin", function() use ($total) { ?>
        <ul class="showing-results">
            <li>
                <span>
                    <?php echo osc_pagination_showing(
                        mdh_pagination_from(Params::getParam("iPage"), Params::getParam("iDisplayLength")),
                        mdh_pagination_to(Params::getParam("iPage"), Params::getParam("iDisplayLength"), $total),
                        $total); ?>
                </span>
            </li>
        </ul>
    <?php });
    osc_show_pagination_admin(
        array(
            "iPage" => Params::getParam("iPage"),
            "iDisplayLength" => Params::getParam("iDisplayLength"),
            "iTotalDisplayRecords" => $total
        )
    );
}

function mdh_pagination_select()
{
    ?>
    <form class="nocsrf inline" method="get" action="<?php echo osc_admin_base_url(true); ?>">
        <?php foreach( Params::getParamsAsArray('get') as $key => $value ) { ?>
        <?php if( $key != 'iDisplayLength' ) { ?>
        <input type="hidden" name="<?php echo $key; ?>" value="<?php echo osc_esc_html($value); ?>" />
        <?php } } ?>
        <select name="iDisplayLength" class="select-box-extra select-box-medium float-left" onchange="this.form.submit();" >
            <option value="10"><?php printf(__('%d Listings'), 10); ?></option>
            <option value="25" <?php if( Params::getParam('iDisplayLength') == 25 ) echo 'selected'; ?> ><?php printf(__('%d Listings'), 25); ?></option>
            <option value="50" <?php if( Params::getParam('iDisplayLength') == 50 ) echo 'selected'; ?> ><?php printf(__('%d Listings'), 50); ?></option>
            <option value="100" <?php if( Params::getParam('iDisplayLength') == 100 ) echo 'selected'; ?> ><?php printf(__('%d Listings'), 100); ?></option>
        </select>
    </form>
    <?php
}

?>