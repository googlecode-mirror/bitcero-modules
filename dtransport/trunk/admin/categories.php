<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','categories');
include ('header.php');

/**
* @desc Visualiza todas las categorías existentes
**/
function showCategories(){
	global $xoopsModule, $xoopsSecurity;

	$categos = array();
	DTFunctions::getCategos($categos);
    $categories = array();
	foreach ($categos as $row){
		$cat = new DTCategory();
		$cat->assignVars($row);

		$categories[] = array(
                    'id'=>$cat->id(),
                    'name'=>$cat->name(),
                    'parent'=>$cat->parent(),
                    'active'=>$cat->active(),
                    'description' => $cat->desc(),
                    'indent'=>$row['jumps']
                );

	}
        
    unset($categos);
	
    RMTemplate::get()->add_local_script('jquery.checkboxes.js', 'rmcommon', 'include');
    RMTemplate::get()->add_local_script('jquery.validate.min.js', 'rmcommon', 'include');
    RMTemplate::get()->add_local_script('admin.js', 'dtransport');
    
    RMTemplate::get()->add_head(
        '<script type="text/javascript">
            var dt_message = "'.__('Do you really want to delete selected categories','dtransport').'";
            var dt_select_message = "'.__('Select at least one category to delete!','dtransport').'";
        </script>'
    );
    
    RMTemplate::get()->add_xoops_style('admin.css', 'dtransport');
    
	DTFunctions::toolbar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('Categories','dtransport'));
	xoops_cp_header();
    
    include RMTemplate::get()->get_template('admin/dtrans_categos.php', 'module', 'dtransport');
    
	xoops_cp_footer();	

}


/**
* @desc Formulario de creación/edición de categorías
**/
function formCategos($edit=0){
	global $xoopsModule,$db;
	
	$id = rmc_server_var($_GET, 'id', 0);

	if ($edit){

		//Verificamos si categoría es válida
		if ($id<=0)
            redirectMsg('categories.php', __('Specified category is not valid!','dtransport'),1);

        //Verificamos si la categoría existe
        $cat=new DTCategory($id);
        if ($cat->isNew())
            redirectMsg('categories.php', __('Specified category does not exists!','dtransport'),1);

	}


	DTFunctions::toolbar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".($edit ? __('Edit category','dtransport') : __('New category','dtransport')));
	xoops_cp_header();

	$form=new RMForm($edit ? __('Edit Category','dtransport') : __('New Category','dtransport'),'frmcat','categories.php');
	$form->addElement(new RMFormText(__('Category name','dtransport'),'name',50,150,$edit ? $cat->name() : ''),true);

	if ($edit){
		$form->addElement(new RMFormText(__('Category short name','dtransport'),'nameid',50,150,$edit ? $cat->nameId() : ''));
	}

	$form->addElement(new RMFormTextArea(__('Description','dtransport'),'desc',5,40,$edit ? $cat->desc('e') : ''));

	//Lista de categorías
	$ele=new RMFormSelect(__('Root category','dtransport'),'parent');
	$ele->addOption(0, __('Select category...','dtransport'));
	$categos = array();
	DTFunctions::getCategos($categos, 0, 0, $edit ? $cat->id() : array());
	foreach ($categos as $catego){
		$ele->addOption($catego['id_cat'],str_repeat('--', $catego['jumps']).' '.$catego['name'],$edit ? ($catego['id_cat']==$cat->parent() ? 1 : 0) : 0);		
	}

	$form->addElement($ele);
	$form->addElement(new RMFormYesno(__('Active category','dtransport'),'active',$edit ? $cat->active() : 1));

	$form->addElement(new RMFormHidden('action',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMFormHidden('id',$id));

	$buttons =new RMFormButtonGroup();
	$buttons->addButton('sbt',__('Submit','dtransport'),'submit');
	$buttons->addButton('cancel',__('Cancel','dtransport'),'button', 'onclick="window.location=\'categories.php\';"');

	$form->addElement($buttons);
	
	$form->display();
	
	xoops_cp_footer();
}




/**
* @desc Almacena la informaciín perteneciente a la categoría en la base de datos
**/
function saveCategories($edit=0){
    global $xoopsSecurity;
    
	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	if (!$xoopsSecurity->check(true, false, $edit ? 'XOOPS_TOKEN': 'XT'))
		redirectMsg('categories.php', __('Session token expired!','dtransport'), 1);
        
    $db = XoopsDatabaseFactory::getDatabaseConnection();	

	if ($edit){

		//Verificamos si categoría es válida
		if ($id<=0)
			redirectMsg('categories.php', __('Specified category is not valid!','dtransport'),1);

		//Verificamos si la categoría existe
		$cat=new DTCategory($id);
		if ($cat->isNew())
			redirectMsg('categories.php', __('Specified category does not exists!','dtransport'),1);

		//Comprueba que el nombre de la categoría no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_categos')." WHERE name='$name' AND id_cat<>'".$id."' AND parent=$parent";
		list($num)=$db->fetchRow($db->queryF($sql));
        
		if ($num>0)
			redirectMsg('categories.php?action=edit&id='.$id, __('Already exists another category with same name!','dtransport'),1);	

		//Verificamos si el nameId existe
		if ($nameid){
			$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_categos')." WHERE nameid='$nameid' AND id_cat<>'".$id."' AND parent=$parent";
			list($num)=$db->fetchRow($db->queryF($sql));
			if ($num>0){
				redirectMsg('categories.php?action=edit&id='.$id, __('Already exists another category with same short name!','dtransport'),1);
				die();
			}

		}

	}else{
		//Comprueba que el nombre de la categoría no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_categos')." WHERE name='$name' AND parent=$parent";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('categories.php', __('Already exists another category with same name!','dtransport'),1);	
			die();
		}

		$cat=new DTCategory();
	}


	//Genera $nameid Nombre identificador
	$found=false; 
	$i = 0;
	if ($name!=$cat->name() || empty($nameid)){
		do{
			
			$nameid = TextCleaner::getInstance()->sweetstring($name).($found ? $i : '');
        		$sql = "SELECT COUNT(*) FROM ".$db->prefix('dtrans_categos'). " WHERE nameid = '$nameid' AND parent=$parent";
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
	$cat->setParent($parent);
	$cat->setActive($active);
	$cat->setNameId($nameid);

	if (!$cat->save()){
		redirectMsg('categories.php', __('Category could not be saved!','dtransport'),1);
		die();
	}else{
		redirectMsg('categories.php', __('Category saved successfully!','dtransport'),0);
		die();
	}

}


/**
* @desc Elimina las categorías especificadas
**/
function deleteCategos(){
	global $xoopsModule, $xoopsSecurity;

	$ids = rmc_server_var($_POST, 'ids', array());
	
	//Verificamos si nos proporcionaron alguna categoria
	if (!is_array($ids) || empty($ids))
		redirectMsg('./categories.php', __('You must select at least one category to delete!','dtransport'),1);

	if (!$xoopsSecurity->check())
	    redirectMsg('categories.php', __('Session token expired!','dtransport'), 1);

	$errors='';
	foreach ($ids as $k){
	    //Verificamos si la categoría es válida
		if ($k<=0){
		    $errors.=sprintf(__('Category ID "%s" is not valid!','dtransport'),$k);
			continue;	
		}

		//verificamos si la categoría existe
		$cat=new DTCategory($k);
		if ($cat->isNew()){
		    $errors.=sprintf(__('Category "%s" does not exists!','dtransport'),$k);
			continue;
		}

		if (!$cat->delete()){
            $errors.=sprintf(__('Category "%s" could not be deleted!','dtransport'),$cat->name());
		}

	}

	if ($errors!=''){
	    redirectMsg('categories.php', __('There was errors trying to delete categories','dtransport').'<br />'.$errors,1);
		die();	
	}else{
	    redirectMsg('categories.php',__('Categories deleted successfully!','dtransport'),0);
		die();
	}

}


/**
* @desc Activa/deactiva categorías
**/
function activeCategos($act=0){

	$ids = rmc_server_var($_POST, 'ids', array());

	
	//Verificamos si nos proporcionaron alguna categoria
	if (!is_array($ids) || empty($ids)){
		redirectMsg('categories.php', __('Select at least one category to delete!','dtransport'),1);
		die();
	}
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $sql = "UPDATE ".$db->prefix("dtrans_categos")." SET active=$act WHERE id_cat IN (".implode(",",$ids).")";

	if (!$db->queryF($sql))
		redirectMsg('categories.php', __('Database could not be updated!','dtransport').'<br />'.$errors,1);
	else
		redirectMsg('categories.php', __('Database updated successfully!','dtransport'),0);
	
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch ($action){
	case 'new':
		formCategos();
	    break;
	case 'edit':
		formCategos(1);
	    break;
	case 'save':
		saveCategories();
	    break;
	case 'saveedit':
		saveCategories(1);
	    break;
	case 'delete':
		deleteCategos();
	    break;
	case 'active':
		activeCategos(1);
	    break;
	case 'desactive':
		activeCategos();
	    break;
	default:
		showCategories();
        break;
}
