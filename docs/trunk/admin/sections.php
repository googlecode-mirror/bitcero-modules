<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2


define('AH_LOCATION', 'sections');
include 'header.php';

include_once '../include/functions.php';

/**
* @desc Muestra la barra de menus
*/
function optionsBarSections(){
    global $tpl;
    $id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
    $tpl->append('xoopsOptions', array('link' => './sections.php?id='.$id, 'title' => _AS_AH_SECTIONS, 'icon' => '../images/sections16.png'));
    $tpl->append('xoopsOptions', array('link' => './sections.php?op=new&id='.$id, 'title' => _AS_AH_NEWSECTIONS, 'icon' => '../images/add.png'));
    $tpl->append('xoopsOptions', array('link' => './resources.php', 'title' => _AS_AH_RESOURCES, 'icon' => '../images/res16.png'));
}


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

function showSections(){
	global $xoopsModule,$db,$tpl,$util,$adminTemplate;
	include XOOPS_ROOT_PATH."/cache/recommends.php";

	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;	

	//Lista de Publicaciones
	$sql="SELECT id_res,title FROM ".$db->prefix('pa_resources');
	$result=$db->queryF($sql);
	while ($rows=$db->fetchArray($result)){
		$res = new AHResource();
		$res->assignVars($rows);
		$tpl->append('resources',array('id'=>$res->id(),'title'=>$res->title()));
	}

	//Secciones
	$sql="SELECT * FROM ".$db->prefix('pa_sections')." WHERE id_res='$id' AND parent=0 ORDER BY `order`";
	$result=$db->queryF($sql);
	while ($rows=$db->fetchArray($result)){
		$sec= new AHSection();
		$sec->assignVars($rows);

		$tpl->append('sections',array('id'=>$sec->id(),'title'=>$sec->title(),'order'=>$sec->order(),
				'resource'=>$sec->resource(),'parent'=>$sec->parent(),'indent'=>0,'featured'=>$sec->featured()));
		
		child($id,$sec->id(),1);
		
	}

	$tpl->assign('lang_secexist',_AS_AH_EXIST);
	$tpl->assign('lang_id',_AS_AH_ID);
	$tpl->assign('lang_title',_AS_AH_TITLE);
	$tpl->assign('lang_order',_AS_AH_ORDER);
	$tpl->assign('lang_res',_AS_AH_RESOURCES);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_del',_DELETE);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_select',_SELECT);
	$tpl->assign('id',$id);
	$tpl->assign('lang_notres',_AS_AH_NOTRES);
	$tpl->assign('token', $util->getTokenHTML());
	$tpl->assign('lang_save',_AS_AH_SAVE);
	$tpl->assign('lang_recommend',_AS_AH_RECOMMEND);
	$tpl->assign('lang_norecommend',_AS_AH_NORECOMMEND);

	optionsBarSections();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_AH_SECTIONS);
	$adminTemplate = 'admin/ahelp_sections.html';
	xoops_cp_header();
	xoops_cp_footer();

}


/**
* @desc Formulario de creación y edición de sección
**/
function showForm($edit=0){
	global $xoopsModule,$db, $xoopsConfig;
	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;	
	$id_sec=isset($_REQUEST['sec']) ? intval($_REQUEST['sec']) : 0;

	//Verifica si se proporcionó una publicación para la sección
	if ($id<=0){
		redirectMsg('./sections.php?id='.$id,_AS_AH_NOTRESOURCE,1);
		die();
	}
	
	//Verifica si la publicación existe
	$res= new AHResource($id);
	if ($res->isNew()){
		redirectMsg('./sections.php?id='.$id,_AS_AH_NOTEXIST,1);
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
	}
	

	$form=new RMForm($edit ? _AS_AH_EDITSECTIONS : _AS_AH_NEWSECTIONS,'frmsec','sections.php');
	$form->tinyCSS(XOOPS_URL.'/modules/ahelp/styles/editor.css');
	$form->addElement(new RMLabel(_AS_AH_RESOURCE,$res->title()));
	$form->addElement(new RMText(_AS_AH_TITLE,'title',50,200,$edit ? $sec->title() : ''),true);
	$form->addElement(new RMText(_AS_AH_SHORTNAME, 'nameid', 50, 200, $edit ? $sec->nameId() : ''));
	if ($edit){
		$ele = new RMEditorAddons(_OPTIONS,'options','content',$xoopsConfig['editor_type'],$res->id(),$sec->id(),$id_sec);
		$cHead = $ele->jsFunctions();
		$form->addElement($ele);
	} else {
		$form->addElement(new RMLabel(_OPTIONS, _AS_AH_REFFIG));
	}
	$form->addElement(new RMEditor(_AS_AH_CONTENT,'content','90%','300px',$edit ? $sec->getVar('content', 'e') : '','', 0));
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
	$form->addElement(new RMTextOptions(_OPTIONS, $dohtml, $doxcode, $doimage, $dosmiley, $dobr));
	
	// Arbol de Secciones
	$ele= new RMSelect(_AS_AH_SECTION,'parent');
	$ele->addOption(0,_SELECT);
	$tree = array();
	getSectionTree($tree, 0, 0, $res->id(), 'id_sec, title', $edit ? $sec->id() : 0);
	foreach ($tree as $k){
		$ele->addOption($k['id_sec'], str_repeat('--', $k['saltos']).' '.$k['title'], $edit ? ($sec->parent()==$k['id_sec'] ? 1 : 0) : 0);
	}
	
	$form->addElement($ele);

	$form->addElement(new RMText(_AS_AH_ORDER,'order',5,5,$edit ? $sec->order() : ''),true);
	// Usuario
	if ($edit) $form->addElement(new RMFormUserEXM(_AS_AH_FUSER, 'uid', 0, array($sec->uid()), 30));

	$buttons =new RMButtonGroup();
	$buttons->addButton('sbt',_AS_AH_SAVENOW,'submit');
	$buttons->addButton('ret', _AS_AH_SAVERET, 'submit', 'onclick="document.forms[\'frmsec\'].op.value=\''.($edit ? 'saveretedit' : 'saveret').'\';"');
	$buttons->addButton('cancel',_CANCEL,'button', 'onclick="window.location=\'sections.php?id='.$id.'\';"');

	$form->addElement($buttons);

	$form->addElement(new RMHidden('op',$edit ? 'saveedit': 'save' ));
	$form->addElement(new RMHidden('id',$id));
	$form->addElement(new RMHidden('id_sec',$id_sec));
	
	optionsBarSections();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".($edit ? _AS_AH_EDITSECTION : _AS_AH_NEWSECTIONS));
	xoops_cp_header($cHead);
	
	$form->display();

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
$op=isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch ($op){
	case 'new':
		showForm();
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
		showSections();

}
