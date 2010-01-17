<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include '../../mainfile.php';

/**
* This file handle comments from Common Utilties
*/

if (!$xoopsSecurity->checkReferer()){
    redirect_header(XOOPS_URL, 2, __('You are not allowed to do this action!', 'rmcommon'));
    die();
}

// Check if user is a Registered User
if(!$xoopsUser){
    
    $name = rmc_server_var($_POST, 'comment_name', '');
    $email = rmc_server_var($_POST, 'comment_email', '');
    $url = rmc_server_var($_POST, 'comment_url', '');
    $xuid = 0;
    
} else {
    
    $name = $xoopsUser->getVar('uname');
    $email = $xoopsUser->getVar('email');
    $url = $xoopsUser->getVar('url');
    $xuid = $xoopsUser->uid();
    
}

// Check uri
$uri = urldecode(rmc_server_var($_POST, 'uri', ''));
if (trim($uri)==''){
    header('loaction: '.XOOPS_URL);
    die();
}

// Check params
$params = rmc_server_var($_POST, 'params', '');
if (trim($params)==''){
    redirect_header($uri, 2, __('There are not params to save!','rmcommon'));
    die();
}

// Object type
$type = rmc_server_var($_POST, 'type', '');
if (trim($type)==''){
    redirect_header($uri, 2, __('Object type missing!','rmcommon'));
    die();
}

// Object name
$object = rmc_server_var($_POST, 'object', '');
if (trim($object)==''){
    redirect_header($uri, 2, __('Object name missing!','rmcommon'));
    die();
}

// Text
$text = rmc_server_var($_POST, 'comment_text', '');
if (trim($text)==''){
    redirect_header($uri, 2, __('You must write a message!','rmcommon'));
    die();
}

RMEvents::get()->run_event('rmcommon.comment_postdata', null);

// Save comment user
$db = Database::getInstance();
if($xoopsUser){
    
    $sql = "SELECT id_user FROM ".$db->prefix("rmc_comusers")." WHERE xuid=".$xoopsUser->uid();
    
} else {
    
    $sql = "SELECT id_user FROM ".$db->prefix("rmc_comusers")." WHERE email='$email'";
    
}

$result = $db->query($sql);
list($uid) = $db->fetchRow($result);

if ($uid<=0){
    
    $db->queryF("INSERT INTO ".$db->prefix("rmc_comusers")." (`xuid`,`name`,`email`) VALUES ('$xuid','$name','$email')");
    $uid = $db->getInsertId();
    
}

$comment = new RMComment();
$comment->setVar('id_obj', $object);
$comment->setVar('type', $type);
$comment->setVar('parent', isset($parent) ? $parent : 0);
$comment->setVar('params', $params);
$comment->setVar('content', $text);
$comment->setVar('user', $xuid);
$comment->setVar('ip', $_SERVER['REMOTE_ADDR']);

if ($comment->save()){
    if ($xoopsUser) $xoopsUser->incrementPost();
    RMEvents::get()->run_event('rmcommon.comment_saved', $comment, $uri);
    redirect_header($uri.'#comment-'.$comment->id(), 1, __('Comment posted successfully!','rmcommon'));
    
} else {
    
    redirect_header($uri, 1, __('Comment could not be posted!','rmcommon').'<br />'.$comment->errors());
    
}