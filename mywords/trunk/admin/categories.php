<?php
// $Id: categories.php 50 2009-09-17 20:36:31Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','categories');
require('header.php');

include_once '../include/general.func.php';

/**
 * Muestra una lista de las categorías existentes
 */
function showCategos(){
	global $tpl, $adminTemplate, $xoopsModule, $xoopsSecurity;
	
	$row = array();
	MWFunctions::categos_list($row);
	$categories = array();
    
	foreach ($row as $k){
		$catego = new MWCategory();
		$catego->assignVars($k);
		$categories[] = array(
            'id_cat'=>$catego->id(),
            'description'=>$catego->getVar('description','n'),
			'posts'=>$catego->getVar('posts','n'), 
            'name'=>$catego->getVar('name','n'),
            'indent'=>$k['indent'],
            'shortname'=>$catego->getVar('shortname','n')
        );
	}
    
    // Categories pagination
    // Paginamos
    $limit = 15;
    if (count($categories)>$limit){
        
        $page = rmc_server_var($_GET, 'page', 1);
        $page = $page<1 ? 1 : $page;
        $limit_c = $page * ($limit-1);
        
        $limit_c = $limit_c>count($categories)-1 ? count($categories) - 1 : $limit_c;
        $tpages = ceil(count($categories)/3);
        
        if ($page>$tpages) $page=$tpages;
        
        $nav = new RMPageNav(count($categories), $limit, $page, 5);
        $nav->target_url('?page={PAGE_NUM}');
        
        $i = ($page-1)*$limit;

        while ($i<=$limit_c){
            
            $pcategories[] = $categories[$i];
            $i++;
            
        }
        
        $categories = $pcategories;
        
    }
	
	MWFunctions::include_required_files();
	
	xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '.__('Categories','admin_mywords'));
	xoops_cp_header();
	
    $desc = ''; $posts = ''; $parent = ''; $selcat = ''; $new = ''; $name=''; $shortcut = '';
    extract($_GET);
	include RMTemplate::get()->get_template('admin/mywords_categories.php','module','mywords');
	RMTemplate::get()->assign('xoops_pagetitle', __('Categories Management','admin_mywords'));
	RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
	RMTemplate::get()->add_script('../include/js/categories.js');
	
	xoops_cp_footer();
	
}

/**
 * Presenta un formulario para la creación de una nueva
 * categoría para los artículos
 */
function newForm(){
	global $xoopsModule;
	
	$id = isset($_GET['id']) ? $_GET['id'] : 0;
	if ($id<=0){
	    redirectMsg('categories.php',  __('You must specify a valid category','admin_mywords'), 1);
		die();
	}
	// Cargamos la categoría
	$catego = new MWCategory($id);
	// Si no existe entonces devolvemos un error
	if ($catego->isNew()){
	    redirectMsg('cats.php', __('Specified category not exists!','admin_mywords'), 1);
		die();
	}
	
	MWFunctions::include_required_files();
	//xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '.($edit ? _AS_MW_EDITTITLE : _AS_MW_NEWCAT));
	xoops_cp_header();
	
	$cats = array();
	MWFunctions::categos_list($cats, 0, 0, true, $id);
	
	$form = new RMForm($edit ? __('Editar Categoría','admin_mywords') : __('Crear Categoría','admin_mywords'), 'frmNew', 'categories.php');
    $form->styles('width: 30%;','odd');
	$form->addElement(new RMFormText(__('Category name','admin_mywords'), 'name', 50, 150, $catego->getVar('name')), true);
    $form->addElement(new RMFormText(__('Category slug','admin_mywords'), 'shortname', '', '150', $catego->getVar('shortname','n')));
	$form->addElement(new RMFormTextArea(__('Category description','admin_mywords'), 'desc', 5, 45, $catego->getVar('description','e')));
	$ele = new RMFormSelect(__('Category Parent','admin_mywords'), 'parent');
	$ele->addOption(0, _SELECT, $catego->getVar('parent')==0 ? 1 : 0);
	foreach ($cats as $k){
		$ele->addOption($k['id_cat'], str_repeat("-", $k['indent']) . ' ' . $k['name'], $catego->getVar('parent')==$k['id_cat'] ? 1 : 0);
	}
	$form->addElement($ele);
	
	$form->addElement(new RMFormHidden('op', 'saveedit'));
	$form->addElement(new RMFormHidden('id', $id));
	
	$ele = new RMFormButtonGroup('',' ');
	$ele->addButton('sbt', __('Update Category','admin_mywords'), 'submit');
	$ele->addButton('cancel', __('Cancel','admin_mywords'), 'button');
	$ele->setExtra('cancel', "onclick='history.go(-1);'");
	$form->addElement($ele);
	$form->display();
	
	xoops_cp_footer();
	
}

/**
 * Almacenamos la categoría en la base de datos
 */
function saveCatego($edit = 0){
	global $xoopsSecurity, $db;
	
	if (!$xoopsSecurity->check()){
		redirectMsg('categories.php', __('Sorry, session token expired!','admin_mywords'), 1);
		die();
	}
	
    $query = '';
	foreach ($_POST as $k => $v){
		$$k = $v;
        if ($k=='op' || $k=='XOOPS_TOKEN_REQUEST') continue;
        $query .= $query=='' ? "$k=".urlencode($v) : '&'.$k.'='.urlencode($v);
	}
    
    $query = $edit ? '&op=edit' : '';
	
	if ($edit){
		if ($id<=0){
			redirectMsg('categories.php', __('You must specify a valid category','admin_mywords'), 1);
			die();
		}
		
		$catego = new MWCategory($id);
		if ($catego->isNew()){
			redirectMsg('categories.php', __('Specified category not exists!','admin_mywords'), 1);
			die();
		}
	} else {
		$catego = new MWCategory();
	}
		
	if ($name==''){
		redirectMsg('categories.php?'.$query, __('Please specify a name for this category!','admin_mywords'), 1);
		die();
	}
	
	$shortname = $shortname=='' ? TextCleaner::sweetstring($name) : $shortname;
	
	# Verificamos que no exista la categoría
	$result = $db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_categories")." WHERE parent='$parent'".($edit ? " AND id_cat<>$id" : '')." AND (name='$name' OR shortname='$shortname')");
	list($num) = $db->fetchRow($result);
	
	if ($num>0){
		redirectMsg('categories.php?'.$query, __('There is already a category with the same name!','admin_mywords'), 1);
		die();
	}
	
	# Si todo esta bien guardamos la categoría
	$catego->setVar('name',$name);
	$catego->setVar('shortname',$shortname);
	$catego->setVar('description',$desc);
	$catego->setVar('parent',$parent);
	if (!$edit) $catego->setVar('posts',0);
	
	$result = $catego->save();
    
	if ($result){
		redirectMsg('categories.php', __('Category created succesfully!','admin_mywords'), 0);
	} else {
		redirectMsg('categories.php?'.$query, __('There was an error!','admin_mywords'). "<br />" . $catego->errors(), 1);
	}
	
}
/**
 * Elimina una categoría de la base de datos.
 * Las subcategorías pertenecientes a esta categoría no son eliminadas,
 * sino que son asignadas a la categoría superior.
 */
function deleteCatego(){
	global $db, $util, $xoopsModule;
	
	$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
	$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
	
	if ($id<=0){
		redirectMsg('cats.php', _AS_MW_ERRID, 1);
		die();
	}
	
	if ($ok){
		
		if (!$util->validateToken()){
			redirectMsg('cats.php', _AS_MW_ERRTOKEN, 1);
			die();
		}
	
		$catego = new MWCategory($id);
		if ($catego->delete()){
			redirectMsg('cats.php', _AS_MW_DBOK, 0);
			die();
		} else {
			redirectMsg('cats.php', _AS_MW_DBERROR . '<br />' . $catego->error(), 1);
			die();
		}
		
	} else {
		$util = new RMUtils();
		$hiddens['op'] = 'delete';
		$hiddens['ok'] = 1;
		$hiddens['id'] = $id;
		$buttons['sbt']['value'] = _SUBMIT;
		$buttons['sbt']['type'] = 'submit';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['extra'] = 'onclick="window.location=\'cats.php\';"';
		
		optionsBar();
		xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '._AS_MW_DELETECATEGO);
		xoops_cp_header();
		$util->msgBox($hiddens, 'cats.php', _AS_MW_CONFIRMDEL . '<br /><br />' . _AS_MW_DELETEDESC, XOOPS_ALERT_ICON, $buttons, true,'400px');
		xoops_cp_footer();
	}
	
}

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch ($op){
	case 'save':
		saveCatego();
		break;
	case 'saveedit':
		saveCatego(1);
		break;
	case 'edit':
		newForm();
		break;
	case 'delete':
		deleteCatego();
		break;
	default:
		showCategos();
		break;
}
