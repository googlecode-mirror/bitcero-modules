<?php
// $Id$
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','championships');
include 'header.php';

function m_show_championships(){
    global $xoopsModule;
    
    $page = rmc_server_var($_REQUEST,'page', 1);
    $page = $page<=0 ? 1 : $page;
    $limit = 15;
    
    $db = Database::getInstance();
    
    //Barra de Navegación
    $sql = "SELECT COUNT(*) FROM ".$db->prefix('mch_champs');
    
    list($num)=$db->fetchRow($db->query($sql));

    $tpages = ceil($num/$limit);
    $page = $page > $tpages ? $tpages : $page; 

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('champ.php?page={PAGE_NUM}');
    
    $form = new RMForm('','','');
    $editor = new RMFormEditor('','description', '98%', '200px', '', 'html');
    $start = new RMFormDate('','start',time());
    $end = new RMFormDate('','end',time());
    
    MCHFunctions::toolbar();
    xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; ".__('Championships','match'));
    RMTemplate::get()->assign('xoops_pagetitle', __('Championships','match'));
    RMTemplate::get()->add_style('admin.css', 'match');
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
    RMTemplate::get()->add_local_script('admin_match.js','match');
    RMTemplate::get()->add_head("<script type='text/javascript'>\nvar mch_message='".__('Do you really want to delete selected championships?','match')."';\n
        var mch_select_message = '".__('You must select some championships before to execute this action!','match')."';</script>");
    xoops_cp_header();
    
    $match_extra_options = RMEvents::get()->run_event('match.more.options');
    
    include RMTemplate::get()->get_template("admin/mch_champs.php", 'module', 'match');
    xoops_cp_footer();
    
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    
    case 'save':
        m_save_coach();
        break;
    case 'saveedit':
        m_save_coach(1);
        break;
    case 'new':
        m_coaches_form();
        break;
    case 'edit':
        m_coaches_form(1);
        break;
    case 'delete':
        m_delete_coaches();
        break;
    default:
        m_show_championships();
        break;
    
}
