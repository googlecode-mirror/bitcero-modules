<?php
// $Id$
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','ranking');
include 'header.php';

function m_show_ranking(){
    global $xoopsModule;
    
    $champ = rmc_server_var($_REQUEST,'champ',0);
    $category = rmc_server_var($_REQUEST,'category',0);
        
    $champs = MCHFunctions::all_championships(); 
    $categories = array();
    MCHFunctions::categories_tree($categories);    
    
    if($champ>0 && $category>0) $ranking = MCHFunctions::get_ranking($champ, $category);
    
    MCHFunctions::toolbar();
    xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; ".__('Coaches','match'));
    RMTemplate::get()->assign('xoops_pagetitle', __('Coaches','match'));
    RMTemplate::get()->add_style('admin.css', 'match');
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
    RMTemplate::get()->add_local_script('admin_match.js','match');
    RMTemplate::get()->add_head("<script type='text/javascript'>\nvar mch_message='".__('Do you really want to delete selected coaches?','match')."';\n
        var mch_select_message = '".__('You must select some coach before to execute this action!','match')."';</script>");
    xoops_cp_header();
    
    $match_extra_options = RMEvents::get()->run_event('match.more.options');
    
    include RMTemplate::get()->get_template("admin/mch_ranking.php", 'module', 'match');
    xoops_cp_footer();
    
}

$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    
    default:
        m_show_ranking();
        break;
        
}
