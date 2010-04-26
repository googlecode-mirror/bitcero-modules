<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','works');
include 'header.php';


function showImages(){

	global $xoopsModule, $db;

	$work = rmc_server_var($_REQUEST, 'work', 0);

	//Verificamos que el trabajo sea válido
	if ($work<=0){
		redirectMsg('./works.php', __('Provided work ID is not valid!','admin_works'),1);
		die();
	}

	//Verificamos que el trabajo exista
	$work = new PWWork($work);
	if ($work->isNew()){
		redirectMsg('./works.php', __('Specified work does not exists!','admin_work'),1);
		die();
	}
	
	//Barra de Navegación
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('pw_images')." WHERE work='".$work->id()."'";
	
	list($num)=$db->fetchRow($db->query($sql));
    
    $page = rmc_server_var($_REQUEST,'page', 1);
    $limit = 15;

    $tpages = ceil($num/$limit);
    $page = $page > $tpages ? $tpages : $page; 

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('images.php?page={PAGE_NUM}&amp;work='.$work->id());

	$sql = "SELECT * FROM ".$db->prefix('pw_images')." WHERE work='".$work->id()."'";
	$sql.= " LIMIT $start,$limit";
	$result = $db->query($sql);
    $images = array();
	while($row = $db->fetchArray($result)){
		$img = new PWImage();
		$img->assignVars($row);

		$images[] = array(
            'id'=>$img->id(),
            'title'=>$img->title(),
            'image'=>$img->image(),
            'work'=>$img->work(),
            'desc'=>$img->desc()
        );
	}
    
    $images = RMEvents::get()->run_event('works.list.images', $images, $work);

	PWFunctions::toolbar();
	xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; ".__('Work Images','admin_works'));
    RMTemplate::get()->assign('xoops_pagetitle', $work->title().' &raquo; Work Images','admin_mywords');
    RMTemplate::get()->add_style('admin.css', 'works');
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
	xoops_cp_header();
    
    include RMTemplate::get()->get_template("admin/pw_images.php", 'module', 'works');
    
	xoops_cp_footer();
}

/**
* @desc Formulario de creación/edición de Imágenes
**/
function formImages($edit = 0){

	global $xoopsModule, $xoopsModuleConfig;

	$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$work = isset($_REQUEST['work']) ? intval($_REQUEST['work']) : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;

	$ruta = "&pag=$page&limit=$limit";
	
	//Verificamos que el trabajo sea válido
	if ($work<=0){
		redirectMsg('./works.php',_AS_PW_ERRWORKVALID,1);
		die();
	}

	//Verificamos que el trabajo exista
	$work = new PWWork($work);
	if ($work->isNew()){
		redirectMsg('./works.php',_AS_PW_ERRWORKEXIST,1);
		die();
	}

	
	if ($edit){
		//Verificamos que la imagen sea válida
		if ($id<=0){
			redirectMsg('./images.php?work='.$work->id().$ruta,_AS_PW_ERRIMGVALID,1);
			die();
		}

		//Verificamos que la imagen exista
		$img = new PWImage($id);
		if ($img->isNew()){
			redirectMsg('./images.php?work='.$work->id().$ruta,_AS_PW_ERRIMGEXIST,1);
			die();
		}
	}


	optionsBar();
	xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; <a href='./images.php?work=".$work->id()."'>"._AS_PW_IMGLOC."</a> &raquo;".($edit ? _AS_PW_EDITIMG : _AS_PW_NEWIMG));
	xoops_cp_header();

	$form = new RMForm($edit ? _AS_PW_EDITIMG : _AS_PW_NEWIMG,'frmImg','images.php');
	$form->setExtra("enctype='multipart/form-data'");

	$form->addElement(new RMText(_AS_PW_FTITLE,'title',50,100,$edit ? $img->title() : ''), true);
	$form->addElement(new RMFile(_AS_PW_FIMAGE,'image',45, $xoopsModuleConfig['size_image']*1024), true);
	if ($edit){
		$form->addElement(new RMLabel(_AS_PW_FIMGACT,"<img src='".XOOPS_UPLOAD_URL."/works/ths/".$img->image()."' />"));
	}

	$form->addElement(new RMTextArea(_AS_PW_FDESC,'desc',4,50,$edit ? $img->desc() : ''));
	
	$form->addElement(new RMHidden('op',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMHidden('id',$id));
	$form->addElement(new RMHidden('work',$work->id()));
	$form->addElement(new RMHidden('page',$page));
	$form->addElement(new RMHidden('limit',$limit));

	$ele = new RMButtonGroup();
	$ele->addButton('sbt', _SUBMIT, 'submit');
	$ele->addButton('cancel', _CANCEL, 'button', 'onclick="window.location=\'images.php?work='.$work->id().$ruta.'\';"');
	$form->addElement($ele);

	$form->display();

	xoops_cp_footer();
}

/**
* @desc Almacena las imágenes en la base de datos
**/
function saveImages($edit = 0){
	global $xoopsModuleConfig, $util;

	foreach ($_POST as $k => $v){
		$$k = $v;
	}

	$ruta = "&pag=$page&limit=$limit";

	//Verificamos que el trabajo sea válido
	if ($work<=0){
		redirectMsg('./works.php',_AS_PW_ERRWORKVALID,1);
		die();
	}

	//Verificamos que el trabajo exista
	$work = new PWWork($work);
	if ($work->isNew()){
		redirectMsg('./works.php',_AS_PW_ERRWORKEXIST,1);
		die();
	}

	if (!$util->validateToken()){
		redirectMsg('./images.php?work='.$work->id().$ruta,_AS_PW_ERRSESSID, 1);
		die();
	}

	if ($edit){
		//Verificamos que la imagen sea válida
		if ($id<=0){
			redirectMsg('./images.php?work='.$work->id().$ruta,_AS_PW_ERRIMGVALID,1);
			die();
		}

		//Verificamos que la imagen exista
		$img = new PWImage($id);
		if ($img->isNew()){
			redirectMsg('./images.php?work='.$work->id().$ruta,_AS_PW_ERRIMGEXIST,1);
			die();
		}
	}else{
		$img = new PWImage();
	}

	$img->setTitle($title);
	$img->setDesc(substr($desc,0,100));
	$img->setWork($work->id());
	
	//Imagen
	include_once XOOPS_ROOT_PATH.'/rmcommon/uploader.class.php';
	$up = new RMUploader(true);
	$folder = XOOPS_UPLOAD_PATH.'/works';
	$folderths = XOOPS_UPLOAD_PATH.'/works/ths';
	if ($edit){
		$image = $img->image();
		$filename=$img->image();
	}
	else{
		$filename = '';
	}

	//Obtenemos el tamaño de la imagen
	$thSize = $xoopsModuleConfig['image_ths'];
	$imgSize = $xoopsModuleConfig['image'];

	
	$up->prepareUpload($folder, array($up->getMIME('jpg'),$up->getMIME('png'),$up->getMIME('gif')), $xoopsModuleConfig['size_image']*1024);//tamaño

	if ($up->fetchMedia('image')){

	
		if (!$up->upload()){
			redirectMsg('./images.php?op='.($edit ? 'edit' : 'new').'&work='.$work->id().$ruta,$up->getErrors(), 1);
			die();
		}
					
		if ($edit && $img->image()!=''){
			@unlink(XOOPS_UPLOAD_PATH.'/works/'.$img->image());
			@unlink(XOOPS_UPLOAD_PATH.'/works/ths/'.$img->image());
			
		}

		$filename = $up->getSavedFileName();
		$fullpath = $up->getSavedDestination();
		// Redimensionamos la imagen
		$redim = new RMImageControl($fullpath, $fullpath);
		switch ($xoopsModuleConfig['redim_image']){
			
			case 0:
				//Recortar miniatura
				$redim->resizeWidth($imgSize[0]);
				$redim->setTargetFile($folderths."/$filename");				
				$redim->resizeAndCrop($thSize[0],$thSize[1]);
				break;	
			case 1: 
				//Recortar imagen grande
				$redim->resizeWidthOrHeight($imgSize[0],$imgSize[1]);
				$redim->setTargetFile($folderths."/$filename");
				$redim->resizeWidth($thSize[0]);			
				break;
			case 2:
				//Recortar ambas
				$redim->resizeWidthOrHeight($imgSize[0],$imgSize[1]);
				$redim->setTargetFile($folderths."/$filename");
				$redim->resizeAndCrop($thSize[0],$thSize[1]);
				break;
			case 3:
				//Redimensionar
				$redim->resizeWidth($imgSize[0]);
				$redim->setTargetFile($folderths."/$filename");
				$redim->resizeWidth($thSize[0]);
				break;				
		}

	}

	
	$img->setImage($filename);

	if (!$img->save()){
		redirectMsg('./images.php?work='.$work->id().$ruta,_AS_PW_DBERROR.$img->errors(),1);
		die();
	}else{	
		redirectMsg('./images.php?work='.$work->id().$ruta,_AS_PW_DBOK,0);
		die();

	}
}

/**
* @desc Elimina de la base de datos las imagenes especificadas
**/
function deleteImages(){
	global $util, $xoopsModule;

	$work = isset($_REQUEST['work']) ? intval($_REQUEST['work']) : 0;
	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$ok = isset($_POST['ok']) ? intval($_POST['ok']) : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;

	$ruta = "&pag=$page&limit=$limit";

	//Verificamos que nos hayan proporcionado una imagen para eliminar
	if (!is_array($ids) && ($ids<=0)){
		redirectMsg('./images.php?work='.$work.$ruta,_AS_PW_ERRNOTIMGDEL,1);
		die();
	}
	
	if (!is_array($ids)){
		$image = new PWImage($ids);
		$ids = array($ids);
	}


	if ($ok){

		if (!$util->validateToken()){
			redirectMsg('./images.php?work='.$work->id().$ruta,_AS_PW_ERRSESSID, 1);
			die();
		}

		$errors = '';
		foreach ($ids as $k){
			//Verificamos si la imagen es válida
			if ($k<=0){
				$errors.=sprintf(_AS_PW_NOTVALID, $k);
				continue;
			}

			//Verificamos si la imagen existe
			$img = new PWImage($k);
			if ($img->isNew()){
				$errors.=sprintf(_AS_PW_NOTEXIST, $k);
				continue;
			}
		
			if (!$img->delete()){
				$errors.=sprintf(_AS_PW_NOTDELETE,$k);
			}
		}
	
		if ($errors!=''){
			redirectMsg('./images.php?work='.$work.$ruta,_AS_PW_DBERRORS.$errors,1);
			die();
		}else{
			redirectMsg('./images.php?work='.$work.$ruta,_AS_PW_DBOK,0);
			die();
		}


	}else{
		optionsBar();
		xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; <a href='./images.php?work=".$work."'>".
		_AS_PW_IMGLOC."</a> &raquo;"._AS_PW_DELETE);
		xoops_cp_header();

		$hiddens['ok'] = 1;
		$hiddens['ids[]'] = $ids;
		$hiddens['op'] = 'delete';
		$hiddens['work'] = $work;
		$hiddens['pag'] = $page;
		$hiddens['limit'] = $limit;
		
		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'images.php?work='.$work.$ruta.'\';"';
		
		$util->msgBox($hiddens, 'images.php',($image ? sprintf(_AS_PW_DELETECONF, $image->title()) : _AS_PW_DELETECONFS). '<br /><br />' ._AS_PW_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
	
		xoops_cp_footer();

	}
}


$op = isset($_REQUEST['op'])  ? $_REQUEST['op'] : '';
switch($op){
	case 'new':
		formImages();
		break;
	case 'edit':
		formImages(1);
		break;
	case 'save':
		saveImages();
		break;
	case 'saveedit':
		saveImages(1);
		break;
	case 'delete':
		deleteImages();
		break;
	default:
		showImages();
}
