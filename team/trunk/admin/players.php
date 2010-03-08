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
	
	$team = TCFunctions::get('team');
	$team = new TCTeam($team);
	
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
	if (!$team->isNew()){
		foreach ($team->coachs(true) as $coach){
			$tpl->append('coachs', array('id'=>$coach->id(),'name'=>$coach->name(),'image'=>$coach->image()));
		}
	}
	
	// Jugadores
	$result = $db->query("SELECT * FROM ".$db->prefix("coach_players")." WHERE team='".$team->id()."'");
	while ($row = $db->fetchArray($result)){
		$player = new TCPlayer();
		$player->assignVars($row);
		$tpl->append('players', array('id'=>$player->id(),'name'=>$player->name(),'image'=>$player->image(),
			'number'=>$player->number(),'age'=>$player->age(),'date'=>formatTimestamp($player->date(), 's')));
	}
	
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('Jugadores','admin_team'));
	xoops_cp_header();
	
	include RMTemplate::get()->get_template("admin/coach_players.php",'module','team');
	xoops_cp_footer();
	
}

function formPlayer($edit = 0){
	global $xoopsModule, $mc, $util, $xoopsConfig;
	
	$idteam = TCFunctions::request('team');
	if ($idteam<=0){
		redirectMsg('teams.php', _AS_TC_ERRIDTEAM, 1);
		die();
	}
	
	$team = new TCTeam($idteam);
	if($team->isNew()){
		redirectMsg('teams.php', _AS_TC_ERRTEAMNOEXISTS, 1);
		die();
	}
	
	if ($edit){
		$id = TCFunctions::get('id');
		if ($id<=0){
			redirectMsg('players.php?team='.$idteam, _AS_TC_ERRID, 1);
			die();
		}
		
		$player = new TCPlayer($id);
		if ($player->isNew()){
			redirectMsg('players.php?team='.$isteam, _AS_TC_ERRNOEXISTS, 1);
			die();
		}
	}
	
	optionsBar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./players.php?team=$idteam'>"._AS_TC_PLAYLOC."</a> &raquo; ".($edit ? _AS_TC_FEDITTITLE : _AS_TC_FNEWTITLE));
	xoops_cp_header();
	
	$form = new RMForm($edit ? _AS_TC_FEDITTITLE : _AS_TC_FNEWTITLE, 'frmNew', 'players.php');
	$form->oddClass('oddForm');
	$form->setExtra('enctype="multipart/form-data"');
	$form->addElement(new RMLabel(_AS_TC_FTEAM, "<h2 style='margin: 0;'>".$team->name()."</h2>"));
	$form->addElement(new RMText(_AS_TC_FNAME, 'name', 50, 100, $edit ? $player->name() : ''), true);
	if ($edit) $form->addElement(new RMText(_AS_TC_FSHORTNAME, 'nameid', 50, 100, $player->nameId()));
	
	$ele = new RMDate(_AS_TC_FBIRTH, 'birth', $edit ? $player->birth() : null);
	$ele->startYear(1940);
	$form->addElement($ele);
	$form->addElement(new RMText(_AS_TC_FNUMBER, 'number', 5, 3, $edit ? $player->number() : ''), true, 'num');
	$form->addElement(new RMFile(_AS_TC_FPIC, 'image', 45));
	if ($edit && $player->image()!='') 
		$form->addElement(new RMLabel(_AS_TC_FCURPIC, "<img src='".XOOPS_URL."/uploads/teams/players/ths/".$player->image()."' alt='' />"));
	
	$form->addElement(new RMEditor(_AS_TC_FBIO, 'bio', '90%', '300px', $edit ? $player->bio('e') : ''));
	if ($edit){
		$html = $player->getVar('dohtml');
		$xcode = $player->getVar('doxcode');
		$doimage = $player->getVar('doimage');
		$smiley = $player->getVar('dosmiley');
		$br = $player->getVar('dobr');
	} else {
		$html = $xoopsConfig['editor_type']=='tiny' ? 1 : 0;
		$xcode = $xoopsConfig['editor_type']=='tiny' ? 0 : 1;
		$doimage = $xoopsConfig['editor_type']=='tiny' ? 0 : 1;
		$smiley = $xoopsConfig['editor_type']=='tiny' ? 0 : 1;
		$br = $xoopsConfig['editor_type']=='tiny' ? 0 : 1;
	}
	$form->addElement(new RMTextOptions(_OPTIONS, $html, $xcode, $doimage, $smiley, $br));
	$ele = new RMButtonGroup();
	$ele->addButton('sbt', _SUBMIT, 'submit');
	$ele->addButton('cancel', _CANCEL, 'button', 'onclick="window.location=\'players.php?team='.$team->id().'\';"');
	$form->addElement($ele);
	$form->addElement(new RMHidden('op', $edit ? 'saveedit' : 'save'));
	if ($edit) $form->addElement(new RMHidden('id', $id));
	$form->addElement(new RMHidden('team', $idteam));
	
	$form->display();
	
	xoops_cp_footer();
}

function savePlayer($edit = 0){
	global $db, $mc, $util;
	
	$nameid = '';
	$teams = array();
	$idteam = 0;
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if (!$util->validateToken()){
		redirectMsg('players.php?team='.$idteam.($edit ? "&op=edit&id=$id" : "&op=new"), _AS_TC_ERRSESSID, 1);
		break;
	}
	
	$idteam = TCFunctions::request('team');

	if ($idteam<=0){
		redirectMsg('teams.php', _AS_TC_ERRIDTEAM, 1);
		die();
	}
	
	$team = new TCTeam($idteam);
	
	if($team->isNew()){
		redirectMsg('teams.php', _AS_TC_ERRTEAMNOEXISTS, 1);
		die();
	}
	
	if ($edit){
		$id = TCFunctions::post('id');
		if ($id<=0){
			redirectMsg('players.php?team='.$idteam, _AS_TC_ERRID, 1);
			die();
		}
		$player = new TCPLayer($id);
		if ($player->isNew()){
			redirectMsg('players.php?team='.$idteam, _AS_TC_ERRNOEXISTS, 1);
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
			redirectMsg('players.php?team='.$idteam.'&op=edit&id='.$player->id(), _AS_TC_ERREXISTS, 1);
			die();
		}
		
	} else {
	
		$player = new TCPlayer();
		
		$i = 0;
		do{
			$nameid = $util->sweetstring($name).($i>0 ? $i : '');
			$sql = "SELECT COUNT(*) FROM ".$db->prefix("coach_players")." WHERE nameid='$nameid'";
			list($num) = $db->fetchRow($db->query($sql));
			$i++;
		} while($num>0);
		
		$sql = "SELECT COUNT(*) FROM ".$db->prefix("coach_players")." WHERE name='$name'";
		list($num) = $db->fetchRow($db->query($sql));
		if ($num>0){
			redirectMsg('players.php?team='.$idteam.'&op=new&id='.$cat->id(), _AS_TC_ERREXISTS, 1);
			die();
		}
		
	}
	
	// Cargamos la imágen
	include_once XOOPS_ROOT_PATH.'/rmcommon/uploader.class.php';
	$up = new RMUploader(true);
	
	$up->prepareUpload(XOOPS_UPLOAD_PATH.'/teams/players', array($up->getMIME('jpg'),$up->getMIME('png'),$up->getMIME('gif')), $mc['filesize']*1024);//tamaño
	
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
		$redim = new RMImageControl($fullpath, $fullpath);
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
	$player->setBirth(rmsoft_read_date('birth'));
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
		redirectMsg('players.php?team='.$idteam, _AS_TC_DBOK, 0);
	} else {
		redirectMsg('players.php?team='.$idteam.'&op='.($edit ? 'edit&id='.$player->id() : 'new'), _AS_TC_DBFAIL.'<br />'.$player->errors(), 0);
	}
	
}

function deletePlayer(){
	global $db;
	
	$team = TCFunctions::request('team');
	$players = TCFunctions::request('players');
	if (empty($players)){
		redirectMsg('players.php?team='.$team, _AS_TC_NOSEL, 1);
		die();
	}
	
	foreach ($players as $k){
		$player = new TCPlayer($k);
		$player->delete();
	}
	
	redirectMsg('players.php?team='.$team, _AS_TC_DBOK, 0);
	
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
