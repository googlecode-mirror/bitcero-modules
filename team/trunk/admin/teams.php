<?php
// $Id$
// --------------------------------------------------------------
// Equipo Club Gallos
// Un modulo sencillo para el manejo de equipos
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','teams');
include 'header.php';

/**
* @desc Muestra todos los equipos registrados
*/
function showTeams(){
	global $db, $tpl, $xoopsModule, $xoopsConfig, $adminTemplate;
	
	$tpl->assign('lang_existing', _AS_TC_EXISTING);
	$tpl->assign('lang_id', _AS_TC_ID);
	$tpl->assign('lang_image',_AS_TC_IMAGE);
	$tpl->assign('lang_name',_AS_TC_NAME);
	$tpl->assign('lang_catego',_AS_TC_CATEGO);
	$tpl->assign('lang_coachs',_AS_TC_COACHS);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_delete',_DELETE);
	$tpl->assign('lang_confirmm', _AS_TC_CONFM);
	$tpl->assign('lang_confirm', _AS_TC_CONFDEL);
	$tpl->assign('lang_date', _AS_TC_DATE);
	$tpl->assign('lang_players', _AS_TC_PLAYERS);
	
	$result = $db->query("SELECT * FROM ".$db->prefix("coach_teams")." ORDER BY name");
	$teams = array();
	while ($row = $db->fetchArray($result)){
		$team = new TCTeam();
		$team->assignVars($row);
		$cat = new TCCategory($team->category());
		$coachs = '';
		foreach ($team->coachs(true) as $coach){
			$coachs .= $coachs =='' ? $coach->name() : '<br />'.$coach->name();
		}
		$teams[] = array('id'=>$team->id(),'name'=>$team->name(),'image'=>$team->image(),
				'catego'=>$cat->name(),'coachs'=>$coachs,'date'=>formatTimestamp($team->created(), 'c'));
	}
	
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_TC_TEAMSLOC);
	xoops_cp_header();
	
	include RMTemplate::get()->get_template("admin/coach_teams.php",'module','team');
	
	xoops_cp_footer();
	
}

function formTeams($edit = 0){
	global $db, $xoopsModule, $mc, $xoopsConfig;
	
	if ($edit){
		$id = TCFunctions::get('id');
		if ($id<=0){
			redirectMsg('teams.php', __('Id not valid','admin_team'), 1);
			die();
		}
		$team = new TCTeam($id);
		if ($team->isNew()){
			redirectMsg('teams.php', __('No existe el equipo especificado','admin_team'), 1);
			die();
		}
	}
	
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./teams.php'>".__('Equipos','admin_team')."</a>
			&raquo; ".($edit ? __('Editar Equipo','admin_team'): __('Crear Equipo','admin_team')));
	xoops_cp_header();
	
	$form = new RMForm($edit ? __('Editar Equipo','admin_team') : __('Crear Equipo','admin_team'), 'frmTeam', 'teams.php');
	$form->setExtra('enctype="multipart/form-data"');
	$form->oddClass('oddForm');
	
	$ele = new RMFormRadio(__('Categoría','admin_team'), 'cat',0,1,1);
	
	$result = $db->query("SELECT * FROM ".$db->prefix("coach_categos")." ORDER BY name");
	while ($row = $db->fetchArray($result)){
		$ele->addOption($row['name'], $row['id_cat'], $edit ? ($team->category()==$row['id_cat'] ? 1 : 0) : 0);
	}
	$form->addElement($ele, true, 'checked');
	$form->addElement(new RMFormText(__('Nombre','admin_team'), 'name', 50, 100, $edit ? $team->name() : ''), true);
	if ($edit) $form->addElement(new RMFormText(__('Nombre corto','admin_team'), 'nameid', 50, 100, $edit ? $team->nameId() : ''));
	$form->addElement(new RMFormFile(__('Imagen','admin_team'), 'image', 46, $mc['filesize']*1024));
	if ($edit && $team->image()!='')
		$form->addElement(new RMFormLabel(__('Imagen actual','admin_team'), '<img src="'.XOOPS_URL.'/uploads/teams/ths/'.$team->image().'" alt="" />'));
	
	$form->addElement(new RMFormEditor(__('Información','admin_team'), 'desc', '90%', '300px', $edit ? $team->desc('e') : ''));
	if ($edit){
		$html = $team->getVar('dohtml');
		$xcode = $team->getVar('doxcode');
		$doimage = $team->getVar('doimage');
		$smiley = $team->getVar('dosmiley');
		$br = $team->getVar('dobr');
	} else {
		$html = $xoopsConfig['editor_type']=='tiny' ? 1 : 0;
		$xcode = $xoopsConfig['editor_type']=='tiny' ? 0 : 1;
		$doimage = $xoopsConfig['editor_type']=='tiny' ? 0 : 1;
		$smiley = $xoopsConfig['editor_type']=='tiny' ? 0 : 1;
		$br = $xoopsConfig['editor_type']=='tiny' ? 0 : 1;
	}
	$form->addElement(new RMFormTextOptions(__('Opciones','admin_team'), $html, $xcode, $doimage, $smiley, $br));
	$form->addElement(new RMFormSubTitle(__('Entrenadores','admin_team'), 1));
	$ele = new RMFormCheck(__('Entrenadores','admin_team'));
	$ele->asTable(3);
	$result = $db->query("SELECT * FROM ".$db->prefix("coach_coachs")." ORDER BY name");
	if ($edit) $coachs = $team->coachs(false);
	while ($row = $db->fetchArray($result)){
		$coach = new TCCoach();
		$coach->assignVars($row);
		$ele->addOption($coach->name(), 'coachs[]', $coach->id(), $edit ? (in_array($coach->id(), $coachs) ? 1 : 0) : 0);
	}
	$form->addElement($ele);
	
	$ele = new RMFormButtonGroup();
	$ele->addButton('sbt', __('Enviar','admin_team'), 'submit');
	$ele->addButton('cancel', __('Cancelar','admin_team'), 'button','onclick="window.location=\'teams.php\';"');
	$form->addElement($ele);
	$form->addElement(new RMFormHidden('op',$edit ? 'saveedit' : 'save'));
	if ($edit) $form->addElement(new RMFormHidden('id', $team->id()));
	
	$form->display();
	
	xoops_cp_footer();
}

function saveTeam($edit = 0){
	global $db, $mc, $xoopsSecurity;
	
	$nameid = '';
	$coachs = array();
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if (!$xoopsSecurity->validateToken()){
		redirectMsg('teams.php'.($edit ? "?op=edit&id=$id" : "?op=new"), __('Identficador de sesión expiró','admin_team'), 1);
		break;
	}
	
	if ($edit){
		$id = TCFunctions::post('id');
		if ($id<=0){
			die();
			redirectMsg('teams.php', __('Id no válido','admin_team'), 1);
			die();
		}
		$team = new TCTeam($id);
		if ($team->isNew()){
			redirectMsg('teams.php', __('No existe el equipo especificado','admin_team'), 1);
			die();
		}
		
		$i = 0;
		do{
			$nameid = $nameid!='' && $i==0 ? $nameid : ($util->sweetstring($name).($i>0 ? $i : ''));
			$sql = "SELECT COUNT(*) FROM ".$db->prefix("coach_teams")." WHERE nameid='$nameid' AND id_team<>'".$team->id()."'";
			list($num) = $db->fetchRow($db->query($sql));
			$i++;
		} while($num>0);
		
		$sql = "SELECT COUNT(*) FROM ".$db->prefix("coach_teams")." WHERE name='$name' AND cat=$cat AND id_team<>'".$team->id()."'";
		list($num) = $db->fetchRow($db->query($sql));
		if ($num>0){
			redirectMsg('teams.php?op=edit&id='.$coach->id(), __('Ya existe un equipo con el mismo nombre','admin_team'), 1);
			die();
		}
		
	} else {
	
		$team = new TCTeam();
		
		$i = 0;
		do{
			$nameid = TextCleaner::getInstance()->sweetstring($name).($i>0 ? $i : '');
			$sql = "SELECT COUNT(*) FROM ".$db->prefix("coach_teams")." WHERE nameid='$nameid'";
			list($num) = $db->fetchRow($db->query($sql));
			$i++;
		} while($num>0);
		
		$sql = "SELECT COUNT(*) FROM ".$db->prefix("coach_teams")." WHERE name='$name' AND cat='$cat'";
		list($num) = $db->fetchRow($db->query($sql));
		if ($num>0){
			redirectMsg('teams.php?op=new', __('Ya existe un equipo con el mismo nombre','admin_team'), 1);
			die();
		}
		
	}
	
	// Cargamos la imágen
	include_once RMCPATH.'/class/uploader.php';
	$up = new RMFileUploader(XOOPS_UPLOAD_PATH.'/teams', $mc['filesize']*1024, array('jpg','png','gif'));
	
	if ($up->fetchMedia('image')){

	
		if (!$up->upload()){
			if ($edit){
				redirectMsg('./teams.php?op=new',$up->getErrors(), 1);
				die();
			}else{
				redirectMsg('./teams.php?op=edit&id='.$team->id(),$up->getErrors(), 1);
				die();
			}
		}
					
		if ($edit && $team->image()!=''){
			@unlink(XOOPS_UPLOAD_PATH.'/teams/'.$team->image());
			@unlink(XOOPS_UPLOAD_PATH.'/teams/ths/'.$team->image());
		}

		$filename = $up->getSavedFileName();
		$fullpath = $up->getSavedDestination();
		// Redimensionamos la imagen
		$redim = new RMImageResizer($fullpath, $fullpath);
		$redim->resizeWidth($mc['img_size']);
		$redim->setTargetFile(XOOPS_UPLOAD_PATH."/teams/ths/$filename");
		$redim->resizeWidth($mc['th_size']);	
		
	} else {
		
		$filename = $edit ? $team->image() : '';
		
	}
	
	$team->setCategory($cat);
	$team->setName($name);
	$team->setNameId($nameid);
	$team->setDesc($desc);
	$team->setImage($filename);
	if (!$edit) $team->setCreated(time());
	$team->setCoachs($coachs);
	
	$team->setVar('dohtml', isset($dohtml) ? 1 : 0);
	$team->setVar('doxcode', isset($doxcode) ? 1 : 0);
	$team->setVar('doimage', isset($doimage) ? 1 : 0);
	$team->setVar('dosmiley', isset($dosmiley) ? 1 : 0);
	$team->setVar('dobr', isset($dobr) ? 1 : 0);
	
	if ($team->save()){
		redirectMsg('teams.php', __('Base de datos actualizada correctamente','admin_team'), 0);
	} else {
		redirectMsg('teams.php?op='.($edit ? "edit&id=".$team->id() : "new"), __('No se pudo actualizar la base de datos','admin_team') . '<br />' . $team->errors(), 0);
	}
	
}

function deleteTeams(){
	global $db;
	
	$teams = TCFunctions::request('teams');
	if (empty($teams)){
		redirectMsg('teams.php', __('No has seleccionado ningún equipo','admin_team'), 1);
		die();
	}
	
	foreach ($teams as $k){
		$team = new TCTeam($k);
		$team->delete();
	}
	
	redirectMsg('teams.php', __('Base de datos actualizada correctamente','admin_team'), 0);
}

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch($op){
	case 'new':
		formTeams();
		break;
	case 'save':
		saveTeam();
		break;
	case 'edit':
		formTeams(1);
		break;
	case 'saveedit':
		saveTeam(1);
		break;
	case 'delete':
		deleteTeams();
		break;
	default:
		showTeams();
		break;
}
