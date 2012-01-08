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

define('DT_LOCATION','submit');
include '../../mainfile.php';

$mc=& $xoopsModuleConfig;


//Verificamos si está permitido el envío de descargas
if (!$mc['send_download']){
	redirect_header(XOOPS_URL."/modules/dtransport/",2,_MS_DT_ERRNOTDOWNS);
	die();
}

$item = new DTSoftware();
//Verificamos si el usuario pertenece a un grupo con permisos de envío de descargas
if (!$item->isAllowedDowns($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS,$mc['groups_send'])){
	redirect_header(XOOPS_URL."/modules/dtransport/",2,_MS_DT_ERRUSERDOWNS);
	die();
}



/**
* @desc Formulario de descargas
**/
function formItems($edit=0){
	global $tpl,$xoopsConfig,$xoopsOption,$db,$xoopsUser,$mc;

	$xoopsOption['template_main'] = 'dtrans_submit.html';
	$xoopsOption['module_subpage'] = 'submit';
	
	$id=isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;


	if ($edit){
		//Verificamos si el elemento es válido
		if ($id<=0){
			redirect_header(XOOPS_URL."/modules/dtransport/",2,_MS_DT_ERRID);
			die();
		}


		//Verificamos si el elemento existe
		$item = new DTSoftware($id);
		if ($item->isNew()){
			redirect_header(XOOPS_URL."/modules/dtransport/",2,_MS_DT_ERREXIST);
			die();
		}

		//Verificamos si el usuario es el propietario de la descarga
		if ($xoopsUser->uid()!=$item->uid()){
			redirect_header(XOOPS_URL."/modules/dtransport/",2,_MS_DT_ERRUSER);
			die();
		}
	
	}


	include ('header.php');
	DTFunctions::makeHeader();
	

	$form=new RMForm($edit ? _MS_DT_EDITSW : _MS_DT_CREASW,'frmsw','submit.php');
	$form->setExtra("enctype='multipart/form-data'");

	$form->addElement(new RMText(_MS_DT_NAME,'name',50,150,$edit ? $item->name() : ''),true);
	$form->addElement(new RMText(_MS_DT_VERSION, 'version', 10, 50, $edit ? $item->version() : ''), true);

	//Lista de categorías
	$ele=new RMSelect(_MS_DT_CATEGO,'category');
	$ele->addOption(0,_SELECT, $edit ? 0 : 1);
	$categos = array();
	DTFunctions::getCategos($categos, 0, 0, array(), true,1);
	foreach ($categos as $k){
		$cat =& $k['object'];
		$ele->addOption($cat->id(),str_repeat('--', $k['jumps']).' '.$cat->name(),$edit ? ($cat->id()==$item->category() ? 1 : 0) : 0);		
	}

	$form->addElement($ele,true,'noselect:0');

	
	$form->addElement(new RMEditor(_MS_DT_SHORTDESC,'shortdesc','70%','50px',$edit ? $item->shortDesc() : '','textarea'),true);
	$form->addElement(new RMEditor(_MS_DT_DESC,'desc','90%','350px',$edit ? $item->desc() : '',$xoopsConfig['editor_type']),true);
	$form->addElement(new RMFile(_MS_DT_IMAGE,'image', 45, $mc['image']*1024));
	if ($edit){
		$img = "<img src='".XOOPS_URL."/uploads/dtransport/ths/".$item->image()."' border='0' />";
		$form->addElement(new RMLabel(_MS_DT_IMAGEACT,$img));	
	}
	

	if ($edit){
		$tags='';
		foreach ($item->tags(true) as $tag){			
			$tags .= $tags=='' ? $tag->tag() : " ".$tag->tag();
		}
	}

	$text = new RMText(_MS_DT_TAGS,'tags',50,255,$edit ? $tags : '');
	$text->setDescription(_MS_DT_DESCTAGS);
	$form->addElement($text,true);
	
	//Licencias
	$ele=new RMSelect(_MS_DT_LICENCES,'licences[]',1,$edit ? ($item->licences() ? $item->licences() : array(0)) : array(0));	
	$ele->addOption('0', _MS_DT_LICOTHER);
	$sql="SELECT * FROM ".$db->prefix('dtrans_licences');
	$result=$db->queryF($sql);
	while ($rows=$db->fetchArray($result)){
		$ele->addOption($rows['id_lic'],$rows['name']);
	}

	$form->addElement($ele,true);

	//Plataformas
	$ele=new RMSelect(_MS_DT_PLATFORMS,'platforms[]',1,$edit ? ($item->platforms()  ? $item->platforms() : array(0)): array(0));	
	$ele->addOption('0', _MS_DT_LICOTHER);
	$sql="SELECT * FROM ".$db->prefix('dtrans_platforms');
	$result=$db->queryF($sql);
	while ($rows=$db->fetchArray($result)){
		$ele->addOption($rows['id_platform'],$rows['name']);
	}

	$form->addElement($ele,true);
	

	// Autor e idioma
	$form->addElement(new RMSubTitle(_MS_DT_OTHER, 1, 'head'));
	$form->addElement(new RMText(_MS_DT_AUTHOR, 'author', 50, 150, $edit ? $item->author() : ''));
	$form->addElement(new RMText(_MS_DT_AUTHORURL, 'url', 50, 255, $edit ? $item->url() : ''));
	$form->addElement(new RMText(_MS_DT_LANGS, 'langs', 50, 255, $edit ? $item->langs() : ''));

	//Alerta de software
	$form->addElement(new RMSubTitle(_MS_DT_ALERT, 1, 'head'));

	$edit ? $alert=$item->alert(): '';
	$form->addElement(new RMYesNo(_MS_DT_ACTALERT,'alert',$edit ? ($item->alert() ? 1 : 0) : 0));
	$ele=new RMText(_MS_DT_LIMIT,'limit',5,10,$edit ? ($alert  ? $alert->limit() : '') : '');
	$ele->setDescription(_MS_DT_DESCLIMIT);
	$form->addElement($ele);

	$sel=new RMSelect(_MS_DT_MODE,'mode');
	$sel->addOption(0,_MS_DT_MP,$edit ? ($alert ? (!$alert->mode() ? 1 : 0): 0) : 0);
	$sel->addOption(1,_MS_DT_EMAIL,$edit ? ($alert ? ($alert->mode() ? 1 : 0) : 0) : 0);
	
	$form->addElement($sel);


	$form->addElement($ele);

	$form->addElement(new RMHidden('op',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMHidden('id',$id));

	$buttons =new RMButtonGroup();
	$buttons->addButton('sbt',_SUBMIT,'submit');
	$buttons->addButton('cancel',_CANCEL,'button', 'onclick="window.location=\''.XOOPS_URL."/modules/dtransport/mydownloads.php".'\';"');

	$form->addElement($buttons);

	$tpl->assign('formsw',$form->render());

	// Ubicación Actual
	$location = "<strong>"._MS_DT_YOUREHERE."</strong> <a href='".DT_URL."'>".$xoopsModule->name()."</a> &raquo; ";
	$location .= _MS_DT_SEND;
	$tpl->assign('dt_location', $location);
	
	include('footer.php');	


}


/**
* @desc Almacena la informació de la descarga en la base de datos
**/
function saveItems($edit=0){
	global $mc,$xoopsUser,$db,$xoopsConfig;

	$util=& RMUtils::getInstance();
	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	$app=0;
	if ($edit){
		//Verificamos si el elemento es válido
		if ($id<=0){
			redirect_header(XOOPS_URL."/modules/dtransport/",2,_MS_DT_ERRID);
			die();
		}


		//Verificamos si el elemento existe
		$item = new DTSoftware($id);
		if ($item->isNew()){
			redirect_header(XOOPS_URL."/modules/dtransport/",2,_MS_DT_ERREXIST);
			die();
		}
		$filename = $item->image();
		$app = $item->approved();
	
		//Verificamos si se aprueba la edicion
		if (!$mc['aprove_edit'] && $item->approved()){
			$item = new DTSoftwareEdited($id);
			$item->setSoftware($id);
			
		}

	}else{

		$item = new DTSoftware();
	}
	
	//Genera $nameid Nombre identificador
	$found=false; 
	$i = 0;
	if ($name!=$item->name()){
		do{
			$nameid = $util->sweetstring($name).($found ? $i : '');
        		$sql = "SELECT COUNT(*) FROM ".$db->prefix('dtrans_software'). " WHERE nameid = '$nameid'";
        		list ($num) =$db->fetchRow($db->queryF($sql));
        		if ($num>0){
        			$found =true;
        		    $i++;
        		}else{
        			$found=false;
        		}
		}while ($found==true);
		$item->setNameId($nameid);
	}
		
	$item->setName($name);
	$item->setShortDesc($shortdesc);
	$item->setDesc($desc);
	$item->setLimits(0);

	if ($xoopsUser){
		$item->setUid($xoopsUser->uid());
		$item->setUname($xoopsUser->uname());
		$item->setApproved($mc['approve_register']);
	}else{
		//Usuarios Anonimos
		$item->setUid(0);
		$item->setUname($xoopsConfig['anonymous']);
		$item->setApproved($mc['approve_anonymous']);	
	}
	if ($edit){
		$item->setModified(time());
	}else{
		$item->setCreated(time());
		$item->setModified(time());
	}
	if ($edit && !$mc['aprove_edit']){
		$item->setCreated(time());
	}
	

	$item->setSecure(0);
	$item->setGroups(array(0));	
	
	
	$item->setCategory($category);
	$item->setVersion($version);
	$item->setAuthor($author);
	$item->setUrl(formatUrl($url));
	$item->setLangs($langs);

	
	if ($edit && !$mc['aprove_edit'] && $app){
		$item->setTags($tags);
	}else{
		$tgs=explode(" ",$tags);
		if (count($tgs)>$mc['limit_tags']){
			$tgs=array_slice($tgs,0,$mc['limit_tags']);
		}	
		foreach ($tgs as $k){
			$v=trim($k);
			if ($v=="" || (strlen($v)<$mc['caracter_tags'])){
				continue;
			}
			$tag = new DTTag($v);
			if (!$tag->isNew()){
				$ids[]=$tag->id();
				continue;
			}		
			
			$tag->setTag($v);
			$tag->save();
			$ids[]=$tag->id();
		}	
		$item->setTags($ids);
	}
	
	
	//Alerta
	if ($alert){
		$item->createAlert($alert);
		$item->setLimit($limit);
		$item->setMode($mode);
	}

	//Licencias
	$item->setLicences($licences);
	
	//Plataformas
	$item->setPlatforms($platforms);

	//Imagen
	include_once XOOPS_ROOT_PATH.'/rmcommon/uploader.class.php';
	$up = new RMUploader(true);
	$folder = XOOPS_UPLOAD_PATH.'/dtransport/';
	$folderths = XOOPS_UPLOAD_PATH.'/dtransport/ths';
	if ($edit){
		if ($mc['aprove_edit']){
			$filename=$item->image();
		}
	}
	else{
		$filename = '';
	}
	
	$up->prepareUpload($folder, array($up->getMIME('jpg'),$up->getMIME('png'),$up->getMIME('gif')), $mc['image']*1024);//tamaño
	
	if ($up->fetchMedia('image')){

	
		if (!$up->upload()){
			if ($item->isNew()){
				redirect_header('./submit.php?op=new',2,$up->getErrors());
				die();
			}else{
				redirect_header('./submit.php?op=edit&id='.$id,2,$up->getErrors());
				die();
			}
		}
		

		$filename = $up->getSavedFileName();
		$fullpath = $up->getSavedDestination();
		// Redimensionamos la imagen
		$redim = new RMImageControl($fullpath, $fullpath);
		switch ($mc['redim_image']){
			case 0:
				//Recortar miniatura
				$redim->resizeWidth($mc['size_image']);
				$redim->setTargetFile($folderths."/$filename");				
				$redim->resizeAndCrop($mc['size_ths'],$mc['size_ths']);
				
			break;	
			case 1: 
				//Recortar imagen grande
				$redim->setTargetFile($folderths."/$filename");
				$redim->resizeWidth($mc['size_ths']);
				$redim->setTargetFile($fullpath);
				$redim->resizeAndCrop($mc['size_image'],$mc['size_image']);				
			break;
			case 2:
				//Recortar ambas
				$redim->resizeAndCrop($mc['size_image'],$mc['size_image']);
				$redim->setTargetFile($folderths."/$filename");
				$redim->resizeAndCrop($mc['size_ths'],$mc['size_ths']);
			break;
			case 3:
				//Redimensionar
				$redim->resizeWidth($mc['size_image']);
				$redim->setTargetFile($folderths."/$filename");
				$redim->resizeWidth($mc['size_ths']);
			break;			
		}

	}
	
	$item->setImage($filename);
	if ($edit && !$mc['aprove_edit'] && $app){
		$item->setFields();
	}
	
	if (!$item->save(true,$alert,true,true)){
		
		redirect_header('./submit.php'.($edit ? '?op=edit&id='.$id : ''),1,_MS_DT_ERRSAVE.$item->errors());
		die();
	}else{
		
		if (!$edit){
			//Notificamos el envío de descargas
			$xoopsMailer =& getMailer();
			$xoopsMailer->usePM();
			if ($item->approved()){
				$xoopsMailer->setTemplate('send_downloadapp.tpl');
			}else{
				$xoopsMailer->setTemplate('send_downloadnoapp.tpl');
			}
			$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
			$xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
			$xoopsMailer->assign('SITEURL', XOOPS_URL."/");
			$xoopsMailer->assign('LINK_RESOURCE',XOOPS_URL."/modules/dtransport/admin/items.php?op=edit&id=".$item->id());
			$xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH."/modules/dtransport/language/".$xoopsConfig['language']."/mail_template/");
			foreach  ($mc['groups_notif'] as $k){
				$g[]=new XoopsGroup($k);
			}
			$xoopsMailer->setToGroups($g);
			$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
			$xoopsMailer->setFromName($xoopsConfig['sitename']);
			$xoopsMailer->setSubject(sprintf(_MS_DT_SUBJECT,$item->name()));
			if (!$xoopsMailer->send(true)){
				redirect_header(XOOPS_URL.'/modules/dtransport/mydownloads.php',2,$xoopsMailer->getErrors());
			}
		}
		else{
			if (!$mc['aprove_edit'] && $mc['edit_notif'] && $app){
				$xoopsMailer =& getMailer();
				$xoopsMailer->usePM();
				$xoopsMailer->setTemplate('edit_download.tpl');
				$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
				$xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
				$xoopsMailer->assign('SITEURL', XOOPS_URL."/");
				$xoopsMailer->assign('LINK_RESOURCE',XOOPS_URL."/modules/dtransport/admin/items.php?op=edit&type=edit&id=".$item->software());
				$xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH."/modules/dtransport/language/".$xoopsConfig['language']."/mail_template/");
				foreach  ($mc['groups_notif'] as $k){
					$g[]=new XoopsGroup($k);
				}
				$xoopsMailer->setToGroups($g);
				$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
				$xoopsMailer->setFromName($xoopsConfig['sitename']);
				$xoopsMailer->setSubject(sprintf(_MS_DT_SUBJECTEDIT,$item->name()));
				if (!$xoopsMailer->send(true)){
					redirect_header(XOOPS_URL.'/modules/dtransport/mydownloads.php',2,$xoopsMailer->getErrors());
				}
		
			}
		
		
		}	
			

		redirect_header('./mydownloads.php',2,_MS_DT_DBOK);
	}
	
}



$op=isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch ($op){
	case 'edit':
		formItems(1);
	break;
	case 'save':
		saveItems();
	break;
	case 'saveedit':
		saveItems(1);
	break;
	default:
		formItems();
}
?>
