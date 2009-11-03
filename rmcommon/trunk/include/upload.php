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
//XoopsLogger::getInstance()->activated = false;
//XoopsLogger::getInstance()->renderingEnabled = false;

function error($message, $token = false){
	echo json_encode($message);
	die();
}
/**
* Handle uploaded image files only.
*/
include XOOPS_ROOT_PATH.'/modules/rmcommon/loader.php';

$security = rmc_server_var($_POST, 'security', 0);
$category = rmc_server_var($_POST, 'category', 0);

$data = TextCleaner::getInstance()->decrypt($security, true);
echo $data; die();
$data = explode("|", $data); // [0] = referer, [1] = session_id(), [2] = user, [3] = token


if (!$xoopsSecurity->check(true, $data[0])){
	error(__('You are not allowed to do this action','rmcommon'));
}

if ($category<=0){
	error(__('Sorry, category has not been specified!','rmcommon'));
}

$c = TextCleaner::getInstance()->crypt("Soy Eduardo", true);
echo $c.'<br />';
echo TextCleaner::getInstance()->decrypt($c, true);

die();