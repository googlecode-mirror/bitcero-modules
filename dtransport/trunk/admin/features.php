<?php
// $Id: features.php 37 2008-03-03 18:46:45Z BitC3R0 $
// --------------------------------------------------------------
// D-Transport
// Módulo para la administración de descargas
// http://www.redmexico.com.mx
// http://www.exmsystem.com
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
// @copyright: 2007 - 2008 Red México

define('DT_LOCATION','features');
include ('header.php');

/**
* @desc Muestra la barra de menus
*/
function optionsBar(){
    global $tpl;

    $item = isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;
	
    $tpl->append('xoopsOptions', array('link' => './features.php?item='.$item, 'title' => _AS_DT_FEATURES, 'icon' => '../images/features16.png'));
    if ($item){
	$tpl->append('xoopsOptions', array('link' => './features.php?op=new&item='.$item, 'title' => _AS_DT_NEWFEATURE, 'icon' => '../images/add.png'));
    }
}


/**
* @desc Visualiza las caracteríticas existentes de un elemento especificado
**/
function showFeatures(){
	global $xoopsModule,$adminTemplate,$tpl,$db,$util,$xoopsModuleConfig;


	$item = isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;

	$sw=new DTSoftware($item);
	
	if ($sw->isNew() && $item>0){
		redirectMsg('features.php', _AS_DT_ERR_ITEMEXIST, 1);
		die();
	}
	

	$sql = "SELECT * FROM ".$db->prefix('dtrans_features')." WHERE id_soft=$item ";
	$result = $db->queryF($sql);
	while ($rows=$db->fetchArray($result)){
		$ft = new DTFeature();
		$ft->assignVars($rows);

		$tpl->append('features',array('id'=>$ft->id(),'title'=>$ft->title(),'created'=>formatTimestamp($ft->created(), 's'),
				'modified'=>formatTimestamp($ft->modified(), 's'), 'software'=>$sw->name()));
	
	}

	$tpl->assign('item',$item);
	$tpl->assign('lang_exists',sprintf(_AS_DT_EXISTS,$sw->name()));
	$tpl->assign('lang_id',_AS_DT_ID);
	$tpl->assign('lang_title',_AS_DT_TITLE);
	$tpl->assign('lang_modified',_AS_DT_MODIFIED);
	$tpl->assign('lang_new', _AS_DT_NEW);
	$tpl->assign('lang_created',_AS_DT_CREATED);
	$tpl->assign('lang_featnew',_AS_DT_FEATNEW);
	$tpl->assign('lang_software',_AS_DT_SOFTWARE);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_del',_DELETE);
	$tpl->assign('lang_selectitem',_AS_DT_SELECTITEM);
	$tpl->assign('lang_listsoft',_AS_DT_LSOFT);
	$tpl->assign('parent','features');

	optionsBar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./items.php'>"._AS_DT_SW."</a> &raquo; "._AS_DT_FEATURES);
	$adminTemplate = 'admin/dtrans_features.html';
	xoops_cp_header();
	xoops_cp_footer();

}

/**
* @desc Formulario de características
**/
function formfeatures($edit=0){
	global $db,$xoopsModule,$xoopsConfig;

	$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$item = isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;

	//Verificamos que el software sea válido
	if ($item<=0){
		redirectMsg('./features.php',_AS_DT_ERR_ITEMVALID,1);
		die();
	}
	//Verificamos que el software exista
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirectMsg('./features.php',_AS_DT_ERR_ITEMEXIST,1);
		die();
	}



	if ($edit){
		//Verificamos que característica sea válida
		if ($id<=0){
			redirectMsg('./features.php?item='.$item,_AS_DT_ERRFEATVALID,1);
			die();
		}

		//Verificamos que la característica exista
		$ft = new DTFeature($id);
		if ($ft->isNew()){
			redirectMsg('./features.php?item='.$item,_AS_DT_ERRFEATEXIST,1);
			die();
		}

	}

	optionsBar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./items.php'>"._AS_DT_SW."</a> &raquo; ".($edit ? _AS_DT_EDITFEATURE : _AS_DT_NEWFEATURE));
	xoops_cp_header();
	
	$form = new RMForm($edit ? sprintf(_AS_DT_EDITFEATURES,$sw->name()) : sprintf(_AS_DT_NEWFEATURES,$sw->name()),'frmfeat','features.php');

	$sw = new DTSoftware($item);
	$form->addElement(new RMLabel(_AS_DT_SOFTWARE,$sw->name()));
	

	$form->addElement(new RMText(_AS_DT_TITLE,'title',50,200,$edit ? $ft->title() : ''),true);
	$form->addElement(new RMText(_AS_DT_SHORTNAME, 'nameid', 50, 200, $edit ? $ft->nameId() : ''));
	$form->addElement(new RMEditor(_AS_DT_CONTENT,'content','90%','350px',$edit ? $ft->content() : '',$xoopsConfig['editor_type']),true);
	if ($edit){
		$dohtml = $ft->getVar('dohtml');
		$dobr = $ft->getVar('dobr');
		$doimage = $ft->getVar('doimage');
		$dosmiley = $ft->getVar('dosmiley');
		$doxcode = $ft->getVar('doxcode');
	} else {
		$dohtml = 1;
		$dobr = 0;
		$doimage = 0;
		$dosmiley = 0;
		$doxcode = 0;
	}
	$form->addElement(new RMTextOptions(_OPTIONS, $dohtml, $doxcode, $doimage, $dosmiley, $dobr));
	
	$form->addElement(new RMHidden('op',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMHidden('id',$id));
	$form->addElement(new RMHidden('item',$item));
	
	$buttons =new RMButtonGroup();
	$buttons->addButton('sbt',_SUBMIT,'submit');
	$buttons->addButton('cancel',_CANCEL,'button', 'onclick="window.location=\'features.php?item='.$item.'\';"');

	$form->addElement($buttons);
			

	$form->display();

	xoops_cp_footer();
}




/**
* @desc Almacena la característica en la base de datos
**/
function saveFeatures($edit=0){
	global $util,$db;

	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	if (!$util->validateToken()){
		if (!$edit){
			redirectMsg('./features.php?op=new&item='.$item,_AS_DT_SESSINVALID, 1);
			die();
		}else{
			redirectMsg('./features.php?op=edit&id='.$id.'&item='.$item,_AS_DT_SESSINVALID, 1);
			die();
		}
	}

	//Verificamos que el software sea válido
	if ($item<=0){
		redirectMsg('./features.php',_AS_DT_ERR_ITEMVALID,1);
		die();
	}
	//Verificamos que el software exista
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirectMsg('./features.php',_AS_DT_ERR_ITEMEXIST,1);
		die();
	}


	if ($edit){
		//Verificamos que característica sea válida
		if ($id<=0){
			redirectMsg('./features.php?item='.$item,_AS_DT_ERRFEATVALID,1);
			die();
		}

		//Verificamos que la característica exista
		$ft = new DTFeature($id);
		if ($ft->isNew()){
			redirectMsg('./features.php?item='.$item,_AS_DT_ERRFEATEXIST,1);
			die();
		}

		//Comprueba que el título de la característica no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_features')." WHERE title='$title' AND id_feat<>".$ft->id()." AND id_soft=".$ft->software();
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./features.php?op=edit&id='.$id.'&item='.$item,_AS_DT_ERRNAME,1);	
			die();
		}
		
		if ($nameid){

			$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_features')." WHERE nameid='$nameid' AND id_feat<>'".$id."'";
			list($num)=$db->fetchRow($db->queryF($sql));
			if ($num>0){
				unset($nameid);
			}

		}


	}else{
		//Comprueba que el título de la característica no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_features')." WHERE title='$title' AND id_soft=".$sw->id();
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./features.php?op=new&item='.$item,_AS_DT_ERRNAME,1);	
			die();
		}
		
		if ($nameid){

			$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_features')." WHERE nameid='$nameid' AND id_soft<>'".$sw->id()."'";
			list($num)=$db->fetchRow($db->queryF($sql));
			if ($num>0){
				unset($nameid);
			}

		}

		$ft = new DTFeature();

	}


	$found=false; 
	$i = 0;
	if ($title!=$ft->title() && empty($nameid)){
		do{
    			$nameid = $util->sweetstring($title).($found ? $i : '');
        		$sql = "SELECT COUNT(*) FROM ".$db->prefix('dtrans_features'). " WHERE nameid = '$nameid'";
        		list ($num) =$db->fetchRow($db->queryF($sql));
        		if ($num>0){
        			$found =true;
        		    $i++;
        		}else{
        			$found=false;
        		}
		}while ($found==true);
	}


	$ft->setSoftware($item);
	$ft->setTitle($title);
	$ft->setContent($content);
	if (!$edit) $ft->setCreated(time());
	$ft->setModified(time());
	$ft->setNameId($nameid);
	
	$ft->setVar('dohtml', isset($dohtml) ? 1 : 0);
	$ft->setVar('doxcode', isset($doxcode) ? 1 : 0);
	$ft->setVar('dobr', isset($dobr) ? 1 : 0);
	$ft->setVar('dosmiley', isset($dosmiley) ? 1 : 0);
	$ft->setVar('doimage', isset($doimage) ? 1 : 0);

	if (!$ft->save()){
		if ($ft->isNew()){
			redirectMsg('./features.php?op=new&item='.$item,_AS_DT_DBERROR,1);
			die();
		}else{
			redirectMsg('./features.php?op=edit&item='.$item,_AS_DT_DBERROR,1);
			die();			
		}
	}else{
		redirectMsg('./features.php?item='.$item,_AS_DT_DBOK,0);
		die();
	}



}

/**
* @desc Elimina la característica especificada de la base de datos
**/
function deleteFeatures(){
	global $util,$xoopsModule;
	
	$ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
	$item = isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;
	$ok = isset($_POST['ok']) ? intval($_POST['ok']) : 0;
	
	//Verificamos que el software sea válido
	if ($item<=0){
		redirectMsg('./features.php',_AS_DT_ERR_ITEMVALID,1);
		die();
	}
	//Verificamos que el software exista
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirectMsg('./features.php',_AS_DT_ERR_ITEMEXIST,1);
		die();
	}

	//Verificamos si nos proporcionaron alguna caracteristica
	if (!is_array($ids) && $ids<=0){
		redirectMsg('./features.php?item='.$item,_AS_DT_ERRFEAT,1);
		die();	
	}

	$num=0;
	if (!is_array($ids)){
		$feat=new DTFeature($ids);
		$ids=array($ids);
		$num=1;
	}


	if ($ok){	

		if (!$util->validateToken()){
				redirectMsg('./features.php?item='.$item,_AS_DT_SESSINVALID, 1);
				die();
		}

		foreach ($ids as $k){

			//Verificamos si la característica es válida
			if ($k<=0){
				$errors.=sprintf(_AS_DT_ERRFEATVAL,$k);
				continue;
			}

			//Verificamos si la caracteristica existe
			$ft=new DTFeature($k);
			if ($ft->isNew()){
				$errors.=sprintf(_AS_DT_ERRFEATEX,$k);
				continue;			
			}
		
			if (!$ft->delete()){
				$errors.=sprintf(_AS_DT_ERRFEATDEL,$k);
			}


		}

			
		if ($errors!=''){
			redirectMsg('./features.php?item='.$item,_AS_DT_ERRORS.$errors,1);
			die();
		}else{
			redirectMsg('./features.php?item='.$item,_AS_DT_DBOK,0);
			die();
		}	

	}else{
		optionsBar();
		xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./items.php'>"._AS_DT_SW."</a> &raquo; "._AS_DT_DELETEFEATURE);
		xoops_cp_header();

		$hiddens['ok'] = 1;
		$hiddens['id[]'] = $ids;
		$hiddens['item'] = $item;
		$hiddens['op'] = 'delete';

		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'features.php?item='.$item.'\';"';
		
		$util->msgBox($hiddens, 'features.php', ($num ? sprintf(_AS_DT_DELETECONF,$feat->title()) : _AS_DT_DELCONF). '<br /><br />' ._AS_DT_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
	
		xoops_cp_footer();

	}

}


/**
* @desc Cambia a nueva una característica
**/
function newFeatures(){

	$ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
	$item = isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;
	
	//Verificamos si se proporcionó una caracteristica
	if (!is_array($ids) || empty($ids)){
		redirectMsg('./features.php?item='.$item,_AS_DT_ERRFEAT,1);
		die();
	}

	$errors='';
	foreach ($ids as $k){

		//Verificamos si la característica es válida
		if ($k<=0){
			$errors.=sprintf(_AS_DT_ERRFEATVAL,$k);
			continue;
		}

		//Verificamos si la caracteristica existe
		$ft=new DTFeature($k);
		if ($ft->isNew()){
			$errors.=sprintf(_AS_DT_ERRFEATEX,$k);
			continue;			
		}

		$ft->setShowNew(!$ft->showNew());
		$ft->setModified(time());	
		
		if (!$ft->save()){
			$errors.=sprintf(_AS_DT_ERRFEATSAVE,$k);
		}
	
	}

	if ($errors!=''){
		redirectMsg('./features.php?item='.$item,_AS_DT_ERRORS.$errors,1);
		die();
	}else{
		redirectMsg('./features.php?item='.$item,_AS_DT_DBOK,0);
		die();
	}


}




$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch ($op){
	case 'new':
		formFeatures();
	break;
	case 'edit':
		formFeatures(1);
	break;
	case 'save':
		saveFeatures();
	break;
	case 'saveedit':
		saveFeatures(1);
	break;
	case 'delete':
		deleteFeatures();
	break;
	case 'newfeat':
		newFeatures();
	break;
	default:
		showFeatures();
}





?>
