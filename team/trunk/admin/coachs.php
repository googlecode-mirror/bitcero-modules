<?php
// $Id$
// --------------------------------------------------------------
// Equipo Club Gallos
// Un modulo sencillo para el manejo de equipos
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','coachs');
include 'header.php';

function showCoachs(){
	global $xoopsModule, $tpl, $db;
	
	$result = $db->query("SELECT * FROM ".$db->prefix("coach_coachs")." ORDER BY name");
	$coachs = array();
	while ($row = $db->fetchArray($result)){
		$coach = new TCCoach();
		$coach->assignVars($row);
		$coachs[] = array('id'=>$coach->id(),'name'=>$coach->name(),'image'=>$coach->image(),'created'=>$coach->created());
	}
	
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_TC_COACHSLOC);
	xoops_cp_header();
	
	include RMTemplate::get()->get_template("admin/coach_coachs.php", 'module', 'team');
	xoops_cp_footer();
	
}

function formCoachs($edit=0){
	global $xoopsModule, $db, $mc, $xoopsConfig;
	
	if ($edit){
		$id = TCFunctions::get('id');
		if ($id<=0){
			redirectMsg('coachs.php', __('Id no válido','admin_team'), 1);
			die();
		}
		$coach = new TCCoach($id);
		if ($coach->isNew()){
			redirectMsg('coachs.php', __('El entrenador especificado no existe','admin_team'), 1);
			die();
		}
	}
	
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./coachs.php'>".__('Entrenadores','admin_team')."</a> &raquo; ".($edit ? __('Editar entrenador','admin_team') : __('Crear entrenador','admin_team')));
	$cHead = '<link href="'.TC_URL.'/styles/admin.css" media="all" rel="stylesheet" type="text/css" />';
	xoops_cp_header($cHead);
	
	$form = new RMForm($edit ? __('Editar Entrenador','admin_team') : __('Crear Entrenador','admin_team'), 'frmNew', 'coachs.php', 'post');
	$form->oddClass('oddForm');
	$form->setExtra('enctype="multipart/form-data"');
		
	$form->addElement(new RMFormText(__('Nombre','admin_team'), 'name', 50, 150, $edit ? $coach->name() : ''), true);
	if ($edit) $form->addElement(new RMFormText(__('Nombre corto','admin_team'), 'nameid', 50, 150, $coach->nameId()));
	$form->addElement(new RMFormText(__('Cargo','admin_team'), 'role', 50, 150, $edit ? $coach->role() : ''), true);
	$form->addElement(new RMFormFile(__('Imagen','admin_team'), 'image', 45, $mc['filesize']*1024));
	if ($edit && $coach->image()!=''){
		$form->addElement(new RMFormLabel(__('Imagen actual','admin_team'), "<img src='".XOOPS_URL."/uploads/teams/coachs/ths/".$coach->image()."' alt='' />"));
	}
	$form->addElement(new RMFormEditor(__('Biografía','admin_team'), 'bio', '90%','300px', $edit ? $coach->bio('e'): ''));
	
	$form->addElement(new RMFormSubTitle(__('Equipos','admin_team'), 1));
	$ele = new RMFormCheck(__('Seleccionar equipos','admin_team'));
	$ele->asTable(3);
	$sql = "SELECT * FROM ".$db->prefix("coach_teams")." ORDER BY name";
	$result = $db->query($sql);
	if ($edit) $teams = $coach->teams(false);
	while ($row = $db->fetchArray($result)){
		$team = new TCTeam();
		$team->assignVars($row);
		$cat =& $team->category(true);
		$ele->addOption($team->name()." <span class='coachNameCat'>(".$cat->name().")</span>", 'teams[]', $team->id(), $edit ? (in_array($team->id(), $teams) ? 1 : 0) : 0);
	}
	$form->addElement($ele);
	
	$ele = new RMFormButtonGroup();
	$ele->addButton('sbt',__('Enviar','admin_team'),'submit');
	$ele->addButton('cancel',__('Cancelar','admin_team'),'button','onclick="window.location=\'coachs.php\';"');
	$form->addElement($ele);
	$form->addElement(new RMFormHidden('op',$edit ? 'saveedit' : 'save'));
	if ($edit) $form->addElement(new RMFormHidden('id', $id));
	$form->display();
	
	xoops_cp_footer();
	
}

function saveCoach($edit = 0){
	global $db, $mc, $xoopsSecurity;
	
	$nameid = '';
	$teams = array();
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if (!$xoopsSecurity->check()){
		redirectMsg('coachs.php'.($edit ? "?op=edit&id=$id" : "?op=new"), __('El identificador de sesión ha expirado','admin_team'), 1);
		break;
	}
	
	if ($edit){
		$id = TCFunctions::post('id');
		if ($id<=0){
			redirectMsg('coachs.php', __('Id no válido','admin_team'), 1);
			die();
		}
		$coach = new TCCoach($id);
		if ($coach->isNew()){
			redirectMsg('coachs.php', __('El entrenador especificado no existe','admin_team'), 1);
			die();
		}
		
		$i = 0;
		do{
			$nameid = $nameid!='' && $i==0 ? $nameid : ($util->sweetstring($name).($i>0 ? $i : ''));
			$sql = "SELECT COUNT(*) FROM ".$db->prefix("coach_coachs")." WHERE nameid='$nameid' AND id_coach<>'".$coach->id()."'";
			list($num) = $db->fetchRow($db->query($sql));
			$i++;
		} while($num>0);
		
		$sql = "SELECT COUNT(*) FROM ".$db->prefix("coach_coachs")." WHERE name='$name' AND id_coach<>'".$coach->id()."'";
		list($num) = $db->fetchRow($db->query($sql));
		if ($num>0){
			redirectMsg('coachs.php?op=edit&id='.$coach->id(), __('Ya existe un entrenador con ese nombre','admin_team'), 1);
			die();
		}
		
	} else {
	
		$coach = new TCCoach();
		
		$i = 0;
		do{
			$nameid = TextCleaner::getInstance()->sweetstring($name).($i>0 ? $i : '');
			$sql = "SELECT COUNT(*) FROM ".$db->prefix("coach_coachs")." WHERE nameid='$nameid'";
			list($num) = $db->fetchRow($db->query($sql));
			$i++;
		} while($num>0);
		
		$sql = "SELECT COUNT(*) FROM ".$db->prefix("coach_coachs")." WHERE name='$name'";
		list($num) = $db->fetchRow($db->query($sql));
		if ($num>0){
			redirectMsg('coachs.php?op=new&id='.$cat->id(), __('Ya existe un entreandor con el mismo nombre','admin_team'), 1);
			die();
		}
		
	}
	
	// Cargamos la imágen
	include_once RMCPATH.'/class/uploader.php';
	$up = new RMFileUploader(XOOPS_UPLOAD_PATH.'/teams/coachs', $mc['filesize']*1024, array('jpg','png','gif'));
	
	if ($up->fetchMedia('image')){

	
		if (!$up->upload()){
			if ($edit){
				redirectMsg('./coachs.php?op=new',$up->getErrors(), 1);
				die();
			}else{
				redirectMsg('./coachs.php?op=edit&id='.$coach->id(),$up->getErrors(), 1);
				die();
			}
		}
					
		if ($edit && $coach->image()!=''){
			@unlink(XOOPS_UPLOAD_PATH.'/teams/coachs/'.$coach->image());
			@unlink(XOOPS_UPLOAD_PATH.'/teams/coachs/ths/'.$coach->image());
		}

		$filename = $up->getSavedFileName();
		$fullpath = $up->getSavedDestination();
		// Redimensionamos la imagen
		$redim = new RMImageResizer($fullpath, $fullpath);
		$redim->resizeWidth($mc['img_size']);
		$redim->setTargetFile(XOOPS_UPLOAD_PATH."/teams/coachs/ths/$filename");
		switch ($mc['resize_method']){
			case 1:
				//Recortar miniatura
				$redim->resizeAndCrop($mc['th_size'],$mc['th_size']);
				break;	
			case 0: 
				$redim->resizeWidth($mc['th_size']);
				break;			
		}

	} else {
		
		$filename = $edit ? $coach->image() : '';
		
	}
	
	$coach->setName($name);
	$coach->setNameId($nameid);
	$coach->setBio($bio);
	$coach->setImage($filename);
	$coach->setRole($role);
	$coach->setTeams($teams);
	if (!$edit) $coach->setCreated(time());
	
	if ($coach->save()){
		redirectMsg('coachs.php', __('Base de datos actualizada correctamente','admin_template'), 0);
	} else {
		redirectMsg('coachs.php?op='.($edit ? 'edit&id='.$coach->id() : 'new'), __('Error al actualizar la base de datos','admin_template') .'<br />'.$coach->errors());
	}
	
}

function deleteCoachs(){
	global $db;
	
	$coachs = TCFunctions::request('coachs');
	if (empty($coachs)){
		redirectMsg('coachs.php', __('Selecciona al menos un entrenador','admin_template'), 1);
		die();
	}
	
	foreach ($coachs as $k){
		$coach = new TCCoach($k);
		$coach->delete();
	}
	
	redirectMsg('coachs.php', __('Base de datos actulizada correctamente','admin_template'), 0);
	
}

$op = TCFunctions::request('op');

switch($op){
	case 'new':
		formCoachs();
		break;
	case 'save':
		saveCoach();
		die();
	case 'delete':
		deleteCoachs();
		break;
	case 'edit':
		formCoachs(1);
		break;
	case 'saveedit':
		saveCoach(1);
		break;
	default:
		showCoachs();
		break;
}
