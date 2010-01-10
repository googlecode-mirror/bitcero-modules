<?php
// $Id: bookmarks.php 16 2009-09-13 01:38:59Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','bookmarks');
require('header.php');

include_once '../include/general.func.php';

/**
* @desc Muestra la lista de los elementos disponibles
*/
function show_bookmarks(){
    global $xoopsModule, $xoopsConfig, $xoopsSecurity;
    
    // Cargamos los sitios
    $db = Database::getInstance();
    $sql = "SELECT * FROM ".$db->prefix("mw_bookmarks")." ORDER BY title";
    $result = $db->query($sql);
    
    while ($row = $db->fetchArray($result)){
        $bm = new MWBookmark();
        $bm->assignVars($row);
        $bookmarks[] = array(
        	'id'=>$bm->id(),
        	'name'=>$bm->getVar('title'),
        	'icon'=>$bm->getVar('icon'),
        	'desc'=>$bm->getVar('alt'),
        	'active'=>$bm->getVar('active'),
        	'url'=>str_replace(array('{','}'), array('<span>{','}</span>'), $bm->getVar('url'))
        );
    }
    
    $temp = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH.'/modules/mywords/images/icons');
    foreach ($temp as $icon){
        $icons[] = array('url'=>XOOPS_URL."/modules/mywords/images/icons/$icon", 'name'=>$icon);
    }
    
    MWFunctions::include_required_files();
    
    xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '.__('Bookmark Sites','admin_mywords'));
	xoops_cp_header();
	
	include RMTemplate::get()->get_template('admin/mywords_bookmarks.php','module','mywords');
	RMTemplate::get()->assign('xoops_pagetitle', __('Bookmarks Management','admin_mywords'));
	RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
	RMTemplate::get()->add_script('../include/js/scripts.php?file=bookmarks.js');
	
	xoops_cp_footer();
    
}

/**
* @desc Muestra el formulario para agregar un nuevo sitio
*/
function edit_bookmark(){
    global $xoopsModule, $xoopsConfig, $xoopsSecurity;
    
    $id = rmc_server_var($_GET, 'id', 0);
    if ($id<=0){
		redirectMsg('bookmarks.php', __('Site ID not provided!','admin_mywords'), 1);
		die();
	}
	
	$book = new MWBookmark($id);
	if($book->isNew()){
		redirectMsg('bookmarks.php', __('Social site not exists!','admin_mywords'), 1);
		die();
	}
	
	$temp = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH.'/modules/mywords/images/icons');
    foreach ($temp as $icon){
        $icons[] = array('url'=>XOOPS_URL."/modules/mywords/images/icons/$icon", 'name'=>$icon);
    }
    
    MWFunctions::include_required_files();
    
    xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; <a href="bookmarks.php">'.__('Social Sites','admin_mywords').'</a> &raquo; '.__('Editing Social Site','admin_mywords'));
	xoops_cp_header();
	
    $show_edit = true;
	include RMTemplate::get()->get_template('admin/mywords_bookmarks.php','module','mywords');
	RMTemplate::get()->assign('xoops_pagetitle', __('Bookmarks Management','admin_mywords'));
	RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
	RMTemplate::get()->add_script('../include/js/scripts.php?file=bookmarks.js');
	
	xoops_cp_footer();
    
}

/**
* @desc Almacena los datos de un sitio
*/
function save_bookmark($edit){
    global $xoopsSecurity;
    
    if (!$xoopsSecurity->check()){
		redirectMsg('bookmarks.php', __('Operation not allowed!', 'admin_mywords'), 1);
		die();
    }
    
    if ($edit){
		
		$id = rmc_server_var($_POST, 'id', 0);
		if ($id<=0){
			redirectMsg('bookmarks.php', __('Site ID not provided!','admin_mywords'), 1);
			die();
		}
		
		$book = new MWBookmark($id);
		if($book->isNew()){
			redirectMsg('bookmarks.php', __('Social site not exists!','admin_mywords'), 1);
			die();
		}
		
		$qs = '?action=edit&id='.$id;
		
    } else {
		
		$book = new MWBookmark();
		
    }
    
    $title = rmc_server_var($_POST, 'title', '');
    $alt = rmc_server_var($_POST, 'alt', '');
    $url = rmc_server_var($_POST, 'url', '');
    $icon = rmc_server_var($_POST, 'icon', '');
    
    if($title==''){
		redirectMsg('bookmarks.php'.$qs, __('You must specify a title for this site!','admin_mywords'), 1);
		die();
    }
    
    if($url=='' || $url=='http://'){
		redirectMsg('bookmarks.php'.$qs, __('You must specify a formatted URL for this site!','admin_mywords'), 1);
		die();
    }
    
    $url = formatURL($url);
    
    $book->setVar('title',$title);
    $book->setVar('alt',$alt);
    $book->setVar('url',$url);
    $book->setVar('icon',$icon);
    $book->setVar('active',1);
    
    if ($book->save()){
        redirectMsg('bookmarks.php', __('Database updated successfully!','admin_mywords'), 0);
    } else {
        redirectMsg('bookmarks.php'.$qs, __('Errors ocurred while trying to update database!'). '<br />' . $book->errors(), 1);
    }
    
}

/**
* @desc Activa o desactiva los sitios
*/
function activate_bookmark($act){
    global $xoopsSecurity;
    
    $books = rmc_server_var($_POST, 'books', array());
    
    if (!$xoopsSecurity->check()){
        redirectMsg('bookmarks.php', __('Sorry, operation not allowed!','admin_mywords'), 1);
        die();
    }
    
    if (!is_array($books) || empty($books)){
        redirectMsg('bookmarks.php', __('Please, specify a valid site ID!','admin_mywords'), 1);
        die();
    }
    
    $db = Database::getInstance();
    $sql = "UPDATE ".$db->prefix("mw_bookmarks")." SET active=".($act?1:0)." WHERE id_book IN(".implode(',',$books).")";
    
    if ($db->queryF($sql)){
		redirectMsg('bookmarks.php', __('Database updated successfully!','admin_mywords'), 0);
	} else {
		redirectMsg('bookmarks.php', __('Errors ocurred while trying to update database!','admin_mywords').'<br />'.$db->error(), 0);
	}
    
}

/**
* @desc Elimina un sitio de la base de datos
*/
function delete_bookmark(){
    global $xoopsSecurity;
    
    $books = rmc_server_var($_POST, 'books', array());
    
    if (!$xoopsSecurity->check()){
        redirectMsg('bookmarks.php', __('Sorry, operation not allowed!','admin_mywords'), 1);
        die();
    }
    
    if (!is_array($books) || empty($books)){
        redirectMsg('bookmarks.php', __('Please, specify a valid site ID!','admin_mywords'), 1);
        die();
    }
    
    $db = Database::getInstance();
    $sql = "DELETE FROM ".$db->prefix("mw_bookmarks")." WHERE id_book IN (".implode(',',$books).")";
	
	if ($db->queryF($sql)){
		redirectMsg('bookmarks.php', __('Database updated successfully!','admin_mywords'), 0);
	} else {
		redirectMsg('bookmarks.php', __('Errors ocurred while trying to update database!','admin_mywords').'<br />'.$db->error(), 0);
	}
       
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    case 'new':
        save_bookmark();
        break;
    case 'edit':
        edit_bookmark();
        break;
    case 'save':
        saveBookmark();
        break;
    case 'saveedit':
        save_bookmark(true);
        break;
    case 'activate':
        activate_bookmark(1);
        break;
    case 'deactivate':
        activate_bookmark(0);
        break;
    case 'delete':
        delete_bookmark();
        break;
    default:
        show_bookmarks();
        break;
}

?>
