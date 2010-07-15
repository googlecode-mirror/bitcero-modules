<?php
// $Id$
// --------------------------------------------------------------
// Rapid Docs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------


define('RMCLOCATION', 'sections');
include 'header.php';

include_once '../include/functions.php';

/**
* @desc Obtiene las secciones hijas de una sección
* @param int $id Publicación a que pertenece
* @param int $parent Sección padre a qur pertenece
**/
function child($id,$parent,$indent){
	global $tpl,$db,$util;
	include XOOPS_ROOT_PATH."/cache/recommends.php";

	$child= array();
	$sql="SELECT * FROM ".$db->prefix('pa_sections')." WHERE id_res='$id' AND parent='$parent' ORDER BY `order`";
	$result=$db->queryF($sql);
	while ($rows=$db->fetchArray($result)){
		$sec= new AHSection();
		$sec->assignVars($rows);

		$recommend=false;
		if (!in_array(array('id'=>$sec->id(),'type'=>'section'),$items)) $recommend=true;
		
		$tpl->append('sections',array('id'=>$sec->id(),'title'=>$sec->title(),'order'=>$sec->order(),
				'resource'=>$sec->resource(),'parent'=>$sec->parent(),'indent'=>$indent,'recommend'=>$recommend));
		
		child($id,$sec->id(),$indent+1);	
	}
}

function rd_show_sections(){
	global $xoopsModule, $xoopsSecurity;
    
	include XOOPS_CACHE_PATH."/rdrecommends.php";

	$id= rmc_server_var($_GET,'id', 0);
    
    $res = new RDResource($id);
    
    $db = Database::getInstance();

	//Lista de Publicaciones
	$sql="SELECT id_res,title FROM ".$db->prefix('pa_resources');
	$result=$db->queryF($sql);
    $resources = array();
	while ($rows=$db->fetchArray($result)){
		$r = new RDResource();
		$r->assignVars($rows);
		$resources[] = array('id'=>$r->id(),'title'=>$r->getVar('title'));
        unset($r);
	}

	//Secciones
	$sql="SELECT * FROM ".$db->prefix('pa_sections')." WHERE id_res='$id' AND parent=0 ORDER BY `order`";
	$result=$db->queryF($sql);
    $sections = array();
	while ($rows=$db->fetchArray($result)){
		$sec= new AHSection();
		$sec->assignVars($rows);

		$sections[] = array('id'=>$sec->id(),'title'=>$sec->title(),'order'=>$sec->order(),
				'resource'=>$sec->resource(),'parent'=>$sec->parent(),'indent'=>0,'featured'=>$sec->featured());
		
		child($id,$sec->id(),1);
		
	}
    
	RDFunctions::toolbar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('Sections Management','docs'));
    RMTemplate::get()->assign('xoops_pagetitle', __('Sections Management','docs'));
    RMTemplate::get()->add_style('admin.css', 'docs');
	xoops_cp_header();
    
    include RMTemplate::get()->get_template('admin/rd_sections.php', 'module', 'docs');
    
	xoops_cp_footer();

}


/**
* @desc Formulario de creación y edición de sección
**/
function rd_show_form($edit=0){
	global $xoopsModule, $xoopsConfig, $xoopsSecurity;
    
    define('RMCSUBLOCATION','newresource');
	$id=rmc_server_var($_GET, 'id', 0);
    
    if ($id<=0){
        redirectMsg('sections.php?id='.$id, __('You must select a resource in order to create a new section','docs'),1);
        die();
    }
    
    // Check if provided resource exists
    global $res;
    $res= new RDResource($id);
    if ($res->isNew()){
        redirectMsg('sections.php?id='.$id, __('Specified resource does not exists!','docs'),1);
        die();
    }
	

	if ($edit){
        
        $id_sec = rmc_server_var($_GET, 'sec', 0);
        
		//Verifica si la sección es válida
		if ($id_sec<=0){
			redirectMsg('sections.php?id='.$id, __('Specify a section to edit','docs'),1);
			die();
		}
		
		//Comprueba si la sección es existente
        global $sec;
		$sec=new RDSection($id_sec);
		if ($sec->isNew()){
			redirectMsg('sections.php?id='.$id, __('Specified section does not exists','docs'),1);
			die();
		}
        
	}
	

	/*
    $form=new RMForm($edit ? _AS_AH_EDITSECTIONS : _AS_AH_NEWSECTIONS,'frmsec','sections.php');
	$form->tinyCSS(XOOPS_URL.'/modules/ahelp/styles/editor.css');
	$form->addElement(new RMFormLabel(_AS_AH_RESOURCE,$res->getVar('title')));
	$form->addElement(new RMFormText(_AS_AH_TITLE,'title',50,200,$edit ? $sec->title() : ''),true);
	$form->addElement(new RMFormText(_AS_AH_SHORTNAME, 'nameid', 50, 200, $edit ? $sec->nameId() : ''));
	if ($edit){
		$ele = new RMEditorAddons(_OPTIONS,'options','content',$xoopsConfig['editor_type'],$res->id(),$sec->id(),$id_sec);
		$cHead = $ele->jsFunctions();
		$form->addElement($ele);
	} else {
		$form->addElement(new RMFormLabel(_OPTIONS, _AS_AH_REFFIG));
	}
	$form->addElement(new RMFormEditor(_AS_AH_CONTENT,'content','90%','300px',$edit ? $sec->getVar('content', 'e') : '','', 0));
	if ($edit){
		$dohtml = $sec->getVar('dohtml');
		$doxcode = $sec->getVar('doxcode');
		$dobr = $sec->getVar('dobr');
		$dosmiley = $sec->getVar('dosmiley');
		$doimage = $sec->getVar('doimage');
	} else {
		$dohtml = 1;
		$doxcode = 0;
		$dobr = 0;
		$dosmiley = 0;
		$doimage = 0;
	}
	$form->addElement(new RMFormTextOptions(_OPTIONS, $dohtml, $doxcode, $doimage, $dosmiley, $dobr));
	
	// Arbol de Secciones
	$ele= new RMFormSelect(_AS_AH_SECTION,'parent');
	$ele->addOption(0,_SELECT);
	$tree = array();
	getSectionTree($tree, 0, 0, $res->id(), 'id_sec, title', $edit ? $sec->id() : 0);
	foreach ($tree as $k){
		$ele->addOption($k['id_sec'], str_repeat('--', $k['saltos']).' '.$k['title'], $edit ? ($sec->parent()==$k['id_sec'] ? 1 : 0) : 0);
	}
	
	$form->addElement($ele);

	$form->addElement(new RMFormText(_AS_AH_ORDER,'order',5,5,$edit ? $sec->order() : ''),true);
	// Usuario
	if ($edit) $form->addElement(new RMFormUserEXM(_AS_AH_FUSER, 'uid', 0, array($sec->uid()), 30));

	$buttons =new RMFormButtonGroup();
	$buttons->addButton('sbt',_AS_AH_SAVENOW,'submit');
	$buttons->addButton('ret', _AS_AH_SAVERET, 'submit', 'onclick="document.forms[\'frmsec\'].op.value=\''.($edit ? 'saveretedit' : 'saveret').'\';"');
	$buttons->addButton('cancel',_CANCEL,'button', 'onclick="window.location=\'sections.php?id='.$id.'\';"');

	$form->addElement($buttons);

	$form->addElement(new RMFormHidden('op',$edit ? 'saveedit': 'save' ));
	$form->addElement(new RMFormHidden('id',$id));
	$form->addElement(new RMFormHidden('id_sec',$id_sec));
	
	$form->display();
    */
    
    $form=new RMForm($edit ? _AS_AH_EDITSECTIONS : _AS_AH_NEWSECTIONS,'frmsec','sections.php');
    $tiny = TinyEditor::getInstance();
    $tiny->add_config('theme_advanced_buttons1', 'rd_refs');
    $tiny->add_config('theme_advanced_buttons1', 'rd_figures');
    $editor = new RMFormEditor(_AS_AH_CONTENT,'content','100%','300px',$edit ? $sec->getVar('content', 'e') : '','', 0);
    
    RMTemplate::get()->add_style('admin.css', 'docs');
    RMTemplate::get()->add_script('../include/js/scripts.php?file=metas.js');
    RDFunctions::toolbar();
    xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".($edit ? _AS_AH_EDITSECTION : _AS_AH_NEWSECTIONS));
    xoops_cp_header();
    
    include RMTemplate::get()->get_template('admin/rd_sections_form.php', 'module', 'docs');
    
	xoops_cp_footer();
}

/**
* @desc Almacena información de las secciones
**/
function saveSections($edit=0, $ret = 0){
	global $util,$db, $xoopsUser;
	
	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	if (!$util->validateToken()){
		redirectMsg('./sections.php?op=new&id='.$id,_AS_AH_SESSINVALID, 1);
		die();
	}


	if ($edit){

		//Verifica si la sección es válida
		if ($id_sec<=0){
			redirectMsg('./sections.php?id='.$id,_AS_AH_NOTSECTION,1);
			die();
		}
		
		//Comprueba si la sección es existente
		$sec=new AHSection($id_sec);
		if ($sec->isNew()){
			redirectMsg('./sections.php?id='.$id,_AS_AH_NOTEXISTSEC,1);
			die();
		}
		
		//Comprueba que el título de la sección no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('pa_sections')." WHERE title='$title' AND id_res='$id' AND id_sec<>$id_sec";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./sections.php?op=new&id='.$id,_AS_AH_ERRTITLE,1);	
			die();
		}


	}else{

		//Comprueba que el título de la sección no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('pa_sections')." WHERE title='$title' AND id_res='$id'";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./sections.php?op=new&id='.$id,_AS_AH_ERRTITLE,1);	
			die();
		}
		$sec = new AHSection();
		
	}

	//Genera $nameid Nombre identificador
	$nameid = $nameid=='' ? $util->sweetstring($title) : $nameid;
	
	$sec->setTitle($title);
	$sec->setContent($content);
	$sec->setOrder($order);
	$sec->setResource($id);
	$sec->setNameId($nameid);
	$sec->setParent($parent);
	$sec->setVar('dohtml', $dohtml);
	$sec->setVar('doxcode', $doxcode);
	$sec->setVar('dobr', $dobr);
	$sec->setVar('dosmiley', $dosmiley);
	$sec->setVar('doimage', $doimage);
	if (!isset($uid)){
		$sec->setUid($xoopsUser->uid());
		$sec->setUname($xoopsUser->uname());
	} else {
		$xu = new XoopsUser($uid);
		if ($xu->isNew()){
			$sec->setUid($xoopsUser->uid());
			$sec->setUname($xoopsUser->uname());
		} else {
			$sec->setUid($uid);
			$sec->setUname($xu->uname());
		}
	}
	if ($sec->isNew()){
		$sec->setCreated(time());
		$sec->setModified(time());
	}else{
		$sec->setModified(time());
	}
	
	if (!$sec->save()){
		if ($sec->isNew()){
			redirectMsg('./sections.php?op=new&id='.$id,_AS_AH_DBERROR . "<br />" . $sec->errors(),1);
			die();			
		}else{
			redirectMsg('./sections.php?op=edit&id='.$id.'&sec='.$id_sec,_AS_AH_DBERROR. "<br />" . $sec->errors(),1);
			die();
		}		

	}else{
		if ($ret){
			redirectMsg('./sections.php?op=edit&sec='.$sec->id().'&id='.$id,_AS_AH_DBOK,0);
		} else {
			redirectMsg('./sections.php?id='.$id,_AS_AH_DBOK,0);
		}
	}


}

/**
* @desc Elimina la información de una sección
**/
function delSections(){
global $xoopsModule,$util;
	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;	
	$id_sec = isset($_REQUEST['sec']) ? intval($_REQUEST['sec']) : 0;
	$ok = isset($_POST['ok']) ? intval($_POST['ok']) : 0;

	//Verifica si la sección es válida
	if ($id_sec<=0){
		redirectMsg('./sections.php?id='.$id,_AS_AH_NOTSECTION,1);
		die();
	}
		
	//Comprueba si la sección es existente
	$sec=new AHSection($id_sec);
	if ($sec->isNew()){
		redirectMsg('./sections.php?id='.$id,_AS_AH_NOTEXISTSEC,1);
		die();
	}

	if ($ok){
		
		if (!$util->validateToken()){
			redirectMsg('./sections.php?id='.$id,_AS_AH_SESSINVALID, 1);
			die();
		}

		if (!$sec->delete()){
			redirectMsg('./sections.php?id='.$id,_AS_AH_DBERROR,1);
			die();

		}else{
			redirectMsg('./sections.php?id='.$id,_AS_AH_DBOK,0);
		}

		
	
	}else{
		optionsBarSections();
		xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_AH_SECTIONS);
		xoops_cp_header();

		$hiddens['ok'] = 1;
		$hiddens['id'] = $id;
		$hiddens['sec'] = $id_sec;
		$hiddens['op'] = 'delete';
		
		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'sections.php?id='.$id.'\';"';
		
		$util->msgBox($hiddens, 'sections.php', sprintf(_AS_AH_DELETECONF, $sec->title()). '<br /><br />' . _AS_AH_ADV._AS_AH_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
	
		xoops_cp_footer();

	}
	
}


/**
* @desc Modifica el orden de las secciones
**/
function changeOrderSections(){
	global $util;
	$orders=isset($_REQUEST['orders']) ? $_REQUEST['orders'] : array();
	$id=isset($_REQUEST['id']) ? $_REQUEST['id'] : array();	
	

	if (!$util->validateToken()){
		redirectMsg('./sections.php?id='.$id,_AS_AH_SESSINVALID, 1);
		die();
	}	

	if (!is_array($orders) || empty($orders)){
		redirectMsg('./sections.php?id='.$id,_AS_AH_NOTSECTION,1);
		die();
	}
	
	$errors='';
	foreach ($orders as $k=>$v){
	
		//Verifica si la sección es válida
		if ($k<=0){
			$errors.=sprintf(_AS_AH_NOTVALID, $k);
			continue;
		}	
		
		//Comprueba si la sección es existente
		$sec=new AHSection($k);
		if ($sec->isNew()){
			$errors.=sprintf(_AS_AH_NOTEXISTSECT,$k);
			continue;
		}	
		

		$sec->setOrder($v);		
		if (!$sec->save()){
			$errors.=sprintf(_AS_AH_NOTSAVEORDER, $k);		
		}
	}

	if ($errors!=''){
		redirectMsg('./sections.php?id='.$id,_AS_AH_ERRORS.$errors,1);
		die();

	}else{
		redirectMsg('./sections.php?id='.$id,_AS_AH_DBOK,0);
	}



}

/**
* @desc Permite recomendar una seccion
**/
function recommendSections($sw){
	
	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$id_sec = isset($_REQUEST['sec']) ? intval($_REQUEST['sec']) : 0;

	$sec = new AHSection($id_sec);
	$sec->setFeatured($sw);
	if ($sec->save()){
		redirectMsg("sections.php?id=$id", _AS_AH_DBOK, 0);
	} else {
		redirectMsg("sections.php?id=$id", _AS_AH_DBERROR.'<br />'.$sec->errors(), 1);
	}
	
}

/**
* @desc Permite No recomendar una publicación
**/
function delRecommendSections(){
	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$id_sec = isset($_REQUEST['sec']) ? intval($_REQUEST['sec']) : 0;

	delRecommend('section',$id_sec);
	header ("location:./sections.php?id=$id");
	

}


$action = rmc_server_var($_REQUEST, 'action', '');

switch ($action){
	case 'new':
		rd_show_form();
	    break;
	case 'edit':
		showForm(1);
	break;
	case 'save':
		saveSections();
		break;
	case 'saveret':
		saveSections(0, 1);
		break;
	case 'saveedit':
		saveSections(1);
		break;
	case 'saveretedit':
		saveSections(1, 1);
		break;
	case 'delete':
		delSections();
	break;
	case 'changeorder':
		changeOrderSections();
	break;
	case 'recommend':
		recommendSections(1);
	break;
	case 'norecommend':
		recommendSections(0);
	break;
	default: 
		rd_show_sections();
        break;
}
