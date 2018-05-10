<?php

/*
 * Copyright (C) 2017 Puiu Calin
 * This program is a commercial software: is forbidden to use this software without licence, 
 * on multiple installations, and by purchasing from other source than those authorized for the sale of software.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

if ((!defined('ABS_PATH')))
    exit('ABS_PATH is not loaded. Direct access is not allowed.');
if (!OC_ADMIN)
    exit('User access is not allowed.');

?>
<h2><?php _e('Help', 'currency'); ?></h2>
<p><strong><?php _e('How to use this plugin?', 'currency'); ?></strong></p>
<p><?php _e('Nothing more to do, just install the plugin and the plugin will use an hook available in search to add the filter for currency.', 'currency'); ?></p>
<p><strong><?php _e('How to include the currency selector in another area on search page?', 'currency'); ?></strong></p>
<p><?php _e('If you want to include the selector manual in other area from your search sidebar, just comment this line from  file index.php. The path for this file will be oc-content/plugins/currency/index.php.', 'currency'); ?>
<br />
<?php _e('Exclude the hook which include the currency selector like this:', 'currency'); ?>
<br />
<strong><span style="color:#2adb2a;font-size:16px;">//</span>osc_add_hook('search_form', 'currency_c_selector');</strong>
</p>
<p><?php _e('Using this function you can include the selector in another area:', 'currency'); ?></p>
<p><strong>&lt;?php currency_c_selector(); ?&gt;</strong></p>
<p><strong><?php _e('Make sure that the line is included in the search form.', 'currency'); ?></strong></p>