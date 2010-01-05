<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','tags');
require('header.php');

include_once '../include/general.func.php';

/**
* Show all existing tags
*/
function show_tags(){
    global $xoopsModule;
    
    MWFunctions::include_required_files();
    xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '.__('Tags','admin_mywords'));
    xoops_cp_header();
    
    xoops_cp_footer();
    
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    default:
        show_tags();
        break;
}