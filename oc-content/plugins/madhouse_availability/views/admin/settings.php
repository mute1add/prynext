<?php mdh_current_plugin_path("views/admin/navigation.php"); ?>

<div class="vpadder-lg bg-light dk  clearfix">
	<h2 class=""><?php _e("Helpers", mdh_current_plugin_name()); ?></h2>
	<ul>
		<li><code>echo mdh_availability_start()</code></li>
		<li><code>echo mdh_availability_has_end()</code></li>
		<li><code>echo mdh_availability_end()</code></li>
		<li><code>echo mdh_availability_duration()</code></li>
		<li><code>echo mdh_search_availability_start()</code></li>
		<li><code>echo mdh_search_availability_end()</code></li>
	</ul>
    <h2 class=""><?php _e("Customisation", mdh_current_plugin_name()); ?></h2>
    <p class="row-space-2"><?php _e("To customize the form, create a folder inside your theme (<code>oc-content/YOUR_THEME<strong>/plugins/madhouse_availability/</strong></code>). Then, just copy the default view named edit.php (<code>oc-content/plugins/madhouse_availability/views/web/edit.php</code>) to your theme  (<code>oc-content/YOUR_THEME<strong>/plugins/madhouse_availability/edit.php</strong></code>)", mdh_current_plugin_name()); ?></p>
    <p class="row-space-3"><?php _e("You can do the same procedure for each views (edit.php, detail.php, search.php).", mdh_current_plugin_name()); ?></p>
</div>
<form class="form-horizontal" action="<?php echo mdh_availability_do_url(); ?>" method="post">
	<div class="vpadder-lg bg-light clearfix">
		<h2><?php _e("Settings", mdh_current_plugin_name()); ?></h2>

		<div class="form-group">
			<label class="col-sm-2 control-label"><?php _e("Display settings", mdh_current_plugin_name()); ?></label>
			<div class="col-sm-3">
				<select name="end_date">
					<option value="0" <?php echo (osc_get_preference('end_date', mdh_current_preferences_section()) == 0) ? 'selected="selected"': ""; ?>><?php _e("Display nothing ", mdh_current_plugin_name()); ?></option>
					<option value="1" <?php echo (osc_get_preference('end_date', mdh_current_preferences_section()) == 1) ? 'selected="selected"': ""; ?>><?php _e("Display an end date", mdh_current_plugin_name()); ?></option>
					<option value="2" <?php echo (osc_get_preference('end_date', mdh_current_preferences_section()) == 2) ? 'selected="selected"': ""; ?>><?php _e("Display a duration", mdh_current_plugin_name()); ?></option>
				</select>
				<p class="help-block"><?php _e('Display an end date, a duration or nothing', mdh_current_plugin_name()); ?></p>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label"><?php _e("Search settings", mdh_current_plugin_name()); ?></label>
			<div class="col-sm-8">
				<?php printf(__("Include items that will be available in the next %s days", mdh_current_plugin_name()),
                        '<div class="vpadder-xs width-sm d-ib"><input class="form-control" name="search_include_past_item" type="text" value="' . osc_get_preference('search_include_past_item', mdh_current_preferences_section()) . '" /></div>'); ?>
                <p class="help-block"><?php _e("When users are searching for items, include items even if availability not already passed but will be available in X days.", mdh_current_plugin_name()); ?></p>
			</div>
		</div>
		<h3 class="h3 row-space-3"><?php _e("Templates position", mdh_current_plugin_name()); ?></h3>
		<div class="form-group">
			<label class="col-sm-2 control-label"><?php _e("Post", mdh_current_plugin_name()); ?></label>
			<div class="col-sm-2">
				<!-- default_status -->
				<select name="form_post_position">
					<option value="-1"><?php _e("Don't display", mdh_current_plugin_name()); ?></option>
					<?php for($i = 1 ; $i <=10 ; $i++): ?>
						<option <?php echo (osc_get_preference('form_post_position', mdh_current_preferences_section()) == $i) ? 'selected="selected"': ""; ?> value="<?php echo $i; ?>"><?php echo $i ?> </option>
					<?php endfor; ?>
				</select>
				<p class="help-block"><?php _e("Use 'item_form' hook", mdh_current_plugin_name()); ?></p>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label"><?php _e("Edit", mdh_current_plugin_name()); ?></label>
			<div class="col-sm-2">
				<!-- default_status -->
				<select name="form_edit_position">
					<option value="-1"><?php _e("Don't display", mdh_current_plugin_name()); ?></option>
					<?php for($i = 1 ; $i <=10 ; $i++): ?>
						<option <?php echo (osc_get_preference('form_edit_position', mdh_current_preferences_section()) == $i) ? 'selected="selected"': ""; ?> value="<?php echo $i; ?>"><?php echo $i ?> </option>
					<?php endfor; ?>
				</select>
				<p class="help-block"><?php _e("Use 'item_edit' hook", mdh_current_plugin_name()); ?></p>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label"><?php _e("Detail", mdh_current_plugin_name()); ?></label>
			<div class="col-sm-2">
				<!-- default_status -->
				<select name="detail_position">
					<option value="-1"><?php _e("Don't display", mdh_current_plugin_name()); ?></option>
					<?php for($i = 1 ; $i <=10 ; $i++): ?>
						<option <?php echo (osc_get_preference('detail_position', mdh_current_preferences_section()) == $i) ? 'selected="selected"': ""; ?> value="<?php echo $i; ?>"><?php echo $i ?> </option>
					<?php endfor; ?>
				</select>
				<p class="help-block"><?php _e("Use 'item_detail' hook", mdh_current_plugin_name()); ?></p>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label"><?php _e("Search", mdh_current_plugin_name()); ?></label>
			<div class="col-sm-2">
				<!-- default_status -->
				<select name="form_search_position">
					<option value="-1"><?php _e("Don't display", mdh_current_plugin_name()); ?></option>
					<?php for($i = 1 ; $i <=10 ; $i++): ?>
						<option <?php echo (osc_get_preference('form_search_position', mdh_current_preferences_section()) == $i) ? 'selected="selected"': ""; ?> value="<?php echo $i; ?>"><?php echo $i ?> </option>
					<?php endfor; ?>
				</select>
				<p class="help-block"><?php _e("Use 'search_form' hook", mdh_current_plugin_name()); ?></p>

			</div>
		</div>
	</div>
	<div class="space-in-md bg-light dker b-b">
            <input type="submit" id="save_changes" value="<?php _e('Save changes', mdh_current_plugin_name()); ?>" class="btn btn-primary btn-block"/>
        </div>
</form>