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

define('TC_LOCATION','teams');
include '../../mainfile.php';

$id = TCFunctions::get('id');
if ($id==''){
	redirect_header(XOOPS_URL.'/modules/team/', 1, _MS_TC_ERRID);
	die();
}

$myts =& MyTextSanitizer::getInstance();

$id = $myts->addSlashes($id);
$id = str_replace("/", "", $id);

$team = new TCTeam($id);
if ($team->isNew()){
	redirect_header(XOOPS_URL.'/modules/team/',1,_MS_TC_ERRNOEXISTIS);
	die();
}

$xoopsOption['template_main'] = "coach_team.html";
include 'header.php';

$cat = $team->category(true);
$tpl->assign('coach_title', $team->name()." <em>(".$cat->name().")</em>");
$tpl->assign('lang_comment', _MS_TC_COMMENT);
$tpl->assign('lang_players', _MS_TC_PLAYERS);
$tpl->assign('lang_info', _MS_TC_INFO);
$tpl->assign('lang_coachs', _MS_TC_COACHS);

$tpl->assign('team', array('id'=>$team->id(),'desc'=>$team->desc()));

// Integrantes
$players = $team->players(true);
foreach ($players as $player){
	$link = TC_URL.'/'.($mc['urlmode'] ? 'player/'.$player->nameId().'/' : 'player.php?id='.$player->id());
	$tpl->append('players', array('id'=>$player->id(),'name'=>$player->name(),'image'=>$player->image(),
			'number'=>$player->number(), 'link'=>$link));
}

// ENtrenadores
$coachs = $team->coachs(true);
foreach ($coachs as $coach){
	$link = TC_URL.'/'.($mc['urlmode'] ? 'coach/'.$coach->nameId().'/' : 'player.php?t=c&amp;id='.$coach->id());
	$tpl->append('coachs', array('id'=>$coach->id(),'name'=>$coach->name(),'role'=>$coach->role(),'link'=>$link,
			'image'=>$coach->image()));
}

$tpl->assign('xoops_pagetitle', sprintf(_MS_TC_PTITLE, $team->name()));
$location = "<a href='".TC_URL."'>".$xoopsModule->name()."</a> &raquo; 
	<a href='".TC_URL."/".($mc['urlmode'] ? 'cat/'.$cat->nameId()."/" : 'category.php?id='.$cat->id())."'>
	".$cat->name()."</a> &raquo; ".sprintf(_MS_TC_PTITLE, $team->name());
$tpl->assign('coach_location', $location);

include 'footer.php';

?>