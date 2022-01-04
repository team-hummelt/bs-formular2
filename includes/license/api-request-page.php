<?php
defined('ABSPATH') or die();

/**
 * REGISTER PLUGIN
 * @package Hummelt & Partner WordPress-Plugin
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */

global $hupa_license_exec;
$data = json_decode(file_get_contents("php://input"));

$options = get_option($this->basename . '_server_api');

if(isset($data)):
if($data->make_id == 'make_exec'){
    global $hupa_license_exec;
    $makeJob = $hupa_license_exec->make_api_exec_job($data);
    $backMsg =  [
        'msg' => $makeJob->msg,
        'status' => $makeJob->status,
    ];
    echo json_encode($backMsg);
    exit();
}

if($data->client_id !== $options['client_id']){
    $backMsg =  [
        'reply' => 'ERROR',
        'status' => false,
    ];
    echo json_encode($backMsg)."<br><br>";
    exit('ERROR');
}

switch ($data->make_id) {
    case'send_versions':
        $backMsg = [
            'status' => true,
            'theme_version' => 'v'.$this->version,
        ];
        break;
    default:
        $backMsg = [
          'status' => false
        ];
}

$response = new stdClass();
if($data) {
    echo json_encode($backMsg);
}
endif;
