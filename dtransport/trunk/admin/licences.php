<?php
// $Id: licences.php 19 2008-01-24 23:10:54Z ginis $
// --------------------------------------------------------------
// D-Transport
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

define('DT_LOCATION','licences');
include ('header.php');


/**
* @desc Muestra la barra de menus
*/
function optionsBar(){
    global $tpl;
    $tpl->append('xoopsOptions', array('link' => './licences.php', 'title' => _AS_DT_LICENCES, 'icon' => '../images/lics16.png'));
    $tpl->append('xoopsOptions', array('link' => './licences.php?op=new', 'title' => _AS_DT_NEWLICENSE, 'icon' => '../images/add.png'));
}


/**
* @desc Visualiza todas las licencias existentes
**/
function showLicences(){

	global $adminTemplate,$xoopsModule,$tpl,$db;

	$sql="SELECT * FROM ".$db->prefix('dtrans_licences');
	$result=$db->queryF($sql);
	while ($rows=$db->fetchArray($result)){

		$lc=new DTLicense();
		$lc->assignVars($rows);

		$tpl->append('licences',array('id'=>$lc->id(),'name'=>$lc->name(),'url'=>$lc->link(),'type'=>$lc->type()));
	}

	$tpl->assign('lang_exist',_AS_DT_EXIST);
	$tpl->assign('lang_id',_AS_DT_ID);
	$tpl->assign('lang_name',_AS_DT_NAME);
	$tpl->assign('lang_url',_AS_DT_URL);
	$tpl->assign('lang_type',_AS_DT_TYPE);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_del',_DELETE);


	optionsBar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_DT_LICENCES);
	$adminTemplate = 'admin/dtrans_licences.html';
	xoops_cp_header();
	xoops_cp_footer();


}


/**
* @desc Formulario de licencias
**/
function formLicences($edit=0){

	global $xoopsModule;

	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

	if ($edit){
		//Verificamos si la licencia es válida
		if ($id<=0){
			redirectMsg('./licences.php',_AS_DT_ERRLICVALID,1);
			die();
		}

		//Verificamos si la licencia existe
		$lc=new DTLicense($id);
		if ($lc->isNew()){
			redirectMsg('./licences.php',_AS_DT_ERRLICEXIST,1);
			die();
		}

	}


	optionsBar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".($edit ? _AS_DT_EDITLIC : _AS_DT_NEWLICENSE));
	xoops_cp_header();

	$form=new RMForm($edit ? _AS_DT_EDITLIC : _AS_DT_NEWLICENSE,'frmlic','licences.php');
	$form->addElement(new RMText(_AS_DT_NAME,'name',50,150,$edit ? $lc->name() : ''),true);
	$form->addElement(new RMText(_AS_DT_URL,'url',50,255,$edit ? $lc->link() : ''));

	$ele=new RMSelect(_AS_DT_TYPE,'type');
	$ele->addOption(0,_AS_DT_FREE,$edit ? ($lc->type()==0 ? 1 : 0) : 0);
	$ele->addOption(1,_AS_DT_RESTRICT,$edit ? ($lc->type()==1 ? 1 : 0) : 0);

	$form->addElement($ele);

	$form->addElement(new RMHidden('op',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMHidden('id',$id));

	$buttons =new RMButtonGroup();
	$buttons->addButton('sbt',_SUBMIT,'submit');
	$buttons->addButton('cancel',_CANCEL,'button', 'onclick="window.location=\'licences.php\';"');

	$form->addElement($buttons);
	

	$form->display();

	xoops_cp_footer();

}


/**
* @desc Almacena la informaciónj de las licencias en la base de datos
**/
function saveLicences($edit=0){

	global $util,$db;

	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	if (!$util->validateToken()){
		redirectMsg('./licences.php',_AS_DT_SESSINVALID, 1);
		die();
	}



	if ($edit){
		//Verificamos si la licencia es válida
		if ($id<=0){
			redirectMsg('./licences.php',_AS_DT_ERRLICVALID,1);
			die();
		}

		//Verificamos si la licencia existe
		$lc=new DTLicense($id);
		if ($lc->isNew()){
			redirectMsg('./licences.php',_AS_DT_ERRLICEXIST,1);
			die();
		}

		//Comprueba que la licencia no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_licences')." WHERE name='$name' AND id_lic<>".$lc->id();
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./licences.php',_AS_DT_ERRNAME,1);	
			die();
		}


	}else{

		//Comprueba que la licencia no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_licences')." WHERE name='$name'";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./licences.php',_AS_DT_ERRNAME,1);	
			die();
		}

		$lc=new DTLicense();
	}

	$lc->setName($name);
	//Verificamos si se proporcionó una url correcta
	if ($url && !isURL($url)){
		if ($lc->isNew()){
			redirectMsg('./licences.php?op=new',_AS_DT_ERRURL,1);
			die();
		}else{
			redirectMsg('./licences.php?op=edit&id='.$id,_AS_DT_ERRURL,1);
			die();
		}
	}
	$lc->setLink($url);
	$lc->setType($type);


	if (!$lc->save()){
		if ($lc->isNew()){
			redirectMsg('./licences.php?op=new',_AS_DT_DBERROR,1);
			die();
		}else{
			redirectMsg('./licences.php?op=edit&id='.$id,_AS_DT_DBERROR,1);
			die();
		}
	}else{
		redirectMsg('./licences.php',_AS_DT_DBOK,0);
		die();
	}
	
}
	

/**
* @desc Elimina la licencia especificada
**/
function deleteLicences(){

	global $xoopsModule,$util;

	$ids=isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
	$ok=isset($_POST['ok']) ? intval($_POST['ok']) : 0;
	
	//Verificamos si nos proporcionaron alguna licencia
	if (!is_array($ids) && $ids<=0){
		redirectMsg('./features.php?item='.$item,_AS_DT_NOTLIC,1);
		die();	
	}

	$num=0;
	if (!is_array($ids)){
		$lc=new DTLicense($ids);
		$ids=array($ids);
		$num=1;
	}

	if ($ok){

		if (!$util->validateToken()){
			redirectMsg('./licences.php',_AS_DT_SESSINVALID, 1);
			die();
		}

		$errors='';
		foreach ($ids as $k){

			//Verificamos si la licnecia es válida
			if ($k<=0){
				$errors.=sprintf(_AS_DT_ERRLICVAL,$k);
				continue;
			}

			//Verificamos si la licencia existe
			$lic=new DTLicense($k);
			if ($lic->isNew()){
				$errors.=sprintf(_AS_DT_ERRLICEX,$k);
				continue;
			}

			if (!$lic->delete()){
				$errors.=sprintf(_AS_DT_ERRLICDEL,$k);
			}


		}

		
		if ($errors!=''){
			redirectMsg('./licences.php',_AS_DT_ERRORS.$errors,1);
			die();
		}else{
			redirectMsg('./licences.php',_AS_DT_DBOK,0);
			die();			
		}

	}else{
		xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_DT_DELETELIC);
		xoops_cp_header();

		$hiddens['ok'] = 1;
		$hiddens['id[]'] = $ids;
		$hiddens['op'] = 'delete';

		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'licences.php\';"';
		
		$util->msgBox($hiddens, 'licences.php',($num ? sprintf(_AS_DT_DELETECONF,$lc->name()) :  _AS_DT_DELCONF). '<br /><br />' ._AS_DT_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
	
		xoops_cp_footer();


	}




}



$op=isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch ($op){
	case 'new':
		formLicences();
	break;
	case 'edit':
		formLicences(1);
	break;
	case 'save':
		saveLicences();
	break;
	case 'saveedit':
		saveLicences(1);
	break;
	case 'delete':
		deleteLicences();
	break;
	default:
		showLicences();

}



?>
