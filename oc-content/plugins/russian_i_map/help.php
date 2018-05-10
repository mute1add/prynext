<?php
/*
Plugin Name: Russian Interactive Map
Plugin URI: https://osclass-pro.com
Description: Russian Interactive Map
Version: 1.0.1
Author: DIS
Author URI: https://osclass-pro.com
Short Name: russian_i_map
*/
?>

<div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
    <div style="padding: 0 20px 20px;">
        <div>
            <fieldset>
                <legend>
                    <h1><?php _e('Help', 'russian_i_map'); ?></h1>
                </legend>
				     <h2>
                    <?php _e('Map size you can configure in map_ru.php 86 line. Default size :width: 960px; height: 500px', 'russian_i_map'); ?>
                </h2>
                <p>
                    <?php _e('Color you can set in 19 line: colorRegion = \'#14A7D1\' - color of regions. In code exist comments for Help.', 'russian_i_map'); ?>
                </p>
                <p>
                    <?php _e('Insert in you theme file, for example in main.php this code:', 'russian_i_map'); ?>:
                </p>
                <pre>
					&lt;?php if(function_exists(russian_i_map)){echo russian_i_map();}?&gt;
                </pre>

                <br/>
                
                <center>

                    <h2>
					<?php _e('Premium themes and plugins:', 'russian_i_map'); ?> <a href="https://osclass-pro.com" target="_blank"><?php _e('osclass-pro.com', 'russian_i_map'); ?></a>
                    </h2>
					<h2>
                    <?php _e('Russian Osclass forum:', 'russian_i_map'); ?> <a href="https://4osclass.net" target="_blank">4osclass.net</a>
                    </h2>

                </center>

            </fieldset>
        </div>
    </div>
</div>