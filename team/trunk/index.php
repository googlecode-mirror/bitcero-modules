<?php
// $Id$
// --------------------------------------------------------------
// Equipo Club Gallos
// Un modulo sencillo para el manejo de equipos
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('TC_LOCATION','index');
include '../../mainfile.php';

/**
* @desc Comprobamos cuantos equipos existen en la base de datos
* Si solo existe un equipo redirigimos automáticamente a la
* información de ese equipo
*/
$result = $xoopsDB->query("SELECT nameid FROM ".$xoopsDB->prefix("coach_teams"));
$mc =& $xoopsModuleConfig;
if ($xoopsDB->getRowsNum($result)==1){
	$row = $xoopsDB->fetchArray($result);
	$link = XOOPS_URL."/modules/team/".($mc['urlmode'] ? "t/$row[nameid]/" : "team.php?id=$row[nameid]");
	header('Location: '.$link);
	die();
}

$xoopsOption['template_main'] = "coach_index.html";
include 'header.php';

$tpl->assign('coach_title',_MS_TC_TITLE);
$tpl->assign('lang_comment', _MS_TC_COMMENT);

// Categorías

$tpl->assign('lang_categos', _MS_TC_CATTITLE);

$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("coach_categos")." ORDER BY name");
$cats = array();
while ($row = $xoopsDB->fetchArray($result)){
	$cat = new TCCategory();
	$cat->assignVars($row);
	$cats[$cat->id()] = $cat;
	$link = TC_URL.'/'.($mc['urlmode'] ? 'cat/'.$cat->nameId().'/' : 'category.php?id='.$cat->id());
	$tpl->append('categos', array('id'=>$cat->id(),'name'=>$cat->name(),'desc'=>$cat->desc(),'link'=>$link));
}

// Equipos

$tpl->assign ('lang_teams', _MS_TC_TEAMSTITLE);

$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("coach_teams")." ORDER BY name");
while ($row = $xoopsDB->fetchArray($result)){
	$team = new TCTeam();
	$team->assignVars($row);
	$link = TC_URL.'/'.($mc['urlmode'] ? 't/'.$team->nameId().'/' : 'team.php?id='.$team->id());
	$tpl->append('teams', array('id'=>$team->id(),'name'=>$team->name()." <em>(".$cats[$team->category(false)]->name().")</em>",'link'=>$link));
}

include 'footer.php';
