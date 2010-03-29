<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','customers');
include 'header.php';

/**
* @desc Visualiza todos los clientes
**/
function showClients(){
	global $xoopsModule, $db, $tpl, $adminTemplate;

	//Barra de Navegación
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('pw_clients');
	
	list($num)=$db->fetchRow($db->query($sql));
	
	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
	$limit = 20;
    $tpages = ceil($num/$limit);
    $page = $page > $tpages ? $tpages : $page;  
    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('clients.php?page={PAGE_NUM}');

	$sql = "SELECT * FROM ".$db->prefix('pw_clients')." ORDER BY id_client";
	$sql.= " LIMIT $start,$limit";
	$result = $db->query($sql);
	while($rows = $db->fetchArray($result)){
	
		$client = new PWClient();
		$client->assignVars($rows);

		$type = new PWType($client->type());

		$tpl->append('clients',array('id'=>$client->id(),'name'=>$client->name(),'business'=>$client->businessName(),
		'type'=>$type->type()));
	}

    PWFunctions::toolbar();
    RMTemplate::get()->add_style('admin.css', 'works');
	xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; "._AS_PW_CLIENTLOC);
	xoops_cp_header();
    
    include RMTemplate::get()->get_template('admin/pw_clients.php', 'module', 'works');

	xoops_cp_footer();
}

/**
* @desc Formulario de creación/edición de clientes
**/
function formClients($edit = 0){

	global $xoopsModule, $db;

	optionsBar();
	xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; <a href='./clients.php'>"._AS_PW_CLIENTLOC."</a> &raquo;".($edit ? _AS_PW_EDITCLIENT : _AS_PW_NEWCLIENT));

	xoops_cp_header();

	$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	
	$ruta = "pag=$page&limit=$limit";

	if ($edit){
		//Verificamos si el cliente es válido
		if ($id<=0){
			redirectMsg('./clients.php?'.$ruta,_AS_PW_ERRCLIENTVALID,1);
			die();
		}

		//Verificamos si el cliente existe
		$client = new PWClient($id);
		if ($client->isNew()){
			redirectMsg('./clients.php?'.$ruta,_AS_PW_ERRCLIENTEXIST,1);
			die();
		}
	}

	$form = new RMForm($edit ? _AS_PW_EDITCLIENT : _AS_PW_NEWCLIENT,'frmClient','clients.php');

	$form->addElement(new RMText(_AS_PW_FNAME,'name',50,200,$edit ? $client->name() : ''),true);
	$form->addElement(new RMText(_AS_PW_FBUSINESS,'business',50,200,$edit ? $client->businessName() : ''));
	$form->addElement(new RMText(_AS_PW_FMAIL,'mail',50,100,$edit ? $client->email() : ''));
	$form->addElement(new RMTextArea(_AS_PW_FDESC,'desc',4,50,$edit ? $client->desc() : ''),true);

	//Tipos de Cliente
	$ele = new RMSelect(_AS_PW_FTYPE,'type');
	$ele->addOption(0,_SELECT);
	$result = $db->query("SELECT * FROM ".$db->prefix('pw_types'));	
	while ($row = $db->fetchArray($result)){
		$ele->addOption($row['id_type'],$row['type'], $edit ? ($row['id_type']==$client->type() ? 1 :0) : 0);
	}
	
	$form->addElement($ele,true,'noselect:0');
	
	
	$form->addElement(new RMHidden('op',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMHidden('id',$id));
	$form->addElement(new RMHidden('page',$page));
	$form->addElement(new RMHidden('limit',$limit));

	$ele = new RMButtonGroup();
	$ele->addButton('sbt', _SUBMIT, 'submit');
	$ele->addButton('cancel', _CANCEL, 'button', 'onclick="window.location=\'clients.php?'.$ruta.'\';"');
	$form->addElement($ele);
	$form->display();
	
	xoops_cp_footer();
}


/**
* @desc Almacena en la base de datos la información del cliente
**/
function saveClients($edit = 0){
	global $util;

	foreach ($_POST as $k => $v){
		$$k = $v;
	}
		
	$ruta = "pag=$page&limit=$limit";

	if (!$util->validateToken()){
		redirectMsg('./clients.php?op='.($edit ? 'edit&id='.$id : 'new').'&'.$ruta, _AS_PW_ERRSESSID, 1);
		die();
	}

	if ($edit){
		//Verificamos si el cliente es válido
		if ($id<=0){
			redirectMsg('./clients.php?'.$ruta,_AS_PW_ERRCLIENTVALID,1);
			die();
		}

		//Verificamos si el cliente existe
		$client = new PWClient($id);
		if ($client->isNew()){
			redirectMsg('./clients.php?'.$ruta,_AS_PW_ERRCLIENTEXIST,1);
			die();
		}

	}else{
		$client = new PWClient();
	}

	$client->setName($name);
	$client->setBusinessName($business);
	$client->setEmail($mail);
	$client->setType($type);
	$client->setDesc(substr($desc,0,50));
	$client->isNew() ? $client->setCreated(time()) : $client->setModified(time());

	if (!$client->save()){
		redirectMsg('./clients.php?'.$ruta,_AS_PW_DBERROR.$client->errors(),1);
		die();
	}else{
		redirectMsg('./clients.php?'.$ruta,_AS_PW_DBOK,0);
		die();
	}
}


/**
* @desc Elimina de la base de datos la información del cliente
**/
function deleteClients(){

	global $util, $xoopsModule;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$ok = isset($_POST['ok']) ? intval($_POST['ok']) : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	
	$ruta = "pag=$page&limit=$limit";

	//Verificamos que nos hayan proporcionado un cliente para eliminar
	if (!is_array($ids) && ($ids<=0)){
		redirectMsg('./clients.php?'.$ruta,_AS_PW_ERRNOTCLIENTDEL,1);
		die();
	}
	
	if (!is_array($ids)){
		$user = new PWClient($ids);
		$ids = array($ids);
	}


	if ($ok){

		if (!$util->validateToken()){
			redirectMsg('./clients.php?'.$ruta,_AS_PW_ERRSESSID, 1);
			die();
		}

		$errors = '';
		foreach ($ids as $k){
			//Verificamos si el cliente es válido
			if ($k<=0){
				$errors.=sprintf(_AS_PW_NOTVALID, $k);
				continue;
			}

			//Verificamos si el cliente existe
			$client = new PWClient($k);
			if ($client->isNew()){
				$errors.=sprintf(_AS_PW_NOTEXIST, $k);
				continue;
			}
		
			if (!$client->delete()){
				$errors.=sprintf(_AS_PW_NOTDELETE,$k);
			}
		}
	
		if ($errors!=''){
			redirectMsg('./clients.php?'.$ruta,_AS_PW_DBERRORS.$errors,1);
			die();
		}else{
			redirectMsg('./clients.php?'.$ruta,_AS_PW_DBOK,0);
			die();
		}


	}else{
		optionsBar();
		xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; 
		<a href='./clients.php'>"._AS_PW_CLIENTLOC."</a> &raquo;"._AS_PW_DELETE);
		xoops_cp_header();

		$hiddens['ok'] = 1;
		$hiddens['ids[]'] = $ids;
		$hiddens['op'] = 'delete';
		$hiddens['pag'] = $page;
		$hiddens['limit'] = $limit;
		
		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'clients.php?'.$ruta.'\';"';
		
		$util->msgBox($hiddens, 'clients.php',($user ? sprintf(_AS_PW_DELETECONF, $user->name()) : _AS_PW_DELETECONFS). '<br /><br />' ._AS_PW_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
	
		xoops_cp_footer();

	}
}


$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch($op){
	case 'new':
		formClients();
		break;
	case 'edit':
		formClients(1);
		break;
	case 'save':
		saveClients();
		break;
	case 'saveedit':
		saveClients(1);
		break;
	case 'delete':
		deleteClients();
		break;
	default:
		showClients();
}
?>
