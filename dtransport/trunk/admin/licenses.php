<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','licenses');
include ('header.php');

/**
* @desc Visualiza todas las licencias existentes
**/
function showLicences(){
    
    global $xoopsModule, $xoopsSecurity;
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
	$sql="SELECT * FROM ".$db->prefix('dtrans_licences');
	$result=$db->queryF($sql);
    $licences = array();
	while ($rows=$db->fetchArray($result)){

		$lc=new DTLicense();
		$lc->assignVars($rows);

		$licences[] = array(
            'id'=>$lc->id(),
            'name'=>$lc->name(),
            'url'=>$lc->link(),
            'type'=>$lc->type()
        );
        
	}

    RMTemplate::get()->add_xoops_style('admin.css', 'dtransport');
    RMTemplate::get()->add_local_script('jquery.validate.min.js', 'rmcommon', 'include');
    RMTemplate::get()->add_local_script('jquery.checkboxes.js', 'rmcommon', 'include');
    RMTemplate::get()->add_local_script('admin.js', 'dtransport');
    
    RMTemplate::get()->add_head(
        '<script type="text/javascript">
            var dt_message = "'.__('Do you really want to delete selected licenses','dtransport').'";
            var dt_select_message = "'.__('Select at least one licence to delete!','dtransport').'";
        </script>'
    );
    
	DTFunctions::toolbar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('Licences Management','dtransport'));
	xoops_cp_header();
    
    include RMTemplate::get()->get_template('admin/dtrans_licenses.php', 'module', 'dtransport');
    
	xoops_cp_footer();


}


/**
* @desc Formulario de licencias
**/
function formLicences($edit=0){

	global $xoopsModule;

	$id = rmc_server_var($_REQUEST, 'id', 0);

	if ($edit){
		//Verificamos si la licencia es válida
		if ($id<=0){
			redirectMsg('licenses.php', __('You must provide a valid licencse ID!','dtransport'),1);
			die();
		}

		//Verificamos si la licencia existe
		$lc=new DTLicense($id);
		if ($lc->isNew()){
			redirectMsg('licenses.php', __('Specified licence ID does not exists!','dtransport'),1);
			die();
		}

	}


	
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".($edit ? __('Edit Licence','dtransport') : __('New Licence','dtransport')));
	xoops_cp_header();

	$form=new RMForm($edit ? __('Edit Licence','dtransport') : __('New Licence','dtransport'),'frmlic','licenses.php');
	$form->addElement(new RMFormText(__('Licence name','dtransport'),'name',50,150,$edit ? $lc->name() : ''),true);
	$form->addElement(new RMFormText(__('Licence reference URL','dtransport'),'url',50,255,$edit ? $lc->link() : ''));

	$ele=new RMFormSelect(__('Licence type','dtranport'),'type');
	$ele->addOption(0, __('Open source licence','dtransport'),$edit ? ($lc->type()==0 ? 1 : 0) : 0);
	$ele->addOption(1, __('Restrictive licence','dtranport'),$edit ? ($lc->type()==1 ? 1 : 0) : 0);

	$form->addElement($ele);

	$form->addElement(new RMFormHidden('action',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMFormHidden('id',$id));

	$buttons =new RMFormButtonGroup();
	$buttons->addButton('sbt', __('Save Changes','dtransport'),'submit');
	$buttons->addButton('cancel',__('Cancel','stransport'),'button', 'onclick="window.location=\'licenses.php\';"');

	$form->addElement($buttons);
	

	$form->display();

	xoops_cp_footer();

}


/**
* @desc Almacena la informaciónj de las licencias en la base de datos
**/
function saveLicences($edit=0){

	global $xoopsSecurity;

	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	if (!$xoopsSecurity->check()){
		redirectMsg('./licenses.php',__('Session token expired!','dtransport'), 1);
		die();
	}

    $tc = TextCleaner::getInstance();
    $nameid = $tc->sweetstring($name);

    $db = XoopsDatabaseFactory::getDatabaseConnection();

	if ($edit){
		//Verificamos si la licencia es válida
		if ($id<=0){
			redirectMsg('licenses.php',__('You must provide a licence identifier in order to edit its data','dtransport'),1);
			die();
		}

		//Verificamos si la licencia existe
		$lc=new DTLicense($id);
		if ($lc->isNew()){
			redirectMsg('licenses.php', __('Specified licence does not exists!','dtransport'),1);
			die();
		}

		//Comprueba que la licencia no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_licences')." WHERE (name='$name' OR nameid='$nameid') AND id_lic<>".$lc->id();
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('licenses.php?action=edit&id='.$id."&name=$name&url=$url", __('Another licence with same name already exists!','dtransport'),1);	
			die();
		}


	}else{

		//Comprueba que la licencia no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_licences')." WHERE name='$name' OR nameid='$nameid'";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('licenses.php', __('Another licence with same name already exists!','dtransport'),1);	
			die();
		}

		$lc=new DTLicense();
	}

	$lc->setName($name);
    $lc->setNameId($nameid);
	//Verificamos si se proporcionó una url correcta
	$lc->setLink($url);
	$lc->setType($type);

	if (!$lc->save()){
		if (!$lc->isNew()){
			redirectMsg('licenses.php?action=edit&id='.$id."&name=$name&url=$url&type=$type", __('Licence could not be saved! Please try again','dtransport'),1);
			die();
		}
	}else{
		redirectMsg('licenses.php', __('License saved successfully!','dtransport'),0);
		die();
	}
	
}
	

/**
* @desc Elimina la licencia especificada
**/
function deleteLicences(){

	global $xoopsSecurity;

	$ids = rmc_server_var($_REQUEST, 'ids', array());
	
	//Verificamos si nos proporcionaron alguna licencia
	if (empty($ids) || !is_array($ids)){
		redirectMsg('licenses.php', __('No licenses has been specified!','dtransport'),1);
		die();	
	}

	if (!$xoopsSecurity->check()){
	    redirectMsg('licenses.php', __('Session token expired!','dtransport'), 1);
		die();
	}

	$errors='';
	foreach ($ids as $k){

	    //Verificamos si la licnecia es válida
		if ($k<=0){
		    $errors.=sprintf(__('Specified ID for licence does not exists: %s','dtransport'),$k);
			continue;
		}

		//Verificamos si la licencia existe
		$lic=new DTLicense($k);
		if ($lic->isNew()){
		    $errors.=sprintf(__('Sepecified licence does not exists: %s','dtransport'),$k);
			continue;
		}

		if (!$lic->delete()){
		    $errors.=sprintf(__('Licence "%s" could not be deleted!','dtransport'),$lic->name());
		}

	}

	if ($errors!=''){
	    redirectMsg('licenses.php', __('Errors ocurred whilke trying to delete licenses:','dtransport').'<br />'.$errors,1);
	}else{
	    redirectMsg('licenses.php', __('Licences deleted successfully!','dtransport'),0);
	}

}

$action = rmc_server_var($_REQUEST,'action','');

switch ($action){
	case 'new':
		formLicences();
	break;
	case 'edit':
		formLicences(1);
	break;
	case 'save':
		saveLicences();
	break;
	case 'saveedit':
		saveLicences(1);
	break;
	case 'delete':
		deleteLicences();
	break;
	default:
		showLicences();

}
