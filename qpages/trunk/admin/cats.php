<?php
// $Id$
// --------------------------------------------------------
// Quick Pages
// Módulo para la publicación de páginas individuales
// CopyRight © 2007 - 2008. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License as
// published by the Free Software Foundation; either version 2 of
// the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public
// License along with this program; if not, write to the Free
// Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
// MA 02111-1307 USA
// --------------------------------------------------------
// @copyright: 2007 - 2008 Red México
// @author: BitC3R0

define('QP_LOCATION','categos');
require('header.php');

include_once '../include/general.func.php';

function optionsBar(){
	global $tpl;
	
    $tpl->append('xoopsOptions', array('link' => './cats.php', 'title' => _AS_QP_CATLIST, 'icon' => '../images/categos16.png'));
    $tpl->append('xoopsOptions', array('link' => './cats.php?op=new', 'title' => _AS_QP_NEWCAT, 'icon' => '../images/add.png'));
    
}
/**
 * Muestra una lista de las categorías existentes
 */
function showCategos(){
	global $tpl, $db, $adminTemplate, $xoopsModule, $util;
	
	$tpl->assign('lang_categoslist', _AS_QP_LIST);
	$tpl->assign('lang_id', _AS_QP_ID);
	$tpl->assign('lang_name', _AS_QP_NAME);
	$tpl->assign('lang_catpages', _AS_QP_CATPAGES);
	$tpl->assign('lang_options', _OPTIONS);
	$tpl->assign('lang_edit', _EDIT);
	$tpl->assign('lang_delete', _DELETE);
	
	$util = new RMUtils();
	$row = array();
	qpArrayCategos($row);
	
	foreach ($row as $k){
		$catego = new QPCategory($k['id_cat']);
		$catego->update();
		list($num) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("qpages_pages")." WHERE cat='$k[id_cat]'"));
		$k['posts'] = $num;
		$k['nombre'] = str_repeat("-", $k['saltos']) . ' ' . $k['nombre'];
		$tpl->append('categos', $k);
	}
	
	optionsBar();
	$adminTemplate = "admin/qp_categos.html";
	xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '._AS_QP_LIST);
	xoops_cp_header();
	
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
			redirectMsg('cats.php', _AS_QP_ERRID, 1);
			die();
		}
		// Cargamos la categoría
		$catego = new QPCategory($id);
		// Si no existe entonces devolvemos un error
		if ($catego->isNew()){
			redirectMsg('cats.php', _AS_QP_ERRID, 1);
			die();
		}
	}
	
	xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '._AS_QP_NEWCAT);
	xoops_cp_header();
	
	$cats = array();
	qpArrayCategos($cats, 0, 0, $edit ? array($id) : 0);
	
	$form = new RMForm($edit ? _AS_QP_EDITTITLE : _AS_QP_NEWTITLE, 'frmNew', 'cats.php');
	$form->addElement(new RMText(_AS_QP_NAME, 'nombre', 50, 150, $edit ? $catego->getName() : ''), true);
	$form->addElement(new RMTextArea(_AS_QP_DESC, 'descripcion', 5, 45, $edit ? $catego->getDescription() : ''));
	$ele = new RMSelect(_AS_QP_CATPARENT, 'parent');
	$ele->addOption(0, _SELECT, $edit ? ($catego->getParent()==0 ? 1 : 0) : 1);
	foreach ($cats as $k){
		$ele->addOption($k['id_cat'], str_repeat("-", $k['saltos']) . ' ' . $k['nombre'], $edit ? ($catego->getParent()==$k['id_cat'] ? 1 : 0) : 0);
	}
	$form->addElement($ele);
	$form->addElement(new RMHidden('op', $edit ? 'saveedit' : 'save'));
	if ($edit){
		$form->addElement(new RMHidden('id', $id));
	}
	$ele = new RMButtonGroup('',' ');
	$ele->addButton('sbt', $edit ? _EDIT : _SUBMIT, 'submit');
	$ele->addButton('cancel', _CANCEL, 'button');
	$ele->setExtra('cancel', "onclick='history.go(-1);'");
	$form->addElement($ele);
	$form->display();
	
	xoops_cp_footer();
	
}

/**
 * Almacenamos la categoría en la base de datos
 */
function saveCatego($edit = 0){
	global $db, $util;
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if ($edit && $id<=0){
		redirectMsg('cats.php', _AS_QP_ERRID, 1);
		die();
	}
	
	if ($nombre==''){
		redirectMsg('cats.php?op=new', _AS_QP_ERRNAME, 1);
		die();
	}
	
	$nombre_amigo = $util->sweetstring($nombre);
	
	# Verificamos que no exista la categoría
	$result = $db->query("SELECT COUNT(*) FROM ".$db->prefix("qpages_categos")." WHERE parent='$parent'".($edit ? " AND id_cat<>$id" : '')." AND (nombre='$nombre' OR nombre_amigo='$nombre_amigo')");
	list($num) = $db->fetchRow($result);
	
	if ($num>0){
		redirectMsg('cats.php?op=new', _AS_QP_ERREXISTS, 1);
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
		redirectMsg('cats.php', _AS_QP_DBOK, 0);
	} else {
		redirectMsg('cats.php?op=new', _AS_QP_DBERROR . "<br />" . $catego->errors(), 1);
	}
	
}
/**
 * Elimina una categoría de la base de datos.
 * Las subcategorías pertenecientes a esta categoría no son eliminadas,
 * sino que son asignadas a la categoría superior.
 */
function deleteCatego(){
	global $db, $xoopsModule, $util;
	
	$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
	$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
	
	if ($id<=0){
		redirectMsg('cats.php', _AS_QP_ERRID, 1);
		die();
	}
	
	if ($ok){
		$catego = new QPCategory($id);
		if ($catego->delete()){
			redirectMsg('cats.php', _AS_QP_DBOK, 0);
			die();
		} else {
			redirectMsg('cats.php', _AS_QP_DBERROR . '<br />' . $catego->error(), 1);
			die();
		}
	} else {
		
		$hiddens['op'] = 'delete';
		$hiddens['ok'] = 1;
		$hiddens['id'] = $id;
		$buttons['sbt']['value'] = _SUBMIT;
		$buttons['sbt']['type'] = 'submit';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['extra'] = 'onclick="javascript:history.go(-1);"';
		
		xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '._AS_QP_DELCAT);
		xoops_cp_header();
		$util->msgBox($hiddens, 'cats.php', _AS_QP_CONFIRMDEL . '<br /><br />' . _AS_QP_DELETEDESC, XOOPS_ALERT_ICON, $buttons, $display=true, '400px');
		xoops_cp_footer();
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
?>