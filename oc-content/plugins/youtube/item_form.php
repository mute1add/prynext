<h2><?php _e('Youtube video', 'youtube'); ?></h2>
<div class="box">
    <div class="row">
        <?php printf( __( 'Enter the youtube url, i.e.: <em>%s</em> or <em>%s</em>', 'youtube' ), 'https://www.youtube.com/watch?v=kfaJn8plWfk', 'https://www.youtube.com/v/kfaJn8plWfk') ; ?>
    </div>
    <div class="row" style="width: 500px;">
        <input type="text" name="s_youtube" value="<?php echo $detail['s_youtube'] ; ?>" />
    </div>
</div>