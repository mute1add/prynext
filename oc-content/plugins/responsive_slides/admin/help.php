<link href="<?php echo osc_base_url().'oc-content/plugins/responsive_slides/admin/admin.css'; ?>" rel="stylesheet" type="text/css" />
<div class="rslides_menu">
	<ul>
		<li><a href="<?php echo osc_admin_render_plugin_url("responsive_slides/admin/create.php"); ?>"><?php _e('Create', 'responsive_slides'); ?></a></li>
		<li><a href="<?php echo osc_admin_render_plugin_url("responsive_slides/admin/list.php"); ?>"><?php _e('Manage', 'responsive_slides'); ?></a></li>
		<li><a href="<?php echo osc_admin_render_plugin_url("responsive_slides/admin/settings.php"); ?>"><?php _e('Settings', 'responsive_slides'); ?></a></li>
		<li class="active"><a href="<?php echo osc_admin_render_plugin_url("responsive_slides/admin/help.php"); ?>"><?php _e('Help', 'responsive_slides'); ?></a></li>
	</ul>
</div>
<div class="rslides_content">
	<h2 class="render-title"><?php _e('Help', 'responsive_slides'); ?></h2>

	<h3 class="render-title" style="margin-bottom: 0"><?php _e('What is ResponsiveSlides?', 'responsive_slides'); ?></h3>
	<p>ResponsiveSlides is a tiny jQuery plugin that creates a responsive slider.ResponsiveSlides.js is created by <a href="http://responsiveslides.com/" target="_blank" rel="nofollow">@viljamis</a></p>

	<h3 class="render-title" style="margin-bottom: 0"><?php _e('How to use?', 'responsive_slides'); ?></h3>
	<p>Add following code into your theme file, <pre>&lt;?php osc_run_hook('responsive_slider'); ?&gt;</pre> wherever you want the slider to appear. It would be usually the files called main.php (homepage) or header.php (header). And that's all the modification you need to do to your current theme files, the slider is configurable from the admin panel.</p>

	<h3 class="render-title" style="margin-bottom: 0"><?php _e('Credits', 'responsive_slides'); ?></h3>
	<p>Thanks to original authors <a href="http://forums.osclass.org/profile/?u=4527">RajaSekar</a> <?php _e('and', 'responsive_slides'); ?> <a href="http://forums.osclass.org/profile/?u=1728">trains58554</a></p>
</div>