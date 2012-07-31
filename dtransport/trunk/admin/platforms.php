<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','platforms');
include ('header.php');


/**
* @desc Visualiza las plataformas existentes y muestra formulario de plataformas
**/
function showPlatforms($edit=0){
    global $xoopsSecurity, $xoopsModule;

	$id = rmc_server_var($_REQUEST, 'id', 0);

    $db = XoopsDatabaseFactory::getDatabaseConnection();    
	$sql="SELECT * FROM ".$db->prefix('dtrans_platforms');
	$result=$db->queryF($sql);
    $platforms = array();
	while($rows=$db->fetchArray($result)){

		$plat=new DTPlatform();
		$plat->assignVars($rows);

		$platforms[] = array(
            'id'=>$plat->id(),
            'name'=>$plat->name()
        );
	}

	if ($edit){

		//Verificamos si plataforma es válida
		if ($id<=0){
			redirectMsg('platforms.php', __('You must specified a valid platform ID!','dtransport'),1);
			die();
		}

		//Verificamos si plataforma existe
		$plat=new DTPlatform($id);
		if ($plat->isNew()){
			redirectMsg('platforms.php', __('Sepecified platform does not exists!','dtransport'),1);
			die();
		}
	}
	
    RMTemplate::get()->add_xoops_style('admin.css', 'dtransport');
    RMTemplate::get()->add_local_script('jquery.validate.min.js', 'rmcommon', 'include');
    RMTemplate::get()->add_local_script('jquery.checkboxes.js', 'rmcommon', 'include');
    RMTemplate::get()->add_local_script('admin.js', 'dtransport');
    
    RMTemplate::get()->add_head(
        '<script type="text/javascript">
            var dt_message = "'.__('Do you really want to delete selected platforms','dtransport').'";
            var dt_select_message = "'.__('Select at least one platform to delete!','dtransport').'";
        </script>'
    );
    
	DTFunctions::toolbar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".($edit ? __('Edit Platform','dtransport') : __('New Platform','dtransport')));
	xoops_cp_header();
  
    include RMTemplate::get()->get_template('admin/dtrans_platforms.php', 'module', 'dtransport');
	
	xoops_cp_footer();

}


/**
* @desc Almacena la información de las plataformas
**/
function savePlatforms($edit=0){
	global $xoopsSecurity;

	foreach ($_POST as $k=>$v){
		$$k=$v;
	}
    
    if(!$xoopsSecurity->check()){
        redirectMsg('Session token expired!','dtransport');
    }
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();

    $tc = TextCleaner::getInstance();
    $nameid = $tc->sweetstring($name);

	if ($edit){

		//Verificamos si plataforma es válida
		if ($id<=0){
			redirectMsg('platforms.php', __('You must specify a valid platform ID!','dtrasnport'),1);
			die();
		}

		//Verificamos si plataforma existe
		$plat=new DTPlatform($id);
		if ($plat->isNew()){
			redirectMsg('platforms.php', __('Specified platform does not exists!','dtransport'),1);
			die();
		}

		//Comprueba que la plataforma no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_platforms')." WHERE (name='$name' OR nameid='$nameid') AND id_platform<>".$plat->id();
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('platforms.php', __('Another platform with same name already exists!','dtransport'),1);	
			die();
		}


	}else{

		//Comprueba que la plataforma no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_platforms')." WHERE name='$name' OR nameid='$nameid'";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('platforms.php', __('Another platform with same name already exists!','dtransport'),1);	
			die();
		}
	
		$plat=new DTPlatform();
        
	}
    
	$plat->setName($name);
    $plat->setNameId($nameid);

	if (!$plat->save()){
		redirectMsg('platforms.php', __('Database could not be updated!','dtransport').'<br />'.$plat->errors(),1);
		die();
	}else{
		redirectMsg('./platforms.php', __('Platform saved successfully!','dtransport'),0);
		die();
	}


}



/**
* @desc Elimina la plataforma especificada
**/
function deletePlatforms(){

	global $xoopsModule,$xoopsSecurity;

	$ids = rmc_server_var($_POST, 'ids', array());

	//Verificamos si nos proporcionaron alguna plataforma
	if (!is_array($ids) || empty($ids)){
		redirectMsg('platforms.php', __('You must select at least one platform to delete!','dtransport'),1);
		die();	
	}
	
	if (!$xoopsSecurity->check()){
	    redirectMsg('platforms.php', __('Session token expired','dtransport'), 1);
		die();
	}

	$errors='';
	foreach ($ids as $k){		
	    //Verificamos si la plataforma es válida
		if ($k<=0){
		    $errors .= sprintf(__('Invalid platform ID: %s','dtransport'),$k);
			continue;	
		}

		//Verificamos si la plataforma existe
		$plat=new DTPlatform($k);
		if ($plat->isNew()){
		    $errors .= sprintf(__('Does nto exists platform with ID %s','dtransport'),$k);
			continue;			
		}

		if (!$plat->delete()){
		    $errors .= sprintf(__('Platform "%s" could not be deleted!','dtransport'),$plat->name());
		}

	}

	if ($errors!='')
	    redirectMsg('platforms.php', __('Errors ocurred while trying to delete platforms:','dtransport').'<br />'.$errors,1);
	else
	    redirectMsg('platforms.php', __('Platforms deleted successfully!','dtransport'),0);

}


$action = rmc_server_var($_REQUEST, 'action', '');

switch ($action){
	case 'edit':
		showPlatforms(1);
	    break;
	case 'save':
		savePlatforms();
	    break;
	case 'saveedit':
		savePlatforms(1);
	    break;
	case 'delete':
		deletePlatforms();
	    break;
	default:
		showPlatforms();

}
