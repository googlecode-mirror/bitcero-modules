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
    global $xoopsDB;
    
    xoops_cp_header();
    
    xoops_cp_footer();    
    
}



$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    default:
        cm_show_messages();
        break;
}