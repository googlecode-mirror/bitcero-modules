<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* This is the images manager file for RMCommon. It is based on EXM system
* and as a substitute for xoops image manager
*/

include_once '../../include/cp_header.php';
require_once XOOPS_ROOT_PATH.'/modules/rmcommon/admin_loader.php';
define('RMCLOCATION','imgmanager');

/**
* Show all images existing in database
*/
function show_images(){
	global $xoopsModule, $xoopsModuleConfig;
	
	$db = Database::getInstance();
	
	$catnum = RMFunctions::get_num_records("rmc_img_cats");
	if ($catnum<=0){
		redirectMsg('images.php?action=newcat', __('There are not categories yet! Please create one in order to add images.','rmcommon'), 1);
		die();
	}
	
}

function show_categories(){
    
    xoops_cp_header();
    xoops_cp_footer();
    
}

/**
* Show form to create categories
*/
function new_category($edit = 0){
	define('RMSUBLOCATION','rmc_imgnewcat');
	RMFunctions::create_toolbar();
	xoops_cp_header();
	
    extract($_GET);
    
	$form = new RMForm('','','');
	$fwrite = new RMFormGroups('','write',true,1, 3, isset($write) ? $write : array(XOOPS_GROUP_ADMIN));
	$fread = new RMFormGroups('','read',true,1, 3, isset($read) ? $read : array(0));
	
    RMTemplate::get()->add_script('include/js/imgmanager.js');
	include RMTemplate::get()->get_template('categories_form.php', 'module', 'rmcommon');
	RMTemplate::get()->add_style('imgmgr.css','rmcommon');
	xoops_cp_footer();
	
}

/**
* Stores data for new categories
*/
function save_category($edit = 0){
    global $xoopsDB, $xoopsModuleConfig, $xoopsModule;
    
    $q = 'images.php?action='.($edit ? 'editcat' : 'newcat');
    foreach($_POST as $k => $v){
        if ($k=='action' || $k=='XOOPS_TOKEN_REQUEST') continue;
        if (is_array($v)){
            $q .= '&'.RMFunctions::urlencode_array($v, $k);
        } else {
            $q .= '&'.$k.'='.urlencode($v);
        }
        
    }
    extract($_POST);
    
    if ($edit){
        
        if ($id<=0){
            redirectMsg('images.php?action=showcats', __('Specify a valid category id','rmcommon'), 1);
            die();
        }
        
        $cat = new RMImageCategory($id);
        if ($cat->isNew()){
            redirectMsg('images.php?action=showcats', __('Specified category does not exists!','rmcommon'), 1);
            die();
        }
        
    } else {
        $cat = new RMImageCategory();
    }
    
    if ($name==''){
        redirectMsg($q, __('Please specify a category name','rmcommon'), 1);
        die();
    }
    
    if (empty($read)) $read = array(0);
    if (empty($write)) $write = array(0);
    
    // Check if resize data is correct
    $schecked = array();
    foreach ($sizes as $size){
        if (trim($size['name'])=='') continue;
        if ($size['type']!='none' && $size['width']<=0 && $size['height']<=0) continue;
        $schecked = $size;
    }
    
    if (empty($schecked)){
        redirectMsg($q, __('You must create one size for this category at least!','rmcommon'), 1);
        die();
    }
    
    $cat->setVar('name', $name);
    $cat->setVar('status', $status);
    $cat->setVar('groups', array('write'=>$write,'read'=>$read));
    $cat->setVar('sizes',$schecked);
    
    if ($cat->save()){
        redirectMsg('images.php?action=showcats', __('Category saved successfully!','rmcommon'), 0);
    } else {
        redirectMsg($q, __('There was erros while trying to save this category.','rmcommon').'<br />'.$cat->errors(), 1);
    }
    
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch ($action){
    case 'showcats':
        show_categories();
        break;
	case 'newcat':
		new_category();
		break;
    case 'save':
        save_category();
        break;
	default:
		show_images();
		break;
}