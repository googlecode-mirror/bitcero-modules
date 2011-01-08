<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include '../../../mainfile.php';

// Deactivate the logger
error_reporting(0);
$xoopsLogger->activated = false;

function response($data, $error=0, $token=0){
    
    if($token)
        $data = array_merge($data, array('token'=>$xoopsSecurity->getTokenHTML()));
    
    $data = array_merge($data, array('error'=>$error));
        
    echo json_encode($data);
    die();
    
}

// Check Security settings
if(!$xoopsSecurity->checkReferer(1)){
    _e('Operation not allowed!','rmcommon');
    die();
}

$mod = rmc_server_var($_POST, 'module', '');
$id = rmc_server_var($_POST, 'block', '');
$token = rmc_server_var($_POST, 'XOOPS_TOKEN_REQUEST', '');

/*if (!$xoopsSecurity->check()){
    _e('Sorry, you are not allowed to view this page','rmcommon');
    die();
}*/

if($mod=='' || $id==''){
    
    $data = array(
        'error' => 1,
        'message' => __('The block specified seems to be invalid. Please try again.','rmcommon')
    );
    
}

response(array('mod'=>$mod, 'id'=>$id), 0, 1);

die();