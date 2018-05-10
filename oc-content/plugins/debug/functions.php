<?php
/*
 * Copyright (C) 2016 Puiu Calin
 * This program is a commercial software: is forbidden to use this software without licence, 
 * on multiple installations, and by purchasing from other source than those authorized for the sale of software.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

function debug_read_script() {
    ?>
    <script type="text/javascript">
        //update text color 
        function updateColorDate(jscolor) {
            $('.debug_area .debug_read_text span').css('color', '#' + jscolor);
        }
        function updateColorText(jscolor) {
            $('.debug_area .debug_read_text i').css('color', '#' + jscolor);
        }
        //update database
        function updateDate(jscolor) {
            var url = '<?php echo osc_ajax_plugin_url('debug/ajax/ajax.php') . '&case=date&date_color='; ?>' + jscolor;
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                success: function (data) {
                    var success = data.success;
                    var message = data.msg;
                    if (success == 'false') {
                        alert(message);
                    }
                }
            });
        }
        function updateText(jscolor) {
            var url = '<?php echo osc_ajax_plugin_url('debug/ajax/ajax.php') . '&case=text&text_color='; ?>' + jscolor;
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                success: function (data) {
                    var success = data.success;
                    var message = data.msg;
                    if (success == 'false') {
                        alert(message);
                    }
                }
            });
        }
    </script>
    <?php
}

function debug_read_style() {
    ?>
    <style type="text/css">
    <?php
//color from database date
    if (osc_get_preference('debug_date_color', 'debug') != '') {
        ?>
            .debug_area .debug_read_text span {color: #<?php echo osc_get_preference('debug_date_color', 'debug'); ?>}
        <?php
    }
    ?>
    <?php
//color from database text
    if (osc_get_preference('debug_text_color', 'debug') != '') {
        ?>
            .debug_area .debug_read_text i {color: #<?php echo osc_get_preference('debug_text_color', 'debug'); ?>}
        <?php
    }
    ?>
    </style>
    <?php
}

function debug_read_transform($size) {
    # size smaller then 1kb
    if ($size < 1024)
        return $size . ' Byte';
    # size smaller then 1mb
    if ($size < 1048576)
        return sprintf("%4.2f KB", $size / 1024);
    # size smaller then 1gb
    if ($size < 1073741824)
        return sprintf("%4.2f MB", $size / 1048576);
    # size smaller then 1tb
    if ($size < 1099511627776)
        return sprintf("%4.2f GB", $size / 1073741824);
    # size larger then 1tb
    else
        return sprintf("%4.2f TB", $size / 1073741824);
}

function debug_read_file($file, $start, $perpage) {
    $lines = file($file);
    $contains = array("[", "]");
    $replace = array("<span>[", "]</span><i>");
    $lines = array_slice($lines, $start, $perpage);
    
    
    foreach ($lines as  $line) {
      echo'<strong>';
      echo $result = str_replace($contains, $replace, $line) . '</i>';
      echo '</strong>';
    }
}

function debug_read_count_rows($file) {
    $lines = file($file);
    $result = count($lines);
    return $result;
}

function debug_calinbehtuk_rights() {
    ?>
    <div class="author">
        <div>
            <span class="text"><?php _e('2016 All rights reserved Debug Read File Plugin by Puiu Calin', 'debug'); ?></span>
            <span class="logo"><a href="http://theme.calinbehtuk.ro/"><img src="<?php echo osc_base_url() . 'oc-content/plugins/debug/admin/images/calinbehtuk.png'; ?>" /></a></span>
        </div>
    </div>
    <?php
}

function debug_other_products() {
    $one = array('title' => 'One theme', 'link' => 'http://market.osclass.org/themes/general/responsive-one-theme_196', 'image' => 'one');
    $rita = array('title' => 'Rita theme', 'link' => 'http://market.osclass.org/themes/general/premium-theme-rita_443', 'image' => 'rita');
    $ema = array('title' => 'Ema theme', 'link' => 'http://market.osclass.org/themes/premium-osclass-theme-ema_347', 'image' => 'ema');
    $message = array('title' => 'S Message Plugin', 'link' => 'http://market.osclass.org/plugins/messaging/s-message_532', 'image' => 'message');
    $first = array('title' => 'First One Plugin', 'link' => 'http://market.osclass.org/plugins/ad-management/first-one-plugin_426', 'image' => 'first');
    $wishlist = array('title' => 'Wishlist Plugin', 'link' => 'http://market.osclass.org/plugins/ad-management/wishlist-plugin_473', 'image' => 'wishlist');
    $eighteen = array('title' => 'Eighteen Plugin', 'link' => 'http://market.osclass.org/plugins/eighteen-18_281', 'image' => 'eighteen');
    //products
    $products = array('0' => $one, '1' => $rita, '2' => $ema, '3' => $message, '4' => $first, '5' => $wishlist, '6' => $eighteen);
    shuffle($products);
    $products = array_slice($products, 0, 4);
    ?>
    <div class="other_products">
        <h4><?php _e('You may like from our products', 'debug'); ?></h4>
        <ul>
            <?php foreach ($products as $product) { ?>
                <li><a target="blank" href="<?php echo $product['link']; ?>"><img title="<?php echo $product['title']; ?>" src="<?php echo osc_base_url() . 'oc-content/plugins/debug/images/products/'; ?><?php echo $product['image']; ?>.png" /><span><?php echo $product['title']; ?></span></a></li>
            <?php } ?>
        </ul>
    </div>
    <?php
}

function debug_read_pagination($pages, $page, $link) {
    $paginationCtrls = '';
    if ($pages != 1 && $pages != 0) {
        if ($page > 1) {
            $previous = $page - 1;
            $paginationCtrls .= '<a class="s_previ" href="' . $link . 's_page=' . $previous . '"> < </a>';
            for ($i = $page - 4; $i < $page; $i++) {
                if ($i > 0) {
                    $paginationCtrls .= '<a href="' . $link . 's_page=' . $i . '">' . $i . '</a>';
                }
            }
        }
        $paginationCtrls .= '<span class="curent_c">' . $page . '</span>';
        for ($i = $page + 1; $i <= $pages; $i++) {
            $paginationCtrls .= '<a href="' . $link . 's_page=' . $i . '">' . $i . '</a>';
            if ($i >= $page + 4) {
                break;
            }
        }
        if ($page != $pages) {
            $next = $page + 1;
            $paginationCtrls .= '<a class="s_next" href="' . $link . 's_page=' . $next . '"> > </a>';
        }
    }
    echo $paginationCtrls;
}
