<div class="bg-white">
    <ul class="nav nav-tabs nav-tabs-alt nav-md vpadder-lg">
        <li class="<?php echo (Params::getParam("route") == mdh_current_plugin_name())?"active": ""; ?>">
            <a href="<?php echo mdh_availability_url(); ?>"><?php _e("Settings",mdh_current_plugin_name()); ?></a>
        </li>
        <li class="">
            <a href="<?php echo osc_plugin_configure_url(mdh_current_plugin_name(true)) ?>"><?php _e("Categories",mdh_current_plugin_name()); ?></a>
        </li>
        <li class="">
            <a href="http://wearemadhouse.wordpress.com"><?php _e("Help",mdh_current_plugin_name()); ?></a>
        </li>
        <li class="">
            <a class="" href="http://market.osclass.org/user/profile/5" target="_blank"><span class="label label-primary"><?php _e("See more plugins by Madhouse",mdh_current_plugin_name()); ?></span></a>
        </li>
    </ul>
</div>