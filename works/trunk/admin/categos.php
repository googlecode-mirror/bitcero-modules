<?php
// $Id$
// --------------------------------------------------------
// Professional Works
// Manejo de Portafolio de Trabajos
// CopyRight © 2008. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.exmsystem.org
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
// @copyright: 2008 Red México

define('PW_LOCATION','categories');
include 'header.php';

/**
* @desc Barra de Menus
*/
function optionsBar(){
	global $tpl;
	
	$tpl->append('xoopsOptions', array('link' => './categos.php', 'title' => _AS_PW_CATEGOS, 'icon' => '../images/cats16.png'));
	$tpl->append('xoopsOptions', array('link' => './categos.php?op=new', 'title' => _AS_PW_NEWCATEGO, 'icon' => '../images/add.png'));
}

function showCategories(){
	global $xoopsModule, $mc, $tpl, $db, $adminTemplate, $util;
	
	$tpl->assign('lang_existing', _AS_PW_EXISTING);
	$tpl->assign('lang_id', _AS_PW_ID);
	$tpl->assign('lang_name', _AS_PW_NAME);
	$tpl->assign('lang_desc', _AS_PW_DESC);
	$tpl->assign('lang_works', _AS_PW_WORKS);
	$tpl->assign('lang_options', _OPTIONS);
	$tpl->assign('lang_active', _AS_PW_ACTIVE);
	$tpl->assign('lang_order',_AS_PW_ORDER);
	$tpl->assign('lang_edit', _EDIT);
	$tpl->assign('lang_delete', _DELETE);
	$tpl->assign('lang_save',_AS_PW_SAVE);
	$tpl->assign('token',$util->getTokenHTML());
	$tpl->assign('lang_activ',_AS_PW_ACTIV);
	$tpl->assign('lang_desactive',_AS_PW_DESACTIVE);

	
	$result = $db->query("SELECT * FROM ".$db->prefix("pw_categos")." ORDER BY `order`,active");
	while ($row = $db->fetchArray($result)){
		$cat = new PWCategory();
		$cat->assignVars($row);
		$link = PW_URL.'/'.($mc['urlmode'] ? 'cat/'.$cat->nameId().'/' : 'category.php?id='.$cat->id());
		$tpl->append('categos', array('id'=>$cat->id(),'link'=>$link,'name'=>$cat->name(),'active'=>$cat->active(),
		'order'=>$cat->order(),'works'=>$cat->works()));
	}

	
	optionsBar();
	xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; "._AS_PW_CATLOC);
	$adminTemplate = "admin/pw_categories.html";
	xoops_cp_header();
	
	xoops_cp_footer();
}

function formCategory($edit = 0){
	global $mc, $xoopsModule, $db;
	
	optionsBar();
	xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; 
		<a href='categos.php'>"._AS_PW_CATLOC.'</a> &raquo; '.($edit ? _AS_PW_FEDIT : _AS_PW_FNEW));
	xoops_cp_header();

	$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	

	if ($edit){
		//Verificamos si la categoría es válida
		if ($id<=0){
			redirectMsg('./categos.php?op=edit&id='.$id,_AS_PW_ERRCATVALID,1);
			die();
		}

		//Verificamos si la categoría existe
		$cat = new PWCategory($id);
		if ($cat->isNew()){
			redirectMsg('./categos.php?op=edit&id='.$id,_AS_PW_ERRCATEXIST,1);
			die();
		}
	}

	
	$form = new RMForm($edit ? _AS_PW_FEDIT : _AS_PW_FNEW,'frmNew','categos.php');

	$form->addElement(new RMText(_AS_PW_FNAME, 'name', 50, 150, $edit ? $cat->name() : ''), true);

	if ($edit) $form->addElement(new RMText(_AS_PW_FSHORTNAME, 'nameid', 50, 150, $cat->nameId()), true);
	$form->addElement(new RMEditor(_AS_PW_FDESC, 'desc', '90%','250px', $edit ? $cat->desc('e') : ''));
	$form->addElement(new RMYesNo(_AS_PW_FACTIVE, 'active', $edit ? $cat->active() : 1));
	$form->addElement(new RMText(_AS_PW_FORDER, 'order', 8, 3, $edit ? $cat->order() : 0), true, 'num');

	
	$form->addElement(new RMHidden('op', $edit ? 'saveedit' : 'save'));
	if ($edit) $form->addElement(new RMHidden('id', $cat->id()));
	$ele = new RMButtonGroup();
	$ele->addButton('sbt', _SUBMIT, 'submit');
	$ele->addButton('cancel', _CANCEL, 'button', 'onclick="window.location=\'categos.php\';"');
	$form->addElement($ele);
	$form->display();
	
	xoops_cp_footer();
	
}

function saveCategory($edit = 0){
	global $db, $mc, $util;
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if (!$util->validateToken()){
		redirectMsg('./categos.php?op='.($edit ? 'edit&id='.$id : 'new'), _AS_PW_ERRSESSID, 1);
		die();
	}

	if ($edit){
		//Verificamos si la categoría es válida
		if ($id<=0){
			redirectMsg('./categos.php?op=edit&id='.$id,_AS_PW_ERRCATVALID,1);
			die();
		}

		//Verificamos si la categoría existe
		$cat = new PWCategory($id);
		if ($cat->isNew()){
			redirectMsg('./categos.php?op=edit&id='.$id,_AS_PW_ERRCATEXIST,1);
			die();
		}

		//Verificamos el nombre de la categoría
		$sql = "SELECT COUNT(*) FROM ".$db->prefix('pw_categos')." WHERE name='$name' AND id_cat<>'$id'";
		list($num) = $db->fetchRow($db->query($sql));
		if ($num>0){
			redirectMsg('./categos.php?op=edit&id='.$id,_AS_PW_ERRCATNAMEEXIST,1);
			die();
		}

		if ($nameid){

			$sql="SELECT COUNT(*) FROM ".$db->prefix('pw_categos')." WHERE nameid='$nameid' AND id_cat<>'".$id."'";
			list($num)=$db->fetchRow($db->queryF($sql));
			if ($num>0){
				redirectMsg('./categos.php?op=edit&id='.$id,_AS_PW_ERRCATNAMEID,1);
				die();
			}

		}


	}else{
		$cat = new PWCategory();
	}

	//Genera $nameid Nombre identificador
	$found=false; 
	$i = 0;
	if ($name!=$cat->name() || empty($nameid)){
		do{
			$nameid = $util->sweetstring($name).($found ? $i : '');
        		$sql = "SELECT COUNT(*) FROM ".$db->prefix('pw_categos'). " WHERE nameid = '$nameid'";
        		list ($num) =$db->fetchRow($db->queryF($sql));
        		if ($num>0){
        			$found =true;
        		    $i++;
        		}else{
        			$found=false;
        		}
		}while ($found==true);
	}


	$cat->setName($name);
	$cat->setDesc($desc);
	$cat->setOrder($order);
	$cat->setActive($active);
	$cat->setNameId($nameid);
	$cat->isNew() ? $cat->setCreated(time()) : '';

	if (!$cat->save()){
		redirectMsg('./categos.php',_AS_PW_DBERROR.$cat->errors(),1);
		die();
	}else{
		redirectMsg('./categos.php',_AS_PW_DBOK,0);
		die();
	}
}


/**
* @desc Elimina las categorías proporcionadas
**/
function deleteCategory(){

	global $util, $xoopsModule, $db;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$ok = isset($_POST['ok']) ? intval($_POST['ok']) : 0;

	//Verificamos que nos hayan proporcionado una categoría para eliminar
	if (!is_array($ids) && ($ids<=0)){
		redirectMsg('./categos.php',_AS_PW_ERRNOTCATDEL,1);
		die();
	}
	
	if (!is_array($ids)){
		$catego = new PWCategory($ids);
		$ids = array($ids);
	}


	if ($ok){

		if (!$util->validateToken()){
			redirectMsg('./categos.php',_AS_PW_ERRSESSID, 1);
			die();
		}

		$errors = '';
		foreach ($ids as $k){
			//Verificamos si la categoría es válida
			if ($k<=0){
				$errors.=sprintf(_AS_PW_NOTVALID, $k);
				continue;
			}

			//Verificamos si la categoría existe
			$cat = new PWCategory($k);
			if ($cat->isNew()){
				$errors.=sprintf(_AS_PW_NOTEXIST, $k);
				continue;
			}
		
			if (!$cat->delete()){
				$errors.=sprintf(_AS_PW_NOTDELETE,$k);
			}else{
				$sql = "UPDATE ".$db->prefix('pw_categos')." SET parent='".$cat->parent()."' WHERE parent='".$cat->id()."'";
				$result = $db->queryF($sql);

			}
		}
	
		if ($errors!=''){
			redirectMsg('./categos.php',_AS_PW_DBERRORS.$errors,1);
			die();
		}else{
			redirectMsg('./categos.php',_AS_PW_DBOK,0);
			die();
		}


	}else{
		optionsBar();
		xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; 
		<a href='categos.php'>"._AS_PW_CATLOC.'</a> &raquo; '._AS_PW_DELETE);
		xoops_cp_header();

		$hiddens['ok'] = 1;
		$hiddens['ids[]'] = $ids;
		$hiddens['op'] = 'delete';
		
		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'categos.php\';"';
		
		$util->msgBox($hiddens, 'categos.php',($catego ? sprintf(_AS_PW_DELETECONF, $catego->name()) : _AS_PW_DELETECONFS). '<br /><br />' ._AS_PW_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
	
		xoops_cp_footer();

	}
}

/**
* @desc Actualiza el orden de las categorías
**/
function updateCategory(){
	global $util;

	$orders = isset($_REQUEST['order']) ? $_REQUEST['order'] : null;

	if (!$util->validateToken()){
		redirectMsg('./categos.php',_AS_PW_ERRSESSID, 1);
		die();
	}

	$errors = '';
	foreach ($orders as $k => $v){

		//Verificamos si la categoría es válida
		if ($k<=0){
			$errors.=sprintf(_AS_PW_NOTVALID, $k);
			continue;
		}

		//Verificamos si la categoría existe
		$cat = new PWCategory($k);
		if ($cat->isNew()){
			$errors.=sprintf(_AS_PW_NOTEXIST, $k);
			continue;
		}
		
		if ($cat->order()==$v) continue;

		$cat->setOrder($v);

		if (!$cat->save()){
			$errors.=sprintf(_AS_PW_NOTSAVE,$k);
		}
	}

	if ($errors!=''){
		redirectMsg('./categos.php',_AS_PW_DBERRORS.$errors,1);
		die();
	}else{
		redirectMsg('./categos.php',_AS_PW_DBOK,0);
		die();
	}
}

/**
* @desc Activa/desactiva las categorías especificadas
**/
function activeCategory($act = 0){

	global $util;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : null;


	if (!$util->validateToken()){
		redirectMsg('./categos.php',_AS_PW_ERRSESSID, 1);
		die();
	}


	//Verificamos que nos hayan proporcionado una categoría
	if (!is_array($ids)){
		redirectMsg('./categos.php',_AS_PW_ERRNOTCATEGORY,1);
		die();
	}

	$errors = '';
	foreach ($ids as $k){
		
		//Verificamos si la categoría es válida
		if ($k<=0){
			$errors.=sprintf(_AS_PW_NOTVALID, $k);
			continue;
		}

		//Verificamos si la categoría existe
		$cat = new PWCategory($k);
		if ($cat->isNew()){
			$errors.=sprintf(_AS_PW_NOTEXIST, $k);
			continue;
		}
		

		$cat->setActive($act);
		if (!$cat->save()){
			$errors.=sprintf(_AS_PW_NOTSAVE,$k);
		}
	}

	if ($errors!=''){
		redirectMsg('./categos.php',_AS_PW_DBERRORS.$errors,1);
		die();
	}else{
		redirectMsg('./categos.php',_AS_PW_DBOK,0);
		die();
	}
	

}



$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch($op){
	case 'new':
		formCategory();
		break;
	case 'edit':
		formCategory(1);
		break;
	case 'save':
		saveCategory();
		break;
	case 'saveedit':
		saveCategory(1);
		break;
	case 'delete':
		deleteCategory();
		break;
	case 'update':
		updateCategory();
		break;
	case 'active':
		activeCategory(1);
		break;
	case 'desactive':
		activeCategory();
		break;
	default:
		showCategories();
		break;
}

?>
