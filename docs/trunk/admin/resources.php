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
function show_resources(){
	global $xoopsModule,$xoopsConfig, $xoopsSecurity;
	
    $db = Database::getInstance();
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
			'created'=>formatTimestamp($res->getVar('created'), 'm'),
            'public'=>$res->getVar('public'),
			'quick'=>$res->getVar('quick'),
            'approvededit'=>$res->getVar('editor_approve'),
            'featured'=>$res->getVar('featured'),
			'approved'=>$res->getVar('approved'),
            'owname'=>$res->getVar('owname'),
            'owner'=>$res->getVar('owner'),
            'description'=>$res->getVar('description')
        );
	}


    RMTemplate::get()->add_style('admin.css', 'docs');
    RMTemplate::get()->assign('xoops_pagetitle', __('Resources', 'docs'));
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
    RMTemplate::get()->add_script(XOOPS_URL.'/modules/docs/include/js/admin.js');
    
    RMTemplate::get()->add_head('<script type="text/javascript">
    var rd_message = "'.__('Do you really wish to delete selected resources?','docs').'";
    var rd_select_message = "'.__('You must select an element before to do this action!','docs').'";
    </script>');
    
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('Resources','docs'));
	RDFunctions::toolbar();
	xoops_cp_header();
	
	include RMTemplate::get()->get_template('admin/qd_resources.php', 'module', 'docs'); 
	
	xoops_cp_footer();

}

/**
* Formulario para crear publicaciones
**/
function rd_show_form($edit=0){
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
	$form->addElement(new RMFormYesno(__('Show index to restricted users','docs'),'showindex',$edit ? $res->getVar('show_index') : 0));
	$form->addElement(new RMFormYesno(__('Featured','docs'),'featured',$edit ? $res->getVar('featured') : 0));
	$form->addElement(new RMFormYesno(__('Approve inmediatly','docs'),'approvedres',$edit ? $res->getVar('approved') : 1));

	$buttons =new RMFormButtonGroup();
	$buttons->addButton('sbt',$edit ? __('Update Resource','docs') : __('Create Resource','docs'),'submit');
	$buttons->addButton('cancel',__('Cancel','docs'),'button', 'onclick="window.location=\'resources.php\';"');

	$form->addElement($buttons);

	$form->addElement(new RMFormHidden('action',$edit ? 'saveedit': 'save' ));
	if ($edit) $form->addElement(new RMFormHidden('id',$id));
	$form->addElement(new RMFormHidden('page',$page));
	$form->addElement(new RMFormHidden('app',$edit ? $res->getVar('approved') : 0));
	$form->display();


	xoops_cp_footer();
}


/**
* @desc Almacena la información de la publicación
**/
function rd_save_resource($edit=0){
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
				$errors = RDfunctions::mail_approved($res);
				redirectMsg('./resources.php?page='.$page,$errors,1);				
			}


		}

		redirectMsg('./resources.php?limit='.$limit.'&page='.$page,__('Resource saved successfully!','docs'),0);
	}

}

/**
* @desc Elimina publicaciones
**/
function rd_delete_resource(){
	global $xoopsModule,$xoopsSecurity;

	$ids = rmc_server_var($_POST, 'ids', array());
    $page = rmc_server_var($_POST, 'page', 1);

	if (!is_array($ids)){
        redirectMsg("resources.php?page=".$page, __("Select at least one resources to delete!",'docs'), 1);
        die();
    }
		
	if (!$xoopsSecurity->check()){
	    redirectMsg('./resources.php?page='.$page, __('Session token expired!','docs'), 1);
		die();
	}
    
    $errors = '';
    foreach($ids as $id){
        
        if ($id<=0){
            $errors .= sprintf(__('"%s" is not a valid resource ID','docs'), $id);
            continue;
        }
        
        $res = new RDResource($id);
        if ($res->isNew()){
            $errors .= sprintf(__('Resource with ID "%s" does not exists','docs'), $id);
            continue;
        }
        
        if (!$res->delete()){
            $errors .= sprintf(__('Resource "%s" could not be deleted!','docs'), $res->getVar('title')).'<br />'.$res->errors();
        }
        
    }
    
    if ($errors!=''){
        redirectMsg("resources.php?page=$page", __('Errors ocurred while deleting resources','docs').'<br />'.$errors, 1);
    } else {
        redirectMsg("resources.php?page=$page", __('Resources deleted susccessfully!','docs'), 0);
    }

}

/**
* @desc Publica o no publicaciones
**/
function public_resources($pub=0){
	global $xoopsSecurity;
    
	$resources= rmc_server_var($_POST, 'resources', array());
	$page = rmc_server_var($_POST, 'page', 1);	

	if (!$xoopsSecurity->check()){
		redirectMsg('./resources.php?page='.$page, __('Session token expired!','docs'), 1);
		die();
	}	

	//Verifica que se haya proporcionado una publicación
	if (!is_array($resources) || empty($resources)){
		redirectMsg('./resources.php?page='.$page, __('You must select at least a single resource!','docs'),1);
		die();		
	}
	
	$errors='';
	foreach ($resources as $k){
		
		//Comprueba si la publicación es válida
		if ($k<=0){
			$errors.=sprintf(__('Provided resourse ID "%s" is not valid!','docs'), $k);
			continue;
		}
		
		//Comprueba si la publicación existe
		$res= new RDResource($k);
		if ($res->isNew()){
			$errors.=sprintf(__('Resource with ID "%s" does not exists!','docs'), $k);
			continue;
		}
		
		$res->setVar('public', $pub);
		if (!$res->save()){
			$errors.=sprintf(__('Resource "%s" could not be updated!','docs'), $res->getVar('title'));
		}		
	}
	
	if ($errors!=''){
		redirectMsg('./resources.php?page='.$page, __('Errors ocurred while trying to update resources.','docs').'<br />'.$errors,1);
		die();		
	}else{
		redirectMsg('./resources.php?page='.$page, __('Resources updated successfully!','docs'),0);
	}

}

/**
* @descActiva o no la opción de indice rápido
**/
function quick_resources($quick=0){
	global $xoopsSecurity;
    
	$resources= rmc_server_var($_POST, 'resources', array());
    $page = rmc_server_var($_POST, 'page', 1);    

    if (!$xoopsSecurity->check()){
        redirectMsg('./resources.php?page='.$page, __('Session token expired!','docs'), 1);
        die();
    }    

    //Verifica que se haya proporcionado una publicación
    if (!is_array($resources) || empty($resources)){
        redirectMsg('./resources.php?page='.$page, __('You must select at least a single resource!','docs'),1);
        die();        
    }
	
	$errors='';
	foreach ($resources as $k){
		
		//Comprueba si la publicación es válida
        if ($k<=0){
            $errors.=sprintf(__('Provided resourse ID "%s" is not valid!','docs'), $k);
            continue;
        }
        
        //Comprueba si la publicación existe
        $res= new RDResource($k);
        if ($res->isNew()){
            $errors.=sprintf(__('Resource with ID "%s" does not exists!','docs'), $k);
            continue;
        }
		
		$res->setVar('quick', $quick);
		if (!$res->save()){
			$errors.=sprintf(__('Resource "%s" could not be updated!','docs'), $res->getVar('title'));
		}		
	}
	
	if ($errors!=''){
        redirectMsg('./resources.php?page='.$page, __('Errors ocurred while trying to update resources.','docs').'<br />'.$errors,1);
        die();        
    }else{
        redirectMsg('./resources.php?page='.$page, __('Resources updated successfully!','docs'),0);
    }

}

/**
* @desc Permite recomendar una publicación
**/
function recommend_resource($sw){
    
	$id = rmc_server_var($_GET, 'id', 0);
	$page = rmc_server_var($_GET, 'page', 0);
	
	$res = new RDResource($id);
	$res->setVar('featured', $sw);
	if ($res->save()){
		redirectMsg("resources.php?limit='.$limit.'&pag='.$pag", __('Database updated successfully!','docs'), 0);
	} else {
		redirectMsg("resources.php?limit='.$limit.'&pag='.$pag", __('Database coould not be updated!','docs').'<br />'.$res->errors(), 1);
	}
	
}

/**
* @desc Permite aprobar o no una publicación
**/
function approved_resources($app=0){

	global $xoopsSecurity,$xoopsConfig,$xoopsModuleConfig;
    
	$resources = rmc_server_var($_POST, 'resources', array());
	$page = rmc_server_var($_POST, 'page', 1);
	
	if (!$xoopsSecurity->check()){
		redirectMsg('./resources.php?page='.$page, __('Session token expired!','docs'), 1);
		die();
	}
	
	//Verifica que se haya proporcionado una publicación
	if (!is_array($resources) || empty($resources)){
		redirectMsg('./resources.php?page='.$page, __('Select at least a resource!','docs'),1);
		die();		
	}
	
	$errors='';
	foreach ($resources as $k){
		
		//Comprueba si la publicación es válida
		if ($k<=0){
			$errors.=sprintf(__('Resource ID "%s" is not valid!','docs'), $k);
			continue;
		}
		
		//Comprueba si la publicación existe
		$res= new RDResource($k);
		if ($res->isNew()){
			$errors.=sprintf(__('Resource with ID "%s" does not exists!','docs'), $k);
			continue;
		}
        
		$approved=$res->getVar('approved');
		$res->setVar('approved', $app);
        
		if (!$res->save()){
			$errors.=sprintf(__('Resoource "%s" could not be saved!','docs'), $k);
		}else{
			if ($app && !$approved){
				$errors = RDFunctions::mail_approved($res);
			}
			
		}	
	}
	
	if ($errors!=''){
		redirectMsg('./resources.php?page='.$page,__('Errors ocurred while trying to update resources.').'<br />'.$errors,1);
	}else{
		redirectMsg('./resources.php?page='.$page, __('Resources updated successfully!','docs'),0);
	}

}


$action=isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

switch ($action){
	case 'new':
		qd_show_form();
	break;
	case 'edit':
		qd_show_form(1);
	break;
	case 'save':
		rd_save_resource();
	break;
	case 'saveedit':
		rd_save_resource(1);
	break;
	case 'delete':
		rd_delete_resource();
	break;
	case 'recommend':
		recommend_resource(1);
	break;
	case 'norecommend':
		recommend_resource(0);
	break;
	case 'public':
		public_resources(1);
	break;
	case 'private':
		public_resources(0);
	break;
	case 'qindex':
		quick_resources(1);
	break;
	case 'noqindex':
		quick_resources(0);
	break;	
	case 'approve':
		approved_resources(1);
	break;
	case 'draft':
		approved_resources(0);
	break;
	default:
		show_resources();

}
?>
