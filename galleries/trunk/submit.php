<?php
// $Id$
// --------------------------------------------------------
// Gallery System
// Manejo y creación de galerías de imágenes
// CopyRight © 2008. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.exmsystem.org
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
// --------------------------------------------------------
// @copyright: 2008 Red México

define('GS_LOCATION','submit');
include '../../mainfile.php';

if (!GSFunctions::canSubmit($xoopsUser)){
	redirect_header(XOOPS_URL.'/modules/galleries/', 2, _MS_GS_ERRACCESS);
	die();
}

if ($exmUser){
	$user = new GSUser($exmUser->uid(), 1);
	
	if ($user->usedQuota()>=$user->quota() && !$exmUser->isAdmin()){
		redirect_header(XOOPS_URL.'/modules/galleries/', 2, _MS_GS_QUOTAEX);
		die();
	}
	
}

function showForm(){
	global $db, $xoopsOption, $exmUser, $mc, $tpl, $xmh, $xoopsModuleConfig, $user;
	
	$util =& RMUtils::getInstance();
	
	$xoopsOption['template_main'] = "gs_submit.html";
	$xoopsOption['module_subpage'] = 'submit';
	include 'header.php';
	
	GSFunctions::makeHeader();
	$mc =& $xoopsModuleConfig;
	
	$tpl->assign('lang_uploadyour', _MS_GS_UPLOADY);
	
	$tpl->assign('lang_step1', _MS_GS_STEP1);
	$tpl->assign('lang_step2', _MS_GS_STEP2);
	$tpl->assign('lang_step3', _MS_GS_STEP3);
	$tpl->assign('lang_step4', _MS_GS_STEP4);
	$tpl->assign('lang_choose', _MS_GS_CHOOSE);
	$tpl->assign('lang_privacy', _MS_GS_PRIVACY);
	$tpl->assign('lang_privateme', _MS_GS_PRIVATEME);
	$tpl->assign('lang_privatef', _MS_GS_PRIVATEF);
	$tpl->assign('lang_public', _MS_GS_PUBLIC);
	$tpl->assign('lang_upload', _MS_GS_UPLOAD);
	$tpl->assign('lang_clicktou', _MS_GS_CLICKTOU);
	$tpl->assign('lang_tags', _MS_GS_TAG);
	$tpl->assign('lang_tagsesp',_MS_GS_TAGSESP);
	$tpl->assign('lang_maxsize', sprintf(_MS_GS_MAXSIZE, formatBytesSize($mc['size_image'] * 1024)));
	$tpl->assign('used_graph', GSFunctions::makeQuota($user, false));
	$tpl->assign('form_action', GS_URL.'/'.($mc['urlmode'] ? 'submit/' : 'submit.php'));
	$tpl->assign('token', $util->getTokenHTML());
	$used = round($user->usedQuota()*(100/$user->quota())).'%';
	$tpl->assign('lang_used', sprintf(_MS_GS_USED, $used, formatBytesSize($user->quota()), formatBytesSize($user->usedQuota()>=$user->quota() ? 0 : $user->quota()- $user->usedQuota())));
	
	$xmh .= "<link href='".GS_URL."/styles/submit.css' type='text/css' media='all' rel='stylesheet' />\n";
	include 'footer.php';
	
}

/**
* @desc Almacena las imágenes en la base de datos y en el disco duro
*/
function saveImages(){
	global $db, $xoopsOption, $exmUser, $mc, $tpl, $xmh, $xoopsModuleConfig, $util;
	
	$util =&RMUtils::getInstance();
	$mc =& $xoopsModuleConfig;

	foreach ($_POST as $k => $v){
		$$k = $v;
	}

	//Verificamos si el usuario se encuentra registrado	
	$user = new GSUser($exmUser->uname());
	if($user->isNew()){
		//Insertamos información del usuario
		$user->setUid($exmUser->uid());
		$user->setUname($exmUser->uname());
		$user->setQuota($mc['quota']*1024*1024);
		$user->setDate(time());

		if(!$user->save()){
			redirect_header('./submit.php',1,_MS_GS_ERRUSER);
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
		$k = $util->sweetstring($k);
		if ($k=='') continue;
		// Comprobamos que la palabra tenga la longitud permitida
		if(strlen($k)<$mc['min_tag'] || strlen($k)>$mc['max_tag']){
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
		if ($tag->save()){
			$ret[] = $tag->id();
		}
	}

	$errors = '';
	$k = 1;
	include_once XOOPS_ROOT_PATH.'/rmcommon/uploader.class.php';
	$up = new RMUploader(true);
	$folder = $mc['storedir']."/".$exmUser->uname();
	$folderths = $mc['storedir']."/".$exmUser->uname()."/ths";
	
	foreach ($_FILES['images']['name'] as $k => $v){
		if ($v=='') continue;
		$img = new GSImage();
		$img->setOwner($exmUser->uid());
		$img->setPublic($privacy);
		$img->setCreated(time());
		
		//Imagen
		$filename = '';
			
		$up->prepareUpload($folder, array($up->getMIME('jpg'),$up->getMIME('png'),$up->getMIME('gif')), $mc['size_image']*1024);//tamaño
		
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
			$redim = new RMImageControl($fullpath, $fullpath);
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
				$errors .= sprintf(_MS_GS_ERRSAVEIMG, $v)." (".$img->errors().")";
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
		redirect_header(XOOPS_URL.'/modules/galleries/submit.php',2,_MS_GS_DBERRORS.$errors);
		die();
	}else{
		redirect_header(XOOPS_URL.'/modules/galleries/cpanel.php',2,_MS_GS_DBOK);
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

?>