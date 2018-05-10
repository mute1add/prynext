<?php

if ((!defined('ABS_PATH')))
    exit('ABS_PATH is not loaded. Direct access is not allowed.');
/*
 * Copyright (C) 2016 Puiu Calin
 * This program is a commercial software: is forbidden to use this software without licence, 
 * on multiple installations, and by purchasing from other source than those authorized for the sale of software.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

switch (Params::getParam('case')) {
    case('date'):
        $date_color = Params::getParam('date_color');
        if (osc_set_preference('debug_date_color', $date_color, 'debug')) {
            $json['success'] = 'true';
        } else {
            $json['msg'] = __("An error occurred when saving the color in database", 'debug');
            $json['success'] = 'false';
        }
        echo json_encode($json);
        break;
    case('text'):
        $text_color = Params::getParam('text_color');
        if (osc_set_preference('debug_text_color', $text_color, 'debug')) {
            $json['success'] = 'true';
        } else {
            $json['msg'] = __("An error occurred when saving the color in database", 'debug');
            $json['success'] = 'false';
        }
        echo json_encode($json);
        break;
}