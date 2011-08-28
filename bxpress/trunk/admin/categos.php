<?php
// $Id$
// --------------------------------------------------------------
// EXMBB Forums
// An simple forums module for XOOPS and Common Utilities
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION', 'categories');
include 'header.php';

/**
* @desc Muestra la lista de categorías existentes
*/
function showCategories(){
    global $xoopsModuleConfig, $xoopsConfig, $xoopsModule, $xoopsSecurity;
    
    $db = Database::getInstance();
    
    $result = $db->query("SELECT * FROM ".$db->prefix("exmbb_categories")." ORDER BY `order`, title");
    $categos = array();
    
    while ($row = $db->fetchArray($result)){
        $catego = new BBCategory();
        $catego->assignVars($row);
        $categos[] = array(
            'id'=>$catego->id(),
            'title'=>$catego->title(),
            'desc'=>$catego->description(),
            'status'=>$catego->status()
        );
    }
    
    BBFunctions::menu_bar();
    
    $form = new RMForm('','','');
    $groups = new RMFormGroups('','groups', 1, 1, 2, array(0));
    
    xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('Categories','exmbb'));
    xoops_cp_header();
    
    include RMTemplate::get()->get_template('admin/forums_categos.php', 'module', 'bxpress');
    
    xoops_cp_footer();
    
}

/**
* @desc Muestra el formulario para edición/creación de categorías
*/
function showForm($edit = 0){
    global $xoopsModule, $xoopsConfig, $xoopsModuleConfig;
    
    define('RMSUBLOCATION','newcategory');
    
    if ($edit){
        $id = rmc_server_var($_GET, 'id', 0);
        if ($id<=0){
            redirectMsg('categos.php', __('You had not provided a category ID','exmbb'), 1);   
            die();
        }
        
        $catego = new BBCategory($id);
        if ($catego->isNew()){
            redirectMsg('categos.php', __('Specified category does not exists!','exmbb'), 1);   
            die();
        }
    }
    
    BBFunctions::menu_bar();
    xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".($edit ? __('Edit Category','exmbb') : __('New Category','exmbb')));
    xoops_cp_header();
    
    $form = new RMForm($edit ? __('Edit Category','exmbb') : __('New Category','exmbb'), 'frmCat', 'categos.php');
    $form->addElement(new RMFormText(__('Name','exmbb'), 'title', 50, 100, $edit ? $catego->title() : ''), true);
    if ($edit){
        $form->addElement(new RMFormText(__('Short name','exmbb'), 'friendname', 50, 100, $catego->friendName()))   ;
    }
    $form->addElement(new RMFormEditor(__('Description','exmbb'), 'desc', '90%', '300px', $edit ? $catego->description() : '', $xoopsConfig['editor_type']));
    $form->addElement(new RMFormYesNo(__('Show description','exmbb'), 'showdesc', $edit ? $catego->showDesc() : 1));
    $form->addElement(new RMFormYesNo(__('Activate','exmbb'), 'status', $edit ? $catego->status() : 1));
    $form->addElement(new RMFormGroups(__('Groups','exmbb'), 'groups', 1, 1, 4, $edit ? $catego->groups() : array(0)), true, 'checked');
    $form->addElement(new RMFormHidden('op', $edit ? 'saveedit' : 'save'));
    if ($edit) $form->addElement(new RMFormHidden('id', $catego->id()));
    $buttons = new RMFormButtonGroup();
    $buttons->addButton('sbt', __('Submit','exmbb'), 'submit', '', true);
    $buttons->addButton('cancel', __('Cancel','exmbb'), 'button', 'onclick="window.location=\'categos.php\';"');
    $form->addElement($buttons);
    
    $form->display();
    
    xoops_cp_footer();
}

/**
* @desc Almacena los datos de una categoría
*/
function saveCatego($edit = 0){
    global $xoopsConfig, $xoopsModuleConfig, $xoopsSecurity;
    
    if (!$xoopsSecurity->check()){
        redirectMsg('categos.php', __('Session token expired!','exmbb'), 1);
        die();
    }
    
    $db = Database::getInstance();
    
    $friendname = '';
    $showdesc = 0;
    $status = 0;
    
    foreach ($_POST as $k => $v){
        $$k = $v;
    }
    
    if($title==''){
        redirectMsg('categos.php', __('Please provide a name for this category!','exmbb'), 1);
        die();
    }
    
    if ($edit){
        
        if ($id<=0){
            redirectMsg('categos.php', __('The specified category ID is not valid!','exmbb'), 1);   
            die();
        }
        
        $catego = new BBCategory($id);
        if ($catego->isNew()){
            redirectMsg('categos.php', __('Specified category does not exists!','exmbb'), 1);   
            die();
        }
        
        // Comprobamos que no exista el nombre
        list($num) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("exmbb_categories")." WHERE title='$title' AND id_cat<>'$id'"));
        if ($num>0){
            redirectMsg('categos.php?op=edit&id='.$id, _AS_BB_ERREXISTS, 1);
            die();
        }
        
    } else {
        $catego = new BBCategory();   
    }
    
    // Asignamos valores
    $catego->setTitle($title);
    $friendname = $friendname!='' ? TextCleaner::getInstance()->sweetstring($friendname) : TextCleaner::getInstance()->sweetstring($title);
    
    // Comprobamos que el nombre no este asignada a otra categoría
    list($num) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("exmbb_categories")." WHERE friendname='$friendname' AND id_cat<>'$id'"));
    if ($num>0){
        redirectMsg('categos.php?op=edit&id='.$id, __('Already exist a category with the same name for the URL','exmbb'), 1);
        die();
    }
    
    $catego->setDescription($desc);
    $catego->setFriendName($friendname);
    $catego->setGroups(!isset($groups) || is_array($groups) ? array(0) : $groups);
    $catego->setOrder($order<=0 ? 0 : intval($order));
    $catego->setShowDesc($showdesc);
    $catego->setStatus($status);
    
    if ($catego->save()){
        redirectMsg('categos.php', __('Category saved succesfully!','exmbb'), 0);
    } else {
        redirectMsg('categos.php', __('Category could not be saved!','exmbb') . '<br />' . $catego->errors(), 1);
    }
    
}


/**
* @desc Eliminina categorías
**/
function deleteCatego(){
	global $xoopsModule, $xoopsSecurity;	
	
	$ids = rmc_server_var($_POST, 'ids', array());
	
	//Verificamos si se ha proporcionado una categoría
	if (empty($ids)){
		redirectMsg('./categos.php',__('You must select at least one category','exmbb'),1);
		die();		
	}	

	if (!$xoopsSecurity->check()){
	    redirectMsg('categos.php', __('Session token expired!','exmbb'), 1);
		die();
	}

	$errors='';
	foreach ($ids as $k){
	    //Verificamos que la categoría sea válida
		if ($k<=0){
		    $errors.=sprintf(__('Category ID %s is not valid!','exmbb'), '<strong>'.$k.'</strong>').'<br />';
			continue;
		}	
	
	    //Verificamos que categoría exista
		$cat=new BBCategory($k);
		if ($cat->isNew()){
		    $errors.=sprintf(__('Category with id %s does not exists!','exmbb'), '<strong>'.$k.'</strong>').'<br />';
			continue;
		}
	
	    if (!$cat->delete()){
		    $errors.=sprintf(__('Category %s could not be deleted!','exmbb'),'<strong>'.$k.'</strong>').'<br />';
		}
	}
	
	if ($errors!=''){
	    redirectMsg('./categos.php',__('There was errors during this operation','exmbb').'<br />'.$errors,1);
		die();
	}else{
	    redirectMsg('./categos.php',__('Categories deleted successfully!','exmbb'),0);
		die();
	}

}

/**
* @desc Activa o desactiva una categoría
**/
function activeCatego($act=0){
	global $xoopsSecurity;

	$cats = rmc_server_var($_REQUEST,'ids', array());

	//Verificamos si se ha proporcionado una categoría
	if (empty($cats)){
		redirectMsg('./categos.php', __('You must select at least one category','exmbb'),1);
		die();		
	}

	if (!$xoopsSecurity->check()){
	        redirectMsg('categos.php', __('Session token expired!','exmbb'), 1);
	        die();
	}

	$errors='';
	foreach ($cats as $k){
	
		
		//Verificamos que la categoría sea válida
		if ($k<=0){
			$errors.=sprintf(__('Category ID %s is not valid!','exmbb'), '<strong>'.$k.'</strong>').'<br />';
			continue;
		}	
		//Verificamos que categoría exista
		$cat=new BBCategory($k);
		if ($cat->isNew()){
			$errors.=sprintf(_AS_BB_ERRCATNOEXIST,$k);
			continue;
		}
	
		$cat->setStatus($act);
		if (!$cat->save()){
			$errors.=sprintf(_AS_BB_ERRCATNOSAVE,$k);
		}
	}
	
	if ($errors!=''){
		redirectMsg('./categos.php',_AS_BB_ERRACTION.$errors,1);
		die();
	}else{
		redirectMsg('./categos.php',_AS_BB_DBOK,0);
		die();
	}
	

}


/**
* @desc Almacena los camobios realizados en el orden de las categorías
**/
function updateOrderCatego(){
	global $util;

	$orders=isset($_POST['orders']) ? $_POST['orders'] : array();

	if (!$util->validateToken()){
		        redirectMsg('categos.php', _AS_BB_ERRTOKEN, 1);
		        die();
		}
	

	foreach ($orders as $k=>$v){

				
		//Verificamos que la categoría sea válida
		if ($k<=0){
			$errors.=sprintf(_AS_BB_ERRCATNOVALID,$k);
			continue;
		}	
		//Verificamos que categoría exista
		$cat=new BBCategory($k);
		if ($cat->isNew()){
			$errors.=sprintf(_AS_BB_ERRCATNOEXIST,$k);
			continue;
		}

		//Actualizamos el orden
		$cat->setOrder($v);
		if (!$cat->save()){
			$errors.=sprintf(_AS_BB_ERRCATNOSAVE,$k);
		}
	}
	
	if ($errors!=''){
		redirectMsg('./categos.php',_AS_BB_ERRACTION.$errors,1);
		die();
	}else{
		redirectMsg('./categos.php',_AS_BB_DBOK,0);
		die();
	}




}




$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    case 'new':
        showForm();
        break;
    case 'save':
        saveCatego();
        break;
    case 'edit':
        showForm(1);
        break;
    case 'saveedit':
        saveCatego(1);
        break;
    case 'savechanges':
	    updateOrderCatego();
        break;
    case 'delete':
	    deleteCatego();
        break;
    case 'activebulk':
	    activeCatego(1);
        break;
    case 'disablebulk':
	    activeCatego();
        break;
    default:
        showCategories();
        break;
}
