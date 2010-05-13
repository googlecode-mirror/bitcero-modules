<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2


define('AH_LOCATION', 'resources');
include 'header.php';

/**
* @desc Muestra la barra de menus
*/
function optionsBarResource(){
    global $tpl;
    $pag = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
    $limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;  

    $tpl->append('xoopsOptions', array('link' => './resources.php?limit='.$limit.'&pag='.$pag, 'title' => _AS_AH_RESOURCES, 'icon' => '../images/res16.png'));
    $tpl->append('xoopsOptions', array('link' => './resources.php?op=new&limit='.$limit.'&pag='.$pag, 'title' => _AS_AH_NEWRESOURCE, 'icon' => '../images/add.png'));
}

/**
* @desc Muestra todas las publicaciones existentes
**/
function showResources(){
	global $xoopsModule,$adminTemplate,$db,$tpl,$util,$xoopsConfig;
	optionsBarResource();

	//Navegador de páginas
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('pa_resources');
	list($num)=$db->fetchRow($db->queryF($sql));
	
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
    $limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$limit = $limit<=0 ? 15 : $limit;

	if ($page > 0){ $page -= 1; }
    	$start = $page * $limit;
    	$tpages = (int)($num / $limit);
    	if($num % $limit > 0) $tpages++;
    	$pactual = $page + 1;
    	if ($pactual>$tpages){
    	    $rest = $pactual - $tpages;
    	    $pactual = $pactual - $rest + 1;
    	    $start = ($pactual - 1) * $limit;
    	}
	
    
    	if ($tpages > 1) {
    	    $nav = new XoopsPageNav($num, $limit, $start, 'pag', 'limit='.$limit, 0);
    	    $tpl->assign('resourcesNavPage', $nav->renderNav(4, 1));
    	}

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	$tpl->assign('lang_showing', sprintf(_AS_AH_SHOWING, $start + 1, $showmax, $num));
	$tpl->assign('limit',$limit);
	$tpl->assign('pag',$pactual);
	//Fin navegador de páginas
	
	$sql="SELECT * FROM ".$db->prefix('pa_resources')." ORDER BY created DESC LIMIT $start,$limit";
	$result=$db->queryF($sql);
	while ($rows=$db->fetchArray($result)){
		$res = new AHResource();
		$res->assignVars($rows);
		$tpl->append('resources',array('id'=>$res->id(),'title'=>$res->title(),
				'created'=>date($xoopsConfig['datestring'],$res->created()), 'public'=>$res->isPublic(),
				'quick'=>$res->quick(),'approvededit'=>$res->approveEditors(),'featured'=>$res->featured(),
				'approved'=>$res->approved(),'owname'=>$res->owname()));

	}
	
	$tpl->assign('lang_resources',_AS_AH_RESEXIST);
	$tpl->assign('lang_id',_AS_AH_ID);
	$tpl->assign('lang_title',_AS_AH_TITLE);
	$tpl->assign('lang_date',_AS_AH_DATE);
	$tpl->assign('lang_approve',_AS_AH_APPROVE);
	$tpl->assign('lang_public',_AS_AH_PUBLIC);
	$tpl->assign('lang_quick',_AS_AH_QUICK);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_delete',_DELETE);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_pub',_AS_AH_PUB);
	$tpl->assign('lang_nopub',_AS_AH_NOPUB);
	$tpl->assign('lang_quick',_AS_AH_QUICK);
	$tpl->assign('lang_noquick',_AS_AH_NOQUICK);
	$tpl->assign('token', $util->getTokenHTML());
	$tpl->assign('lang_result',_AS_AH_RESULT);
	$tpl->assign('lang_submit',_SUBMIT);
	$tpl->assign('lang_sections',_AS_AH_SECTIONS);
	$tpl->assign('lang_recommend',_AS_AH_RECOMMEND);
	$tpl->assign('lang_norecommend',_AS_AH_NORECOMMEND);
	$tpl->assign('lang_approv',_AS_AH_APPROVRES);
	$tpl->assign('lang_noapprov',_AS_AH_NOAPPROV);


	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_AH_RESOURCES);
	$adminTemplate = 'admin/ahelp_resources.html';
	xoops_cp_header();
	 
	xoops_cp_footer();

}

/**
* Formulario para crear publicaciones
**/
function showForm($edit=0){

	global $xoopsModule,$xoopsConfig,$xoopsModuleConfig;
	optionsBarResource();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_AH_RESOURCES);
	xoops_cp_header();

	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$pag = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
        $limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;  

	if ($edit){
		//Comprueba si la publicación es válida
		if ($id<=0){
			redirectMsg('./resources.php?limit='.$limit.'&pag='.$pag,_AS_AH_IDNOTVALID,1);
			die();		
		}
		
		//Comprueba si la publicación existe
		$res= new AHResource($id);
		if ($res->isNew()){
			redirectMsg('./resources.php?limit='.$limit.'&pag='.$pag,_AS_AH_NOTEXIST,1);
			die();
		}	

	}

	$form = new RMForm($edit ? _AS_AH_EDITRESOURCE : _AS_AH_NEWRESOURCE,'frmres','resources.php');
	$form->setExtra("enctype='multipart/form-data'");
	
	$form->addElement(new RMText(_AS_AH_TITLE,'title',50,150,$edit ? $res->title() : ''),true);
	if ($edit) $form->addElement(new RMText(_AS_AH_NAMEID,'nameid',50,150,$res->nameid()));
	$form->addElement(new RMTextArea(_AS_AH_DESC,'desc',5,50,$edit ? $res->desc() : ''),true);
		
	//Imagen
	$form->addElement(new RMFile(_AS_AH_IMAGE, 'image', 45, $xoopsModuleConfig['size_image']*1024));
	if ($edit){
		$img = "<img src='".XOOPS_URL."/uploads/ahelp/".$res->image()."' border='0' />";
		$form->addElement(new RMLabel(_AS_AH_IMAGEACT,$img));

	}

	$form->addElement(new RMFormUserEXM(_AS_AH_EDITORS,'editors',1,$edit ? $res->editors() : '',30));

	//Propietario de la publicacion
	if ($edit){
		$form->addElement(new RMFormUserEXM(_AS_AH_OWNER,'owner',0,$edit ? array($res->owner()) : '',30));
	}	
	$form->addElement(new RMYesno(_AS_AH_APPROV,'approvededit',$edit ? $res->approveEditors() : 0));
	$form->addElement(new RMGroups(_AS_AH_GROUPS,'groups',1,1,5,$edit ? $res->groups() : array(1,2)),true);
	$form->addElement(new RMYesno(_AS_AH_PUBLIC,'public',$edit ? $res->isPublic() : 0));
	$ele = new RMYesNo(_AS_AH_QUICK,'quick',$edit ? $res->quick() : 0);
	$form->addElement($ele);

	
	//Mostrar índice a usuarios sin permiso de publicación
	$form->addElement(new RMYesno(_AS_AH_SHOWINDEX,'showindex',$edit ? $res->showIndex() : 0));
	$form->addElement(new RMYesno(_AS_AH_FEATURED,'featured',$edit ? $res->featured() : 0));
	$form->addElement(new RMYesno(_AS_AH_APPROVEDRES,'approvedres',$edit ? $res->approved() : 1));

	$buttons =new RMButtonGroup();
	$buttons->addButton('sbt',_SUBMIT,'submit');
	$buttons->addButton('cancel',_CANCEL,'button', 'onclick="window.location=\'resources.php\';"');

	$form->addElement($buttons);

	$form->addElement(new RMHidden('op',$edit ? 'saveedit': 'save' ));
	$form->addElement(new RMHidden('id',$id));
	$form->addElement(new RMHidden('limit',$limit));
	$form->addElement(new RMHidden('pag',$pag));
	$form->addElement(new RMHidden('app',$edit ? $res->approved() : 0));
	$form->display();


	xoops_cp_footer();
}


/**
* @desc Almacena la información de la publicación
**/
function saveResources($edit=0){
	global $util,$db,$xoopsModuleConfig,$xoopsUser;
	
	$nameid = '';
	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	if (!$util->validateToken()){
		if (!$edit){
			redirectMsg('./resources.php?op=new&limit='.$limit.'&pag='.$pag,_AS_AH_SESSINVALID, 1);
			die();
		}else{
			redirectMsg('./resources.php?op=edit&id='.$id.'&limit='.$limit.'&pag='.$pag,_AS_AH_SESSINVALID, 1);
			die();
		}
	}
    
	if ($edit){
		//Comprueba si la publicación es válida
		if ($id<=0){
			redirectMsg('./resources.php?op=edit&id='.$id.'&limit='.$limit.'&pag='.$pag,_AS_AH_IDNOTVALID,1);
			die();		
		}
		
		//Comprueba si la publicación existe
		$res= new AHResource($id);
		if ($res->isNew()){
			redirectMsg('./resources.php?op=edit&id='.$id.'&limit='.$limit.'&pag='.$pag,_AS_AH_NOTEXIST,1);
			die();
		}	


		//Comprueba que el título de publicación no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('pa_resources')." WHERE title='$title' AND id_res<>'".$id."'";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./resources.php?op=edit&id='.$id.'&limit='.$limit.'&pag='.$pag,_AS_AH_ERRTITLE,1);	
			die();
		}

		
	}else{
		//Comprueba que el título de publicación no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('pa_resources')." WHERE title='$title' ";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./resources.php?op=new&limit='.$limit.'&pag='.$pag,_AS_AH_ERRTITLE,1);	
			die();
		}
		$res = new AHResource();
	}
	
	//Genera $nameid Nombre identificador
	if (!$edit || $nameid==''){
		$found=false; 
		$i = 0;
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

	}
	
	$res->setTitle($title);
	$res->setDesc(substr($desc, 0, 255));
	$res->isNew() ? $res->setCreated(time()) : $res->setModified(time());
	$res->setEditors($editors);
	$res->setApproveEditors($approvededit);
	$res->setGroups($groups);
	$res->setPublic($public);
	$res->setQuick($quick);
	$res->setNameId($nameid);
	$res->setShowIndex($showindex);
	$res->setFeatured($featured);
	$res->setApproved($approvedres);
	if ($res->isNew()){
		$res->setOwner($xoopsUser->uid());
		$res->setOwname($xoopsUser->uname());
		$res->setModified(time());
	}elseif ($owner!=$res->owner()){
		$xuser=new $xoopsUser($owner);
		$res->setOwner($owner);
		$res->setOwname($xuser->uname());
	}

	//Imagen
	include_once XOOPS_ROOT_PATH.'/rmcommon/uploader.class.php';
	$up = new RMUploader(true);
	$folder = XOOPS_UPLOAD_PATH.'/ahelp';
    
	if ($edit){
		$filename=$res->image();
	}
	else{
		$filename = '';
	}
	
	$up->prepareUpload($folder, array($up->getMIME('jpg'),$up->getMIME('png'),$up->getMIME('gif')), $xoopsModuleConfig['size_image']*1024);//tamaño

	if ($up->fetchMedia('image')){

	
		if (!$up->upload()){
			if ($res->isNew()){
				redirectMsg('./resources.php?op=new&limit='.$limit.'&pag='.$pag, _AS_AH_ERRIMAGE."<br />".$up->getErrors(), 1);
				die();
			}else{
				redirectMsg('./resources.php?op=edit&id='.$id.'limit='.$limit.'&pag='.$pag, _AS_AH_ERRIMAGE."<br />".$up->getErrors(), 1);
				die();
			}
		}
					
		if ($edit && $res->image()!=''){
			@unlink(XOOPS_UPLOAD_PATH.'/ahelp/'.$res->image());
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
		if ($res->isNew()){
			redirectMsg('./resources.php?op=new&limit='.$limit.'&pag='.$pag,_AS_AH_DBERROR."<br />".$res->errors(),1);
			die();
		}else{
						
			redirectMsg('./resources.php?op=edit&id='.$id.'&limit='.$limit.'&pag='.$pag,_AS_AH_DBERROR."<br />".$res->errors(),1);		die();
		}
	}
	else{
		if (!$res->isNew()){
			
			/**
			* Comprobamos si el recurso no estaba aprovado previamente
			* para enviar la notificación.
			* La notificación solo se envía si el dueño es distinto
			* al administrador actual.
			*/
			if (!$app && $app!=$res->approved() && $xoopsUser->uid()!=$res->owner()){
				include ('../include/functions.php');
				$errors=mailApproved($res);
				redirectMsg('./resources.php?limit='.$limit.'&pag='.$pag,$errors,1);				
			}


		}

		redirectMsg('./resources.php?limit='.$limit.'&pag='.$pag,_AS_AH_DBOK,0);
	}


}

/**
* @desc Elimina publicaciones
**/
function delResources(){
	global $xoopsModule,$util;

	$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$ok = isset($_POST['ok']) ? intval($_POST['ok']) : 0;
	$pag = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
        $limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;  

	//Comprueba si la publicación es válida
	if ($id<=0){
		redirectMsg('./resources.php?op=edit&id='.$id.'&limit='.$limit.'&pag='.$pag,_AS_AH_IDNOTVALID,1);
		die();		
	}
	
	//Comprueba si la publicación existe
	$res= new AHResource($id);
	if ($res->isNew()){
		redirectMsg('./resources.php?op=edit&id='.$id.'&limit='.$limit.'&pag='.$pag,_AS_AH_NOTEXIST,1);
		die();
	}	


	if ($ok){
		
		if (!$util->validateToken()){
			redirectMsg('./resources.php?limit='.$limit.'&pag='.$pag,_AS_AH_SESSINVALID, 1);
			die();
		}

		if (!$res->delete()){
			redirectMsg('./resources.php?limit='.$limit.'&pag='.$pag,_AS_AH_DBERROR,1);
			die();

		}else{
			redirectMsg('./resources.php?limit='.$limit.'&pag='.$pag,_AS_AH_DBOK,0);
		}

		
	
	}else{
		optionsBarResource();
		xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_AH_RESOURCES);
		xoops_cp_header();

		$hiddens['ok'] = 1;
		$hiddens['id'] = $id;
		$hiddens['op'] = 'del';
		$hiddens['limit'] = $limit;
		$hiddens['pag'] = $pag;
		
		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'resources.php\';"';
		
		$util->msgBox($hiddens, 'resources.php', sprintf(_AS_AH_DELETECONF, $res->title()). '<br /><br />' . _AS_AH_ADV._AS_AH_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
	
		xoops_cp_footer();

	}
	

}

/**
* @desc Publica o no publicaciones
**/
function publicResources($pub=0){
	global $util;
	$resources=isset($_REQUEST['resources']) ? $_REQUEST['resources'] : array();
	$pag = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
        $limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;  
	

	if (!$util->validateToken()){
		redirectMsg('./resources.php?limit='.$limit.'&pag='.$pag,_AS_AH_SESSINVALID, 1);
		die();
	}
	

	//Verifica que se haya proporcionado una publicación
	if (!is_array($resources) || empty($resources)){
		redirectMsg('./resources.php?limit='.$limit.'&pag='.$pag,_AS_AH_NOTRESOURCE,1);
		die();		
	}
	
	$errors='';
	foreach ($resources as $k){
		
		//Comprueba si la publicación es válida
		if ($k<=0){
			$errors.=sprintf(_AS_AH_IDNOT, $k);
			continue;
		}
		
		//Comprueba si la publicación existe
		$res= new AHResource($k);
		if ($res->isNew()){
			$errors.=sprintf(_AS_AH_NOEXIST, $k);
			continue;
		}
		
		$res->setPublic($pub);
		if (!$res->save()){
			$errors.=sprintf(_AS_AH_NOSAVE, $k);
		}		
	}
	
	if ($errors!=''){
		redirectMsg('./resources.php?limit='.$limit.'&pag='.$pag,_AS_AH_ERRORS.$errors,1);
		die();		
	}else{
		redirectMsg('./resources.php?limit='.$limit.'&pag='.$pag,_AS_AH_DBOK,0);
	}

}

/**
* @descActiva o no la opción de indice rápido
**/
function quickResources($quick=0){
	global $util;
	$resources=isset($_REQUEST['resources']) ? $_REQUEST['resources'] : array();
	$pag = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
        $limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;  
	
	if (!$util->validateToken()){
		redirectMsg('./resources.php?limit='.$limit.'&pag='.$pag,_AS_AH_SESSINVALID, 1);
		die();
	}


	//Verifica que se haya proporcionado una publicación
	if (!is_array($resources) || empty($resources)){
		redirectMsg('./resources.php?limit='.$limit.'&pag='.$pag,_AS_AH_NOTRESOURCE,1);
		die();		
	}
	
	$errors='';
	foreach ($resources as $k){
		
		//Comprueba si la publicación es válida
		if ($k<=0){
			$errors.=sprintf(_AS_AH_IDNOT, $k);
			continue;
		}
		
		//Comprueba si la publicación existe
		$res= new AHResource($k);
		if ($res->isNew()){
			$errors.=sprintf(_AS_AH_NOEXIST, $k);
			continue;
		}
		
		$res->setQuick($quick);
		if (!$res->save()){
			$errors.=sprintf(_AS_AH_NOSAVE, $k);
		}		
	}
	
	if ($errors!=''){
		redirectMsg('./resources.php?limit='.$limit.'&pag='.$pag,_AS_AH_ERRORS.$errors,1);
		die();		
	}else{
		redirectMsg('./resources.php?limit='.$limit.'&pag='.$pag,_AS_AH_DBOK,0);
	}



}

/**
* @desc Permite recomendar una publicación
**/
function recommendResources($sw){
	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$pag = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
    $limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;  
	
	$res = new AHResource($id);
	$res->setFeatured($sw);
	if ($res->save()){
		redirectMsg("resources.php?limit='.$limit.'&pag='.$pag", _AS_AH_DBOK, 0);
	} else {
		redirectMsg("resources.php?limit='.$limit.'&pag='.$pag", _AS_AH_DBERROR.'<br />'.$res->errors(), 1);
	}
	
	header ("location:./resources.php?limit='.$limit.'&pag='.$pag");
}

/**
* @desc Permite aprobar o no una publicación
**/
function approvedResources($app=0){

	global $util,$xoopsConfig,$xoopsModuleConfig;
	$resources=isset($_REQUEST['resources']) ? $_REQUEST['resources'] : array();
	$pag = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
        $limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;  
	
	if (!$util->validateToken()){
		redirectMsg('./resources.php?limit='.$limit.'&pag='.$pag,_AS_AH_SESSINVALID, 1);
		die();
	}
	

	//Verifica que se haya proporcionado una publicación
	if (!is_array($resources) || empty($resources)){
		redirectMsg('./resources.php?limit='.$limit.'&pag='.$pag,_AS_AH_NOTRESOURCE,1);
		die();		
	}
	
	$errors='';
	foreach ($resources as $k){
		
		//Comprueba si la publicación es válida
		if ($k<=0){
			$errors.=sprintf(_AS_AH_IDNOT, $k);
			continue;
		}
		
		//Comprueba si la publicación existe
		$res= new AHResource($k);
		if ($res->isNew()){
			$errors.=sprintf(_AS_AH_NOEXIST, $k);
			continue;
		}
		$approved=$res->approved();
		$res->setApproved($app);
		if (!$res->save()){
			$errors.=sprintf(_AS_AH_NOSAVE, $k);
		}else{
			if ($app && !$approved){
				include ('../include/functions.php');
				$errors=mailApproved($res);
			}
			
		}	
	}
	
	if ($errors!=''){
		redirectMsg('./resources.php?limit='.$limit.'&pag='.$pag,_AS_AH_ERRORS.$errors,1);
		die();		
	}else{
		redirectMsg('./resources.php?limit='.$limit.'&pag='.$pag,_AS_AH_DBOK,0);
	}

}


$op=isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch ($op){
	case 'new':
		showForm();
	break;
	case 'edit':
		showForm(1);
	break;
	case 'save':
		saveResources();
	break;
	case 'saveedit':
		saveResources(1);
	break;
	case 'del':
		delResources();
	break;
	case 'recommend':
		recommendResources(1);
	break;
	case 'norecommend':
		recommendResources(0);
	break;
	case 'public':
		publicResources(1);
	break;
	case 'nopublic':
		publicResources();
	break;
	case 'quick':
		quickResources(1);
	break;
	case 'noquick':
		quickResources();
	break;	
	case 'approved':
		approvedResources(1);
	break;
	case 'noapproved':
		approvedResources();
	break;
	default:
		showResources();

}
?>
