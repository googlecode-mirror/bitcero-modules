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

$rmc_config = RMFunctions::configs();

if (!$rmc_config['enable_comments']){
    redirect_header(rmc_server_var($_REQUEST, 'comment_url', XOOPS_URL), 1, __('Sorry, comments has been disabled by administrator', 'rmcommon'));
    die();
}

if ($action=='save'){

	if (!$xoopsSecurity->checkReferer()){
	    redirect_header(XOOPS_URL, 2, __('You are not allowed to do this action!', 'rmcommon'));
	    die();
	}
        
    if (!$xoopsUser && !$config['anonymous_comments']){
        return;
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
	
    // Check if user is allowed to edit this comment
    if (!$xoopsUser){
        redirect_header(rmc_server_var($_REQUEST, 'comment_url', XOOPS_URL), 1, __('You are not allowed to edit this comment!', 'rmcommon'));
        die();
    }
    
    $id = rmc_server_var($_GET, 'id', 0);
    if ($id<=0){
        redirect_header(rmc_server_var($_REQUEST, 'comment_url', XOOPS_URL), 1, __('Please specify a comment', 'rmcommon'));
        die();
    }
    
    $comment = new RMComment($id);
    if ($comment->isNew()){
        redirect_header(rmc_server_var($_REQUEST, 'comment_url', XOOPS_URL), 1, __('Specified comment does not exists!', 'rmcommon'));
        die();
    }
    
    // Check if user is owner
    $editor = new RMCommentUser($comment->getVar('user'));
    if ($xoopsUser->uid()!=$editor->getVar('xuid') && !$xoopsUser->isAdmin($comment->getVar('id_obj'))){
        redirect_header(rmc_server_var($_REQUEST, 'comment_url', XOOPS_URL), 1, __('You are not allowed to edit this comment!', 'rmcommon'));
        die();
    }
    
	include '../../header.php';
    
    $cpath = XOOPS_ROOT_PATH.'/modules/'.$comment->getVar('id_obj').'/class/'.$comment->getVar('id_obj').'controller.php';
    
    if(is_file($cpath)){
        include $cpath;
        $class = ucfirst($comment->getVar('id_obj')).'Controller';
        $controller = new $class();
    }
    
    $form = new RMForm(__('Edit Comment', 'rmcommon'), 'editComment', 'comments.php');
    $form->addElement(new RMFormLabel(__('In reply to', 'rmcommon'), $controller ? $controller->get_item($comment->getVar('params'), $comment):''));
    $form->addElement(new RMFormLabel(__('Posted date','rmcommon'), formatTimestamp($comment->getVar('posted'), 'mysql')));
    $form->addElement(new RMFormLabel(__('Module','rmcommon'), $comment->getVar('id_obj')));
    
    if($xoopsUser->isAdmin()){
        $user = new RMCommentUser($comment->getVar('user'));
        $ele = new RMFormUser(__('Poster','rmcommon'), 'user', false, $user->getVar('xuid')>0 ? $user->getVar('xuid') : 0);
        $form->addElement($ele);
    }
    
    if($xoopsUser->isAdmin($comment->getVAr('id_obj'))){
        $ele = new RMFormRadio(__('Status','rmcommon'), 'status', 1, 0, 2);
        $ele->addOption(__('Approved', 'rmcommon'), 'approved', $comment->getVar('status')=='approved'?1:0);
        $ele->addOption(__('Unapproved', 'rmcommon'), 'waiting', $comment->getVar('status')=='waiting'?1:0);
        $form->addElement($ele);
    }
    
    $form->addElement(new RMFormTextArea(__('Content','rmcommon'), 'content', null, null, $comment->getVar('content','e'),'100%','150px'), true);
    $form->addElement(new RMFormHidden('action', 'saveedited'));
    $ele = new RMFormButtonGroup();
    $ele->addButton('sbt', __('Update Comment','rmcommon'), 'submit');
    $ele->addButton('cancel', __('Cancel','rmcommon'), 'button', 'onclick="history.go(-1);"');
    $form->addElement($ele);
    
    $form->display();
    
    include '../../footer.php';
	
}