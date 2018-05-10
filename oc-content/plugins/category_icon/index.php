<?php if ( ! defined('OC_ADMIN')) exit('Direct access is not allowed.');
/*
Plugin Name: Category icon
Plugin URI: https://github.com/panaionutwteh/osclass/tree/master/category_icon
Description: This plugin add the category icon
Version: 1.0.0
Author: WTEH
Author URI: http://www.wteh.ro/
Plugin update URI: category-icon
*/
require_once 'DAOCategoryIcon.php';

/**
 *
 */
define("UPLOAD_DIR", osc_content_path() . 'uploads/category_icon/');
/**
 *
 */
define("UPLOAD_PATH", osc_base_url() . 'oc-content/uploads/category_icon/');
$uploadImageName = null;

/**
 *
 */
function categoryIconCallAfterInstall()
{
    $path = osc_plugin_resource('category_icon/struct.sql');
    $sql = file_get_contents($path);
    DAOCategoryIcon::newInstance()->importSql($sql);
}

/**
 *
 */
function categoryIconCallAfterUninstall()
{
    DAOCategoryIcon::newInstance()->dropCategoriIconTable();
}

/**
 * @return int
 */
function categoryIconActions()
{
    global $uploadImageName;

    $dao_preference = new Preference();
    $addnew = Params::getParam('newadd');
    $bs_key_id = Params::getParam('delete');

    if (Params::getParam('file') != 'category_icon/admin.php') {
        return 0;
    }

    if ($addnew == 'true' && uploadCategoryIcon()) {
        $img = $uploadImageName;
        $cat = Params::getParam('sCategory');

        $addIcon = DAOCategoryIcon::newInstance()->addCategoryIcons(array(
            'bs_image_name' => $img,
            'pk_i_id' => $cat
        ));

        if($addIcon){
            osc_add_flash_ok_message(__('The icon has been added', 'category_icon'), 'admin');
        } else {
            osc_add_flash_error_message(_e('An error occurred delete the icon', 'category_icon'), 'admin');
        }
        osc_redirect_to(osc_admin_render_plugin_url('category_icon/admin.php'));
    } elseif ($bs_key_id != '') {
        $deleteIcon = DAOCategoryIcon::newInstance()->deleteByIconId($bs_key_id);
        if($deleteIcon) {
            osc_add_flash_ok_message(__('The icon has been deleted', 'category_icon'), 'admin');
        } else {
            osc_add_flash_error_message(__('An error occurred delete the icon', 'category_icon'), 'admin');
        }
        osc_redirect_to(osc_admin_render_plugin_url('category_icon/admin.php'));
    }
}


/**
 * @return int
 */
function uploadCategoryIcon()
{
    global $uploadImageName;

    if (!empty($_FILES['imageName'])) {
        $myFile = $_FILES['imageName'];

        // if directory exist
        if (!is_dir(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR);
            // set proper permissions on the new dir
            chmod(UPLOAD_DIR, 0777);
        }

        if ($myFile["error"] !== UPLOAD_ERR_OK) {
            osc_add_flash_error_message(__('An error occurred saving the icon', 'category_icon'), 'admin');
            osc_redirect_to(osc_admin_render_plugin_url('category_icon/admin.php'));
        }

        // ensure a safe filename
        $name = preg_replace("/[^A-Z0-9._-]/i", "_", $myFile["name"]);

        $parts = pathinfo($name);

        $name = md5($parts["filename"]) . '.' . $parts["extension"];
        // don't overwrite an existing file
        $i = 0;
        $parts = pathinfo($name);
        while (file_exists(UPLOAD_DIR . $name)) {
            $i++;
            $name = md5($parts["filename"]) . "-" . $i . "." . $parts["extension"];
        }

        // preserve file from temporary directory
        $success = move_uploaded_file($myFile["tmp_name"],
            UPLOAD_DIR . $name);
        if (!$success) {
            osc_add_flash_error_message(__('An error occurred saving the icon', 'category_icon'), 'admin');
            osc_redirect_to(osc_admin_render_plugin_url('category_icon/admin.php'));
        }
        // set proper permissions on the new file
        chmod(UPLOAD_DIR . $name, 0755);

        $uploadImageName = $name;

        return 1;
    }
}

// get category icon by category primary key
/**
 * @param $categoryId
 * @return int
 */
function get_category_icon($categoryId)
{
    $icon = DAOCategoryIcon::newInstance()->findByIconCategoryId($categoryId);

    if(count($icon) > 0) {
        foreach ($icon as $imgname) {
            return UPLOAD_PATH . $imgname['bs_image_name'];
        }
    }

    return 0;
}

/**
 * @return array
 */
function get_all_categories_icon()
{
    $icons = DAOCategoryIcon::newInstance()->getAllCategoryIcons();
    if (count($icons) > 0) {
        return $icons;
    }
    return array();
}

/**
 *
 */
function categoryIconAdmin()
{
    osc_admin_render_plugin('category_icon/admin.php');
}

/**
 *
 */
function categoryIconAdminMenu()
{
    osc_admin_menu_plugins('Category icon', osc_admin_render_plugin_url('category_icon/admin.php'), 'category_icon_submenu');
}

/**
 *
 */
function categoryIconLoadScripts()
{
    osc_enqueue_style('categoryIcon', osc_base_url() . 'oc-content/plugins/category_icon/assets/css/custom_category_icon.css');
}

// This is needed in order to be able to activate the plugin
osc_register_plugin(osc_plugin_path(__FILE__), 'categoryIconCallAfterInstall');
// This is a hack to show a Uninstall link at plugins table (you could also use some other hook to show a custom option panel)
osc_add_hook(osc_plugin_path(__FILE__) . "_uninstall", 'categoryIconCallAfterUninstall');
osc_add_hook(osc_plugin_path(__FILE__) . "_configure", 'categoryIconAdmin');
osc_add_hook('admin_menu_init', 'categoryIconAdminMenu');
// register css
osc_add_hook('init_admin', 'categoryIconLoadScripts');
osc_add_hook('init_admin', 'categoryIconActions');
?>