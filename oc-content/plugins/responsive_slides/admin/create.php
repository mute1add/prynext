<link href="<?php echo osc_base_url().'oc-content/plugins/responsive_slides/admin/admin.css'; ?>" rel="stylesheet" type="text/css" />
<?php
$link = Params::getParam('link');
$caption = Params::getParam('caption');
$description = Params::getParam('description');
if( Params::getParam('option') == 'stepone' ) {
	if( Params::getParam('create') == 1) {
		// Where the file is going to be placed
		// Check that the uploaded file is actually an image
		$valid_mime_types = array("image/jpg","image/jpeg","image/png","image/gif");
		if (in_array($_FILES["image"]["type"], $valid_mime_types)){
			$imagename = $_FILES["image"]["name"];
			$uniqname = uniqid() . '.' . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
			$destination = osc_content_path() . "plugins/responsive_slides/media/" . $uniqname;		
			if(move_uploaded_file($_FILES["image"]["tmp_name"], $destination)){
				ModelSlides::newInstance()->saveSlides($uniqname,$imagename,$caption,$description,$link);
				?>
				<div class="slidersuccess">
				<?php _e('The slider has been updated.','responsive_slides'); ?>
				</div>
				<?php
				echo "<script>location.href='".osc_admin_render_plugin_url("responsive_slides/admin/list.php")."'</script>";				
			}
			else{
				?>
				<div class="slidererror">
				<?php _e('There was an error uploading the file, please try again!', 'responsive_slides'); ?>
				</div>
				<?php
			}
		}
		else{
			?>
			<div class="slidererror">
			<?php _e('File type not allowed, Allowed file: *.jpg,*.jpeg,*.png,*.gif', 'responsive_slides'); ?>
			</div>
			<?php
		}		
	}
	else{
		$imagename = Params::getParam('create');
		$uniqname = uniqid() . '.' . pathinfo($imagename, PATHINFO_EXTENSION);
		$source = osc_content_path() . "plugins/responsive_slides/media/" . $imagename;
		$destination = osc_content_path() . "plugins/responsive_slides/media/". $uniqname;
		$actualimagename = ModelSlides::newInstance()->getSlidesByImage($imagename);
		if (copy($source, $destination )) {
			ModelSlides::newInstance()->saveSlides($uniqname,$actualimagename['imagename'],$caption,$link);
			?>
			<div class="slidersuccess">
			<?php _e('The slider has been updated.', 'responsive_slides'); ?>
			</div>
			<?php
			echo "<script>location.href='".osc_admin_render_plugin_url("responsive_slides/admin/list.php")."'</script>";
		}
		else{
			?>
			<div class="slidererror">
			<?php _e('There was an error uploading the file, please try again!', 'responsive_slides'); ?>
			</div>
			<?php
		}		
	}			
}
?>
<div class="rslides_menu">
	<ul>
		<li class="active"><a href="<?php echo osc_admin_render_plugin_url("responsive_slides/admin/create.php"); ?>"><?php _e('Create', 'responsive_slides'); ?></a></li>
		<li><a href="<?php echo osc_admin_render_plugin_url("responsive_slides/admin/list.php"); ?>"><?php _e('Manage', 'responsive_slides'); ?></a></li>
		<li><a href="<?php echo osc_admin_render_plugin_url("responsive_slides/admin/settings.php"); ?>"><?php _e('Settings', 'responsive_slides'); ?></a></li>
		<li><a href="<?php echo osc_admin_render_plugin_url("responsive_slides/admin/help.php"); ?>"><?php _e('Help', 'responsive_slides'); ?></a></li>
	</ul>
</div>
<div class="rslides_content">
	<h2 class="render-title"><?php _e('Add Slide', 'responsive_slides'); ?></h2>
	<div class="form-horizontal">
		<form method="post" action="<?php osc_admin_base_url(true); ?>" enctype="multipart/form-data">
			<input type="hidden" name="page" value="plugins" />
			<input type="hidden" name="action" value="renderplugin" />
			<input type="hidden" name="file" value="responsive_slides/admin/create.php" />
			<input type="hidden" name="option" value="stepone" />
			<input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
			<fieldset>
			<div class="form-row">
				<div class="form-label"><?php _e('Image', 'responsive_slides'); ?></div>
				<div class="form-controls">
					<input type="radio" name="create" value="1" checked />
					<input type="file" size="50" name="image" value="" />
				</div>
			</div>
			<div class="form-row">
				<div class="form-label"><?php _e('Caption','responsive_slides'); ?></div>
				<div class="form-controls">
					<input type="text" name="caption" value="" placeholder="<?php echo osc_esc_html(__('Enter caption','responsive_slides')); ?>" class="xlarge"/>
				</div>
			</div>
			<div class="form-row">
				<div class="form-label"><?php _e('Description','responsive_slides'); ?></div>
				<div class="form-controls">
					<textarea placeholder="Enter description" name="description" placeholder="<?php echo osc_esc_html(__('Enter description','responsive_slides')); ?>"></textarea>
				</div>
			</div>
			<div class="form-row">
				<div class="form-label"><?php _e('Link to URL','responsive_slides'); ?></div>
				<div class="form-controls">
					<input class="xlarge" type="text" name="link" value="" placeholder="<?php echo osc_esc_html(__('http://www.example.com','responsive_slides')); ?>" class="xlarge"/>
				</div>
			</div>
			</fieldset>
			
			<div class="form-actions">
				<button type="submit" class="btn btn-submit"><?php _e('Save changes', 'responsive_slides'); ?></button>
			</div>
		</form>
	</div>
</div>
