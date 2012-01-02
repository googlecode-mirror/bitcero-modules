<?php
// $Id$
// --------------------------------------------------------------
// RapidDocs
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
function child(&$sections, $id,$parent,$indent){
	global $tpl,$db,$util;

	$child= array();
	$sql="SELECT * FROM ".$db->prefix('rd_sections')." WHERE id_res='$id' AND parent='$parent' ORDER BY `order`";
	$result=$db->queryF($sql);
	while ($rows=$db->fetchArray($result)){
		$sec= new RDSection();
		$sec->assignVars($rows);
		
		$sections[] = array(
            'id'=>$sec->id(),
            'title'=>$sec->getVar('title'),
            'order'=>$sec->getVar('order'),
			'resource'=>$sec->getVar('id_res'),
            'parent'=>$sec->getVar('parent'),
            'indent'=>$indent,
            'permalink'=>$sec->permalink(),
            'author'=>$sec->getVar('uname'),
            'created'=>formatTimestamp($sec->getVar('created'), 'l'),
            'modified'=>formatTimestamp($sec->getVar('modified'), 'l')
        );
		
		child($sections, $id,$sec->id(),$indent+1);	
	}
}

function rd_show_sections(){
	global $xoopsModule, $xoopsSecurity;

	$id= rmc_server_var($_GET,'id', 0);
    if($id<=0){
        redirectMsg('resources.php', __('Select a Document to see the sections inside this','docs'), 0);
        die();
    }
    
    $res = new RDResource($id);
    if($res->isNew()){
        redirectMsg('resources.php', __('The specified Document does not exists!','docs'), 1);
        die();
    }
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();

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
    $sections = array();
    RDFunctions::sections_tree_index(0,0,$res,'','',false,$sections,false,true);
    
    // Event
    $sections = RMEvents::get()->run_event('docs.loading.sections', $sections);
    
	RDFunctions::toolbar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('Sections Management','docs'));
    RMTemplate::get()->assign('xoops_pagetitle', __('Sections Management','docs'));
    RMTemplate::get()->add_style('admin.css', 'docs');
    RMTemplate::get()->add_style('sections.css', 'docs');
    RMTemplate::get()->add_local_script('sections.js','docs','include');
    RMTemplate::get()->add_local_script('jquery.ui.nestedSortable.js','docs','include');
	xoops_cp_header();
    
    include RMEvents::get()->run_event('docs.get.sections.template', RMTemplate::get()->get_template('admin/rd_sections.php', 'module', 'docs'));
    
	xoops_cp_footer();

}


/**
* @desc Formulario de creación y edición de sección
**/
function rd_show_form($edit=0){
	global $xoopsModule, $xoopsConfig, $xoopsSecurity, $xoopsUser, $xoopsModuleConfig, $rmc_config;
     
    define('RMCSUBLOCATION','newresource');
	$id=rmc_server_var($_GET, 'id', 0);
    $parent=rmc_server_var($_GET, 'parent', 0);
    
    if ($id<=0){
        redirectMsg('sections.php?id='.$id, __('You must select a Document in order to create a new section','docs'),1);
        die();
    }
    
    // Check if provided Document exists
    global $res;
    $res= new RDResource($id);
    if ($res->isNew()){
        redirectMsg('sections.php?id='.$id, __('Specified Document does not exists!','docs'),1);
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
    
    // Get order
    $order = RDFunctions::order('MAX', $parent, $res->id());
    $order++;
    
    $rmc_config = RMFunctions::configs();
    $form=new RMForm('','frmsec','sections.php');
    
    if ($rmc_config['editor_type']=='tiny'){
        $tiny = TinyEditor::getInstance();
        $tiny->add_config('theme_advanced_buttons1', 'rd_refs');
        $tiny->add_config('theme_advanced_buttons1', 'rd_figures');
        $tiny->add_config('theme_advanced_buttons1', 'rd_toc');
    }
    
    $editor = new RMFormEditor('','content','100%','300px',$edit ? $rmc_config['editor_type']=='tiny' ? $sec->getVar('content') : $sec->getVar('content', 'e') : '','', 0);
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
    include RMEvents::get()->run_event('docs.get.secform.template', RMTemplate::get()->get_template('admin/rd_sections_form.php', 'module', 'docs'));
    
	xoops_cp_footer();
}

/**
* @desc Almacena información de las secciones
**/
function rd_save_sections($edit=0){
	global $xoopsUser, $xoopsSecurity;
	
	foreach ($_POST as $k=>$v){
		$$k=$v;
	}
    
	if (!$xoopsSecurity->check()){
		redirectMsg('./sections.php?op=new&id='.$id, __('Session token expired!','docs'), 1);
		die();
	}
    
    if($id<=0){
        redirectMsg('resources.php', __('A Document was not specified!','docs'), 1);
        die();
    }
    
    $res = new RDResource($id);
    if($res->isNew()){
        redirectMsg('resources.php', __('Specified Document does not exists!','docs'), 1);
        die();
    }
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();

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
    
    RMEvents::get()->run_event('docs.saving.section',$sec);
	
	if (!$sec->save()){
		if ($sec->isNew()){
			redirectMsg('./sections.php?action=new&id='.$id, __('Database could not be updated!','docs') . "<br />" . $sec->errors(),1);
			die();			
		}else{
			redirectMsg('./sections.php?action=edit&id='.$id.'&sec='.$id_sec, __('Sections has been saved but some errors ocurred','docs'). "<br />" . $sec->errors(),1);
			die();
		}		

	}else{
        $res->setVar('modified', time());
        $res->save();
        RMEvents::get()->run_event('docs.section.saved',$sec);
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
function rd_delete_sections(){
    global $xoopsModule;
	
    $id = rmc_server_var($_GET, 'id', 0);
	$id_sec = rmc_server_var($_GET, 'sec', 0);
    
    // Check if a Document id has been provided
    if ($id<=0){
        redirectMsg('resources.php', __('You have not specify a Document id','docs'), 1);
        die();
    }
    
    $res = new RDResource($id);
    if($res->isNew()){
        redirectMsg('The specified Document does not exists!','docs');
        die();
    }
    
    // Check if a section id has been provided
	if ($id_sec<=0){
		redirectMsg('./sections.php?id='.$id, __('You have not specified a section ID to delete!','docs'),1);
		die();
	}
		
	$sec=new RDSection($id_sec);
	if ($sec->isNew()){
		redirectMsg('./sections.php?id='.$id, __('Specified section does not exists!','docs'),1);
		die();
	}

	if (!$sec->delete()){
	    redirectMsg('./sections.php?id='.$id, __('Errors ocurred while trying to delete sections!','docs').'<br />'.$sec->errors(),1);
		die();
	}else{
	    redirectMsg('./sections.php?id='.$id, __('Sections deleted successfully!','docs'), 0);
	}
	
}


/**
 * Respuesta en json
 */
function json_response($m,$e=0,$res=0){
    
    global $xoopsLogger;
    $xoopsLogger->renderingEnabled = false;
    error_reporting(0);
    $xoopsLogger->activated = false;
    
    $url = 'sections.php'.($res>0?'?id='.$res:'');
    
    $resp = array(
        'message' => $m,
        'error' => $e,
        'url' => $url
    );
    
    showMessage($m, $e);
    
    echo json_encode($resp);
    die();
    
}
/**
* @desc Modifica el orden de las secciones
**/
function changeOrderSections(){
    global $xoopsSecurity;

    if(!$xoopsSecurity->check())
        json_response(__('Session token expired!','docs'), 1);
    
     parse_str(rmc_server_var($_POST, 'items', ''));
    
    if(empty($list))
        json_response(__('Data not valid!','docs'), 1);
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $res = '';
    
    $pos = 0;
    foreach($list as $id => $parent){
        $parent = $parent=='root' ? 0 : $parent;
        
        if($parent==0 && !is_object($res))
            $res = new RDSection ($id);
        
        $sql = "UPDATE ".$db->prefix("rd_sections")." SET parent=$parent, `order`=$pos WHERE id_sec=$id";
        $db->queryF($sql);
        $pos++;
    }
    
    json_response(__('Sections positions saved!','docs'),0, $res->getVar('id_res'));

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
		rd_save_sections();
		break;
	case 'saveedit':
		rd_save_sections(1);
		break;
	case 'delete':
		rd_delete_sections();
	    break;
        case 'savesort':
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
