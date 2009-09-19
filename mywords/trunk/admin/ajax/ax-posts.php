<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* This function show an error message
*/
function return_error($msg, $token=true, $redirect=''){
    global $xoopsSecurity;
    
    $ret['error'] = $msg;
    if ($token) $ret['token'] = $xoopsSecurity->createToken();
    if ($redirect!='') $ret['redirect'] = $redirect;
    
    echo json_encode($ret);
    die();
    
}

include '../header.php';

global $xoopsLogger;
$xoopsLogger->renderingEnabled = false;
error_reporting(0);
$xoopsLogger->activated = false;

extract($_POST);

/*if(!$xoopsSecurity->check() || !$xoopsSecurity->checkReferer()){
    $ret = array(
        'error'=>__('You are not allowed to do this operation!','admin_mywords')
    );
    echo json_encode($ret);
    die();
}*/

    
if (isset($id)){
    if($id<=0){
        return_error(__('You must provide a valid post ID','admin_mywords'), 0, 'posts.php');
        die();
    }
        
    $post = new MWPost($id);
    if($post->isNew()){
        return_error(__('You must provide an existing post ID','admin_mywords'), 0, 'posts.php');
        die();
    }
        
    $query = 'op=edit&id='.$id;
    $edit = true;
        
} else {
    $query = 'op=new';
    $post = new MWPost();
    $edit = false;
}
    
/**
* @todo Insert code to verify token
*/
    
// Verify title
if ($title==''){
    return_error(__('You must provide a title for this post','admin_mywords'), true);
    die();
}

if (!isset($shortname) || $shortname==''){
    $shortname = TextCleaner::getInstance()->sweetstring($title);
} else {
    $shortname = TextCleaner::getInstance()->sweetstring($shortname);
}

// Check content
if ($content==''){
    return_error(__('Content for this post has not been provided!','admin_mywords'), true);
    die();
}
    
// Categories
if (!isset($categories) || empty($categories)){
    $categories = array(MWFunctions::get()->default_category_id());
}
    
// Check publish options
if ($visibility=='password' && $vis_password==''){
    return_error(__('You must provide a password for this post or select another visibility option','admin_mywords'), true);
    die();
}
    
$time = explode("-", $schedule);
$schedule = mktime($time[3], $time[4], 0, $time[1], $time[0], $time[2]);
if ($schedule<=time())
    $schedule = 0;

$author = !isset($author) || $author<=0 ? $xoopsUser->uid() : $author;
$authorname = !isset($author) || $author<=0 ? $xoopsUser->uname() : MWFunctions::author_name($author);

// Add Data
$post->setVar('title', $title);
$post->setVar('shortname', $shortname);
$post->setVar('content', $content);
$post->setVar('status', $status);
$post->setVar('visibility', $visibility);
$post->setVar('schedule', $schedule);
$post->setVar('password', $password);
$post->setVar('author', $author);
$post->setVar('comstatus', isset($comstatus) ? $comstatus : 1);
$post->setVar('pingstatus', isset($pingstatus) ? $pingstatus : 1);
$post->setVar('authorname', $authorname);

if ($schedule<=time()){
    $post->setVar('pubdate', time());
} else {
    $post->setVar('pubdate', 0);
}

if (MWFunctions::post_exists($post)){
    return_error(__('There is already another post with same title for same date','admin_mywords'), $xoopsSecurity->createToken());
    die();
}

// Add categories
$post->add_categories($categories);

// Add tags
$post->add_tags($tags);

foreach($meta as $data){
    $post->add_meta($data['key'], $data['value']);
}


if ($post->save()){
    if (!$edit) $xoopsUser->incrementPost();
    $rtn = array(
        'message' => $edit ? __('Post updated successfully','admin_mywords') : __('Post saved successfully','admin_mywords'),
        'token' => $xoopsSecurity->createToken(),
        'link' => '<strong>'.__('Permalink:','admin_mywords').'</strong> '.$post->permalink()
    );
    echo json_encode($rtn);
    die();
} else {
    return_error(__('Errors happened while trying to save this post.','admin_mywords').'<br />'.$post->errors(), true);
    die();
}
