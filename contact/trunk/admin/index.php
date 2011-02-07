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
    global $xoopsDB, $xoopsModuleConfig;
    
    // Styles
    RMTemplate::get()->add_style('admin.css', 'contact');
    
    // Pagination
    $page = rmc_server_var($_GET, 'page', 1);
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
    
    xoops_cp_header();
    
    include RMTemplate::get()->get_template('admin/ct_dashboard.php', 'module', 'contact');
    
    xoops_cp_footer();    
    
}



$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    default:
        cm_show_messages();
        break;
}