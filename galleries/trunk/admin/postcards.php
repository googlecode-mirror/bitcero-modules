<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION', 'postcards');
include 'header.php';


/**
* @desc Visualiza todas las postales existentes
**/
function showPostCards(){
	global $xoopsModule, $tpl, $xoopsModuleConfig, $xoopsSecurity;

	$mc =& $xoopsModuleConfig;

	$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$limit = $limit<=0 ? 15 : $limit;
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
	//Barra de Navegación
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_postcards');
	
	list($num)=$db->fetchRow($db->query($sql));
    
    list($num)=$db->fetchRow($db->query($sql));
    $start = $num<=0 ? 0 : ($page-1) * $limit;
    $tpages =ceil($num / $limit);
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url("postcards.php?page={PAGE_NUM}&limit=$limit");
	//Fin de barra de navegación


	$sql = "SELECT * FROM ".$db->prefix('gs_postcards');
	$sql.= " LIMIT $start,$limit";
	$result = $db->query($sql);
    $posts = array();
    
    $tf = new RMTimeFormatter(0, __('%M% %d%, %Y% - %h%:%i%:%s%','galleries'));
    
	while ($rows = $db->fetchArray($result)){
		$post = new GSPostcard();
		$post->assignVars($rows);
		
		$link = GSFunctions::get_url().($mc['urlmode'] ? 'postcard/view/id/'.$post->code().'/' : '?postcard=view&amp;id='.$post->code());
	
		$posts[] = array(
            'id'=>$post->id(),
            'title'=>$post->title(),
            'date'=>$tf->format($post->date()),
		    'toname'=>$post->toName(),
            'name'=>$post->email(),
            'view'=>$post->viewed(),
            'link'=>$link
        );	
	
	}
	
    GSFunctions::toolbar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('E-cards','galleries'));
	xoops_cp_header();
    
    RMTemplate::get()->add_local_script('gsscripts.php?file=sets&form=frm-postcards&', 'galleries');
    RMTemplate::get()->add_head("<script type='text/javascript'>\nvar delete_warning='".__('Do you really wish to delete selected e-cards?','galleries')."';\n</script>");
    include RMTemplate::get()->get_template('admin/gs_postcards.php','module','galleries');
    
	xoops_cp_footer();

}


/**
* @desc Elimina de la base de datos las postales especificadas
**/
function deletePostCards(){

	global $util, $xoopsModule, $xoopsSecurity;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	
	//Verificamos si nos proporcionaron al menos una postal para eliminar
	if (!is_array($ids)){
		redirectMsg('./postcards.php?pag='.$page.'&limit='.$limit,__('You must select one e-card at least!','galleries'),1);
		die();
	}
    
    if(!$xoopsSecurity->check()){
        redirectMsg('./postcards.php?pag='.$page.'&limit='.$limit,__('Session token expired!','galleries'),1);
        die();
    }

	$errors = '';
	foreach ($ids as $k){
	    //Verificamos si la postal sea válida
		if($k<=0){
		    $errors .= sprintf(__('The ID "%s" is not valid!','galleries'), $k);
			continue;			
		}

		//Verificamos si la postal exista
		$post = new GSPostcard($k);
		if ($post->isNew()){
		    $errors .= sprintf(__('E-Card with id "%s" does not exists!','galleries'), $k);
			continue;
		}	

		if(!$post->delete()){
		    $errors .= sprintf(__('E-Card "%s" could not be deleted!','galleries'), $k);
		}
	}

	if($errors!=''){
	    redirectMsg('./postcards.php?pag='.$page.'&limit='.$limit,__('Errors ocurred while trying to delete e-cards','galleries').$errors,1);
		die();
	}else{
	    redirectMsg('./postcards.php?pag='.$page.'&limit='.$limit, __('E-Cards deleted successfully!','galleries'),0);
	    die();
	}
		
}


$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch($op){
	case 'delete':
		deletePostCards();
	break;
	default:
		showPostCards();
		break;
}
