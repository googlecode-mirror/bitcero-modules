<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','features');
include ('header.php');

/**
* @desc Visualiza las caracteríticas existentes de un elemento especificado
**/
function dt_show_features(){
	global $xoopsModule,$tpl, $functions;

    define('RMCSUBLOCATION','showfeatures');

	$item = rmc_server_var($_REQUEST, 'item', 0);

	$sw = new DTSoftware($item);
	
	if ($sw->isNew() && $item>0)
		redirectMsg('items.php', __('Specified download item does not exists!','dtransport'), RMMSG_WARN);

    $db = XoopsDatabaseFactory::getDatabaseConnection();

	$sql = "SELECT * FROM ".$db->prefix('dtrans_features')." WHERE id_soft=$item";
	$result = $db->query($sql);

    $features = array();
    $tf = new RMTimeFormatter(0,"%T%-%d%-%Y%  %h%:%i%");

	while ($rows=$db->fetchArray($result)){
		$ft = new DTFeature();
		$ft->assignVars($rows);

		$features[] = array(
            'id'=>$ft->id(),
            'title'=>$ft->title(),
            'created'=>$tf->format($ft->created()),
            'modified'=>$tf->format($ft->modified()),
            'software'=>$sw->getVar('name')
        );
	
	}

    $functions->toolbar();
    $tpl->assign('xoops_pagetitle', sprintf(__('Features of "%s"','dtransport'), $sw->getVar('name')));

	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./items.php'>".__('Downloads','dtransport')."</a> &raquo; ".__('Features','dtransport'));

	xoops_cp_header();

    include $tpl->get_template('admin/dtrans_features.php','module','dtransport');

	xoops_cp_footer();

}

/**
* @desc Formulario de características
**/
function dt_form_features($edit=0){
	global $db,$xoopsModule,$xoopsConfig, $functions;

    define('RMCSUBLOCATION','newfeature');

	$id = rmc_server_var($_REQUEST, 'id', 0);
	$item = rmc_server_var($_REQUEST, 'item', 0);

	//Verificamos que el software sea válido
	if ($item<=0)
		redirectMsg('items.php', __('Download item ID not provided!','dtransport'), RMMSG_WARN);

	//Verificamos que el software exista
	$sw = new DTSoftware($item);
	if ($sw->isNew())
		redirectMsg('items.php', __('Specified download item does not exists!','dtransport'), RMMSG_ERROR);

	if ($edit){

		if ($id<=0)
			redirectMsg('features.php?item='.$item, __('Feature ID not specified!','dtransport'), RMMSG_WARN);

		//Verificamos que la característica exista
		$ft = new DTFeature($id);
		if ($ft->isNew())
			redirectMsg('features.php?item='.$item, __('Specified feature does not exists!','dtransport'), RMMSG_ERROR);

	}

	$functions->toolbar();

	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='items.php'>".__('Downloads','dtransport')."</a> &raquo; ".($edit ? __('Edit Feature','dtransport') : __('New Feature','dtransport')));
	xoops_cp_header();
	
	$form = new RMForm($edit ? sprintf(__('Editing feature of "%s"','dtransport'),$sw->getVar('name')) : sprintf(__('New feature for "%s"','dtransport'),$sw->getVar('name')),'frmfeat','features.php');

	$form->addElement(new RMFormLabel(__('Download item','dtransport'),$sw->getVar('name')));
	

	$form->addElement(new RMFormText(__('Feature title','dtransport'),'title',50,200,$edit ? $ft->title() : ''),true);
	$form->addElement(new RMFormText(__('Short name','dtransport'), 'nameid', 50, 200, $edit ? $ft->nameId() : ''));
	$form->addElement(new RMFormEditor(__('Feature content','dtransport'),'content','90%','350px',$edit ? $ft->content() : ''),true);

	$form->addElement(new RMFormHidden('action',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMFormHidden('id',$id));
	$form->addElement(new RMFormHidden('item',$item));
	
	$buttons =new RMFormButtonGroup();
	$buttons->addButton('sbt',_SUBMIT,'submit');
	$buttons->addButton('cancel',_CANCEL,'button', 'onclick="window.location=\'features.php?item='.$item.'\';"');

	$form->addElement($buttons);
			

	$form->display();

	xoops_cp_footer();
}




/**
* @desc Almacena la característica en la base de datos
**/
function dt_save_features($edit=0){
	global $xoopsSecurity;

    $query = '';
	foreach ($_POST as $k=>$v){
		$$k=$v;
        if($k=='XOOPS_TOKEN_REQUEST' || $k=='action') continue;

        $query = $query=='' ? $k.'='.urlencode($v) : '&'.$k.'='.urlencode($v);

	}

	if (!$xoopsSecurity->check())
	    redirectMsg('features.php?action='.($edit ? 'edit&id='.$id : 'new').'&item='.$item, __('Session token not valid!','dtransport'), RMMSG_ERROR);

    //Verificamos que el software sea válido
    if ($item<=0)
        redirectMsg('items.php', __('Download item ID not provided!','dtransport'), RMMSG_WARN);

    //Verificamos que el software exista
    $sw = new DTSoftware($item);
    if ($sw->isNew())
        redirectMsg('items.php', __('Specified download item does not exists!','dtransport'), RMMSG_ERROR);

    $db = XoopsDatabaseFactory::getDatabaseConnection();

	if ($edit){

        if ($id<=0)
            redirectMsg('features.php?item='.$item, __('Feature ID not specified!','dtransport'), RMMSG_WARN);

        //Verificamos que la característica exista
        $ft = new DTFeature($id);
        if ($ft->isNew())
            redirectMsg('features.php?item='.$item, __('Specified feature does not exists!','dtransport'), RMMSG_ERROR);

	}else{

		$ft = new DTFeature();

	}

    $tc = TextCleaner::getInstance();

    if(trim($nameid)=='')
        $nameid = $tc->sweetstring($title);
    else
        $nameid = $tc->sweetstring($nameid);

    //Comprueba que el título de la característica no exista
    $sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_features')." WHERE (title='$title' OR nameid='$nameid' AND id_feat<>".$ft->id()." AND id_soft=".$item;
    list($num) = $db->fetchRow($db->queryF($sql));
    if ($num>0)
        redirectMsg('features.php?'.$query.($edit ? '&action=edit' : '&action=new'), __('Another feature with same title already exists!','dtransport'), RMMSG_WARN);

	$ft->setSoftware($item);
	$ft->setTitle($title);
	$ft->setContent($content);
	if (!$edit) $ft->setCreated(time());
	$ft->setModified(time());
	$ft->setNameId($nameid);

	if (!$ft->save())
		redirectMsg('features.php?'.$query.($edit ? '&action=edit' : '&action=new'), __('Feature could not be saved!','dtransport').'<br />'.$ft->errors(),1);


	redirectMsg('./features.php?item='.$item,_AS_DT_DBOK,0);

}

/**
* @desc Elimina la característica especificada de la base de datos
**/
function deleteFeatures(){
	global $util,$xoopsModule;
	
	$ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
	$item = isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;
	$ok = isset($_POST['ok']) ? intval($_POST['ok']) : 0;
	
	//Verificamos que el software sea válido
	if ($item<=0){
		redirectMsg('./features.php',_AS_DT_ERR_ITEMVALID,1);
		die();
	}
	//Verificamos que el software exista
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirectMsg('./features.php',_AS_DT_ERR_ITEMEXIST,1);
		die();
	}

	//Verificamos si nos proporcionaron alguna caracteristica
	if (!is_array($ids) && $ids<=0){
		redirectMsg('./features.php?item='.$item,_AS_DT_ERRFEAT,1);
		die();	
	}

	$num=0;
	if (!is_array($ids)){
		$feat=new DTFeature($ids);
		$ids=array($ids);
		$num=1;
	}


	if ($ok){	

		if (!$util->validateToken()){
				redirectMsg('./features.php?item='.$item,_AS_DT_SESSINVALID, 1);
				die();
		}

		foreach ($ids as $k){

			//Verificamos si la característica es válida
			if ($k<=0){
				$errors.=sprintf(_AS_DT_ERRFEATVAL,$k);
				continue;
			}

			//Verificamos si la caracteristica existe
			$ft=new DTFeature($k);
			if ($ft->isNew()){
				$errors.=sprintf(_AS_DT_ERRFEATEX,$k);
				continue;			
			}
		
			if (!$ft->delete()){
				$errors.=sprintf(_AS_DT_ERRFEATDEL,$k);
			}


		}

			
		if ($errors!=''){
			redirectMsg('./features.php?item='.$item,_AS_DT_ERRORS.$errors,1);
			die();
		}else{
			redirectMsg('./features.php?item='.$item,_AS_DT_DBOK,0);
			die();
		}	

	}else{
		optionsBar();
		xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./items.php'>"._AS_DT_SW."</a> &raquo; "._AS_DT_DELETEFEATURE);
		xoops_cp_header();

		$hiddens['ok'] = 1;
		$hiddens['id[]'] = $ids;
		$hiddens['item'] = $item;
		$hiddens['op'] = 'delete';

		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'features.php?item='.$item.'\';"';
		
		$util->msgBox($hiddens, 'features.php', ($num ? sprintf(_AS_DT_DELETECONF,$feat->title()) : _AS_DT_DELCONF). '<br /><br />' ._AS_DT_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
	
		xoops_cp_footer();

	}

}


/**
* @desc Cambia a nueva una característica
**/
function newFeatures(){

	$ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
	$item = isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;
	
	//Verificamos si se proporcionó una caracteristica
	if (!is_array($ids) || empty($ids)){
		redirectMsg('./features.php?item='.$item,_AS_DT_ERRFEAT,1);
		die();
	}

	$errors='';
	foreach ($ids as $k){

		//Verificamos si la característica es válida
		if ($k<=0){
			$errors.=sprintf(_AS_DT_ERRFEATVAL,$k);
			continue;
		}

		//Verificamos si la caracteristica existe
		$ft=new DTFeature($k);
		if ($ft->isNew()){
			$errors.=sprintf(_AS_DT_ERRFEATEX,$k);
			continue;			
		}

		$ft->setShowNew(!$ft->showNew());
		$ft->setModified(time());	
		
		if (!$ft->save()){
			$errors.=sprintf(_AS_DT_ERRFEATSAVE,$k);
		}
	
	}

	if ($errors!=''){
		redirectMsg('./features.php?item='.$item,_AS_DT_ERRORS.$errors,1);
		die();
	}else{
		redirectMsg('./features.php?item='.$item,_AS_DT_DBOK,0);
		die();
	}


}



$action = rmc_server_var($_REQUEST, 'action', '');

switch ($action){
	case 'new':
		dt_form_features();
	    break;
	case 'edit':
		dt_form_features(1);
	    break;
	case 'save':
		dt_save_features();
	    break;
	case 'saveedit':
		dt_save_features(1);
	    break;
	case 'delete':
		deleteFeatures();
	break;
	case 'newfeat':
		newFeatures();
	break;
	default:
		dt_show_features();
}

