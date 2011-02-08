<?php
// $Id$
// --------------------------------------------------------------
// Contact
// A simple contact module for Xoops
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','dashboard');
require 'header.php';

/**
* Shows all messages sent by users and stored in database
*/
function cm_show_messages(){
    global $xoopsDB, $xoopsModuleConfig, $xoopsSecurity;
    
    // Styles
    RMTemplate::get()->add_style('admin.css', 'contact');
    
    // Pagination
    $page = rmc_server_var($_GET, 'page', 1);
    $page = $page<=0 ? 1 : $page;
    $result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("contactme"));
    list($num) = $xoopsDB->fetchRow($result);
    
    $limit = $xoopsModuleConfig['limit'];
    
    $tpages = ceil($num / $limit);
    $page = $page > $tpages ? $tpages : $page;

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('index.php?page={PAGE_NUM}');
    
    // Get messages
    $messages = array();
    $result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("contactme")." ORDER BY id_msg DESC LIMIT $start,$limit");
    
    $time = new RMTimeFormatter(0, __('%M% %d%, %Y%', 'contact'));
    
    while($row = $xoopsDB->fetchArray($result)){
        $msg = new CTMessage();
        $msg->assignVars($row);
        
        if($msg->getVar('xuid'))
            $user = new XoopsUser($msg->getVar('xuid'));
        
        $messages[] = array(
            'id' => $msg->id(),
            'subject' => $msg->getVar('subject'),
            'ip' => $msg->getVar('ip'),
            'email' => $msg->getVar('email'),
            'name' => $msg->getVar('name'),
            'company' => $msg->getVar('org'),
            'body' => $msg->getVar('body'),
            'phone' => $msg->getVar('phone'),
            'register' => $msg->getVar('register'),
            'xuid' => $msg->getVar('xuid'),
            'uname' => $msg->getVar('xuid')>0 ? $user->uname() : '',
            'date' => $time->format($msg->getVar('date'))
        );
    }
    
    RMTemplate::get()->add_local_script('admin.js', 'contact');
    RMTemplate::get()->add_local_script('jquery.checkboxes.js', 'rmcommon');
    
    xoops_cp_header();
    
    include RMTemplate::get()->get_template('admin/ct_dashboard.php', 'module', 'contact');
    
    xoops_cp_footer();    
    
}

/**
* Delete a set of selected messages provided by ids array 
*/
function cm_delete_messages(){
    global $xoopsSecurity, $xoopsDB;
    
    $ids = rmc_server_var($_POST, 'ids', array());
    $page = rmc_server_var($_POST, 'page', 1);
    
    if(!$xoopsSecurity->check()){
        redirectMsg('index.php?page='.$page, __('Session token expired!','contact'), 1);
        die();
    }
    
    if(empty($ids)){
        redirectMsg('index.php?page='.$page, __('Select at least one message to delete!','contact'), 1);
        die();
    }
    
    $sql = "DELETE FROM ".$xoopsDB->prefix("contactme")." WHERE id_msg IN (".implode(",", $ids).")";
    
    if($xoopsDB->queryF($sql)){
        redirectMsg('index.php?page='.$page, __('Messages deleted successfully!','contact'), 0);
    } else {
        redirectMsg('index.php?page='.$page, __('Messages could not be deleted!<br />'.$xoopsDB->error(),'contact'), 0);
    }
    
}


/**
* Shows the form to reply a message 
*/
function cm_reply_message(){
    global $xoopsModuleConfig, $xoopsSecurity;
    
    $id = rmc_server_var($_GET, 'id', 0);
    $page = rmc_server_var($_GET, 'page', 1);
    
    if($id<=0)
        redirectMsg('index.php?page='.$page, __('You must specify a message to reply!','contact'));
    
    $msg = new CTMessage($id);
    $subject = $msg->getVar('subject');
    
    $subject = (FALSE===strpos($subject, 'RE:')) ? 'RE: '.$subject : $subject;
    $name = $msg->getVar('name');
    $email = $msg->getVar('email');
    $date = formatTimestamp($msg->getVar('date'), __('M/d/Y - H:i:s','contact'));
    $ip = $msg->getVar('ip');
    
    if($xoopsModuleConfig['quote'])
        $quote = "\n\n\n--- ".__('Original text','contact')." ---\n".__('Received on:','contact')." $date\n".__('Remote address:','contact')." $ip\n\n".$msg->getVar('body', 'e');
    else
        $quote = '';
    
    include_once RMCPATH.'/class/form.class.php';
    $editor = new RMFormEditor('','message', '95%', '300px', $quote, 'simple');
    
    RMTemplate::get()->add_style('admin.css', 'contact');
    RMTemplate::get()->add_local_script('jquery.validate.min.js','rmcommon', 'include');
    RMTemplate::get()->add_local_script('admin.js','contact');
    
    xoops_cp_header();
    
    include RMTemplate::get()->get_template('admin/cm_reply.php', 'module', 'contact');
    
    xoops_cp_footer();
    
}


/**
* Sends a reply made from reply form
*/
function cm_send_reply(){
    global $xoopsSecurity, $xoopsModuleConfig, $xoopsConfig;
    
    $id = rmc_server_var($_POST, 'id', 0);
    $page = rmc_server_var($_POST, 'page', 1);
    
    if($id<=0){
        redirectMsg('index.php?page='.$page, __('You must specify a message ID','contact'), 1);
        die();
    }
    
    if(!$xoopsSecurity->check()){
        redirectMsg('index.php?page='.$page, __('Session token expired!','contact'), 1);
        die();
    }
    
    $msg = new CTMessage($id);
    
    if($msg->isNew()){
        redirectMsg('index.php?page='.$page, __('Sorry, specified message does not exists!','contact'), 1);
        die();
    }
    
    $subject = rmc_server_var($_POST, 'subject', '');
    $message = rmc_server_var($_POST, 'message', 1);
    
    if($subject=='' || $message==''){
        redirectMsg('index.php?action=reply&id='.$id.'&page='.$page, __('Please fill al required fields!','contact'), 1);
        die();
    }
    
    $xoopsMailer =& getMailer();
    $xoopsMailer->useMail();
    $xoopsMailer->setBody($message.'\n--------------\n'.__('Message sent with ContactMe!','contact').'\nFrom '.$xoopsModuleConfig['url']);
    $xoopsMailer->setToEmails($msg->getVar('email'));
    $xoopsMailer->setFromEmail($xoopsConfig['from']);
    $xoopsMailer->setFromName($xoopsConfig['fromname']);
    $xoopsMailer->setSubject($subject);
    
    if (!$xoopsMailer->send(true)){
        redirectMsg('index.php?action=reply&id='.$id.'&page='.$page, __('Message could not be delivered. Please try again.<br />'.$xoopsMailer->getErrors(),'contact'), 1);
        die();
    }
    
    redirectMsg('index.php?page='.$page, __('Message sent successfully!','contact'), 0);
    
}



$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    case 'reply':
        cm_reply_message();
        break;
    case 'delete':
        cm_delete_messages();
        break;
    case 'sendmsg':
        cm_send_reply();
        break;
    default:
        cm_show_messages();
        break;
}