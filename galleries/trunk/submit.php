<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('GS_LOCATION','submit');
include '../../mainfile.php';

if (!GSFunctions::canSubmit($xoopsUser)){
	redirect_header(GSFunctions::get_url(), 2, __('Sorry, you have not permission to upload pictures.','galleries'));
	die();
}

if ($xoopsUser){
	$user = new GSUser($xoopsUser->uid(), 1);
	
	if ($user->usedQuota()>=$user->quota() && !$xoopsUser->isAdmin()){
		redirect_header(GSFunctions::get_url(), 2, __('Sorry, you have exceed your quota limit!','galleries'));
		die();
	}
	
}

function showForm(){
	global $db, $xoopsOption, $xoopsUser, $mc, $tpl, $xoopsConfig, $xoopsModuleConfig, $user, $xoopsSecurity;

	
	$xoopsOption['template_main'] = "gs_submit.html";
	$xoopsOption['module_subpage'] = 'submit';
	include 'header.php';
	
	GSFunctions::makeHeader();
	$mc =& $xoopsModuleConfig;
	
	$tpl->assign('lang_uploadyour', __('Upload your Pictures','galleries'));
	
	$tpl->assign('lang_step1', __('Step 1:','galleries'));
	$tpl->assign('lang_step2', __('Step 2:','galleries'));
	$tpl->assign('lang_step3', __('Step 3:','galleries'));
	$tpl->assign('lang_step4', __('Step 4:','galleries'));
	$tpl->assign('lang_choose', __('Select Files','galleries'));
	$tpl->assign('lang_privacy', __('Set Privacy','galleries'));
	$tpl->assign('lang_privateme', __('Private (<em>Only you will seee these pictures</em>)','galleries'));
	$tpl->assign('lang_privatef', __('For Friends (<em>Only you and your friends will see these pictures</em>)','galleries'));
	$tpl->assign('lang_public', __('Public (<em>Pictures will visible for all</em>)'));
	$tpl->assign('lang_upload', __('Upload Files','galleries'));
	$tpl->assign('lang_clicktou', __('Click to Upload','galleries'));
	$tpl->assign('lang_tagsesp',__('Specify tags to use','galleries'));
    $tpl->assign('lang_tagsdesc', __('Separate each tag with a comma (,).','galleries'));
	$tpl->assign('lang_maxsize', sprintf(__('The maximum file size allowed is <strong>%s</strong>.','galleries'), RMUtilities::formatBytesSize($mc['size_image'] * 1024)));
	$tpl->assign('used_graph', GSFunctions::makeQuota($user, false));
	$tpl->assign('form_action', GSFunctions::get_url().($mc['urlmode'] ? 'submit/' : '?submit=submit'));
	$tpl->assign('token', $xoopsSecurity->getTokenHTML());
	$used = round($user->usedQuota()/$user->quota()*100).'%';
	$tpl->assign('lang_used', sprintf(__('You have used <strong>%s</strong> of <strong>%s</strong> available. You left <strong>%s</strong>','galleries'), $used, RMUtilities::formatBytesSize($user->quota()), RMUtilities::formatBytesSize($user->usedQuota()>=$user->quota() ? 0 : $user->quota()- $user->usedQuota())));
	
	RMTemplate::get()->add_xoops_style('submit.css', 'galleries');
	include 'footer.php';
	
}

/**
* @desc Almacena las imágenes en la base de datos y en el disco duro
*/
function saveImages(){
	global $db, $xoopsOption, $xoopsUser, $mc, $tpl, $xmh, $xoopsModuleConfig, $util;
	
	$mc =& $xoopsModuleConfig;

	foreach ($_POST as $k => $v){
		$$k = $v;
	}

	//Verificamos si el usuario se encuentra registrado	
	$user = new GSUser($xoopsUser->uname());
	if($user->isNew()){
		//Insertamos información del usuario
		$user->setUid($xoopsUser->uid());
		$user->setUname($xoopsUser->uname());
		$user->setQuota($mc['quota']*1024*1024);
		$user->setDate(time());

		if(!$user->save()){
			redirect_header('./submit.php',1, __('Sorry, an error ocurred while trying to register your permissions on database. Try again later!','galleries'));
			die();
		}
	}
	@mkdir($mc['storedir']."/".$user->uname());
	@mkdir($mc['storedir']."/".$user->uname()."/ths");
	@mkdir($mc['storedir']."/".$user->uname()."/formats");
	$mc['saveoriginal'] ? 	@mkdir($mc['storedir']."/originals") : '';
	
	// Insertamos las etiquetas
	$tgs = explode(" ",$tags);
	/**
	* @desc Almacena los ids de las etiquetas que se asignarán a la imagen
	*/
	$ret = array(); 
	foreach ($tgs as $k){
		$k = trim($k);
		$kf = TextCleaner::getInstance()->sweetstring($k);
		if ($kf=='') continue;
		// Comprobamos que la palabra tenga la longitud permitida
		if(strlen($kf)<$mc['min_tag'] || strlen($kf)>$mc['max_tag']){
			continue;
		}
		// Creamos la etiqueta
		$tag = new GSTag($k);
		if (!$tag->isNew()){
			// Si ya existe nos saltamos
			$ret[] = $tag->id(); 
			continue;
		}

		$tag->setTag($k);
        $tag->setVar('nameid', $kf);
		if ($tag->save()){
			$ret[] = $tag->id();
		}
	}

	$errors = '';
	$k = 1;
	
    include_once RMCPATH.'/class/uploader.php';
	$up = new RMFileUploader(true);
	$folder = $mc['storedir']."/".$xoopsUser->uname();
	$folderths = $mc['storedir']."/".$xoopsUser->uname()."/ths";
	
	foreach ($_FILES['images']['name'] as $k => $v){
		if ($v=='') continue;
		$img = new GSImage();
		$img->setOwner($xoopsUser->uid());
		$img->setPublic($privacy);
		$img->setCreated(time());
		
		//Imagen
		$filename = '';
		
        $up = new RMFileUploader($folder, $mc['size_image']*1024, array('jpg','png','gif'));
		
		if ($up->fetchMedia('images',$k)){

			if (!$up->upload()){
				$errors .= $up->getErrors();
				continue;
			}
					
			$filename = $up->getSavedFileName();
			$fullpath = $up->getSavedDestination();
			
			$thSize = $mc['image_ths'];
			$imgSize = $mc['image'];
			
			if ($thSize[0]<=0) $thSize[0] = 100;
			if (!isset($thSize[1]) || $thSize[1]<=0) $thSize[1] = $thSize[0];
			
			if ($imgSize[0]<=0) $imgSize[0] = 500;
			if (!isset($imgSize[1]) || $imgSize[1]<=0) $imgSize[1] = $imgSize[0];
			
			// Almacenamos la imágen original
			if ($mc['saveoriginal']){
				copy($fullpath, $mc['storedir'].'/originals/'.$filename);
			}
			
			// Redimensionamos la imagen
			$redim = new RMImageResizer($fullpath, $fullpath);
			switch ($mc['redim_image']){
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
		
		//Fin de Imagen
		$img->setImage($filename);
		if ($up->getErrors()==''){
			if (!$img->save()){
				$errors .= sprintf(__('Picture %s could not be uploaded!','galleries'), $v)." (".$img->errors().")";
			} else {
				$user->addPic();
				if ($ret) $img->setTags($ret);
			
			}
		}else{
			$errors .= $up->getErrors();
		}
		
		++$k;
	}

	if($errors!=''){
		redirect_header(GSFunctions::get_url().($mc['urlmode'] ? 'cp/images' : '?cp=images'),2, __('Errors ocurred while trying to upload images!','galleries').$errors);
		die();
        
	}else{
		redirect_header(GSFunctions::get_url().($mc['urlmode'] ? 'cp/images' : '?cp=images'),2, __('Pictures stored successfully!','galleries'));
		die();
	}
	
}

$op = isset($_POST['op']) ? $_POST['op'] : '';

switch($op){
	case 'save':
		saveImages();
		break;
	default:
		showForm();
		break;
}
