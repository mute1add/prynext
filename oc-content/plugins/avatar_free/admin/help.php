<h1><?php _e('Avatar Free Plugin', 'avatar_free'); ?></h1>
<p><?php _e('Please edit your user-profile.php add a name attribute on form tag ex: &lt;form <b>name="profile"</b> for picture validation and enctype="multipart/form-data".', 'avatar_free'); ?></p>
<p><?php _e('Check user-profile.php file and add .', 'avatar_free'); ?></p>&lt;? osc_run_hook('user_profile_form', osc_user()); ?&gt;
<h3><?php _e('Required avatar', 'avatar_free'); ?></h3>
<p><?php _e('Edit', 'avatar_free'); ?> <b>avatar_free/index.php line </b><?php _e('uncomment those lines.', 'avatar_free'); ?></p>
<h3><?php _e('What the plugin does?', 'avatar_free'); ?></h3>
<p><?php _e('The avatar plugin show the profile picture upload button on Register page, Profile page, and admin user page. user can upload thir picture while register', 'avatar_free'); ?>.</p>
<h3><?php _e('How to use', 'avatar_free'); ?></h3>
<p><?php _e('Use this code to show the picture of user', 'avatar_free'); ?> <br /><br />
<?php _e('Get picture of item user', 'avatar_free'); ?> : <code>&lt;?php echo show_avatarfree(osc_item_user_id()); ?&gt;</code><br /><br />
<?php _e('Get picture of logged user', 'avatar_free'); ?> : <code>&lt;?php echo show_avatarfree(osc_logged_user_id()); ?&gt;</code><br /><br />
<?php _e('Get picture of public profile user', 'avatar_free'); ?> : <code>&lt;?php echo show_avatarfree(osc_user_id()); ?&gt;</code>
</p>
<h3><?php _e('About', 'avatar_free'); ?></h3>
<p>
<?php _e('Plugin Name', 'avatar_free'); ?>: <?php _e('Avatar Free', 'avatar_free'); ?><br />
<?php _e('Version', 'avatar_free'); ?>    : 1.1.1<br />
</p>

<h3><?php _e('Useful links', 'avatar_free'); ?></h3>
<a href="https://osclass-pro.ru"><b><?php _e('Premium themes and plugins', 'avatar_free'); ?></b></a> | <a href="https://osclass.pro"><?php _e('All about Osclass', 'avatar_free'); ?></a> | <a href="https://4osclass.net"><?php _e('Forum', 'avatar_free'); ?></a>