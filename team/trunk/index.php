<?php
// $Id$
// --------------------------------------------------------
// The Coach
// Manejo de Integrantes de Equipos Deportivos
// CopyRight © 2008. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.exmsystem.org
// --------------------------------------------
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License as
// published by the Free Software Foundation; either version 2 of
// the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public
// License along with this program; if not, write to the Free
// Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
// MA 02111-1307 USA
// --------------------------------------------------------
// @copyright: 2008 Red México

define('TC_LOCATION','index');
include '../../mainfile.php';

/**
* @desc Comprobamos cuantos equipos existen en la base de datos
* Si solo existe un equipo redirigimos automáticamente a la
* información de ese equipo
*/
$result = $db->query("SELECT nameid FROM ".$db->prefix("coach_teams"));
$mc =& $xoopsModuleConfig;
if ($db->getRowsNum($result)==1){
	$row = $db->fetchArray($result);
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

$result = $db->query("SELECT * FROM ".$db->prefix("coach_categos")." ORDER BY name");
$cats = array();
while ($row = $db->fetchArray($result)){
	$cat = new TCCategory();
	$cat->assignVars($row);
	$cats[$cat->id()] = $cat;
	$link = TC_URL.'/'.($mc['urlmode'] ? 'cat/'.$cat->nameId().'/' : 'category.php?id='.$cat->id());
	$tpl->append('categos', array('id'=>$cat->id(),'name'=>$cat->name(),'desc'=>$cat->desc(),'link'=>$link));
}

// Equipos

$tpl->assign ('lang_teams', _MS_TC_TEAMSTITLE);

$result = $db->query("SELECT * FROM ".$db->prefix("coach_teams")." ORDER BY name");
while ($row = $db->fetchArray($result)){
	$team = new TCTeam();
	$team->assignVars($row);
	$link = TC_URL.'/'.($mc['urlmode'] ? 't/'.$team->nameId().'/' : 'team.php?id='.$team->id());
	$tpl->append('teams', array('id'=>$team->id(),'name'=>$team->name()." <em>(".$cats[$team->category(false)]->name().")</em>",'link'=>$link));
}

include 'footer.php';

?>