<?php
// $Id$
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

define('DT_LOCATION','screens');
include '../../mainfile.php';

$mc=& $xoopsModuleConfig;


/**
* @desc Visualiza las pantallas del software y 
* el formulario de creación de pantallas
**/
function screens($edit=0){
	global $xoopsOption,$db,$tpl,$xoopsUser,$mc;
	
	$xoopsOption['template_main'] = 'dtrans_screens.html';
	$xoopsOption['module_subpage'] = 'screens';
	include('header.php');
	DTFunctions::makeHeader();

	$item = isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;
	$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

	//Verificamos si el software es válido
	if ($item<=0){
		redirect_header(XOOPS_URL."/modules/dtransport/mydownloads.php",1,_MS_DT_ERRIDITEM);
		die();

	}
	
	//Veirificamos si el software existe
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirect_header(XOOPS_URL."/modules/dtransport/mydownloads.php",1,_MS_DT_ERRITEMEXIST);
		die();
	}


	//Verificamos si el usuario es el propietario de la descarga
	if ($xoopsUser->uid()!=$sw->uid()){
		redirect_header(XOOPS_URL."/modules/dtransport/",2,_MS_DT_ERRUSER);
		die();
	}


	$sql = "SELECT * FROM ".$db->prefix('dtrans_screens')." WHERE id_soft=$item";
	$result=$db->queryF($sql);
	while ($rows=$db->fetchArray($result)){
		$sc = new DTScreenshot();
		$sc->assignVars($rows);
		
		$tpl->append('screens',array('id'=>$sc->id(),'title'=>$sc->title(),
		'desc'=>substr($util->filterTags($sc->desc()),0,80)."...",'software'=>$sw->name()));		
	}

	
	$sw = new DTSoftware($item);
	$tpl->assign('lang_exists',sprintf(_MS_DT_EXISTITEM,$sw->name()));
	$tpl->assign('lang_id',_MS_DT_ID);
	$tpl->assign('lang_title',_MS_DT_TITLE);
	$tpl->assign('lang_desc',_MS_DT_DESC);
	$tpl->assign('lang_software',_MS_DT_ITEM);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_del',_DELETE);
	$tpl->assign('item',$item);
	$tpl->assign('parent','screens');
	$tpl->assign('lang_deletescreen',_MS_DT_DELETESCREEN);
	$tpl->assign('lang_deletescreens',_MS_DT_DELETESCREENS);

	
	//Formulario de pantallas
	if ($edit){
		//Verificamos si la pantalla es válida
		if ($id<=0){
			redirect_header(XOOPS_URL."/modules/dtransport/screens.php?item=$item",1,_MS_DT_ERRID);
			die();

		}

		//Verificamos si la pantalla existe
		$sc = new DTScreenshot($id);
		if ($sc->isNew()){
			redirect_header(XOOPS_URL."/modules/dtransport/screens.php?item=$item",1,_MS_DT_ERREXIST);
			die();
		}


	}
	
	if ($edit || $mc['limit_screen']>$sw->screensCount()){	

		$form = new RMForm($edit ? sprintf(_MS_DT_EDITSCREENS,$sw->name()) : sprintf(_MS_DT_NEWSCREENS,$sw->name()),'frmscreen','screens.php');
		$form->setExtra("enctype='multipart/form-data'");	
	
		$form->addElement(new RMLabel(_MS_DT_ITEM,$sw->name()));

	
		$form->addElement(new RMText(_MS_DT_TITLE,'title',50,100,$edit ? $sc->title() : ''),true);
		$form->addElement(new RMEditor(_MS_DT_DESC,'desc','100%','100px',$edit ? $sc->desc() :'','textarea'));
		$form->addElement(new RMFile(_MS_DT_IMAGE,'image',45, $xoopsModuleConfig['image']*1024),$edit ? '':true);
	
		if ($edit){
			$img = "<img src='".XOOPS_URL."/uploads/dtransport/ths/".$sc->image()."' border='0' />";
			$form->addElement(new RMLabel(_MS_DT_IMAGEACT,$img));	
		}	

		$form->addElement(new RMHidden('op',$edit ? 'saveedit' : 'save'));
		$form->addElement(new RMHidden('id',$id));
		$form->addElement(new RMHidden('item',$item));
		$buttons =new RMButtonGroup();
		$buttons->addButton('sbt',_SUBMIT,'submit');
		$buttons->addButton('cancel',_CANCEL,'button', 'onclick="window.location=\'screens.php?item='.$item.'\';"');

		$form->addElement($buttons);
	
		$tpl->assign('formscreens',$form->render());

	}


	// Ubicación Actual
	$location = "<strong>"._MS_DT_YOUREHERE."</strong> <a href='".DT_URL."'>".$xoopsModule->name()."</a> &raquo; <a href='".DT_URL."/mydownloads.php'>";
	$location .= _MS_DT_MYDOWNS."</a> &raquo; "._MS_DT_SCREEN;
	$tpl->assign('dt_location', $location);
	
	include ('footer.php');

}


/**
* @desc almacena la informacion de la pantalla en la base de datos
**/
function saveScreens($edit=0){
	global $db,$xoopsModuleConfig,$util;	

	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	//Verificamos que el software sea válido
	if ($item<=0){
		redirect_header(XOOPS_URL."/modules/dtransport/mydownloads.php",1,_MS_DT_ERRIDITEM);
		die();
	}
	//Verificamos que el software exista
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirect_header(XOOPS_URL."/modules/dtransport/mydownloads.php",1,_MS_DT_ERR_ITEMEXIST);
		die();
	}


	if ($edit){
		
		//Verificamos si pantalla es válida
		if ($id<=0){
			redirect_header(XOOPS_URL."/modules/dtransport/screens.php?item=$item",1,_MS_DT_ERRID);
			die();
		}

		//Verificamos que la pantalla exista
		$sc=new DTScreenshot($id);
		if ($sc->isNew()){
			redirect_header(XOOPS_URL."/modules/dtransport/screens.php?item=$item",1,_MS_DT_ERREXIST);
			die();
		}

		//Comprueba que el título de la pantalla no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_screens')." WHERE title='$title' AND id_soft=".$item."id_screen<>".$sc->id();
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirect_header(XOOPS_URL."/modules/dtransport/screens.php?item=$item&id=$id&op=edit",1,_MS_DT_ERRNAME);	
			die();
		}

	}else{

		//Comprueba que el título de la pantalla no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_screens')." WHERE title='$title' AND id_soft=$item";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirect_header(XOOPS_URL."/modules/dtransport/screens.php?item=$item",1,_MS_DT_ERRNAME);	
			die();
		}
		$sc=new DTScreenshot();

	}

	$sc->setTitle($title);
	$sc->setDesc(substr($desc, 0, 255));
	$sc->setModified(time());
	$sc->setSoftware($item);
	
	//Imagen
	include_once XOOPS_ROOT_PATH.'/rmcommon/uploader.class.php';
	$up = new RMUploader(true);
	$folder = XOOPS_UPLOAD_PATH.'/dtransport';
	$folderths = XOOPS_UPLOAD_PATH.'/dtransport/ths';
	if ($edit){
		$filename=$sc->image();
	}
	else{
		$filename = '';
	}
	
	$up->prepareUpload($folder, array($up->getMIME('jpg'),$up->getMIME('png'),$up->getMIME('gif')), $xoopsModuleConfig['image']*1024);//tamaño

	if ($up->fetchMedia('image')){

	
		if (!$up->upload()){
			if ($sc->isNew()){
				redirect_header(XOOPS_URL."/modules/dtransport/screens.php?item=$item",2,$up->getErrors());
				die();
			}else{
				redirect_header(XOOPS_URL."/modules/dtransport/screens.php?item=$item&id=$id&op=edit",2,$up->getErrors());
				die();
			}
		}
					
		if ($edit && $sc->image()!=''){
			@unlink(XOOPS_UPLOAD_PATH.'/dtransport/'.$sc->image());
			@unlink(XOOPS_UPLOAD_PATH.'/dtransport/ths/'.$sc->image());
		}

		$filename = $up->getSavedFileName();
		$fullpath = $up->getSavedDestination();
		// Redimensionamos la imagen
		$redim = new RMImageControl($fullpath, $fullpath);
		switch ($xoopsModuleConfig['redim_image']){
			case 0:
				//Recortar miniatura
				$redim->resizeWidth($xoopsModuleConfig['nscreen']);
				$redim->setTargetFile($folderths."/$filename");				
				$redim->resizeAndCrop($xoopsModuleConfig['thscreen'],$xoopsModuleConfig['thscreen']);
				
			break;	
			case 1: 
				//Recortar imagen grande
				$redim->setTargetFile($folderths."/$filename");
				$redim->resizeWidth($xoopsModuleConfig['thscreen']);
				$redim->setTargetFile($fullpath);
				$redim->resizeAndCrop($xoopsModuleConfig['nscreen'],$xoopsModuleConfig['nscreen']);				
			break;
			case 2:
				//Recortar ambas
				$redim->resizeAndCrop($xoopsModuleConfig['nscreen'],$xoopsModuleConfig['nscreen']);
				$redim->setTargetFile($folderths."/$filename");
				$redim->resizeAndCrop($xoopsModuleConfig['thscreen'],$xoopsModuleConfig['thscreen']);
			break;
			case 3:
				//Redimensionar
				$redim->resizeWidth($xoopsModuleConfig['nscreen']);
				$redim->setTargetFile($folderths."/$filename");
				$redim->resizeWidth($xoopsModuleConfig['thscreen']);
			break;			
		}
		
	}	


	$sc->setImage($filename);
		
		
	if (!$sc->save()){
		if ($sc->isNew()){
			redirect_header(XOOPS_URL."/modules/dtransport/screens.php?item=$item",1,_MS_DT_DBERROR);
			die();
		 }else{
			redirect_header(XOOPS_URL."/modules/dtransport/screens.php?item=$item&id=$id&op=edit",1,_MS_DT_DBERROR);
			die();
		}
	}else{
		redirect_header(XOOPS_URL."/modules/dtransport/screens.php?item=$item",1,_MS_DT_DBOK);
		die();
	}



}


/**
* @desc Elmina las pantallas de la base de datos
**/
function deleteScreens(){

	$ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
	$item = isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;	

	//Verificamos que el software sea válido
	if ($item<=0){
		redirect_header(XOOPS_URL."/modules/dtransport/mydownloads.php",1,_MS_DT_ERRIDITEM);
		die();
	}
	//Verificamos que el software exista
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirect_header(XOOPS_URL."/modules/dtransport/mydownloads.php",1,_MS_DT_ERR_ITEMEXIST);
		die();
	}

	//Verificamos si nos proporcionaron alguna pantalla
	if (!is_array($ids) && $ids<=0){
		redirect_header(XOOPS_URL."/modules/dtransport/screens.php?item=$item",2,_MS_DT_NOTSCREEN);
		die();	
	}

	
	if (!is_array($ids)){
		$ids=array($ids);
	}


	$errors='';
	foreach ($ids as $k){
		//Verificamos si pantalla es válida
		if ($k<=0){
			$errors.=sprintf(_MS_DT_ERRSCVAL,$k);
			continue;				
		}
		//Verificamos que la pantalla exista
		$sc=new DTScreenshot($k);
		if ($sc->isNew()){
			$errors.=sprintf(_MS_DT_ERRSCEX,$k);
			continue;
		}
	
		if (!$sc->delete()){
			$errors.=sprintf(_MS_DT_ERRSCDEL,$k);
		}

	}



	if ($errors!=''){
		redirect_header(XOOPS_URL."/modules/dtransport/screens.php?item=$item",3,_MS_DT_DBERROR."<br />".$errors);
		die();	
	}else{
		redirect_header(XOOPS_URL."/modules/dtransport/screens.php?item=$item",1,_MS_DT_DBOK);
		die();	
	}

	


}


$op=isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch ($op){
	case 'edit':
		screens(1);
	break;
	case 'save':
		saveScreens();
	break;
	case 'saveedit':
		saveScreens(1);
	break;
	case 'delete':
		deleteScreens();
	break;
	default:
		screens();
}


?>
