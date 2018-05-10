<?php

if ((!defined('ABS_PATH')))
    exit('ABS_PATH is not loaded. Direct access is not allowed.');
switch (Params::getParam('case')) {
    case('digits'):
        $option = Params::getParam('option');
        if (osc_set_preference('hide_digits', $option, 'phone')) {
            $json['success'] = 'true';
            $json['option'] = $option;
        } else {
            $json['msg'] = __("An error occurred when saving the settings", 'phone');
            $json['success'] = 'false';
            $json['option'] = $option;
        }
        echo json_encode($json);
        break;
}