<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

define('AH_LOCATION', 'editions');
include 'header.php';

include_once '../include/functions.php';

function optionsBar(){
	global $tpl;
    
    $tpl->append('xoopsOptions', array('link' => 'edits.php', 'title' => _AS_AH_EDITS, 'icon' => '../images/edits16.png'));
}

/**
* @desc Muestra una lista con los elementos editados esperando aprovación
*/
function showEdits(){
	global $xoopsModule, $db, $adminTemplate, $tpl, $mc;
	
	$result = $db->query("SELECT * FROM ".$db->prefix("pa_edits")." ORDER BY modified");
	
	while ($row = $db->fetchArray($result)){
		$edit = new AHEdit();
		$edit->assignVars($row);
		$sec = new AHSection($edit->section());
		$link = XOOPS_URL.'/modules/ahelp/';
		$link .= $mc['access'] ? 'content/'.$sec->id().'/'.$sec->nameId() : 'content.php?id='.$sec->id();
		$tpl->append('edits', array('id'=>$edit->id(),'section'=>array('id'=>$sec->id(),'title'=>$sec->title(),
				'link'=>$link),'title'=>$edit->title(),'date'=>formatTimeStamp($edit->modified()),
				'uname'=>$edit->uname(),'msg'=>htmlspecialchars(sprintf(_AS_AH_CONFIRMMSG, $edit->title()))));
	}
	
	$tpl->assign('lang_edits', _AS_AH_EDITSTITLE);
	$tpl->assign('lang_title', _AS_AH_EDITEDTITLE);
	$tpl->assign('lang_otitle', _AS_AH_ORINGINALTITLE);
	$tpl->assign('lang_date', _AS_AH_MODIFIED);
	$tpl->assign('lang_options', _OPTIONS);
	$tpl->assign('lang_review', _AS_AH_REVIEW);
	$tpl->assign('lang_approve', _AS_AH_APPROVE);
	$tpl->assign('lang_by', _AS_AH_BY);
	$tpl->assign('lang_edit', _EDIT);
	$tpl->assign('lang_delete', _DELETE);
	$tpl->assign('lang_confirmdel', _AS_AH_DELCONF);
	
	$adminTemplate = "admin/ahelp_edits.html";
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_AH_EDITLOC);
	optionsBar();
	
	xoops_cp_header();
	
	xoops_cp_footer();
}

/**
* @desc Muestra el contenido de las secciones editadas y original para su revisión
*/
function reviewEdit(){
	global $tpl, $xoopsModule, $db, $adminTemplate, $mc;
	
	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
	
	if ($id<=0){
		redirectMsg('./edits.php', _AS_AH_NOID, 1);
		die();
	}
	
	$edit = new AHEdit($id);
	if ($edit->isNew()){
		redirectMsg('./edits.php', _AS_AH_NOTEXISTS, 1);
		die();
	}
	
	$sec = new AHSection($edit->section());
	if ($sec->isNew()){
		redirectMsg('./edits.php', _AS_AH_NOTEXISTSSEC, 1);
		die();
	}
	
	include_once '../include/functions.php';
	// Datos de la Sección
	$link = XOOPS_URL.'/modules/ahelp/';
	$link .= $mc['access'] ? 'content/'.$sec->id().'/'.$sec->nameId() : 'content.php?id='.$sec->id();
	$tpl->assign('section', array('id'=>$sec->id(), 'title'=>$sec->title(),
			'text'=>ahParseReferences($sec->content()),'link'=>$link,'res'=>$sec->resource()));
	
	// Datos de la Edición
	$tpl->assign('edit', array('id'=>$edit->id(), 'title'=>$edit->title(),'text'=>ahParseReferences($edit->content())));
	
	$tpl->assign('lang_original', _AS_AH_ORIGINAL);
	$tpl->assign('lang_edited', _AS_AH_EDITED);
	$tpl->assign('lang_title', _AS_AH_TITLE);
	$tpl->assign('lang_approve', _AS_AH_APPROVE);
	$tpl->assign('lang_edit', _EDIT);
	$tpl->assign('lang_delete', _DELETE);
	$tpl->assign('lang_view', _AS_AH_VIEW);
	
	$adminTemplate = "admin/ahelp_reviewedit.html";
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./edits.php'>"._AS_AH_EDITLOC."</a> &raquo; ".sprintf(_AS_AH_EDITREVLOC, $sec->title()));
	optionsBar();
	xoops_cp_header();
	
	xoops_cp_footer();
	
}

function approveEdits(){
	
	$edits = isset($_REQUEST['edits']) ? $_REQUEST['edits'] : array();
	
	if (!is_array($edits) && $edits<=0){
		redirectMsg('./edits.php', _AS_AH_NOID, 1);
		die();
	}
	
	$edits = !is_array($edits) ? array($edits) : $edits;
	
	if (count($edits)<=0){
		redirectMsg('./edits.php', _AS_AH_NOID, 1);
		die();
	}
	
	$errors = false;
	
	foreach ($edits as $k){
		$edit = new AHEdit($k);
		if ($edit->isNew()){
			$errors = true;
			continue;
		}
		
		$sec = new AHSection($edit->section());
		if ($sec->isNew()){
			$errors = true;
			continue;
		}
		
		// Guardamos los valores
		$sec->setTitle($edit->title());
		$sec->setNameId($edit->nameId());
		$sec->modified($edit->modified());
		$sec->setUid($edit->uid());
		$sec->setUname($edit->uname());
		$sec->setOrder($edit->order());
		$sec->setParent($edit->parent());
		$sec->setVar('dohtml', $edit->getVar('dohtml'));
		$sec->setVar('doxcode', $edit->getVar('doxcode'));
		$sec->setVar('dobr', $edit->getVar('dobr'));
		$sec->setVar('doimage', $edit->getVar('doimage'));
		$sec->setVar('dosmiley', $edit->getVar('dosmiley'));
		$sec->setContent($edit->content());
		
		if (!$sec->save()){
			$errors = true;
			continue;
		}
		
		$edit->delete();
		
	}
	
	if ($errors){
		redirectMsg('./edits.php', _AS_AH_ERRORSONAPPROVE, 1);
		die();
	} else {
		redirectMsg('./edits.php', _AS_AH_DBOK, 0);
		die();
	}
	
}

function deleteEdits(){
	$edits = isset($_REQUEST['edits']) ? $_REQUEST['edits'] : array();
	
	if (!is_array($edits) && $edits<=0){
		redirectMsg('./edits.php', _AS_AH_NOID, 1);
		die();
	}
	
	$edits = !is_array($edits) ? array($edits) : $edits;
	
	if (count($edits)<=0){
		redirectMsg('./edits.php', _AS_AH_NOID, 1);
		die();
	}
	
	$errors = false;
	
	foreach ($edits as $k){
		$edit = new AHEdit($k);
		if ($edit->isNew()){
			$errors = true;
			continue;
		}
		
		$edit->delete();
		
	}
	
	if ($errors){
		redirectMsg('./edits.php', _AS_AH_ERRORSONAPPROVE, 1);
		die();
	} else {
		redirectMsg('./edits.php', _AS_AH_DBOK, 0);
		die();
	}
}

function showFormEdits(){
	global $xoopsModule,$db, $xoopsConfig;
	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;	
	
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
	
	if ($id<=0){
		redirectMsg('./edits.php', _AS_AH_NOID, 1);
		die();
	}
	
	$edit = new AHEdit($id);
	if ($edit->isNew()){
		redirectMsg('./edits.php', _AS_AH_NOTEXISTS, 1);
		die();
	}
	
	$sec = new AHSection($edit->section());
	if ($sec->isNew()){
		redirectMsg('./edits.php', _AS_AH_NOTEXISTSSEC, 1);
		die();
	}
	
	$res = new AHResource($sec->resource());

	$form=new RMForm($edit ? _AS_AH_EDITSECTIONS : _AS_AH_NEWSECTIONS,'frmsec','edits.php');
	$form->addElement(new RMLabel(_AS_AH_RESOURCE,$res->title()));
	$form->addElement(new RMText(_AS_AH_TITLE,'title',50,200,$edit->title()),true);
	if ($edit){
		$ele = new RMEditorAddons(_OPTIONS,'options','content',$xoopsConfig['editor_type'],$edit->resource(), $edit->section());
		$cHead = $ele->jsFunctions();
		$form->addElement($ele);
	} else {
		$form->addElement(new RMLabel(_OPTIONS, _AS_AH_REFFIG));
	}
	$form->addElement(new RMEditor(_AS_AH_CONTENT,'content','90%','300px',$edit->getVar('content', 'e')),true);
	$dohtml = $edit->getVar('dohtml');
	$doxcode = $edit->getVar('doxcode');
	$dobr = $edit->getVar('dobr');
	$dosmiley = $edit->getVar('dosmiley');
	$doimage = $edit->getVar('doimage');
	$form->addElement(new RMTextOptions(_OPTIONS, $dohtml, $doxcode, $doimage, $dosmiley, $dobr));
	
	// Arbol de Secciones
	$ele= new RMSelect(_AS_AH_SECTION,'parent');
	$ele->addOption(0,_SELECT);
	$tree = array();
	getSectionTree($tree, 0, 0, $res->id(), 'id_sec, title', $sec->id());
	foreach ($tree as $k){
		$ele->addOption($k['id_sec'], str_repeat('--', $k['saltos']).' '.$k['title'], $edit->parent()==$k['id_sec'] ? 1 : 0);
	}
	
	$form->addElement($ele);

	$form->addElement(new RMText(_AS_AH_ORDER,'order',5,5,$edit->order()),true);
	// Usuario
	$form->addElement(new RMFormUserEXM(_AS_AH_FUSER, 'uid', 0, array($edit->uid()), 30));

	$buttons =new RMButtonGroup();
	$buttons->addButton('sbt',_AS_AH_SAVENOW,'submit');
	$buttons->addButton('cancel',_CANCEL,'button', 'onclick="window.location=\'edits.php\';"');

	$form->addElement($buttons);

	$form->addElement(new RMHidden('op','save'));
	$form->addElement(new RMHidden('id',$edit->id()));
	
	optionsBar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./edits.php'>"._AS_AH_EDITLOC."</a> &raquo; ".sprintf(_AS_AH_EDITEDTLOC, $edit->title()));
	xoops_cp_header($cHead);
	
	$form->display();

	xoops_cp_footer();
}

function saveEdit(){
	global $db, $util, $xoopsUser;
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if (!$util->validateToken()){
		redirectMsg('edits.php?op=edit&id='.$id, _AS_AH_SESSINVALID, 1);
		die();
	}
	
	if ($id<=0){
		redirectMsg('./edits.php', _AS_AH_NOID, 1);
		die();
	}
	
	$edit = new AHEdit($id);
	if ($edit->isNew()){
		redirectMsg('./edits.php', _AS_AH_NOTEXISTS, 1);
		die();
	}
	
	$sec = new AHSection($edit->section());
	if ($sec->isNew()){
		redirectMsg('./edits.php', _AS_AH_NOTEXISTSSEC, 1);
		die();
	}
	
	//Comprueba que el título de la sección no exista
	$sql="SELECT COUNT(*) FROM ".$db->prefix('pa_sections')." WHERE title='$title' AND id_res='".$sec->resource()."' AND id_sec<>'".$sec->id()."'";
	list($num)=$db->fetchRow($db->queryF($sql));
	if ($num>0){
		redirectMsg('./edits.php?op=edit&id='.$edit->id(), _AS_AH_ERRTITLE,1);	
		die();
	}
	
	//Genera $nameid Nombre identificador
	$found=false; 
	$i = 0;
	do{
    		$nameid = $util->sweetstring($title).($found ? $i : '');
        	$sql = "SELECT COUNT(*) FROM ".$db->prefix('pa_sections'). " WHERE nameid = '$nameid'";
        	list ($num) =$db->fetchRow($db->queryF($sql));
        	if ($num>0){
        		$found =true;
        	    $i++;
        	}else{
        		$found=false;
        	}
        
    	}while ($found==true);
	
	$sec->setTitle($title);
	$sec->setContent($content);
	$sec->setOrder($order);
	$sec->setNameId($nameid);
	$sec->setParent($parent);
	$sec->setVar('dohtml', isset($dohtml) ? 1 : 0);
	$sec->setVar('doxcode', isset($doxcode) ? 1 : 0);
	$sec->setVar('dobr', isset($dobr) ? 1 : 0);
	$sec->setVar('dosmiley', isset($dosmiley) ? 1 : 0);
	$sec->setVar('doimage', isset($dosmiley) ? 1 : 0);
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
	$sec->setModified(time());
	if (!$sec->save()){
		redirectMsg('edits.php', _AS_AH_DBERROR . '<br />' . $sec->errors(), 1);
		die();
	} 
	
	$edit->delete();
	redirectMsg('edits.php', _AS_AH_DBOK, 0);
	
}


$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch($op){
	case 'review':
		reviewEdit();
		break;
	case 'approve':
		approveEdits();
		break;
	case 'edit':
		showFormEdits();
		break;
	case 'save':
		saveEdit();
		break;
	case 'delete':
		deleteEdits();
		break;
	default:
		showEdits();
		break;
}

?>
