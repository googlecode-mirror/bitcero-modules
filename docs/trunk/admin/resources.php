<?php
// $Id$
// --------------------------------------------------------------
// Rapid Docs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION', 'resources');
include 'header.php';

/**
* @desc Muestra todas las publicaciones existentes
**/
function showResources(){
	global $xoopsModule,$adminTemplate,$db,$tpl,$util,$xoopsConfig;
	
	//Navegador de páginas
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('pa_resources');
	list($num)=$db->fetchRow($db->queryF($sql));
	
	$page = rmc_server_var($_REQUEST, 'page', 1);
    $limit = rmc_server_var($_REQUEST, 'limit', 15);
	$limit = $limit<=0 ? 15 : $limit;

	$tpages = ceil($num/$limit);
    $page = $page > $tpages ? $tpages : $page; 

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('resources.php?page={PAGE_NUM}');

	//Fin navegador de páginas
	
	$sql="SELECT * FROM ".$db->prefix('pa_resources')." ORDER BY `created` DESC LIMIT $start,$limit";
	$result=$db->queryF($sql);
	$resources = array();
	
	while ($rows=$db->fetchArray($result)){
		$res = new RDResource();
		$res->assignVars($rows);
		$resources[] = array(
            'id'=>$res->id(),
            'title'=>$res->getVar('title'),
			'created'=>formatTimestamp($res->getVar('created'), 'c'),
            'public'=>$res->getVar('public'),
			'quick'=>$res->getVar('quick'),
            'approvededit'=>$res->getVar('editor_approve'),
            'featured'=>$res->getVar('featured'),
			'approved'=>$res->getVar('approved'),
            'owname'=>$res->getVar('owname'),
            'description'=>$res->getVar('description')
        );
	}


    RMTemplate::get()->add_style('admin.css', 'docs');
    
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_AH_RESOURCES);
	RDFunctions::toolbar();
	xoops_cp_header();
	
	include RMTemplate::get()->get_template('admin/qd_resources.php', 'module', 'docs'); 
	
	xoops_cp_footer();

}

/**
* Formulario para crear publicaciones
**/
function qd_show_form($edit=0){
	global $xoopsModule,$xoopsConfig,$xoopsModuleConfig;

    RDFunctions::toolbar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".($edit ? __('Editing Resource','docs') : __('Create Resource','docs')));
	xoops_cp_header();

	$id= rmc_server_var($_GET,'id', 0);
	$page = rmc_server_var($_GET, 'page', 1);

	if ($edit){
		//Comprueba si la publicación es válida
		if ($id<=0){
			redirectMsg('./resources.php?page='.$page, __('You must provide an ID from some resource to edit!', 'docs'),1);
			die();		
		}
		
		//Comprueba si la publicación existe
		$res= new RDResource($id);
		if ($res->isNew()){
			redirectMsg('./resources.php?page='.$page, __('Specified resource does not exists!','docs'),1);
			die();
		}	

	}

	$form = new RMForm($edit ? sprintf(__('Edit Resource: %s','docs'), $res->getVar('title')) : __('New Resource','docs'),'frmres','resources.php');
	
	$form->addElement(new RMFormText(__('Resource title'),'title',50,150,$edit ? $res->getVar('title') : ''),true);
	if ($edit) $form->addElement(new RMFormText(__('Resource slug'),'nameid',50,150,$res->getVar('nameid')));
	$form->addElement(new RMFormTextArea(__('Description'),'desc',5,50,$edit ? $res->getVar('description') : ''),true);

	$form->addElement(new RMFormUser(__('Editors','docs'),'editors',1,$edit ? $res->getVar('editors') : '',30));

	//Propietario de la publicacion
	if ($edit){
		$form->addElement(new RMFormUser(__('Resource owner'),'owner',0,$edit ? array($res->getVar('owner')) : '',30));
	}
	$form->addElement(new RMFormYesno(__('Approve content and changes by editors'),'approvededit',$edit ? $res->getVar('editor_approve') : 0));
	$form->addElement(new RMFormGroups(__('Groups that can see this resource'),'groups',1,1,5,$edit ? $res->getVar('groups') : array(1,2)),true);
	$form->addElement(new RMFormYesno(__('Set as public','docs'),'public',$edit ? $res->getVar('public') : 0));
	$form->addElement(new RMFormYesNo(__('Quick index'),'quick',$edit ? $res->getVar('quick') : 0));

	
	//Mostrar índice a usuarios sin permiso de publicación
	$form->addElement(new RMFormYesno(__('Show index to restricted users','docs'),'showindex',$edit ? $res->showIndex() : 0));
	$form->addElement(new RMFormYesno(__('Featured','docs'),'featured',$edit ? $res->featured() : 0));
	$form->addElement(new RMFormYesno(__('Approve inmediatly','docs'),'approvedres',$edit ? $res->approved() : 1));

	$buttons =new RMFormButtonGroup();
	$buttons->addButton('sbt',__('Create Resource','docs'),'submit');
	$buttons->addButton('cancel',__('Cancel','docs'),'button', 'onclick="window.location=\'resources.php\';"');

	$form->addElement($buttons);

	$form->addElement(new RMFormHidden('action',$edit ? 'saveedit': 'save' ));
	if ($edit) $form->addElement(new RMFormHidden('id',$id));
	$form->addElement(new RMFormHidden('page',$page));
	$form->addElement(new RMFormHidden('app',$edit ? $res->approved() : 0));
	$form->display();


	xoops_cp_footer();
}


/**
* @desc Almacena la información de la publicación
**/
function saveResources($edit=0){
	global $xoopsModuleConfig,$xoopsUser, $xoopsSecurity;
	
	$nameid = '';
    $q = '';
	foreach ($_POST as $k=>$v){
		$$k=$v;
        if ($k=='XOOPS_TOKEN_REQUEST' || $k=='action') continue;
        $q .= $q=='' ? "$k=".urlencode($v) : "&$k=".urlencode($v);
	}
    
    if($action=='save')
        $q .= '&amp;action=new';
    else
        $q .= "&amp;action=edit";

	if (!$xoopsSecurity->check()){
		redirectMsg('resources.php?'.$q, __('Sessiontoken expired!','docs'), 1);
        die();
	}
    
    $db = Database::getInstance();
    
	if ($edit){
		//Comprueba si la publicación es válida
		if ($id<=0){
			redirectMsg('resources.php', __('You must provide a valid resource ID','docs'), 1);
			die();		
		}
		
		//Comprueba si la publicación existe
		$res= new RDResource($id);
		if ($res->isNew()){
			redirectMsg('resources.php', __('Specified resource does not exists!','docs'), 1);
			die();
		}	

		//Comprueba que el título de publicación no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('pa_resources')." WHERE title='$title' AND id_res<>'".$id."'";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('resources.php?'.$q,__('A resource with same title exists already!','docs'),1);	
			die();
		}

		
	}else{
		//Comprueba que el título de publicación no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('pa_resources')." WHERE title='$title' ";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('resources.php?'.$q,__('A resource with same title exists already!','docs'),1);    
            die();
		}
		$res = new RDResource();
	}
	
	//Genera $nameid Nombre identificador
	if ($nameid=='' || $res->getVar('title')!=$title){
		$found=false; 
		$i = 0;
		do{
    		$nameid = TextCleaner::getInstance()->sweetstring($title).($found ? $i : '');
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
	
	$res->setVar('title', $title);
	$res->setVar('description', substr($desc, 0, 255));
	$res->isNew() ? $res->setVar('created', time()) : $res->setVar('modified', time());
	$res->setVar('editors', $editors);
	$res->setVar('editor_approve', $approvededit);
	$res->setVar('groups', $groups);
	$res->setVar('public', $public);
	$res->setVar('quick', $quick);
	$res->setVar('nameid', $nameid);
	$res->setVar('show_index', $showindex);
	$res->setVar('featured', $featured);
	$res->setVar('approved', $approvedres);
	if ($res->isNew()){
		$res->setVar('owner', $xoopsUser->uid());
		$res->setVar('owname', $xoopsUser->uname());
	}elseif ($owner!=$res->getVar('owner')){
		$xuser=new $xoopsUser($owner);
		$res->setVar('owner', $owner);
		$res->setVar('owname', $xuser->uname());
	}

	if (!$res->save()){
        redirectMsg('resources.php?'.$q, __('Resource could not be saved!','docs').'<br />'.$res->errors(), 1);
        die();
	}else{
		if (!$res->isNew()){
			
			/**
			* Comprobamos si el recurso no estaba aprovado previamente
			* para enviar la notificación.
			* La notificación solo se envía si el dueño es distinto
			* al administrador actual.
			*/
			if (!$app && $app!=$res->getVar('approved') && $xoopsUser->uid()!=$res->getVar('owner')){
				include ('../include/functions.php');
				$errors=mailApproved($res);
				redirectMsg('./resources.php?limit='.$limit.'&page='.$page,$errors,1);				
			}


		}

		redirectMsg('./resources.php?limit='.$limit.'&page='.$page,__('Resource saved successfully!','docs'),0);
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


$action=isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

switch ($action){
	case 'new':
		qd_show_form();
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
