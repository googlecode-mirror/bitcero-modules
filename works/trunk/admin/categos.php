<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','categories');
include 'header.php';

function showCategories(){
	global $xoopsModule, $mc, $tpl, $db, $xoopsSecurity;

	$categories = array();
	$result = $db->query("SELECT * FROM ".$db->prefix("pw_categos")." ORDER BY `order`,active");
	while ($row = $db->fetchArray($result)){
		$cat = new PWCategory();
		$cat->assignVars($row);
		$link = PW_URL.'/'.($mc['urlmode'] ? 'cat/'.$cat->nameId().'/' : 'category.php?id='.$cat->id());
		$categories[] = array(
            'id'        => $cat->id(),
            'link'      => $link,
            'name'      => $cat->name(),
            'active'    => $cat->active(),
		    'order'     => $cat->order(),
            'works'     => $cat->works(),
            'nameid'    => $cat->nameId()
        );
	}

	
	PWFunctions::toolbar();
	xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; ".__('Works Categories','admin_works'));
	RMTemplate::get()->assign('xoops_pagetitle', __('Works Categories','admin_works'));
	RMTemplate::get()->add_style('admin.css', 'works');
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
    RMTemplate::get()->add_script('../include/js/admin_works.js');
    RMTemplate::get()->add_head("<script type='text/javascript'>\nvar pw_message='".__('Do you really want to delete selected categories?','admin_works')."';\n
        var pw_select_message = '".__('You must select some category before to execute this action!','admin_works')."';</script>");
    xoops_cp_header();
	xoops_cp_header();
	
	include RMTemplate::get()->get_template("admin/pw_categories.php", 'module', 'works');
	xoops_cp_footer();
}

function formCategory($edit = 0){
	global $mc, $xoopsModule, $db;
	
	PWFunctions::toolbar();
	RMTemplate::get()->assign('xoops_pagetitle',$edit?__('Edit Category','admin_works'):__('Add Category','admin_works'));
	xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; 
		<a href='categos.php'>".__('Categories','admin_works').'</a> &raquo; '.($edit ? __('Edit Category','admin_works') : __('Add Category','admin_works')));
	xoops_cp_header();

	$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	

	if ($edit){
		//Verificamos si la categoría es válida
		if ($id<=0){
			redirectMsg('./categos.php?op=edit&id='.$id,__('Provide a category ID!','admin_works'),1);
			die();
		}

		//Verificamos si la categoría existe
		$cat = new PWCategory($id);
		if ($cat->isNew()){
			redirectMsg('./categos.php?op=edit&id='.$id,__('Specified category was not found!','admin_works'),1);
			die();
		}
	}

	
	$form = new RMForm($edit ? __('Edit Category','admin_works') : __('Add Category','admin_works'),'frmNew','categos.php');

	$form->addElement(new RMFormText(__('Name','admin_works'), 'name', 50, 150, $edit ? $cat->name() : ''), true);

	if ($edit) $form->addElement(new RMFormText(__('Short name','admin_works'), 'nameid', 50, 150, $cat->nameId()), true);
	$form->addElement(new RMFormEditor(__('Description','admin_works'), 'desc', '100%','250px', $edit ? $cat->desc('e') : ''));
	$form->addElement(new RMFormYesNo(__('Enable category','admin_works'), 'active', $edit ? $cat->active() : 1));
	$form->addElement(new RMFormText(__('Display order','admin_works'), 'order', 8, 3, $edit ? $cat->order() : 0), true, 'num');

	
	$form->addElement(new RMFormHidden('op', $edit ? 'saveedit' : 'save'));
	if ($edit) $form->addElement(new RMFormHidden('id', $cat->id()));
	$ele = new RMFormButtonGroup();
	$ele->addButton('sbt', $edit ? __('Save Changes!','admin_works') : __('Add Now!','admin_works'), 'submit');
	$ele->addButton('cancel', _CANCEL, 'button', 'onclick="window.location=\'categos.php\';"');
	$form->addElement($ele);
	$form->display();
	
	xoops_cp_footer();
	
}

function saveCategory($edit = 0){
	global $db, $mc, $xoopsSecurity;
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if (!$xoopsSecurity->check()){
		redirectMsg('./categos.php?op='.($edit ? 'edit&id='.$id : 'new'), __('Session token expired!', 'admin_works'), 1);
		die();
	}

	if ($edit){
		//Verificamos si la categoría es válida
		if ($id<=0){
			redirectMsg('./categos.php?op=edit&id='.$id,__('Wrong category ID!','admin_works'),1);
			die();
		}

		//Verificamos si la categoría existe
		$cat = new PWCategory($id);
		if ($cat->isNew()){
			redirectMsg('./categos.php?op=edit&id='.$id,__('Specified category does not exists!','admin_works'),1);
			die();
		}

		//Verificamos el nombre de la categoría
		$sql = "SELECT COUNT(*) FROM ".$db->prefix('pw_categos')." WHERE name='$name' AND id_cat<>'$id'";
		list($num) = $db->fetchRow($db->query($sql));
		if ($num>0){
			redirectMsg('./categos.php?op=edit&id='.$id,__('A category with same name already exists!','admin_works'),1);
			die();
		}

		if ($nameid){

			$sql="SELECT COUNT(*) FROM ".$db->prefix('pw_categos')." WHERE nameid='$nameid' AND id_cat<>'".$id."'";
			list($num)=$db->fetchRow($db->queryF($sql));
			if ($num>0){
				redirectMsg('./categos.php?op=edit&id='.$id,__('There are already a category with same name id!','admin_works'),1);
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
			$nameid = TextCleaner::sweetstring($name).($found ? $i : '');
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
		redirectMsg('./categos.php',__('Errors ocurred while trying to update database!','admin_works').'<br />'.$cat->errors(),1);
		die();
	}else{
		redirectMsg('./categos.php',__('Database updated successfully!','admin_works'),0);
		die();
	}
}


/**
* @desc Elimina las categorías proporcionadas
**/
function deleteCategory(){

	global $xoopsModule, $db, $xoopsSecurity;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$ok = isset($_POST['ok']) ? intval($_POST['ok']) : 0;
    
	//Verificamos que nos hayan proporcionado una categoría para eliminar
	if (!is_array($ids) && ($ids<=0)){
		redirectMsg('./categos.php',__('No categories selected!','admin_works'),1);
		die();
	}
	
	if (!is_array($ids)){
		$catego = new PWCategory($ids);
		$ids = array($ids);
	}


		if (!$xoopsSecurity->check()){
			redirectMsg('./categos.php',__('Session token expired!','admin_works'), 1);
			die();
		}

		$errors = '';
		foreach ($ids as $k){
			//Verificamos si la categoría es válida
			if ($k<=0){
				$errors.=sprintf(__('Category id "%s" invalid!','admin_works'), $k);
				continue;
			}

			//Verificamos si la categoría existe
			$cat = new PWCategory($k);
			if ($cat->isNew()){
				$errors.=sprintf(__('Category "%s" does not exists!','admin_works'), $k);
				continue;
			}
		
			if (!$cat->delete()){
				$errors.=sprintf(__('Category "%s" could not be deleted!','admin_works'),$k);
			}else{
				$sql = "UPDATE ".$db->prefix('pw_categos')." SET parent='".$cat->parent()."' WHERE parent='".$cat->id()."'";
				$result = $db->queryF($sql);

			}
		}
	
		if ($errors!=''){
			redirectMsg('./categos.php',__('Errors ocurred while trying to delete categories').'<br />'.$errors,1);
			die();
		}else{
			redirectMsg('./categos.php',__('Database updated successfully!','admin_works'),0);
			die();
		}

}

/**
* @desc Actualiza el orden de las categorías
**/
function updateCategory(){
	global $xoopsSecurity;

	$orders = isset($_REQUEST['order']) ? $_REQUEST['order'] : null;

	if (!$xoopsSecurity->check()){
		redirectMsg('./categos.php',__('Session token expired!','admin_works'), 1);
		die();
	}

	$errors = '';
	foreach ($orders as $k => $v){

		//Verificamos si la categoría es válida
		if ($k<=0){
			$errors.=sprintf(__('Category id "%s" not valid!','admin_works'), $k);
			continue;
		}

		//Verificamos si la categoría existe
		$cat = new PWCategory($k);
		if ($cat->isNew()){
			$errors.=sprintf(__('Specified category "%s" does not exists!','admin_works'), $k);
			continue;
		}
		
		if ($cat->order()==$v) continue;

		$cat->setOrder($v);

		if (!$cat->save()){
			$errors.=sprintf(__('Category "%s" could not be saved!','admin_works'),$k);
		}
	}

	if ($errors!=''){
		redirectMsg('./categos.php',__('Errors ocurred while trying to save category','admin_works').$errors,1);
		die();
	}else{
		redirectMsg('./categos.php',__('Database updated successfully!','admin_works'),0);
		die();
	}
}

/**
* @desc Activa/desactiva las categorías especificadas
**/
function activeCategory($act = 0){

	global $xoopsSecurity;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : null;


	if (!$xoopsSecurity->check()){
		redirectMsg('./categos.php',__('Session token expired!','admin_works'), 1);
		die();
	}


	//Verificamos que nos hayan proporcionado una categoría
	if (!is_array($ids)){
		redirectMsg('./categos.php',__('You must select a category to enable/disable','admin_works'),1);
		die();
	}

	$errors = '';
	foreach ($ids as $k){
		
		//Verificamos si la categoría es válida
		if ($k<=0){
			$errors.=sprintf(__('Category id "%s" not valid!','admin_works'), $k);
			continue;
		}

		//Verificamos si la categoría existe
		$cat = new PWCategory($k);
		if ($cat->isNew()){
			$errors.=sprintf(__('Specified category "%s" does not exists!','admin_works'), $k);
			continue;
		}
		

		$cat->setActive($act);
		if (!$cat->save()){
			$errors.=sprintf(__('Category "%s" could not be saved!','admin_works'),$k);
		}
	}

	if ($errors!=''){
		redirectMsg('./categos.php',__('Errors ocurred while trying to save category','admin_works').$errors,1);
		die();
	}else{
		redirectMsg('./categos.php',__('Database updated successfully!','admin_works'),0);
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
