<?php
// $Id$
// --------------------------------------------------------------
// Equipo Club Gallos
// Un modulo sencillo para el manejo de equipos
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','players');
include 'header.php';


function showPlayers(){
	global $xoopsModule, $mc, $adminTemplate, $tpl, $db;
	
	$gteam = TCFunctions::get('team');
	$team = new TCTeam($gteam);
	
	// Equipos
	$tpl->assign('team', $team->isNew() ? 0 : $team->id());
	$result = $db->query("SELECT * FROM ".$db->prefix("coach_teams")." ORDER BY name");
	$teams = array();
	while ($row = $db->fetchArray($result)){
		$ct = new TCTeam();
		$ct->assignVars($row);
		$cat =& $ct->category(true);
		$teams[] = array('id'=>$ct->id(),'name'=>$ct->name()." (".$cat->name().")");
	}
	
	// Entrenadores
    $coachs = array();
	if (!$team->isNew()){
		foreach ($team->coachs(true) as $coach){
			$coachs[] = array('id'=>$coach->id(),'name'=>$coach->name(),'image'=>$coach->image());
		}
	}
	
	// Jugadores
	$result = $db->query("SELECT * FROM ".$db->prefix("coach_players")." WHERE team='".$team->id()."'");
    $players = array();
	while ($row = $db->fetchArray($result)){
		$player = new TCPlayer();
		$player->assignVars($row);
		$players[] = array('id'=>$player->id(),'name'=>$player->name(),'image'=>$player->image(),
			'number'=>$player->number(),'age'=>$player->age(),'date'=>formatTimestamp($player->date(), 'c'));
	}
	
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('Jugadores','admin_team'));
	xoops_cp_header();
	
	include RMTemplate::get()->get_template("admin/coach_players.php",'module','team');
	xoops_cp_footer();
	
}

function formPlayer($edit = 0){
	global $xoopsModule, $mc, $xoopsConfig, $rmc_config;
	
	$idteam = TCFunctions::request('team');
	if ($idteam<=0){
		redirectMsg('teams.php', __('Selecciona un equipo antes de crear jugadores','admin_team'), 1);
		die();
	}
	
	$team = new TCTeam($idteam);
	if($team->isNew()){
		redirectMsg('teams.php', __('El equipo seleccionado no existe','admin_team'), 1);
		die();
	}
	
	if ($edit){
		$id = TCFunctions::get('id');
		if ($id<=0){
			redirectMsg('players.php?team='.$idteam, __('El id del jugador no es válido','admin_team'), 1);
			die();
		}
		
		$player = new TCPlayer($id);
		if ($player->isNew()){
			redirectMsg('players.php?team='.$isteam, __('El jugador seleccionado no existe','admin_team'), 1);
			die();
		}
	}
	
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./players.php?team=$idteam'>".__('Jugadores','admin_team')."</a> &raquo; ".($edit ? __('Editar jugador','admin_team') : __('Crear jugador','admin_team')));
	xoops_cp_header();
	
	$form = new RMForm($edit ? __('Editar Jugador','admin_team') : __('Crear Jugador','admin_team'), 'frmNew', 'players.php');
	$form->oddClass('oddForm');
	$form->setExtra('enctype="multipart/form-data"');
	$form->addElement(new RMFormLabel(__('Equipo','admin_team'), "<h2 style='margin: 0;'>".$team->name()."</h2>"));
	$form->addElement(new RMFormText(__('Nombre del jugador'), 'name', 50, 100, $edit ? $player->name() : ''), true);
	if ($edit) $form->addElement(new RMFormText(__('Nombre corto','admin_team'), 'nameid', 50, 100, $player->nameId()));
	
	$ele = new RMFormDate(__('Fecha de nacimiento','admin_team'), 'birth', $edit ? $player->birth() : null);
	$form->addElement($ele);
	$form->addElement(new RMFormText(__('Número','admin_team'), 'number', 5, 3, $edit ? $player->number() : ''), true, 'num');
	$form->addElement(new RMFormFile(__('Imagen','admin_team'), 'image', 45));
	if ($edit && $player->image()!='') 
		$form->addElement(new RMFormLabel(__('Imagen actual','admin_team'), "<img src='".XOOPS_URL."/uploads/teams/players/ths/".$player->image()."' alt='' />"));
	
	$form->addElement(new RMFormEditor(__('Información','admin_team'), 'bio', '90%', '300px', $edit ? $player->bio('e') : ''));
	if ($edit){
		$html = $player->getVar('dohtml');
		$xcode = $player->getVar('doxcode');
		$doimage = $player->getVar('doimage');
		$smiley = $player->getVar('dosmiley');
		$br = $player->getVar('dobr');
	} else {
		$html = $rmc_config['editor_type']=='tiny' ? 1 : 0;
		$xcode = $rmc_config['editor_type']=='tiny' ? 0 : 1;
		$doimage = $rmc_config['editor_type']=='tiny' ? 0 : 1;
		$smiley = $rmc_config['editor_type']=='tiny' ? 0 : 1;
		$br = $rmc_config['editor_type']=='tiny' ? 0 : 1;
	}
	$form->addElement(new RMFormTextOptions(__('Opciones','admin_team'), $html, $xcode, $doimage, $smiley, $br));
	$ele = new RMFormButtonGroup();
	$ele->addButton('sbt', __('Enviar','admin_team'), 'submit');
	$ele->addButton('cancel', __('Cancelar','admin_team'), 'button', 'onclick="window.location=\'players.php?team='.$team->id().'\';"');
	$form->addElement($ele);
	$form->addElement(new RMFormHidden('op', $edit ? 'saveedit' : 'save'));
	if ($edit) $form->addElement(new RMFormHidden('id', $id));
	$form->addElement(new RMFormHidden('team', $idteam));
	
	$form->display();
	
	xoops_cp_footer();
}

function savePlayer($edit = 0){
	global $db, $mc, $xoopsSecurity;
	
	$nameid = '';
	$teams = array();
	$idteam = 0;
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if (!$xoopsSecurity->validateToken()){
		redirectMsg('players.php?team='.$idteam.($edit ? "&op=edit&id=$id" : "&op=new"), __('El identificador se sesión ha expirado','admin_team'), 1);
		break;
	}
	
	$idteam = TCFunctions::request('team');

	if ($idteam<=0){
		redirectMsg('teams.php', __('No se ha especificado un equipo','admin_team'), 1);
		die();
	}
	
	$team = new TCTeam($idteam);
	
	if($team->isNew()){
		redirectMsg('teams.php', __('El equipo especificado no existe','admin_team'), 1);
		die();
	}
	
	if ($edit){
		$id = TCFunctions::post('id');
		if ($id<=0){
			redirectMsg('players.php?team='.$idteam, __('No se ha especificado un jugador para editar','admin_team'), 1);
			die();
		}
		$player = new TCPLayer($id);
		if ($player->isNew()){
			redirectMsg('players.php?team='.$idteam, __('El jugador especificado no existe','admin_team'), 1);
			die();
		}
		
		$i = 0;
		do{
			$nameid = $nameid!='' && $i==0 ? $nameid : ($util->sweetstring($name).($i>0 ? $i : ''));
			$sql = "SELECT COUNT(*) FROM ".$db->prefix("coach_players")." WHERE nameid='$nameid' AND id_play<>'".$player->id()."'";
			list($num) = $db->fetchRow($db->query($sql));
			$i++;
		} while($num>0);
		
		$sql = "SELECT COUNT(*) FROM ".$db->prefix("coach_players")." WHERE name='$name' AND id_play<>'".$player->id()."'";
		list($num) = $db->fetchRow($db->query($sql));
		if ($num>0){
			redirectMsg('players.php?team='.$idteam.'&op=edit&id='.$player->id(), __('Ya existe otro jugador con el mismo nombre','admin_team'), 1);
			die();
		}
		
	} else {
	
		$player = new TCPlayer();
		
		$i = 0;
		do{
			$nameid = TextCleaner::getInstance()->sweetstring($name).($i>0 ? $i : '');
			$sql = "SELECT COUNT(*) FROM ".$db->prefix("coach_players")." WHERE nameid='$nameid'";
			list($num) = $db->fetchRow($db->query($sql));
			$i++;
		} while($num>0);
		
		$sql = "SELECT COUNT(*) FROM ".$db->prefix("coach_players")." WHERE name='$name'";
		list($num) = $db->fetchRow($db->query($sql));
		if ($num>0){
			redirectMsg('players.php?team='.$idteam.'&op=new&id='.$cat->id(), __('Ya existe otro jugador con el mismo nombre','admin_team'), 1);
			die();
		}
		
	}
	
	// Cargamos la imágen
	include_once RMCPATH.'/class/uploader.php';
	$up = new RMFileUploader(XOOPS_UPLOAD_PATH.'/teams/players', $mc['filesize']*1024, array('jpg','png','gif'));
	
	if ($up->fetchMedia('image')){

	
		if (!$up->upload()){
			if ($edit){
				redirectMsg('./players.php?team='.$idteam.'&op=edit&id='.$player->id(),$up->getErrors(), 1);
				die();
			}else{
				redirectMsg('./players.php?team='.$idteam.'&op=new',$up->getErrors(), 1);
				die();
			}
		}
					
		if ($edit && $player->image()!=''){
			@unlink(XOOPS_UPLOAD_PATH.'/teams/players/'.$player->image());
			@unlink(XOOPS_UPLOAD_PATH.'/teams/players/ths/'.$player->image());
		}

		$filename = $up->getSavedFileName();
		$fullpath = $up->getSavedDestination();
		// Redimensionamos la imagen
		$redim = new RMImageResizer($fullpath, $fullpath);
		$redim->resizeWidth($mc['img_size']);
		$redim->setTargetFile(XOOPS_UPLOAD_PATH."/teams/players/ths/$filename");
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
		
		$filename = $edit ? $player->image() : '';
		
	}
	
	$player->setName($name);
	$player->setNameId($nameid);
	$player->setBirth($birth);
	$player->setBio($bio);
	if (!$edit) $player->setDate(time());
	$player->setTeam($idteam);
	$player->setNumber($number);
	$player->setImage($filename);
	
	$player->setVar('dohtml', isset($dohtml) ? 1 : 0);
	$player->setVar('doxcode', isset($doxcode) ? 1 : 0);
	$player->setVar('doimage', isset($doimage) ? 1 : 0);
	$player->setVar('dosmiley', isset($dosmiley) ? 1 : 0);
	$player->setVar('dobr', isset($dobr) ? 1 : 0);
	
	if ($player->save()){
		redirectMsg('players.php?team='.$idteam, __('Base de datos actualizada correctamente','admin_team'), 0);
	} else {
		redirectMsg('players.php?team='.$idteam.'&op='.($edit ? 'edit&id='.$player->id() : 'new'), __('Ocurrieron errores al intentar actualizar la base de datos','admin_team').'<br />'.$player->errors(), 0);
	}
	
}

function deletePlayer(){
	global $db;
	
	$team = TCFunctions::request('team');
	$players = TCFunctions::request('players');
	if (empty($players)){
		redirectMsg('players.php?team='.$team, __('No has seleccionado jugadores para eliminar','admin_team'), 1);
		die();
	}
	
	foreach ($players as $k){
		$player = new TCPlayer($k);
		$player->delete();
	}
	
	redirectMsg('players.php?team='.$team, __('¡Jugadores eliminados!','admin_team'), 0);
	
}

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch($op){
	case 'new':
		formPlayer();
		break;
	case 'save':
		savePlayer();
		break;
	case 'edit':
		formPlayer(1);
		break;
	case 'saveedit':
		savePlayer(1);
		break;
	case 'delete':
		deletePlayer();
		break;
	default:
		showPlayers();
		break;
}
