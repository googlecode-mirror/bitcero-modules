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

$action = rmc_server_var($_REQUEST, 'action', '');
/**
* This file handle comments from Common Utilties
*/

if ($action=='save'){

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
	$object = strtolower(rmc_server_var($_POST, 'object', ''));
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
	    
	    $db->queryF("INSERT INTO ".$db->prefix("rmc_comusers")." (`xuid`,`name`,`email`,`url`) VALUES ('$xuid','$name','$email','$url')");
	    $uid = $db->getInsertId();
	    
	} else {
	    
	    $db->queryF("UPDATE ".$db->prefix("rmc_comusers")." SET `name`='$name',`email`='$email',`url`='$url' WHERE id_user='$uid'");
	    
	}

	$comment = new RMComment();
	$comment->setVar('id_obj', $object);
	$comment->setVar('type', $type);
	$comment->setVar('parent', isset($parent) ? $parent : 0);
	$comment->setVar('params', $params);
	$comment->setVar('content', $text);
	$comment->setVar('user', $uid);
	$comment->setVar('ip', $_SERVER['REMOTE_ADDR']);
	$comment->setVar('posted', time());

	// Check if comment must be approved
	$rmc_config = RMFunctions::get()->configs();
	if ($xoopsUser && $rmc_config['approve_reg_coms']){
		$comment->setVar('status', 'approved');
	} elseif(!$xoopsUser && $rmc_config['approve_anon_coms']){
		$comment->setVar('status', 'approved');
	} elseif($xoopsUser && $xoopsUser->isAdmin()){
		$comment->setVar('status', 'approved');
	}

	if (!$comment->save()){
	    
	    redirect_header($uri, 1, __('Comment could not be posted!','rmcommon').'<br />'.$comment->errors());
	    
	}

	if ($xoopsUser) $xoopsUser->incrementPost();
	RMEvents::get()->run_event('rmcommon.comment_saved', $comment, $uri);

	// Update comments number if object accepts this functionallity
	if (is_file(XOOPS_ROOT_PATH.'/modules/'.$object.'/class/'.$object.'controller.php')){
	    include_once XOOPS_ROOT_PATH.'/modules/'.$object.'/class/'.$object.'controller.php';
	    $class = ucfirst($object).'Controller';
	    if(class_exists($class)){
	        $controller = new $class();
	        if (method_exists($controller, 'increment_comments_number')){
	            $controller->increment_comments_number($comment);
	        }
	    }
	    
	}

	redirect_header($uri.'#comment-'.$comment->id(), 1, __('Comment posted successfully!','rmcommon'));
	
} elseif ($action=='edit') {
	
	echo "Hola";
	
}