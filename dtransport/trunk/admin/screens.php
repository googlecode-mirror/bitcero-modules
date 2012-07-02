<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','screens');
include ('header.php');

/**
* @des Visualiza todas las pantallas existentes
**/
function showScreens(){
	global $xoopsModule, $xoopsSecurity,
           $tpl,
           $functions,
           $xoopsModule,
           $xoopsModuleConfig,
           $xoopsUser;

    define('RMCSUBLOCATION','screenshots');

    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $tc = TextCleaner::getInstance();
	
	$item = rmc_server_var($_REQUEST, 'item', 0);

    if($item<=0)
        redirectMsg('items.php', __('Download item ID not provided!','dtransport'), RMMSG_WARN);


	$sw = new DTSoftware($item);

	$sql = "SELECT * FROM ".$db->prefix('dtrans_screens')." WHERE id_soft=$item";
	$result = $db->queryF($sql);

	while ($rows=$db->fetchArray($result)){
		$sc = new DTScreenshot();
		$sc->assignVars($rows);
		
		$screens[] = array(
            'id'=>$sc->id(),
            'title'=>$sc->title(),
		    'desc'=>substr($tc->clean_disabled_tags($sc->desc()),0,80)."...",
            'image' => XOOPS_UPLOAD_URL.'/screenshots/'.date('Y', $sc->date()).'/'.date('m', $sc->date()).'/ths/'.$sc->image()
        );


	}

    // CSS Styles
    $tpl->add_style('admin.css','dtransport');
    $tpl->add_style('screens.css','dtransport');
    $tpl->add_style('uploadify.css', 'rmcommon');

    // Javascripts
    $tpl->add_local_script('swfobject.js', 'rmcommon', 'include');
    $tpl->add_local_script('jquery.uploadify.js', 'rmcommon', 'include');
    $tpl->add_local_script('screens.js','dtransport');

    $tc = TextCleaner::getInstance();
    $rmf = RMFunctions::get();

    ob_start();
    include DT_PATH.'/js/screenshots.js';
    $script = ob_get_clean();
    $tpl->add_head_script($script);

	$functions->toolbar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='items.php'>".__('Downloads','dtransport')."</a> &raquo; ".__('Screenshots Management','dtransport'));

    $tpl->assign('xoops_pagetitle', sprintf(__("%s Screenshots",'dtransport'), $sw->getVar('name')));
    include DT_PATH.'/include/js_strings.php';

	xoops_cp_header();

    include $tpl->get_template('admin/dtrans_screens.php', 'module', 'dtransport');

	xoops_cp_footer();

}

/**
* @desc Formulario de Pantallass
**/
function formScreens($edit=0){
	global $xoopsModule,$xoopsConfig,$db,$xoopsModuleConfig;

	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$item = isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;	

	//Verificamos que el software sea válido
	if ($item<=0){
		redirectMsg('./screens.php',_AS_DT_ERR_ITEMVALID,1);
		die();
	}
	//Verificamos que el software exista
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirectMsg('./screens.php',_AS_DT_ERR_ITEMEXIST,1);
		die();
	}

	//Verificamos el limite de pantallas a almacenar
	if ($xoopsModuleConfig['limit_screen']<=$sw->getVar('screens')){
		redirectMsg('./screens.php?item='.$item,_AS_DT_ERRCOUNT,1);
		die();

	}

	if ($edit){
		
		//Verificamos si pantalla es válida
		if ($id<=0){
			redirectMsg('./screens.php?item='.$item,_AS_DT_ERR_SCVALID,1);//
			die();
		}

		//Verificamos que la pantalla exista
		$sc=new DTScreenshot($id);
		if ($sc->isNew()){
			redirectMsg('./screens.php?item='.$item,_AS_DT_ERR_SCEXIST,1);
			die();
		}

	}
	

	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./items.php'>"._AS_DT_SW."</a> &raquo; ".($edit ? _AS_DT_EDITSCREEN : _AS_DT_NEWSCREEN));
	xoops_cp_header();

	$form = new RMForm($edit ? sprintf(_AS_DT_EDITSCREENS,$sw->getVar('name')) : sprintf(_AS_DT_NEWSCREENS,$sw->getVar('name')),'frmscreen','screens.php');
	$form->setExtra("enctype='multipart/form-data'");	
	
	$form->addElement(new RMFormLabel(_AS_DT_ITEM,$sw->getVar('name')));

	
	$form->addElement(new RMFormText(_AS_DT_TITLE,'title',50,100,$edit ? $sc->title() : ''),true);
	$form->addElement(new RMFormEditor(_AS_DT_DESC,'desc','100%','100px',$edit ? $sc->desc() :'','textarea'));
	$form->addElement(new RMFormFile(_AS_DT_IMAGE,'image',45, $xoopsModuleConfig['image']*1024),$edit ? '':true);
	
	if ($edit){
		$img = "<img src='".XOOPS_URL."/uploads/dtransport/ths/".$sc->image()."' border='0' />";
		$form->addElement(new RMFormLabel(_AS_DT_IMAGEACT,$img));
	}	

	$form->addElement(new RMFormHidden('op',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMFormHidden('id',$id));
	$form->addElement(new RMFormHidden('item',$item));
	$buttons =new RMFormButtonGroup();
	$buttons->addButton('sbt',_SUBMIT,'submit');
	$buttons->addButton('cancel',_CANCEL,'button', 'onclick="window.location=\'screens.php?item='.$item.'\';"');

	$form->addElement($buttons);
	
	$form->display();


	xoops_cp_footer();	
}



/**
* @desc Almacena información de las pantallas en la base de datos
**/
function saveScreens($edit=0){
	global $db,$xoopsModuleConfig,$util;	

	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	if (!$util->validateToken()){
		if (!$edit){
			redirectMsg('./screens.php?op=new&item='.$item,_AS_DT_SESSINVALID, 1);
			die();
		}else{
			redirectMsg('./screens.php?op=edit&id='.$id.'&item='.$item,_AS_DT_SESSINVALID, 1);
			die();
		}
	}


	//Verificamos que el software sea válido
	if ($item<=0){
		redirectMsg('./screens.php',_AS_DT_ERR_ITEMVALID,1);
		die();
	}
	//Verificamos que el software exista
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirectMsg('./screens.php',_AS_DT_ERR_ITEMEXIST,1);
		die();
	}

	//Verificamos el limite de pantallas a almacenar
	if ($xoopsModuleConfig['limit_screen']<=$sw->screensCount()){
		redirectMsg('./screens.php?item='.$item,_AS_DT_ERRCOUNT,1);
		die();

	}

	if ($edit){
		
		//Verificamos si pantalla es válida
		if ($id<=0){
			redirectMsg('./screens.php?item='.$item,_AS_DT_ERR_SCVALID,1);
			die();
		}

		//Verificamos que la pantalla exista
		$sc=new DTScreenshot($id);
		if ($sc->isNew()){
			redirectMsg('./screens.php?item='.$item,_AS_DT_ERR_SCEXIST,1);
			die();
		}

		//Comprueba que el título de la pantalla no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_screens')." WHERE title='$title' AND id_soft=".$item."id_screen<>".$sc->id();
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./screens.php?item='.$item,_AS_DT_ERRNAME,1);	
			die();
		}

	}else{

		//Comprueba que el título de la pantalla no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_screens')." WHERE title='$title' AND id_soft=$item";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./screens.php?item='.$item,_AS_DT_ERRNAME,1);	
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
				redirectMsg('./screens.php?op=new&item='.$item,$up->getErrors(), 1);
				die();
			}else{
				redirectMsg('./screens.php?op=edit&item='.$item.'&id='.$id,$up->getErrors(), 1);
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
			redirectMsg('./screens.php?op=new&item='.$item,_AS_DT_DBERROR,1);
			die();
		 }else{
			redirectMsg('./screens.php?op=edit&item='.$item.'&id='.$id,_AS_DT_DBERROR,1);
			die();
		}
	}else{
		redirectMsg('./screens.php?item='.$item,_AS_DT_DBOK,0);
		die();
	}
}

/**
* @desc Elimina pantallas de la base de datos
**/
function deleteScreens(){
	global $xoopsModule,$util;	

	$ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
	$item = isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;	
	$ok = isset($_POST['ok']) ? intval($_POST['ok']) : 0;

	//Verificamos que el software sea válido
	if ($item<=0){
		redirectMsg('./screens.php',_AS_DT_ERR_ITEMVALID,1);
		die();
	}
	//Verificamos que el software exista
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirectMsg('./screens.php',_AS_DT_ERR_ITEMEXIST,1);
		die();
	}


	//Verificamos si nos proporcionaron alguna pantalla
	if (!is_array($ids) && $ids<=0){
		redirectMsg('./screens.php?item='.$item,_AS_DT_NOTSCREEN,1);
		die();	
	}

	$num=0;
	if (!is_array($ids)){
		$scr=new DTScreenshot($ids);
		$ids=array($ids);
		$num=1;
	}

	if ($ok){
		if (!$util->validateToken()){
				redirectMsg('./screens.php?item='.$item,_AS_DT_SESSINVALID, 1);
				die();
		}

		$errors='';
		foreach ($ids as $k){

			//Verificamos si pantalla es válida
			if ($k<=0){
				$errors.=sprintf(_AS_DT_ERRSCVAL,$k);
				continue;				
			}

			//Verificamos que la pantalla exista
			$sc=new DTScreenshot($k);
			if ($sc->isNew()){
				$errors.=sprintf(_AS_DT_ERRSCEX,$k);
				continue;
			}
		

			if (!$sc->delete()){
				$errors.=sprintf(_AS_DT_ERRSCDEL,$k);
			}

		}

		if ($errors!=''){

			redirectMsg('./screens.php?item='.$item,_AS_DT_ERRORS.$errors,1);
			die();
		}else{
			redirectMsg('./screens.php?item='.$item,_AS_DT_DBOK,0);
			die();
		}


	}else{
		optionsBar($sw);
		xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./items.php'>"._AS_DT_SW."</a> &raquo; "._AS_DT_DELETESCREEN);
		xoops_cp_header();

		$hiddens['ok'] = 1;
		$hiddens['id[]'] = $ids;
		$hiddens['item'] = $item;
		$hiddens['op'] = 'delete';

		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'screens.php?item='.$item.'\';"';
		
		$util->msgBox($hiddens, 'screens.php', ($num ? sprintf(_AS_DT_DELETECONF,$scr->title()) : _AS_DT_DELCONF). '<br /><br />' ._AS_DT_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
	
		xoops_cp_footer();

	}


}



$op=isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch ($op){
	case 'new':
		formScreens();
	break;
	case 'edit':
		formScreens(1);
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
		showScreens();
	
}
