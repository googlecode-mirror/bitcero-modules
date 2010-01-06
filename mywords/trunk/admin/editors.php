<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','editors');
require('header.php');

include_once '../include/general.func.php';

/**
 * Mostramos la lista de editores junto con
 * el formulario para crear nuevos editores
 */
function showEditors(){
	global $tpl, $xoopsUser, $xoopsSecurity, $xoopsModule;
	
	MWFunctions::include_required_files();
    xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '.__('Editors','admin_mywords'));
    RMTemplate::get()->assign('xoops_pagetitle', __('Editors Management','admin_mywords'));
	include_once RMCPATH.'/class/form.class.php';
	
	foreach ($_REQUEST as $k => $v){
		$$k = $v;
	}
	
	$db = Database::getInstance();	
    list($num) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_editors")));
    $page = rmc_server_var($_GET, 'page', 1);
    $limit = isset($limit) && $limit>0 ? $limit : 15;
    
    $tpages = ceil($num / $limit);
    $page = $page > $tpages ? $tpages : $page;

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('editors.php?page={PAGE_NUM}');
	$result = $db->query("SELECT * FROM ".$db->prefix("mw_editors")." ORDER BY name LIMIT $start,$limit");	
    $editores = array();			
    
	while($row = $db->fetchArray($result)){
        $ed = new MWEditor();
        $ed->assignVars($row);
		$tpl->append('editors', $ed);
	}
	
	xoops_cp_header();
	RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
    RMTemplate::get()->add_script('../include/js/editors.js');
	include RMTemplate::get()->get_template('admin/mywords_editors.php','module','mywords');
	
	xoops_cp_footer();
	
}

function edit_editor(){
    
    
    
}

/**
 * Agregamos nuevos editores a la base de datos
 */
function save_editor($edit = false){
	global $xoopsConfig, $xoopsSecurity;
	
    $page = rmc_server_var($_POST, 'page', 1);
    
    if (!$xoopsSecurity->check()){
        redirectMsg('editors.php?page='.$page, __('Operation not allowed!','admin_mywords'), 1);
        die();
    }
    
    if ($edit){
        
        $id = rmc_server_var($_GET, 'id', 0);
        if ($id<=0){
            redirectMsg('editors.php?page='.$page, __('Editor ID has not been provided!','admin_mywords'), 1);
            die();
        }
        
        $editor = new MWEditor($id);
        if ($editor->isNew()){
            redirectMsg('editors.php?page='.$page, __('Editor has not been found!','admin_mywords'), 1);
            die();
        }
        
    } else {
        
        $editor = new MWEditor();
        
    }
    
    $name = rmc_server_var($_POST, 'name', '');
    $bio = rmc_server_var($_POST, 'bio', '');
    $uid = rmc_server_var($_POST, 'new_user', 0);
    $perms = rmc_server_var($_POST, 'perms', array());
    
    if (trim($name)==''){
        redirectMsg('editors.php?page='.$page, __('You must provide a display name for this editor!','admin_mywords'), 1);
        die();
    }
    
    if ($uid<=0){
        redirectMsg('editors.php?page='.$page, __('You must specify a registered user ID for this editor!','admin_mywords'), 1);
        die();
    }
    
    $editor->setVar('name', $name);
    $editor->setVar('bio', $bio);
    $editor->setVar('uid', $uid);
    $editor->setVar('privileges', $perms);
    
    if(!$editor->save()){
        redirectMsg('editors.php?page='.$page, __('Errors occurs while trying to save editor data','admin_mywords').'<br />'.$editor->errors(), 1);
        die();
    } else {
        redirectMsg('editors.php?page='.$page, __('Database updated succesfully!','admin_mywords'), 0);
        die();
    }
    
	
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
	case 'new':
		save_editor(false);
		break;
	case 'del':
		deleteEditor();
		break;
	default:
		showEditors();
		break;
}
