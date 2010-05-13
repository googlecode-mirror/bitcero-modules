<?php
// $Id$
// --------------------------------------------------------------
// Foros EXMBB
// Módulo para el manejo de Foros en EXM
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.xoopsmexico.net
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
// --------------------------------------------------------------
// @author: BitC3R0
// @copyright: 2007 - 2008 Red México

define('BB_LOCATION', 'categories');
include 'header.php';

/**
* @desc Muestra la barra de menus
*/
function optionsBar(){
    global $tpl;
    
    $tpl->append('xoopsOptions', array('link' => './categos.php', 'title' => _AS_BB_CATEGOS, 'icon' => '../images/catego16.png'));
    $tpl->append('xoopsOptions', array('link' => './categos.php?op=new', 'title' => _AS_BB_NEWCATEGO, 'icon' => '../images/add.png'));
}

/**
* @desc Muestra la lista de categorías existentes
*/
function showCategories(){
    global $tpl, $mc, $xoopsConfig, $adminTemplate, $xoopsModule, $db,$util;
    
    $result = $db->query("SELECT * FROM ".$db->prefix("exmbb_categories")." ORDER BY `order`, title");
    while ($row = $db->fetchArray($result)){
        $catego = new BBCategory();
        $catego->assignVars($row);
        $tpl->append('categos', array('id'=>$catego->id(),'title'=>$catego->title(), 'order'=>$catego->order(),
            'status'=>$catego->status()));
    }
    
    $tpl->assign('lang_existingcats', _AS_BB_EXISTING);
    $tpl->assign('lang_id', _AS_BB_ID);
    $tpl->assign('lang_title', _AS_BB_TITLE);
    $tpl->assign('lang_order', _AS_BB_ORDER);
    $tpl->assign('lang_active', _AS_BB_ACTIVE);
    $tpl->assign('lang_options', _OPTIONS);
    $tpl->assign('lang_edit', _EDIT);
    $tpl->assign('lang_delete', _DELETE);
    $tpl->assign('lang_disable', _AS_BB_DISABLE);
    $tpl->assign('lang_activ',_AS_BB_ACTIV);
    $tpl->assign('lang_save', _AS_BB_SAVE);
    $tpl->assign('token',$util->getTokenHTML());
    
    optionsBar();
    xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_BB_CATEGOS);
    $adminTemplate = 'admin/forums_categos.html';
    xoops_cp_header();
    
    xoops_cp_footer();
    
}

/**
* @desc Muestra el formulario para edición/creación de categorías
*/
function showForm($edit = 0){
    global $xoopsModule, $xoopsConfig, $xoopsModuleConfig;
    
    if ($edit){
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id<=0){
            redirectMsg('categos.php', _AS_BB_ERRID, 1);   
            die();
        }
        
        $catego = new BBCategory($id);
        if ($catego->isNew()){
            redirectMsg('categos.php', _AS_BB_ERRNOEXISTS, 1);   
            die();
        }
    }
    
    optionsBar();
    xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_BB_NEWCATEGO);
    xoops_cp_header();
    
    $form = new RMForm($edit ? _AS_BB_EDITCATEGO : _AS_BB_NEWCATEGO, 'frmCat', 'categos.php');
    $form->setExtra('enctype="multipart/form-data"');
    $form->addElement(new RMText(_AS_BB_TITLE, 'title', 50, 100, $edit ? $catego->title() : ''), true);
    if ($edit){
        $form->addElement(new RMText(_AS_BB_FRIENDNAME, 'friendname', 50, 100, $catego->friendName()))   ;
    }
    $form->addElement(new RMEditor(_AS_BB_DESC, 'desc', '90%', '300px', $edit ? $catego->description() : '', $xoopsConfig['editor_type']));
    $form->addElement(new RMYesNo(_AS_BB_SHOWDESC, 'showdesc', $edit ? $catego->showDesc() : 1));
    $form->addElement(new RMText(_AS_BB_ORDER, 'order', 5, 10, $edit ? $catego->order() : 0));
    $form->addElement(new RMYesNo(_AS_BB_ACTIVATE, 'status', $edit ? $catego->status() : 1));
    $form->addElement(new RMGroups(_AS_BB_GROUPS, 'groups', 1, 1, 4, $edit ? $catego->groups() : array(0)), true, 'checked');
    $form->addElement(new RMHidden('op', $edit ? 'saveedit' : 'save'));
    if ($edit) $form->addElement(new RMHidden('id', $catego->id()));
    $buttons = new RMButtonGroup();
    $buttons->addButton('sbt', _SUBMIT, 'submit', '', true);
    $buttons->addButton('cancel', _CANCEL, 'button', 'onclick="window.location=\'categos.php\';"');
    $form->addElement($buttons);
    
    $form->display();
    
    xoops_cp_footer();
}

/**
* @desc Almacena los datos de una categoría
*/
function saveCatego($edit = 0){
    global $xoopsConfig, $xoopsModuleConfig, $db, $util;
    
    if (!$util->validateToken()){
        redirectMsg('categos.php', _AS_BB_ERRTOKEN, 1);
        die();
    }
    
    $friendname = '';
    foreach ($_POST as $k => $v){
        $$k = $v;
    }
    
    if ($edit){
        
        if ($id<=0){
            redirectMsg('categos.php', _AS_BB_ERRID, 1);   
            die();
        }
        
        $catego = new BBCategory($id);
        if ($catego->isNew()){
            redirectMsg('categos.php', _AS_BB_ERRNOEXISTS, 1);   
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
    $friendname = $friendname!='' ? $util->sweetstring($friendname) : $util->sweetstring($title);
    // Comprobamos que el nombre no este asignada a otra categoría
    list($num) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("exmbb_categories")." WHERE friendname='$friendname' AND id_cat<>'$id'"));
    if ($num>0){
        redirectMsg('categos.php?op=edit&id='.$id, _AS_BB_ERREXISTSF, 1);
        die();
    }
    $catego->setDescription($desc);
    $catego->setFriendName($friendname);
    $catego->setGroups(!isset($groups) || is_array($groups) ? array(0) : $groups);
    $catego->setOrder($order<=0 ? 0 : intval($order));
    $catego->setShowDesc($showdesc);
    $catego->setStatus($status);
    
    if ($catego->save()){
        redirectMsg('categos.php', _AS_BB_DBOK, 0);
    } else {
        redirectMsg('categos.php', _AS_BB_ERRACTION . '<br />' . $catego->errors(), 1);
    }
    
}


/**
* @desc Eliminina categorías
**/
function deleteCatego(){
	global $xoopsModule,$util;	
	
	$cats=isset($_REQUEST['cats']) ? $_REQUEST['cats'] : null;
	$ok=isset($_POST['ok']) ? intval($_POST['ok']) : 0;
	
	//Verificamos si se ha proporcionado una categoría
	if (!is_array($cats) && ($cats<=0)){
		redirectMsg('./categos.php',_AS_BB_ERRNOTCAT,1);
		die();		
	}

	if (!is_array($cats)) $cats=array($cats);
	

	if ($ok){

		if (!$util->validateToken()){
		        redirectMsg('categos.php', _AS_BB_ERRTOKEN, 1);
		        die();
		}

		$errors='';
		foreach ($cats as $k){
	
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
	
			if (!$cat->delete()){
				$errors.=sprintf(_AS_BB_ERRCATNODEL,$k);
		}
		}
	
		if ($errors!=''){
			redirectMsg('./categos.php',_AS_BB_ERRACTION.$errors,1);
			die();
		}else{
			redirectMsg('./categos.php',_AS_BB_DBOK,0);
			die();
		}

	}else{
		optionsBar();
		xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_BB_DELCATEGO);
		xoops_cp_header();

		$hiddens['ok'] = 1;
		$hiddens['cats[]'] = $cats;
		$hiddens['op'] = 'delete';
		
		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'categos.php\';"';
		
		$util->msgBox($hiddens, 'categos.php',_AS_BB_DELETECONF. '<br /><br />' . _AS_BB_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
	
		xoops_cp_footer();






	}


}

/**
* @desc Activa o desactiva una categoría
**/
function activeCatego($act=0){
	global $util;

	$cats=isset($_REQUEST['cats']) ? $_REQUEST['cats'] : array();

	//Verificamos si se ha proporcionado una categoría
	if (!is_array($cats) || empty($cats)){
		redirectMsg('./categos.php',_AS_BB_ERRNOTCAT,1);
		die();		
	}

	if (!$util->validateToken()){
	        redirectMsg('categos.php', _AS_BB_ERRTOKEN, 1);
	        die();
	}

	$errors='';
	foreach ($cats as $k){
	
		
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




$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch($op){
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

?>
