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
	$sql="SELECT * FROM ".$db->prefix('rd_sections')." WHERE id_res='$id' AND parent='$parent' ORDER BY `order`";
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
	$sql="SELECT id_res,title FROM ".$db->prefix('rd_resources');
	$result=$db->queryF($sql);
    $resources = array();
	while ($rows=$db->fetchArray($result)){
		$r = new RDResource();
		$r->assignVars($rows);
		$resources[] = array('id'=>$r->id(),'title'=>$r->getVar('title'));
        unset($r);
	}

	//Secciones
	$sql="SELECT * FROM ".$db->prefix('rd_sections')." WHERE id_res='$id' AND parent=0 ORDER BY `order`";
	$result=$db->queryF($sql);
    $sections = array();
	while ($rows=$db->fetchArray($result)){
		$sec= new RDSection();
		$sec->assignVars($rows);

		$sections[] = array(
            'id'=>$sec->id(),
            'title'=>$sec->getVar('title'),
            'order'=>$sec->getVar('order'),
			'resource'=>$sec->getVar('id_res'),
            'parent'=>$sec->getVar('parent'),
            'indent'=>0
        );
		
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
	global $xoopsModule, $xoopsConfig, $xoopsSecurity, $xoopsUser, $xoopsModuleConfig;
    
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
		$sec=new RDSection($id_sec);
		if ($sec->isNew()){
			redirectMsg('sections.php?id='.$id, __('Specified section does not exists','docs'),1);
			die();
		}
        
	}
    
    $rmc_config = RMFunctions::configs();
    $form=new RMForm('','frmsec','sections.php');
    
    if ($rmc_config['editor_type']=='tiny'){
        $tiny = TinyEditor::getInstance();
        $tiny->add_config('theme_advanced_buttons1', 'rd_refs');
        $tiny->add_config('theme_advanced_buttons1', 'rd_figures');
    }
    $editor = new RMFormEditor('','content','100%','300px',$edit ? $sec->getVar('content', 'e') : '','', 0);
    $usrfield = new RMFormUser('','uid',false,$edit ? array($sec->getVar('uid')) : $xoopsUser->getVar('uid'));
    
    RMTemplate::get()->add_style('admin.css', 'docs');
    RMTemplate::get()->add_script('../include/js/scripts.php?file=metas.js');
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.validate.min.js');
    RMTemplate::get()->add_head('<script type="text/javascript">var docsurl = "'.XOOPS_URL.'/modules/docs";</script>');
    RDFunctions::toolbar();
    xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".($edit ? __('Edit Section','docs') : __('Create Section','docs')));
    RMTemplate::get()->assign('xoops_pagetitle', ($edit ? __('Edit Section','docs') : __('Create Section','docs')));
    xoops_cp_header();
    
    $sections = array();
    RDFunctions::getSectionTree($sections, 0, 0, $id, 'id_sec, title', isset($sec) ? $sec->id() : 0);
    include RMTemplate::get()->get_template('admin/rd_sections_form.php', 'module', 'docs');
    
	xoops_cp_footer();
}

/**
* @desc Almacena información de las secciones
**/
function saveSections($edit=0, $ret = 0){
	global $xoopsUser, $xoopsSecurity;
	
	foreach ($_POST as $k=>$v){
		$$k=$v;
	}
    
	if (!$xoopsSecurity->check()){
		redirectMsg('./sections.php?op=new&id='.$id, __('Session token expired!','docs'), 1);
		die();
	}
    
    $db = Database::getInstance();

	if ($edit){

		//Verifica si la sección es válida
		if ($id_sec<=0){
			redirectMsg('./sections.php?id='.$id, __('No section has been specified','docs'),1);
			die();
		}
		
		//Comprueba si la sección es existente
		$sec=new RDSection($id_sec);
		if ($sec->isNew()){
			redirectMsg('./sections.php?id='.$id, __('Section does not exists!','docs'),1);
			die();
		}
		
		//Comprueba que el título de la sección no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('rd_sections')." WHERE title='$title' AND id_res='$id' AND id_sec<>$id_sec";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./sections.php?op=new&id='.$id, __('Already exists another section with same title!','docs'),1);	
			die();
		}


	}else{

		//Comprueba que el título de la sección no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('rd_sections')." WHERE title='$title' AND id_res='$id'";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./sections.php?op=new&id='.$id, __('Already exists another section with same title!','docs'),1);	
			die();
		}
		$sec = new RDSection();
		
	}

	//Genera $nameid Nombre identificador
	$nameid = !isset($nameid) || $nameid=='' ? TextCleaner::getInstance()->sweetstring($title) : $nameid;
	
	$sec->setVar('title', $title);
	$sec->setVar('content', $content);
	$sec->setVar('order', $order);
	$sec->setVar('id_res', $id);
	$sec->setVar('nameid', $nameid);
	$sec->setVar('parent', $parent);
    
	if (!isset($uid)){
		$sec->setVar('uid', $xoopsUser->uid());
		$sec->setVar('uname', $xoopsUser->uname());
	} else {
		$xu = new XoopsUser($uid);
		if ($xu->isNew()){
			$sec->setVar('uid', $xoopsUser->uid());
			$sec->setVar('uname', $xoopsUser->uname());
		} else {
			$sec->setVar('uid', $uid);
			$sec->setVar('uname', $xu->uname());
		}
	}
	if ($sec->isNew()){
		$sec->setVar('created', time());
		$sec->setVar('modified', time());
	}else{
		$sec->setVar('modified', time());
	}
    
    // Metas
    if ($edit) $sec->clear_metas(); // Clear all metas
    // Initialize metas array if not exists  
    if (!isset($metas)) $metas = array();
    // Get meta key if "select" is visible
    if (isset($meta_name_sel) && $meta_name_sel!='') $meta_name = $meta_name_sel;
    // Add meta to metas array
    if (isset($meta_name) && $meta_name!=''){
        array_push($metas, array('key'=>$meta_name, 'value'=>$meta_value));
    }
    // Assign metas
    foreach($metas as $value){
        $sec->add_meta($value['key'], $value['value']);
    }
	
	if (!$sec->save()){
		if ($sec->isNew()){
			redirectMsg('./sections.php?action=new&id='.$id, __('Database could not be updated!','docs') . "<br />" . $sec->errors(),1);
			die();			
		}else{
			redirectMsg('./sections.php?action=edit&id='.$id.'&sec='.$id_sec, __('Sections has been saved but some errors ocurred','docs'). "<br />" . $sec->errors(),1);
			die();
		}		

	}else{
		if ($return){
			redirectMsg('./sections.php?action=edit&sec='.$sec->id().'&id='.$id, __('Database updated successfully!','docs'),0);
		} else {
			redirectMsg('./sections.php?id='.$id, __('Database updated successfully!','docs'),0);
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
		rd_show_form(1);
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
