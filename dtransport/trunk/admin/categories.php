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
* @desc Muestra la barra de menus
*/
function optionsBar(){
    global $tpl;
  
    $tpl->append('xoopsOptions', array('link' => './categories.php', 'title' => _AS_DT_CATEGO, 'icon' => '../images/soft16.png'));
    $tpl->append('xoopsOptions', array('link' => './categories.php?op=new', 'title' => _AS_DT_NEWCATEGO, 'icon' => '../images/add.png'));
}

/**
* @desc Visualiza todas las categorías existentes
**/
function showCategos(){
	global $xoopsModule;

	$categos = array();
	DTFunctions::getCategos($categos);
        $categories;
	foreach ($categos as $row){
		$cat = new DTCategory();
		$cat->assignVars($row);

		$categories[] = array(
                    'id'=>$cat->id(),
                    'name'=>$cat->name(),
                    'parent'=>$cat->parent(),
                    'active'=>$cat->active(),
                    'indent'=>$row['jumps']
                );

	}
        
        unset($categos);
	
	DTFunctions::toolbar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('Categories','dtransport'));
	$adminTemplate = 'admin/dtrans_categos.html';
	xoops_cp_header();
	xoops_cp_footer();	

}


/**
* @desc Formulario de creación/edición de categorías
**/
function formCategos($edit=0){
	global $xoopsModule,$db;
	
	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;	

	if ($edit){

		//Verificamos si categoría es válida
		if ($id<=0){
			redirectMsg('./categories.php',_AS_DT_ERRCATVALID,1);
			die();
		}

		//Verificamos si la categoría existe
		$cat=new DTCategory($id);
		if ($cat->isNew()){
			redirectMsg('./categories.php',_AS_DT_ERRCATEXIST,1);
			die();
		}

	}


	optionsBar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".($edit ? _AS_DT_EDITCATEGO : _AS_DT_NEWCATEGO));
	xoops_cp_header();

	$form=new RMForm($edit ? _AS_DT_EDITCATEGO : _AS_DT_NEWCATEGO,'frmcat','categories.php');
	$form->addElement(new RMText(_AS_DT_NAME,'name',50,150,$edit ? $cat->name() : ''),true);

	if ($edit){
		$form->addElement(new RMText(_AS_DT_NAMEID,'nameid',50,150,$edit ? $cat->nameId() : ''));
	}

	$form->addElement(new RMText(_AS_DT_DESC,'desc',50,255,$edit ? $cat->desc() : ''));

	//Lista de categorías
	$ele=new RMSelect(_AS_DT_PARENT,'parent');
	$ele->addOption(0,_SELECT);
	$categos = array();
	DTFunctionsHandler::getCategos($categos, 0, 0, $edit ? $cat->id() : array());
	foreach ($categos as $catego){
		$ele->addOption($catego['id_cat'],str_repeat('--', $catego['jumps']).' '.$catego['name'],$edit ? ($catego['id_cat']==$cat->parent() ? 1 : 0) : 0);		
	}

	$form->addElement($ele);
	$form->addElement(new RMYesno(_AS_DT_ACTIVE,'active',$edit ? $cat->active() : 1));

	$form->addElement(new RMHidden('op',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMHidden('id',$id));

	$buttons =new RMButtonGroup();
	$buttons->addButton('sbt',_SUBMIT,'submit');
	$buttons->addButton('cancel',_CANCEL,'button', 'onclick="window.location=\'categories.php\';"');

	$form->addElement($buttons);
	
	$form->display();
	
	xoops_cp_footer();
}




/**
* @desc Almacena la informaciín perteneciente a la categoría en la base de datos
**/
function saveCategos($edit=0){

	global $util,$db;

	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	if (!$util->validateToken()){
		
		redirectMsg('./categories.php',_AS_DT_SESSINVALID, 1);
		die();
		
	}
		

	if ($edit){

		//Verificamos si categoría es válida
		if ($id<=0){
			redirectMsg('./categories.php',_AS_DT_ERRCATVALID,1);
			die();
		}

		//Verificamos si la categoría existe
		$cat=new DTCategory($id);
		if ($cat->isNew()){
			redirectMsg('./categories.php',_AS_DT_ERRCATEXIST,1);
			die();
		}

		//Comprueba que el nombre de la categoría no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_categos')." WHERE name='$name' AND id_cat<>'".$id."' AND parent=$parent";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./categories.php?op=edit&id='.$id,_AS_DT_ERRNAME,1);	
			die();
		}


		//Verificamos si el nameId existe
		if ($nameid){
			$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_categos')." WHERE nameid='$nameid' AND id_cat<>'".$id."' AND parent=$parent";
			list($num)=$db->fetchRow($db->queryF($sql));
			if ($num>0){
				redirectMsg('./categories.php?op=edit&id='.$id,_AS_DT_ERRNAMEID,1);
				die();
			}

		}


	}else{
		//Comprueba que el nombre de la categoría no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_categos')." WHERE name='$name' AND parent=$parent";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./categories.php?op=new',_AS_DT_ERRNAME,1);	
			die();
		}

		$cat=new DTCategory();
	}


	//Genera $nameid Nombre identificador
	$found=false; 
	$i = 0;
	if ($name!=$cat->name() || empty($nameid)){
		do{
			
			$nameid = $util->sweetstring($name).($found ? $i : '');
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
		redirectMsg('./categories.php',_AS_DT_DBERROR,1);
		die();
	}else{
		redirectMsg('./categories.php',_AS_DT_DBOK,0);
		die();
	}

}


/**
* @desc Elimina las categorías especificadas
**/
function deleteCategos(){

	global $xoopsModule,$util;

	$ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
	$ok = isset($_POST['ok']) ? intval($_POST['ok']) : 0;
	
	//Verificamos si nos proporcionaron alguna categoria
	if (!is_array($ids) && $ids<=0){
		redirectMsg('./categories.php',_AS_DT_ERRCAT,1);
		die();
	}


	$num=0;
	if (!is_array($ids)){
		$ct=new DTCategory($ids);
		$ids=array($ids);
		$num=1;
	}
	


	if ($ok){

		if (!$util->validateToken()){
			redirectMsg('./categories.php',_AS_DT_SESSINVALID, 1);
			die();
		}

		$errors='';
		foreach ($ids as $k){
			//Verificamos si la categoría es válida
			if ($k<=0){
				$errors.=sprintf(_AS_DT_ERRCATVAL,$k);
				continue;	
			}

			//verificamos si la categoría existe
			$cat=new DTCategory($k);
			if ($cat->isNew()){
				$errors.=sprintf(_AS_DT_ERRCATEX,$k);
				continue;
			}

			if (!$cat->delete()){
				$errors.=sprintf(_AS_DT_ERRCATDEL,$k);
			}

		}

		if ($errors!=''){
			redirectMsg('./categories.php',_AS_DT_ERRORS.$errors,1);
			die();	
		}else{
			redirectMsg('./categories.php',_AS_DT_DBOK,0);
			die();
		}

				
	}else{
		
		optionsBar();
		xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_DT_DELCAT);
		xoops_cp_header();

		$hiddens['ok'] = 1;
		$hiddens['id[]'] = $ids;
		$hiddens['op'] = 'delete';
		
		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'categories.php\';"';
		
		$util->msgBox($hiddens, 'categories.php',($num ?  sprintf(_AS_DT_DELETECONF, $ct->name()) : _AS_DT_DELCONF). '<br /><br />' ._AS_DT_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
	
		xoops_cp_footer();

	
	}

}


/**
* @desc Activa/deactiva categorías
**/
function activeCategos($act=0){

	$ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;

	
	//Verificamos si nos proporcionaron alguna categoria
	if (!is_array($ids) || empty($ids)){
		redirectMsg('./categories.php',_AS_DT_ERRCAT,1);
		die();
	}

	$errors='';
	foreach ($ids as $k){
		//Verificamos si la categoría es válida
		if ($k<=0){
			$errors.=sprintf(_AS_DT_ERRCATVAL,$k);
			continue;	
		}

		//verificamos si la categoría existe
		$cat=new DTCategory($k);
		if ($cat->isNew()){
			$errors.=sprintf(_AS_DT_ERRCATEX,$k);
			continue;
		}

		$cat->setActive($act);
		if (!$cat->save()){
			$errors.=sprintf(_AS_DT_ERRCATSAVE,$k);
		}
	}

	if ($errors!=''){
		redirectMsg('./categories.php',_AS_DT_ERRORS.$errors,1);
		die();	
	}else{
		redirectMsg('./categories.php',_AS_DT_DBOK,0);
		die();
	}
	
}


$op=isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch ($op){
	case 'new':
		formCategos();
	break;
	case 'edit':
		formCategos(1);
	break;
	case 'save':
		saveCategos();
	break;
	case 'saveedit':
		saveCategos(1);
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
		showCategos();		
}

?>
