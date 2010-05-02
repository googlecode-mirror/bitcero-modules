<?php
// $Id$
// --------------------------------------------------------------
// Quick Pages
// Create simple pages easily and quickly
// Author: Eduardo Cortés <i.bitcero@gmail.com>
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
	global $xoopsModule, $xoopsSecurity;
	
	$row = array();
	qpArrayCategos($row);
	
	$categories = array();
	$db = Database::getInstance();
	foreach ($row as $k){
		$catego = new QPCategory($k['id_cat']);
		$catego->update();
		list($num) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("qpages_pages")." WHERE cat='$k[id_cat]'"));
		$k['posts'] = $num;
		$k['nombre'] = str_repeat("&#8212;", $k['saltos']) . ' ' . $k['nombre'];
		$categories[] = $k;
	}
	
	// Event
	$categories = RMEvents::get()->run_event('qpages.categories.list',$categories);
	
	RMTemplate::get()->add_style('admin.css', 'qpages');
	RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
	RMTemplate::get()->add_script('../include/js/qpages.js');
	RMTemplate::get()->assign('xoops_pagetitle', __('Categories management', 'qpages'));
	xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '._AS_QP_LIST);
	xoops_cp_header();
	
	include RMTemplate::get()->get_template("admin/qp_categos.php", 'module', 'qpages');
	
	xoops_cp_footer();
	
}

/**
 * Presenta un formulario para la creación de una nueva
 * categoría para los artículos
 */
function newForm($edit=0){
	global $xoopsModule;
	
	if ($edit){
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		if ($id<=0){
			redirectMsg('cats.php', __('You must provide a Category ID to edit!','qpages'), 1);
			die();
		}
		// Cargamos la categoría
		$catego = new QPCategory($id);
		// Si no existe entonces devolvemos un error
		if ($catego->isNew()){
			redirectMsg('cats.php', __('Specified category does not exists!','qpages'), 1);
			die();
		}
	}
	
	xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '.($edit ? __('Edit Category','qpages') : __('New Category','qpages')));
	xoops_cp_header();
	
	$cats = array();
	qpArrayCategos($cats, 0, 0, $edit ? array($id) : 0);
	
	$form = new RMForm($edit ? __('Edit Category','qpages') : __('New Category','qpages'), 'frmNew', 'cats.php');
	$form->addElement(new RMFormText(__('Category name','qpages'), 'nombre', 50, 150, $edit ? $catego->getName() : ''), true);
	$form->addElement(new RMFormTextArea(__('Description','qpages'), 'descripcion', 5, 45, $edit ? $catego->getVar('descripcion','e') : ''));
	$ele = new RMFormSelect(__('Category parent','qpages'), 'parent');
	$ele->addOption(0, _SELECT, $edit ? ($catego->getParent()==0 ? 1 : 0) : 1);
	foreach ($cats as $k){
		$ele->addOption($k['id_cat'], str_repeat("-", $k['saltos']) . ' ' . $k['nombre'], $edit ? ($catego->getParent()==$k['id_cat'] ? 1 : 0) : 0);
	}
	$form->addElement($ele);
	$form->addElement(new RMFormHidden('op', $edit ? 'saveedit' : 'save'));
	if ($edit){
		$form->addElement(new RMFormHidden('id', $id));
	}
	$ele = new RMFormButtonGroup('',' ');
	$ele->addButton('sbt', $edit ? __('Update Category','qpages') : __('Create Category','qpages'), 'submit');
	$ele->addButton('cancel', __('Cancel','qpages'), 'button');
	$ele->setExtra('cancel', "onclick='history.go(-1);'");
	$form->addElement($ele);
	$form->display();
	
	xoops_cp_footer();
	
}

/**
 * Almacenamos la categoría en la base de datos
 */
function saveCatego($edit = 0){
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if ($edit && $id<=0){
		redirectMsg('cats.php', __('You must provide a category ID to edit!','qpages'), 1);
		die();
	}
	
	if ($nombre==''){
		redirectMsg('cats.php?op=new', __('Please provide a name for this category!','qpages'), 1);
		die();
	}
	
	$nombre_amigo = TextCleaner::getInstance()->sweetstring($nombre);
	
	$db = Database::getInstance();
	
	# Verificamos que no exista la categoría
	$result = $db->query("SELECT COUNT(*) FROM ".$db->prefix("qpages_categos")." WHERE parent='$parent'".($edit ? " AND id_cat<>$id" : '')." AND (nombre='$nombre' OR nombre_amigo='$nombre_amigo')");
	list($num) = $db->fetchRow($result);
	
	if ($num>0){
		redirectMsg('cats.php?op=new', __('There another category with same name!','qpages'), 1);
		die();
	}
	
	# Si todo esta bien guardamos la categoría
	$catego = new QPCategory($edit ? $id : null);
	$catego->setName($nombre);
	$catego->setFriendName($nombre_amigo);
	$catego->setDescription($descripcion);
	$catego->setParent($parent);
	if ($edit){
		$result = $catego->update();
	} else {
		$result = $catego->save();
	}
	if ($result){
		redirectMsg('cats.php', __('Database updated successfully!','qpages'), 0);
	} else {
		redirectMsg('cats.php?op=new', __('Errors ucurred while trying to update database') . "<br />" . $catego->errors(), 1);
	}
	
}
/**
 * Elimina una categoría de la base de datos.
 * Las subcategorías pertenecientes a esta categoría no son eliminadas,
 * sino que son asignadas a la categoría superior.
 */
function deleteCatego(){
	global $db, $xoopsModule, $xoopsSecurity;
	
	$ids = rmc_server_var($_POST, 'ids', array());
	
	if (!$xoopsSecurity->check()){
		redirectMsg('cats.php',__('Session token expired!','qpages'), 1);
		die();
	}
	
	if (!is_array($ids)){
		redirectMsg('cats.php', __('No category has been selected!','qpages'), 1);
		die();
	}
	
	$errors = '';
	
	foreach($ids as $id){
		
		if ($id<=0){
			$errors .= sprintf(__('ID "%s" is not valid!','qpages'), $id).'<br />';
			continue;
		}
		
		$catego = new QPCategory($id);
		
		if ($catego->isNew){
			$errors .= sprintf(__('Category with ID "%s" does not exists!','qpages'), $id).'<br />';
			continue;
		}
		
		if ($catego->delete()){
			continue;
		} else {
			$errors .= sprintf(__('Category "%s" could not be deleted!','qpages'), $catego->getName()).'<br />';
			continue;
		}	
	}
	
	if ($errors!=''){
		redirectMsg('cats.php', __('Errors ocurred while trying to delete categories','qpages').'<br />'.$errors, 1); 
	} else {
		redirectMsg('cats.php', __('Categories deleted successfully!', 'qpages'));
	}
	
}

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch ($op){
	case 'new':
		newForm();
		break;
	case 'save':
		saveCatego();
		break;
	case 'saveedit':
		saveCatego(1);
		break;
	case 'edit':
		newForm(1);
		break;
	case 'delete':
		deleteCatego();
		break;
	default:
		showCategos();
		break;
}
