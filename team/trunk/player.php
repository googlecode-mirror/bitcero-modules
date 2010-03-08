<?php
// $Id$
// --------------------------------------------------------------
// Equipo Club Gallos
// Un modulo sencillo para el manejo de equipos
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('TC_LOCATION','players');
include '../../mainfile.php';

$id = TCFunctions::get('id');
if ($id==''){
	redirect_header(TC_URL, 1, _MS_TC_ERRID);
	die();
}

$myts =& MyTextSanitizer::getInstance();

$id = $myts->addSlashes($id);
$id = str_replace("/", "", $id);

$player = new TCPlayer($id);

if ($player->isNew()){
	redirect_header(TC_URL,1,_MS_TC_ERRNOEXISTIS);
	die();
}

$xoopsOption['template_main'] = "coach_player.html";
include 'header.php';

$tpl->assign('coach_title', $player->name()." (#".$player->number().")");
$tpl->assign('lang_comment', _MS_TC_COMMENT);
$tpl->assign('lang_data', _MS_TC_DATA);
$tpl->assign('lang_name', _MS_TC_NAME);
$tpl->assign('lang_number', _MS_TC_NUMBER);
$tpl->assign('lang_team', _MS_TC_TEAM);
$tpl->assign('lang_age', _MS_TC_AGE);
$tpl->assign('lang_date', _MS_TC_DATE);
$tpl->assign('lang_bio', _MS_TC_BIO);
$tpl->assign('lang_link', _MS_TC_LINK);

$link = TC_URL.'/'.($mc['urlmode'] ? 'player/'.$player->nameId().'/' : 'player.php?id='.$player->id());
$tpl->assign('player', array('id'=>$player->id(),'name'=>$player->name(),'image'=>$player->image(),
		'number'=>$player->number(),'age'=>$player->age(),'date'=>formatTimestamp($player->date(),'c'),
		'bio'=>$player->bio(),'link'=>$link));

$team = new TCTeam($player->team());

$tpl->assign('xoops_pagetitle', sprintf(_MS_TC_PTITLE, $team->name(), $player->number()));
$tlink = TC_URL.'/'.($mc['urlmode'] ? 't/'.$team->nameId().'/' : 'team.php?id='.$team->id());
$location = "<a href='".TC_URL."'>".$xoopsModule->name()."</a> &raquo; <a href='$tlink'>".$team->name()."</a> &raquo; ".$player->name();
$tpl->assign('coach_location', $location);

$tpl->assign('team',array('id'=>$team->id(),'name'=>$team->name(),'link'=>$tlink));
$tpl->assign('lang_players', sprintf(_MS_TC_PLAYERS, $team->name()));

// Integrantes
$players = $team->players(true, 'RAND()');
$i = 1;
foreach ($players as $player){
	if ($i>4) break;
	$link = TC_URL.'/'.($mc['urlmode'] ? 'player/'.$player->nameId().'/' : 'player.php?id='.$player->id());
	$tpl->append('players', array('id'=>$player->id(),'name'=>$player->name(),'image'=>$player->image(),
			'number'=>$player->number(), 'link'=>$link));
	$i++;
}

include 'footer.php';
