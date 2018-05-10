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
    case('digits'):
        $option = Params::getParam('option');
        if (osc_set_preference('hide_digits', $option, 'telephone')) {
            $json['success'] = 'true';
            $json['option'] = $option;
        } else {
            $json['msg'] = __("An error occurred when saving the settings", 'telephone');
            $json['success'] = 'false';
            $json['option'] = $option;
        }
        echo json_encode($json);
        break;
}