<link href="<?php echo osc_base_url().'oc-content/plugins/responsive_slides/admin/admin.css'; ?>" rel="stylesheet" type="text/css" />
<?php 
$id = Params::getParam('id');
$caption = Params::getParam('caption');
$description = Params::getParam('description');
$link = Params::getParam('link');
$slidebyid= ModelSlides::newInstance()->getSlidesById($id);
if( Params::getParam('actions') == 'delete' ){
	unlink(osc_content_path() . "plugins/responsive_slides/media/" . $slidebyid['uniqname']);
	ModelSlides::newInstance()->deleteSlides($id);
	?>
	<div class="slidersuccess">
	<?php _e('The slide has been deleted.','responsive_slides'); ?>
	</div>
	<?php
	echo "<script>location.href='".osc_admin_render_plugin_url("responsive_slides/admin/list.php")."'</script>";
}
else if( Params::getParam('option') == 'stepone' ) {
	if( Params::getParam('update') == 1) {
		// Where the file is going to be placed
		$target_path = osc_content_path() . "plugins/responsive_slides/media/";
		// Check that the uploaded file is actually an image
		$valid_mime_types = array("image/jpg","image/jpeg","image/png","image/gif");
		if (in_array($_FILES["image"]["type"], $valid_mime_types)){
			$destination = osc_content_path() . "plugins/responsive_slides/media/" . $_FILES["image"]["name"];
			$imagename = $_FILES["image"]["name"];
			$uniqname = uniqid() . '.' . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
			$destination = osc_content_path() . "plugins/responsive_slides/media/" . $uniqname;		
			if(move_uploaded_file($_FILES["image"]["tmp_name"], $destination)){
				unlink(osc_content_path() . "plugins/responsive_slides/media/" . $slidebyid['uniqname']);
				ModelSlides::newInstance()->updateSlides($id,$uniqname,$imagename,$caption,$description,$link);
				?>
				<div class="slidersuccess">
				<?php _e('The slide has been updated.','responsive_slides'); ?>
				</div>
				<?php
				echo "<script>location.href='".osc_admin_render_plugin_url("responsive_slides/admin/list.php")."'</script>";
			} 
			else{
				?>
				<div class="slidererror">
				<?php _e('There was an error uploading the file, please try again!','responsive_slides'); ?>
				</div>
				<?php
			}
		}
		else{
			?>
			<div class="slidererror">
			<?php _e('File type not allowed, Allowed file: *.jpg,*.jpeg,*.png,*.gif','responsive_slides'); ?>
			</div>
			<?php
		}	
	}
	else{
		$imagename = Params::getParam('update');
		$uniqname = uniqid() . '.' . pathinfo($imagename, PATHINFO_EXTENSION);
		$source = osc_content_path() . "plugins/responsive_slides/media/" . $imagename;
		$destination = osc_content_path() . "plugins/responsive_slides/media/". $uniqname;
		$actualimagename = ModelSlides::newInstance()->getSlidesByImage($imagename);
		if (copy($source, $destination )) {
			unlink(osc_content_path() . "plugins/responsive_slides/media/" . $slidebyid['uniqname']);
			ModelSlides::newInstance()->updateSlides($id,$uniqname,$actualimagename['imagename'],$caption,$description,$link);
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
			<?php _e('There was an error uploading the file, please try again!','responsive_slides'); ?>
			</div>
			<?php
		}	
		
	}
}
?>
<div class="rslides_menu">
	<ul>
		<li><a href="<?php echo osc_admin_render_plugin_url("responsive_slides/admin/create.php"); ?>"><?php _e('Create', 'responsive_slides'); ?></a></li>
		<li class="active"><a href="<?php echo osc_admin_render_plugin_url("responsive_slides/admin/list.php"); ?>"><?php _e('Manage', 'responsive_slides'); ?></a></li>
		<li><a href="<?php echo osc_admin_render_plugin_url("responsive_slides/admin/settings.php"); ?>"><?php _e('Settings', 'responsive_slides'); ?></a></li>
		<li><a href="<?php echo osc_admin_render_plugin_url("responsive_slides/admin/help.php"); ?>"><?php _e('Help', 'responsive_slides'); ?></a></li>
	</ul>
</div>
<div class="rslides_content">
	<h2 class="render-title"><?php _e('Update Slide','responsive_slides'); ?></h2>
	<form method="post" action="<?php osc_admin_base_url(true); ?>" enctype="multipart/form-data">
	<input type="hidden" name="page" value="plugins" />
	<input type="hidden" name="action" value="renderplugin" />
	<input type="hidden" name="file" value="responsive_slides/admin/edit.php" />
	<input type="hidden" name="option" value="stepone" />
	<input type="hidden" name="id" value="<?php echo $slidebyid['id']; ?>" />
	<input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
	<div class="form-horizontal">
	<fieldset>
	<div class="form-row">
		<div class="form-label"><?php _e('Upload image', 'responsive_slides'); ?></div>
		<div class="form-controls">
		<input type="radio" name="update" value="1" checked />  
		<input type="file" size="50" name="image" value="" />
	</div>
	</div>
	<div class="form-row">
		<strong><?php _e('OR','responsive_slides'); ?></strong>
	</div>
	<div class="form-row">
		<?php _e('Select a image:','responsive_slides'); ?>
	</div>
	<div class="form-row">			
		<table cellspacing="0" cellpadding="0" class="table">
		<thead>
			<tr>
				<th class="col-bulkactions "><?php _e('ID', 'responsive_slides'); ?></th>
				<th class="col-file "><?php _e('File', 'responsive_slides'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php $slides = ModelSlides::newInstance()->getSlides(); ?>
			<?php foreach($slides as $slide) { ?>
			<tr>
				<td class="col-bulkactions"><input type="radio" name="update" value="<?php echo osc_esc_html($slide['uniqname']); ?>" <?php if($slide['uniqname'] == $slidebyid['uniqname']){ echo 'checked'; } ?>/></td>
				<td class="col-file"><div id="media_list_pic"><img style="max-width: 140px; max-height: 120px;" src="<?php echo osc_base_url().'oc-content/plugins/responsive_slides/media/'.$slide['uniqname']; ?>"></div> <div id="media_list_filename"><?php echo $slide['imagename']; ?></div></td>
			</tr>
			<?php } ?>
		</tbody>
		</table>
	</div>		
	<div class="form-row">
		<div class="form-label"><?php _e('Caption', 'responsive_slides'); ?></div>
		<div class="form-controls">
			<input type="text" name="caption" value="<?php echo osc_esc_html($slidebyid['caption']); ?>" placeholder="<?php echo osc_esc_html(__('Enter caption','responsive_slides')); ?>" class="xlarge"/>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label"><?php _e('Description', 'responsive_slides'); ?></div>
		<div class="form-controls">
		<textarea name="description" placeholder="<?php echo osc_esc_html(__('Enter description','responsive_slides')); ?>" ><?php echo $slidebyid['description']; ?></textarea>
		</div>
	</div>
	<div class="form-row">
		<div class="form-label"><?php _e('Link to URL', 'responsive_slides'); ?></div>
		<div class="form-controls">
			<input class="xlarge" type="text" name="link" value="<?php echo osc_esc_html($slidebyid['link']); ?>" placeholder="<?php echo osc_esc_html('http://www.example.com'); ?>" class="xlarge" />
		</div>
	</div>
	</fieldset>
	
	<div class="form-actions">
		<button type="submit" class="btn btn-submit"><?php _e('Save changes','responsive_slides'); ?></button>
	</div>
	</div>
	</form>
</div>