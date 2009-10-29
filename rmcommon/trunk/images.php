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
	
	// Check if some category exists
	$catnum = RMFunctions::get_num_records("rmc_img_cats");
	if ($catnum<=0){
		redirectMsg('images.php?action=newcat', __('There are not categories yet! Please create one in order to add images.','rmcommon'), 1);
		die();
	}
	
	$cat = rmc_server_var($_GET, 'category', 0);
    $sql = "SELECT COUNT(*) FROM ".$db->prefix("rmc_images");
    if ($cat>0) $sql .= " WHERE cat='$cat'";
    /**
	 * Paginacion de Resultados
	 */
	$page = rmc_server_var($_GET, 'page', 1);
	$limit = $xoopsModuleConfig['imgsnumber'];
	list($num) = $db->fetchRow($db->query($sql));
	
	$tpages = ceil($num / $limit);
    $page = $page > $tpages ? $tpages : $page;

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
	
	$nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('images.php?'.($cat>0 ? 'category='.$cat : '').'&page={PAGE_NUM}');
    
    // Get categories
    $sql = "SELECT * FROM ".$db->prefix("rmc_images")." ".($cat>0 ? "WHERE cat='$cat'" : '')." ORDER BY id_img DESC LIMIT $start,$limit";
    
    $result = $db->query($sql);
    $images = array();
    $categories = array();
    $authors = array();
	
	while($row = $db->fetchArray($result)){
		$img = new RMImage();
		
		if (!isset($categories[$img->getVar('cat')])){
			$categories[$img->getVar('cat')] = new RMImageCategory($img->getVar('cat'));
		}
		
		if (!isset($authors[$img->getVar('uid')])){
			$authors[$img->getVar('uid')] = new XoopsUser($img->getVar('uid'));
		}
		
		$images[] = array(
			'id'		=> $img->id(),
			'title'		=> $img->getVar('title'),
			'date'		=> $img->getVar('date'),
			'cat'		=> $categories[$img->getVar('cat')]->getVar('name'),
			'author'	=> $authors[$img->getVar('uid')]
		);
	}
	
	xoops_cp_header();
	RMFunctions::create_toolbar();
	include RMTemplate::get()->get_template('images_images.php','module','rmcommon');
	xoops_cp_footer();
	
}

function images_form($edit = 0){
	global $xoopsModule, $xoopsModuleConfig;
    
    /*$upload = new RMFlashUploader('images', 'images.php');*/
    RMTemplate::get()->add_script('include/js/jquery.uploadify.js');
    RMTemplate::get()->add_script('include/js/images.js');
    RMTemplate::get()->add_style('uploadify.css', 'rmcommon');
	xoops_cp_header();
	
    include RMTemplate::get()->get_template('images_uploadimages.php','module','rmcommon');
    
	xoops_cp_footer();
	
}

function show_categories(){
    global $xoopsModule, $xoopsModuleConfig, $xoopsConfig;
    
    $db = Database::getInstance();
    $sql = "SELECT COUNT(*) FROM ".$db->prefix("rmc_img_cats");
    /**
	 * Paginacion de Resultados
	 */
	$page = rmc_server_var($_GET, 'page', 1);
	$limit = $xoopsModuleConfig['catsnumber'];
	list($num) = $db->fetchRow($db->query($sql));
	
	$tpages = ceil($num / $limit);
    $page = $page > $tpages ? $tpages : $page;

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
	
	$nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('images.php?action=showcats&page={PAGE_NUM}');
    
    // Get categories
    $sql = "SELECT * FROM ".$db->prefix("rmc_img_cats")." ORDER BY id_cat DESC LIMIT $start,$limit";
    
    $result = $db->query($sql);
    $categories = array();
    
    while ($row = $db->fetchArray($result)){
    	$cat = new RMImageCategory();
    	$cat->assignVars($row);
    	$groups = $cat->getVar('groups');
		$categories[] = array(
			'id'		=>	$cat->id(),
			'name'		=>	$cat->getVar('name'),
			'status'	=> 	$cat->getVar('status'),
			'gwrite'	=> 	RMFunctions::get_groups_names($groups['write']),
			'gread'		=> 	RMFunctions::get_groups_names($groups['read']),
			'sizes'		=>	$cat->getVar('sizes'),
			'images'	=>	RMFunctions::get_num_records('rmc_images', 'cat='.$cat->id())
		);
    }
    
    RMTemplate::get()->add_style('general.css','rmcommon');
    RMTemplate::get()->add_style('imgmgr.css','rmcommon');
    RMTemplate::get()->add_script('include/js/jquery.checkboxes.js');
    RMFunctions::create_toolbar();
    xoops_cp_header();
    
    
    include RMTemplate::get()->get_template('images_categories.php', 'module', 'rmcommon');
    
    xoops_cp_footer();
    
}

/**
* Show form to create categories
*/
function new_category($edit = 0){
	define('RMSUBLOCATION','rmc_imgnewcat');
	
	extract($_GET);
	
	// Check category to edit
	if ($edit){
		if ($id<=0){
			redirectMsg('images.php?action=showcats', __('You must specify a category id to edit!','rmcommon'), 1);
			die();
		}
		
		$cat = new RMImageCategory($id);
		
		if ($cat->isNew()){
			redirectMsg('images.php?action=showcats', __('The specified category does not exists!','rmcommon'), 1);
			die();
		}
		
		// Write and read permissions
		$perms = $cat->getVar('groups');
		$write = isset($write) ? $write : $perms['write'];
		$read = isset($read) ? $read : $perms['read'];
		// Active
		$active = $cat->getVar('status');
		
	}
	
	RMFunctions::create_toolbar();
	xoops_cp_header();
    
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
        $schecked[] = $size;
    }
    
    if (empty($schecked)){
        redirectMsg($q, __('You must create one size for this category at least!','rmcommon'), 1);
        die();
    }
    
    // Check if there are a category with same name
    $num = RMFunctions::get_num_records('rmc_img_cats', "name='$name'".($edit ? " AND id_cat<>'".$cat->id()."'" : ''));
    if($num>0){
		redirectMsg($q, __('There are already a category with same name!','rmcommon'), 1);
		die();
    }
    
    $cat->setVar('name', $name);
    $cat->setVar('status', $status);
    $cat->setVar('groups', array('write'=>$write,'read'=>$read));
    $cat->setVar('sizes',$schecked);
    
    if ($cat->save()){
        redirectMsg('images.php?action=showcats', __($edit ? 'Category updated successfully!' : 'Category saved successfully!','rmcommon'), 0);
    } else {
        redirectMsg($q, __('There was erros while trying to save this category.','rmcommon').'<br />'.$cat->errors(), 1);
    }
    
}

/**
* This functions allows to modify the status of categories
*/
function category_status($action='open'){
	
	$cats = rmc_server_var($_REQUEST, 'cats', array());
	
	if (empty($cats)){
		$id = rmc_server_var($_GET, 'id', 0);
		
		if ($id<=0){
			redirectMsg('images.php?action=showcats', __('Specify one category at least to change status!','rmcommon'), 1);
			die();
		}
		
		$cats[] = $id;
		
	}
	
	$db = Database::getInstance();
	$sql = "UPDATE ".$db->prefix("rmc_img_cats")." SET status='".$action."' WHERE id_cat IN(".implode(',',$cats).")";

	if ($db->queryF($sql)){
		redirectMsg('images.php?action=showcats', __('Database updated successfully!','rmcommon'), 0);
		die();
	} else {
		redirectMsg('images.php?action=showcats', __('There was erros while updating database:','rmcommon').'<br />'.$db->error(), 1);
		die();
	}
	
}

function images_upload(){
    die(0);
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch ($action){
    case 'showcats':
        show_categories();
        break;
	case 'newcat':
		new_category();
		break;
	case 'editcat':
		new_category(1);
		break;
    case 'save':
        save_category();
        break;
    case 'saveedit':
    	save_category(1);
    	break;
    case 'opencat':
    	category_status('open');
    	break;
    case 'closecat':
    	category_status('close');
    	break;
    case 'new':
    	images_form(0);
    	break;
    case 'upload':
        images_upload();
        break;
	default:
		show_images();
		break;
}