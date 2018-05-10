<h2 class="render-title"><?php _e('Category icon', 'category_icon'); ?></h2>
<div class="category_icon_add_box">
    <div class="left">
        <form action="<?php echo osc_admin_render_plugin_url('category_icon/admin.php'); ?>" method="post"
              enctype="multipart/form-data">
            <input type="hidden" name="newadd" value="true"/>
            <fieldset>
                <div class="form-horizontal">
                    <div class="form-row">
                        <div class="form-label"><?php _e('Category', 'category_icon') ?></div>
                        <div class="form-controls">
                            <div class="photo_container">
                                <?php osc_categories_select('sCategory', null, __('Select a category', 'admin')); ?><br>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label"><?php _e('Image', 'category_icon') ?></div>
                        <div class="form-controls">
                            <div class="photo_container">
                                <input type="file" name="imageName"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label"></div>
                        <div class="form-controls">
                            <input type="submit" value="Save changes" class="btn btn-submit">
                        </div>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
    <div class="right">
        <h2>Info category icon</h2>

        <p>Category icon plugin allows you to associates an icon categories.</p>
        <h4>How does plugin work?</h4>
        <p>Add this code into your categories loop</p>
        <pre>
            &lt;?php while (osc_has_categories()) { ?&gt;
            </pre>
        <pre style="background: rgba(255, 172, 52, 0.25)">
                &lt;?php if(get_category_icon(osc_category_id())) { ?&gt;
                    <?php echo htmlspecialchars('<img src="<?php echo get_category_icon(osc_category_id()); ?>" />'); ?>
            &lt;?php }
            </pre>
        <pre>
                echo osc_category_name(); ?>
             }
            ?&gt;
        </pre>
        <p>It's compatible with Osclass version 3.3.1 or higher</p>
        <?php printf(__('You have %s version', 'category_icon'), OSCLASS_VERSION); ?>
    </div>
</div>

<div class="category_icon_box">
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Icon</th>
            <th>Category</th>
            <th>Action</th>
        </tr>
        <?php
        if (count(get_all_categories_icon()) > 0) {
            foreach (get_all_categories_icon() as $catI) {
                $category_icon = osc_get_category('id', $catI['pk_i_id']);
                ?>
                <tr>
                    <td>
                        <?php echo $catI['bs_key_id']; ?>
                    </td>
                    <td><img src="<?php echo UPLOAD_PATH . $catI['bs_image_name']; ?>"/></td>
                    <td><?php echo $category_icon['s_name']; ?></td>
                    <td><a class="delete"
                           onclick="javascript:return confirm('<?php _e('This action can not be undone. Are you sure you want to continue?', 'watchlist'); ?>')"
                           href="<?php echo osc_admin_render_plugin_url('category_icon/admin.php') . '&delete=' . $catI['bs_key_id']; ?>"><?php _e('Delete', 'home_gallery'); ?></a>
                    </td>
                </tr>
            <?php }
        } else {
            ?>
            <tr>
                <td colspan="4">
                    <i>No images added</i>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>