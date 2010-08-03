<?php
// $Id$
// --------------------------------------------------------------
// Rapid Docs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include ('../../mainfile.php');
include ('header.php');
load_mod_locale('docs');

// Mensajes de Error
$rmc_messages = array();
if (isset($_SESSION['rmMsg'])){
    foreach ($_SESSION['rmMsg'] as $msg){
        $rmc_messages[] = $msg;
    }
    unset($_SESSION['rmMsg']);
}

$id=rmc_server_var($_GET, 'id', 0);
$res=new RDResource($id);
//Verificamos si el usuario tiene permisos de edicion
if (!$xoopsUser){
    redirect_header(XOOPS_URL.'/modules/docs',2,__('You are not allowed to view this page','docs'));
    die();
}else{
    if (!($xoopsUser->uid()==$res->getVar('owner')) && 
    !$res->isEditor($xoopsUser->uid()) && 
    !$xoopsUser->isAdmin() && !$res->isNew()){
        redirect_header(XOOPS_URL.'/modules/docs/figures.php',2,__('You are not allowed to view this page','docs'));
        die();
    }
}

/**
* @desc Visualiza  todas las figuras existentes de una publicación
**/
function Figures(){
    global $rmc_messages, $xoopsTpl, $xoopsSecurity;
    
    define('DF_LOCATION','list');
	$id = rmc_server_var($_GET, 'id', 0);
	$search = rmc_server_var($_GET, 'search', '');
    $rmc_config = RMFunctions::configs();

	//Navegador de páginas
    $db = Database::getInstance();
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('rd_figures')." WHERE id_res=$id";
	$sql1 = '';
	if ($search){
		
		//Separamos la frase en palabras para realizar la búsqueda
		$words = explode(" ",$search);
		
		foreach($words as $k){
			//Verificamos el tamaño de la palabra
			if (strlen($k) <= 2) continue;	
			$sql1.=($sql1=='' ? ' AND ' : " OR "). " `desc` LIKE '%$k%' ";
		}	
	
	}
	list($num)=$db->fetchRow($db->queryF($sql.$sql1));
	
	$page = rmc_server_var($_REQUEST, 'page', 1);
    $page = $page<=0 ? 1 : $page;
    $limit = 13;

    $tpages = ceil($num/$limit);
    $page = $page > $tpages ? $tpages : $page; 

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 4);
    $nav->target_url("?id=$id&amp;page={PAGE_NUM}&amp;search=$search");
    $ruta='?id='.$id.'&page='.$page.'&search='.$search;
	//Fin navegador de páginas

	$sql="SELECT * FROM ".$db->prefix('rd_figures')." WHERE id_res=$id";
	$sql1='';
	if ($search){
		
		//Separamos la frase en palabras para realizar la búsqueda
		$words = explode(" ",$search);
		
		foreach($words as $k){
			//Verificamos el tamaño de la palabra
			if (strlen($k) <= 2) continue;	
			$sql1.=($sql1=='' ? ' AND ' : " OR "). " `desc` LIKE '%$k%' ";
		}	
	
	}
	$sql2=" ORDER BY id_fig DESC LIMIT $start,$limit ";
	
	$result=$db->queryF($sql.$sql1.$sql2);
    $figures = array();
	while ($rows=$db->fetchArray($result)){
		
		$fig = new RDFigure();
		$fig->assignVars($rows);
		$figures[] = array('id'=>$fig->id(),'desc'=>$fig->getVar('desc'));
		
	}
    
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.min.js');
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery-ui.min.js');
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
    RMTemplate::get()->add_script('include/js/scripts.php?file=ajax.js');
    RMTemplate::get()->add_script('include/js/editor-'.$rmc_config['editor_type'].'.js');
    
    $theme_css = xoops_getcss();
    $vars = $xoopsTpl->get_template_vars();
    extract($vars);
    
    if ($rmc_config['editor_type']=='tiny')
        RMTemplate::get()->add_script(XOOPS_URL.'/modules/rmcommon/api/editors/tinymce/tiny_mce_popup.js');
    elseif($rmc_config['editor_type']=='xoops')
        RMTemplate::get()->add_script(XOOPS_URL.'/modules/rmcommon/api/editors/exmcode/editor-popups.js');
    
    RMTemplate::get()->add_style('refs.css','docs');
    RMTemplate::get()->add_style('jquery.css','rmcommon');
    
    // Options for table header
    $options[] = array(
        'title' => __('Select Resource','docs'),
        'href'  => 'javascript:;',
        'attrs' => 'id="option-resource" onclick="docsAjax.getSectionsList(1);"',
        'tip'   => __('Select another resource to show the figures that belong to this.','docs')
    );
    $options[] = array(
        'title' => __('Create Figure','docs'),
        'href'  => "?action=new&amp;id=$id&amp;search=$search&amp;page=$page",
        'attrs' => 'id="option-new-fig"',
        'tip'   => __('Create a new figure','docs')
    );
    // Get additional options from other modules or plugins
    $options = RMEvents::get()->run_event('docs.figures.options',$options, $id);
    
    // Insert adtional content in template
    $other_content = '';
    $other_content = RMEvents::get()->run_event('docs.additional.figures.content', $other_content, $id);
        
	include RMTemplate::get()->get_template('rd_figures.php','module','docs');

}

/**
* @desc Formulario de creación o edición de figuras
**/
function formFigures($edit=0){
	global $xoopsConfig,$xoopsTpl, $xoopsSecurity, $rmc_messages;	
	
    define('DF_LOCATION','form');
	$id = rmc_server_var($_GET, 'id', 0);
	$id_fig = rmc_server_var($_GET, 'fig', 0);
	$page = rmc_server_var($_GET, 'page', 0);
	$search = rmc_server_var($_GET, 'search', 0);
	$ruta='id='.$id.'&page='.$page.'&search='.$search;
    
    $res = new RDResource($id);
    if($res->isNew()){
        redirectMsg('?'.$ruta, __('A resources has not been specified!','docs'), 1);
        die();
    }

	if ($edit){
		//Comprueba que la figura sea válida
		if ($id_fig<=0){
			redirectMsg('./figures.php?'.$ruta, __('No figure has been specified','docs'),1);
			die();
		}

		//Comprueba  si existe la figura		
		$fig=new RDFigure($id_fig);
		if ($fig->isNew()){
			redirectMsg('./figures.php?'.$ruta, __('Sepecified figure does not exists','docs'),1);
			die();	
		}

	}

    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.min.js');
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery-ui.min.js');
    
    $form=new RMForm($edit ? '' : '','frmfig','figures.php');
    $theme_css = xoops_getcss();
    $vars = $xoopsTpl->get_template_vars();
    extract($vars);
    
    RMTemplate::get()->add_style('refs.css','docs');
    RMTemplate::get()->add_style('figures.css','docs');
    RMTemplate::get()->add_style('jquery.css','rmcommon');
    
    $editor = new RMFormEditor('','content','100%','200px',$edit ? $fig->getVar('content','e') : '');
    $rmc_config = RMFunctions::configs();
    
	include RMTemplate::get()->get_template('rd_figures.php','module','docs');

}

/**
* @desc Almacena toda la información referente a la figura
**/
function saveFigures($edit=0){
	global $xoopsSecurity;
    
	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	$ruta='id='.$id.'&pag='.$pag.'&search='.$search;

	if (!$xoopsSecurity->check()){
		redirectMsg('./figures.php?'.$ruta, __('Session token expired!','docs'), 1);
		die();
	}

	//Comprobar publicacion valida
	if ($id<=0){
		redirectMsg('./figures.php?'.$ruta, __('No resource has been selected!','docs'),1);
		die();
	}
	
	//Comprobar publicación existente existente
	$res=new RDResource($id);
	if ($res->isNew()){
		redirectMsg('./figures.php?'.$ruta, __('Specified resource does not exists!'),1);
		die();

	}

	if ($edit){
		//Comprueba que la figura sea válida
		if ($id_fig<=0){
			redirectMsg('./figures.php?'.$ruta,__('No figure has been selected!','docs'),1);
			die();
		}

		//Comprueba  si existe la figura		
		$fig=new RDFigure($id_fig);
		if ($fig->isNew()){
			redirectMsg('./figures.php?'.$ruta,__('Specified figure does not exists!','docs'),1);
			die();	
		}

	}else{

		$fig=new RDFigure();
	}

	$fig->setVar('id_res',$id);
	$fig->setVar('attrs',$attrs);
	$fig->setVar('desc',$desc);
	$fig->setVar('content',$content);
	
	if (!$fig->save()){
		redirectMsg('./figures.php?action=new&'.$ruta, __('Database could not be updated!','docs'),1);
	}else{
		redirectMsg('./figures.php?'.$ruta, __('Database updated successfully!','docs'),0);
	}
	
}


/**
* @desc Elimina figuras
**/
function deleteFigures(){
	global $xoopsSecurity;
    $id = rmc_server_var($_POST, 'id', 0);
    $figs = rmc_server_var($_POST, 'figs', array());
    $page = rmc_server_var($_POST, 'page', 1);
    $search = rmc_server_var($_POST, 'search', '');
    
    $ruta='?id='.$id.'&page='.$page.'&search='.$search;
    if (!$xoopsSecurity->check()){
        redirectMsg($ruta, __('Session token expired!','docs'), 1);
        die();
    }

    if (!is_array($figs)){
        redirectMsg($ruta, __('Select a figure to delete!','docs'),1);
        die();
    }
    
    $db = Database::getInstance();
    $sql = "DELETE FROM ".$db->prefix("pa_figures")." WHERE id_fig IN(".implode(',',$figs).")";
    
    if (!$db->queryF($sql)){
        redirectMsg($ruta, __('Errores ocurred while trying to delete figures.'),1);
    }else{
        redirectMsg($ruta, __('Figures deleted successfully!','docs'),0);
    }

}

$action = rmc_server_var($_REQUEST, 'action', '');

switch ($action){
	case 'new':
		formFigures();
	    break;
	case 'edit':
		formFigures(1);
	    break;
	case 'save':
		saveFigures();
	    break;
	case 'saveedit':
		saveFigures(1);
	    break;
	case 'delete':
		deleteFigures();
	    break;
	default: 
		figures();
        break;
}
