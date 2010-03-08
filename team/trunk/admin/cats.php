<?php
// $Id$
// --------------------------------------------------------------
// Equipo Club Gallos
// Un modulo sencillo para el manejo de equipos
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('TC_LOCATION','categories');
include 'header.php';

/**
* @desc Muestra la lista de categorías existentes
*/
function showCategories(){
	global $tpl, $xoopsModule, $db, $adminTemplate;
	
	$result = $db->query("SELECT * FROM ".$db->prefix("coach_categos")." ORDER BY name");
	$categos = array();
	while ($row = $db->fetchArray($result)){
		$cat = new TCCategory();
		$cat->assignVars($row);
		$categos[] = array('id'=>$cat->id(), 'name'=>$cat->getVar('name'),'teams'=>count($cat->teams()));
	}
	
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_TC_CATSLOC);
	xoops_cp_header();
	
	include RMTemplate::get()->get_template("admin/coach_categories.php",'module','team');
	
	xoops_cp_footer();
}

function categoriesForm($edit = 0){
	global $db, $xoopsModule;
	
	if ($edit){
		$id = TCFunctions::get('id');
		if ($id<=0){
			redirectMsg('cats.php', __('¡El ID proporcionado no es válido!'), 1);
			die();
		}
		$cat = new TCCategory($id);
		if ($cat->isNew()){
			redirectMsg('cats.php', __('No existe la categoría especificada'), 1);
			die();
		}
	}
	
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./cats.php'>".__('Administración de Categorías', 'admin_team')."</a> &raquo; ".($edit ? __('Editar Categoría', 'admin_team') : __('Crear Categoría', 'admin_team')));
	xoops_cp_header();
	
	$form = new RMForm($edit ? __('Editar Categoría', 'admin_team') : __('Crear Categoría', 'admin_team'), 'frmNew', 'cats.php');
	$form->oddClass('oddForm');
	
	$form->addElement(new RMFormText(__('Nombre', 'admin_team'), 'name', 50, 100, $edit ? $cat->name() : ''), true);
	if ($edit) $form->addElement(new RMFormText(__('Nombre corto', 'admin_team'), 'nameid', 50, 100, $cat->nameId()));
	$form->addElement(new RMFormTextArea(__('Descripción', 'admin_team'), 'desc', 5, 45, $edit ? $cat->getVar('desc') : ''));
	$ele = new RMFormButtonGroup();
	$ele->addButton('sbt', $edit ? __('Guardar Cambios', 'admin_team') : __('Crear Categoría', 'admin_team'), 'submit');
	$ele->addButton('cancel', __('Cancelar', 'admin_team'), 'button', 'onclick="window.location=\'cats.php\';"');
	$form->addElement($ele);
	$form->addElement(new RMFormHidden('op',$edit ? 'saveedit' : 'save'));
	if ($edit) $form->addElement(new RMFormHidden('id', $id));
	$form->display();
	
	xoops_cp_footer();
	
}

function saveCategory($edit = 0){
	global $db, $xoopsSecurity;
	
	$nameid = '';
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if (!$xoopsSecurity->check()){
		redirectMsg('cats.php'.($edit ? "?op=edit&id=$id" : "?op=new"), __('¡El identificador de sesión ha expirado!', 'admin_team'), 1);
		break;
	}
	
	if ($edit){
		$id = TCFunctions::post('id');
		if ($id<=0){
			redirectMsg('cats.php', __('Id no válido', 'admin_team'), 1);
			die();
		}
		$cat = new TCCategory($id);
		if ($cat->isNew()){
			redirectMsg('cats.php', __('No existe la categoría especificada', 'admin_team'), 1);
			die();
		}
		
		$i = 0;
		do{
			$nameid = $nameid!='' && $i==0 ? $nameid : ($util->sweetstring($name).($i>0 ? $i : ''));
			$sql = "SELECT COUNT(*) FROM ".$db->prefix("coach_categos")." WHERE nameid='$nameid' AND id_cat<>'".$cat->id()."'";
			list($num) = $db->fetchRow($db->query($sql));
			$i++;
		} while($num>0);
		
		$sql = "SELECT COUNT(*) FROM ".$db->prefix("coach_categos")." WHERE name='$name' AND id_cat<>'".$cat->id()."'";
		list($num) = $db->fetchRow($db->query($sql));
		if ($num>0){
			redirectMsg('cats.php?op=edit&id='.$cat->id(), __('Ya existe una categoría con el mismo nombre', 'admin_team'), 1);
			die();
		}
		
	} else {
		$cat = new TCCategory();
		
		$i = 0;
		do{
			$nameid = TextCleaner::getInstance()->sweetstring($name).($i>0 ? $i : '');
			$sql = "SELECT COUNT(*) FROM ".$db->prefix("coach_categos")." WHERE nameid='$nameid'";
			list($num) = $db->fetchRow($db->query($sql));
			$i++;
		} while($num>0);
		
		$sql = "SELECT COUNT(*) FROM ".$db->prefix("coach_categos")." WHERE name='$name'";
		list($num) = $db->fetchRow($db->query($sql));
		if ($num>0){
			redirectMsg('cats.php?op=new&id='.$cat->id(), __('Ya existe una categoría con el mismo nombre', 'admin_team'), 1);
			die();
		}
	}

	
	$cat->setVar('name', $name);
	$cat->setVar('nameid', $nameid);
	$cat->setVar('desc', $desc);
	
	if ($cat->save()){
		redirectMsg('cats.php', __('Base de datos actualizada correctamente', 'admin_team'), 0);
	} else {
		redirectMsg('cats.php', __('No se pudo actualizar la base de datos', 'admin_team') . "<br />" . $cat->errors(), 1);
	}
	
}

function deleteCategory(){
	$id = TCFunctions::get('id');
	
	if ($id<=0){
		redirectMsg('cats.php', __('Id no válido', 'admin_team'), 1);
		die();
	}
	$cat = new TCCategory($id);
	if ($cat->isNew()){
		redirectMsg('cats.php', __('La categoría especificada no existe', 'admin_team'), 1);
		die();
	}
	
	if ($cat->delete()){
		redirectMsg('cats.php', __('Base de datos actualizada correctamente', 'admin_team'), 0);
	} else {
		redirectMsg('cats.php', __('No se puedo actualizar la base de datos', 'admin_team') . "<br />" . $cat->errors(), 1);
	}
	
}

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch($op){
	case 'new':
		categoriesForm();
		break;
	case 'save':
		saveCategory(0);
		break;
	case 'edit':
		categoriesForm(1);
		break;
	case 'saveedit':
		saveCategory(1);
		break;
	case 'delete':
		deleteCategory();
		break;
	default:
		showCategories();
		break;
}
