<?php
// $Id: platforms.php 19 2008-01-24 23:10:54Z ginis $
// --------------------------------------------------------------
// D-Transport
// Autor: gina
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
// @author: gina
// @copyright: 2007 - 2008 Red México



define('DT_LOCATION','plats');
include ('header.php');


/**
* @desc Muestra la barra de menus
*/
function optionsBar(){
    global $tpl;
    $tpl->append('xoopsOptions', array('link' => './platforms.php', 'title' => _AS_DT_PLATFORMS, 'icon' => '../images/'));
}

/**
* @desc Visualiza las plataformas existentes y muestra formulario de plataformas
**/
function showPlatforms($edit=0){
		
	global $xoopsModule,$db,$tpl,$adminTemplate;

	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

	$sql="SELECT * FROM ".$db->prefix('dtrans_platforms');
	$result=$db->queryF($sql);
	while($rows=$db->fetchArray($result)){

		$plat=new DTPlatform();
		$plat->assignVars($rows);

		$tpl->append('platforms',array('id'=>$plat->id(),'name'=>$plat->name()));
	}

	$tpl->assign('lang_exist',_AS_DT_EXIST);
	$tpl->assign('lang_id',_AS_DT_ID);
	$tpl->assign('lang_name',_AS_DT_NAME);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_del',_DELETE);

	
	if ($edit){

		//Verificamos si plataforma es válida
		if ($id<=0){
			redirectMsg('./platforms.php',_AS_DT_ERRPLATVALID,1);
			die();
		}

		//Verificamos si plataforma existe
		$plat=new DTPlatform($id);
		if ($plat->isNew()){
			redirectMsg('./platforms.php',_AS_DT_ERRPLATEXIST,1);
			die();
		}
	}
	

	optionsBar();
	$adminTemplate = 'admin/dtrans_platforms.html';
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".($edit ? _AS_DT_EDITPLAT : _AS_DT_NEWPLATFORM));
	xoops_cp_header();

	$form=new RMForm($edit ? _AS_DT_EDITPLAT : _AS_DT_NEWPLATFORM,'frmpalt','platforms.php');

	$form->addElement(new RMText(_AS_DT_NAME,'name',50,150,$edit ? $plat->name() : ''),true);

	$form->addElement(new RMHidden('op',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMHidden('id',$id));

	$buttons =new RMButtonGroup();
	$buttons->addButton('sbt',_SUBMIT,'submit');
	$buttons->addButton('cancel',_CANCEL,'button', 'onclick="window.location=\'platforms.php\';"');

	$form->addElement($buttons);
	
	$tpl->assign('form',$form->render());
	
	xoops_cp_footer();

}



/**
* @desc Almacena la información de las plataformas
**/
function savePlatforms($edit=0){
	global $db;

	foreach ($_POST as $k=>$v){
		$$k=$v;
	}


	if ($edit){

		//Verificamos si plataforma es válida
		if ($id<=0){
			redirectMsg('./platforms.php',_AS_DT_ERRPLATVALID,1);
			die();
		}

		//Verificamos si plataforma existe
		$plat=new DTPlatform($id);
		if ($plat->isNew()){
			redirectMsg('./platforms.php',_AS_DT_ERRPLATEXIST,1);
			die();
		}


		//Comprueba que la plataforma no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_platforms')." WHERE name='$name' AND id_platform<>".$plat->id();
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./platforms.php',_AS_DT_ERRNAME,1);	
			die();
		}


	}else{

		//Comprueba que la plataforma no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_platforms')." WHERE name='$name' ";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./platforms.php',_AS_DT_ERRNAME,1);	
			die();
		}


	
		$plat=new DTPlatform();
	}
	$plat->setName($name);

	if (!$plat->save()){
		redirectMsg('./platforms.php',_AS_DT_DBERROR,1);
		die();
	}else{
		redirectMsg('./platforms.php',_AS_DT_DBOK,0);
		die();
	}


}



/**
* @desc Elimina la plataforma especificada
**/
function deletePlatforms(){

	global $xoopsModule,$util;

	$ids=isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
	$ok=isset($_POST['ok']) ? intval($_POST['ok']) : 0;

	//Verificamos si nos proporcionaron alguna plataforma
	if (!is_array($ids) && $ids<=0){
		redirectMsg('./features.php?item='.$item,_AS_DT_NOTPLAT,1);
		die();	
	}

	$num=0;
	if (!is_array($ids)){
		$pl=new DTPlatform($ids);
		$ids=array($ids);
		$num=1;
	}
	
	if ($ok){
		

		if (!$util->validateToken()){
			redirectMsg('./platforms.php',_AS_DT_SESSINVALID, 1);
			die();
		}

		$errors='';
		foreach ($ids as $k){		
		
			//Verificamos si la plataforma es válida
			if ($k<=0){
				$errors.=sprintf(_AS_DT_ERRPLATVAL,$k);
				continue;	
			}

			//Verificamos si la plataforma existe
			$plat=new DTPlatform($k);
			if ($plat->isNew()){
				$errors.=sprintf(_AS_DT_ERRPLATEX,$k);
				continue;			
			}

			if (!$plat->delete()){
				$errors.=sprintf(_AS_DT_ERRPLATDEL,$k);
			}

		}


		if ($errors!=''){
			redirectMsg('./platforms.php',_AS_DT_ERRORS.$errors,1);
			die();
		}else{
			redirectMsg('./platforms.php',_AS_DT_DBOK,0);
			die();
		}	
	
		
	}else{
		optionsBar();
		xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_DT_PLATFORMS);
		xoops_cp_header();

		$hiddens['ok'] = 1;
		$hiddens['id[]'] = $ids;
		$hiddens['op'] = 'delete';

		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'platforms.php\';"';
		
		$util->msgBox($hiddens, 'platforms.php', ($num ? sprintf(_AS_DT_DELETECONF,$pl->name()) : _AS_DT_DELCONF). '<br /><br />' ._AS_DT_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
	
		xoops_cp_footer();	
		

	}

	
	

}


$op=isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch ($op){
	case 'edit':
		showPlatforms(1);
	break;
	case 'save':
		savePlatforms();
	break;
	case 'saveedit':
		savePlatforms(1);
	break;
	case 'delete':
		deletePlatforms();
	break;
	default:
		showPlatforms();

}


?>
