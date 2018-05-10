<?php
/*
Plugin Name: Avatar Free Plugin
Plugin URI: https://osclass.pro
Description: Avatars for site users.
Version: 1.1.1
Author: Media.Dmj and osclass.pro
Author URI: https://osclass.pro
Short name: Avatar Free Plugin
Plugin update URI: avatar-free
*/

include "ModelAvatarfree.php";

/* Install Plugin */
function avatar_install() {
	ModelAvatarfree::newInstance()->import('avatar_free/struct.sql');
	if (!file_exists(osc_content_path()."/plugins/avatar_free/avatar/")) {
		mkdir(osc_content_path()."/plugins/avatar_free/avatar/", 0755, true);
		fopen(osc_content_path()."/plugins/avatar_free/avatar/index.php", 'a');
	}
}

/* Uninstall Plugin */
function avatar_uninstall() {
	ModelAvatarfree::newInstance()->uninstall();
}


function insertAvatarfree($userId){
	$upload_directory = osc_content_path().'/plugins/avatar_free/avatar/';
	$safe_filename = preg_replace(
                     array("/\s+/", "/[^-\.\w]+/"),
                     array("_", ""),
                     trim($_FILES['avatar']['name'])); 
					 
	$ext = pathinfo($safe_filename, PATHINFO_EXTENSION);
	move_uploaded_file (
                 $_FILES['avatar']['tmp_name'],
                 $upload_directory.$userId.'_avatar.'.$ext);
				 
	$last_added = ModelAvatarfree::newInstance()->getAvatarfree($userId);
  	if($last_added !="" ) { 
		ModelAvatarfree::newInstance()->updateAvatarfree($userId.'_avatar.'.$ext, $userId);
  	} else {
		ModelAvatarfree::newInstance()->insertAvatarfree($userId.'_avatar.'.$ext, $userId);
	}
}



function show_avatarfree($user) {
	$avatar = ModelAvatarfree::newInstance()->getAvatarfree($user); 
		if($avatar){?>
      	<img class="avatar" style="border: 1px solid rgb(221, 221, 221); background: rgb(255, 255, 255) none repeat scroll 0% 0%; padding: 5px; border-radius: 4px; margin-bottom: 5px;" width="130" class="img-thumbnail" src="<?php echo osc_base_url()."oc-content/plugins/avatar_free/avatar/". $avatar.jpg; ?>" />
  		 <?php } else { ?>
		 	<img class="avatar no-avatar" style="border: 1px solid rgb(221, 221, 221); background: rgb(255, 255, 255) none repeat scroll 0% 0%; padding: 5px; border-radius: 4px; margin-bottom: 5px;" width="130" class="img-thumbnail"  src="<?php echo osc_base_url()."oc-content/plugins/avatar_free/no-avatar.jpg";?>" />
		<?php }
		

}
function avatarfree_form(){ ?>
	<div class="control-group">
   		<label class="control-label" for="password"><?php _e('Avatar', 'avatar_free'); ?></label>
         <div class="controls">
    		<?php show_avatarfree(osc_user_id()); ?><br />
        </div>
        <div class="controls">
            <div id="text">
            <input style="display:none;" id="pAvatar"  name="avatar" type="file" />
			<label style="cursor:pointer;" for="pAvatar"><img style="width:10%; margin-right:10px; float:left;"  src="<?php echo osc_base_url()."oc-content/plugins/avatar_free/upload-arrow.png";?>"><h4> Upload photo</h4></label>
            <span id="lblError" style="color: red;"></span>
            </div>
           	
        </div>
    </div>
    <script type="text/javascript">
	    $(document).ready(function() {
	        $(".user-profile form").attr("enctype", "multipart/form-data");
			$("form[name='register']").attr("enctype", "multipart/form-data");
			
			
	    });
	</script>
    <?php if( osc_get_osclass_section() =="profile"){?>
    <script type="text/javascript" src="<?php echo osc_base_url().'oc-includes/osclass/assets/js/jquery.validate.min.js';?>"></script>
   	<?php } ?>
    <script type="text/javascript" src="<?php echo osc_base_url().'oc-content/plugins/avatar_free/js/additional-methods.min.js';?>"></script>
    
    
   	<script type="text/javascript">
			$.validator.addMethod('filesize', function (value, element, param) {
			    return this.optional(element) || (element.files[0].size <= param)
			});

			
			$("form[name='register'], form[name='profile']").validate({
			  rules: {
			    'avatar': {
			      <?php if (!OC_ADMIN) { ?>
			      //required: true,
			      <?php } ?>
			      extension: "png|jpe?g",
			      filesize: 3145728
			    }
			  },
			   messages:{
			        'avatar':{
			           <?php if (!OC_ADMIN) { ?>
			           //required : "<?php //echo osc_esc_js(__('Please upload at least a document','avatar_free')); ?>",
			           <?php } ?>
			           extension:"<?php echo osc_esc_js(__('Only png, jpg formats are allowed!','avatar_free')); ?>",
			           filesize: "<?php echo osc_esc_js(__('Size should less than 3MB','avatar_free')); ?>"
			        }
			    }
			});
			
	</script>

	<style type="text/css">
	label.error {
			color:#ff0000;
			display: block;
		}
	</style>


<?php }


function avatarfree_user_menu() {
	echo '<li style="background:#e7e7e7;"><a href="' . osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'admin/help.php') . '" >' . __('Avatar', 'avatar_free') . '</a></li>';
}


osc_add_hook('admin_menu', 'avatarfree_user_menu');
osc_add_hook('user_register_form', 'avatarfree_form');
osc_add_hook('user_profile_form', 'avatarfree_form');


osc_add_hook('user_register_completed', 'insertAvatarfree');
osc_add_hook('user_edit_completed', 'insertAvatarfree');

osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', 'avatar_uninstall') ;
osc_register_plugin(osc_plugin_path(__FILE__), 'avatar_install') ;
?>