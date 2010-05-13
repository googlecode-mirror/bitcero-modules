<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2


define('AH_LOCATION','publish');
include ('../../mainfile.php');
include 'include/functions.php';


/**
* @desc Formulario para la creación de una nueva publicación
**/
function formPublish(){
	global $xoopsModuleConfig,$xoopsUser,$tpl,$xoopsOption;

	$xoopsOption['template_main']='ahelp_publish.html';
	$xoopsOption['module_subpage'] = 'publish';
	include ('header.php');
	//Verificamos si existen permisos para crear un nuevo recurso
	if (!$xoopsModuleConfig['createres']){
		redirect_header(ah_make_link(),1,_MS_AH_ERRPERM);
		die();
	}

	//Verificamos si usuario tiene permisos de crear nuevo recurso
	$res=new AHResource();
	if (!$res->isAllowedNew(($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS),$xoopsModuleConfig['create_groups'])){
		redirect_header(ah_make_link(),1,_MS_AH_ERRPERMGROUP);
		die();
	}
	

	$form=new RMForm(_MS_AH_NEWRESOURCE,'frmres',ah_make_link('publish/'));
	$form->setExtra("enctype='multipart/form-data'");
	$form->addElement(new RMText(_MS_AH_TITLE,'title',50,150),true);
	$form->addElement(new RMTextArea(_MS_AH_DESC,'desc',5,50),true);

	//Imagen
	$form->addElement(new RMFile(_MS_AH_IMAGE, 'image', 45, $xoopsModuleConfig['size_image']*1024));
	
	//editores de la publicación	
	$form->addElement(new RMFormUserEXM(_MS_AH_EDITORS,'editors',1,'',30));

	//Grupos con permiso de acceso
	$form->addElement(new RMGroups(_MS_AH_GROUPS,'groups',1,1,5,array(1,2)),true);
	$form->addElement(new RMYesno(_MS_AH_PUBLIC,'public'));
	$form->addElement(new RMYesno(_MS_AH_QUICK,'quick'));

	$form->addElement(new RMLabel(_MS_AH_APPROVED,$xoopsModuleConfig['approved'] ? _MS_AH_AUTOAPPR : _MS_AH_NOAPPR));

	$buttons =new RMButtonGroup();
	$buttons->addButton('sbt',_SUBMIT,'submit');
	$buttons->addButton('cancel',_CANCEL,'button', 'onclick="history.go(-1);"');

	$form->addElement($buttons);
	$form->addElement(new RMHidden('op','save'));
	$form->addElement(new RMHidden('approved',$xoopsModuleConfig['approved']));
	$tpl->assign('lang_home',_MS_AH_HOME);
	$tpl->assign('lang_newres',_MS_AH_NEWRESOURCE);

	$tpl->assign('content',$form->render());

	makeHeader();
	makeFooter();
	include ('footer.php');


}

/**
* @desc Almacena información perteneciente a una publicación
**/
function savePublish(){
	global $util,$xoopsModuleConfig,$xoopsUser,$db,$xoopsConfig;

	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	$util=&RMUtils::getInstance();
	if (!$util->validateToken()){
		redirect_header(ah_make_link('publish/'),1,_MS_AH_SESSINVALID);
		die();
	}

	//Comprueba que el título de publicación no exista
	$sql="SELECT COUNT(*) FROM ".$db->prefix('pa_resources')." WHERE title='$title' ";
	list($num)=$db->fetchRow($db->queryF($sql));
	if ($num>0){
		redirect_header(ah_make_link('publish/'),1,_MS_AH_ERRTITLE);
		die();
	}

	$res= new AHResource();

	//Genera $nameid Nombre identificador
	$found=false; 
	$i = 0;
	$util=& RMUtils ::getInstance();
	do{
    		$nameid = $util->sweetstring($title).($found ? $i : '');
        	$sql = "SELECT COUNT(*) FROM ".$db->prefix('pa_resources'). " WHERE nameid = '$nameid'";
        	list ($num) =$db->fetchRow($db->queryF($sql));
        	if ($num>0){
        		$found =true;
        	    $i++;
        	}else{
        		$found=false;
        	}
	}while ($found==true);

		
	$res->setTitle($title);
	$res->setDesc(substr($desc, 0, 255));
	$res->isNew() ? $res->setCreated(time()) : $res->setModified(time());
	$res->setEditors($editors);
	$res->setGroups($groups);
	$res->setPublic($public);
	$res->setQuick($quick);
	$res->setNameId($nameid);
	$res->setOwner($xoopsUser->uid());
	$res->setOwname($xoopsUser->uname());
	$res->setApproved($approved);	

	//Imagen
	include_once XOOPS_ROOT_PATH.'/rmcommon/uploader.class.php';
	$up = new RMUploader(true);
	$folder = XOOPS_UPLOAD_PATH.'/ahelp';
	$filename = '';
	
	$up->prepareUpload($folder, array($up->getMIME('jpg'),$up->getMIME('png'),$up->getMIME('gif')), $xoopsModuleConfig['size_image']*1024);//tamaño

	if ($up->fetchMedia('image')){

	
		if (!$up->upload()){
			if ($res->isNew()){
				redirect_header(ah_make_link('publish/'),1, _MS_AH_ERRIMAGE."<br />".$up->getErrors());
				die();
			}else{
				redirect_header(ah_make_link(),1, _MS_AH_ERRIMAGE."<br />".$up->getErrors());
				die();
			}
		}
					
		
		$filename = $up->getSavedFileName();
		$fullpath = $up->getSavedDestination();
		// Redimensionamos la imagen
		$redim = new RMImageControl($fullpath, $fullpath);
		if ($xoopsModuleConfig['redim_image']==0){
			
			$redim->resizeAndCrop($xoopsModuleConfig['image'],$xoopsModuleConfig['image']);
		}else{
			$redim->resizeWidth($xoopsModuleConfig['image']);
		}


	}
	
	$res->setImage($filename);
	if (!$res->save()){
		redirect_header(ah_make_link('publish/'),1,_MS_AH_DBERROR);
		die();
	}else{
		//Si no se aprobó la publicación enviamos correo al administrador
		if (!$xoopsModuleConfig['approved']){
			$xoopsMailer =& getMailer();
			$xoopsMailer->useMail();
			$xoopsMailer->setTemplate('admin_approv_resource.tpl');
			$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
			$xoopsMailer->assign('ADMINMAIL', $xoopsModuleConfig['mail']);
			$xoopsMailer->assign('SITEURL', XOOPS_URL."/");
			$xoopsMailer->assign('LINK_RESOURCE',XOOPS_URL."/modules/ahelp/resources.php?id=".$res->id());
			$xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH."/modules/ahelp/language/".$xoopsConfig['language']."/mail_template/");
			$xoopsMailer->setToEmails($xoopsModuleConfig['mail']);
			$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
			$xoopsMailer->setFromName($xoopsConfig['sitename']);
			$xoopsMailer->setSubject(sprintf(_MS_AH_SUBJECT,$res->title()));
			if (!$xoopsMailer->send(true)){
				redirect_header(ah_make_link('newpage/'.$res->nameId().'/'),2,$xoopsMailer->getErrors());
			}
			
		}
		redirect_header(ah_make_link('newpage/'.$res->nameId().'/'),1,_MS_AH_DBOK);
		die();
		
	}
}





$op=isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch ($op){
	case 'save':
		savePublish();
	break;
	default: 
		formPublish();

}
