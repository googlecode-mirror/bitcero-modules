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

define('TC_LOCATION','coachs');
include '../../mainfile.php';

$id = TCFunctions::get('id');
if ($id==''){
	redirect_header(TC_URL, 1, _MS_TC_ERRID);
	die();
}

$myts =& MyTextSanitizer::getInstance();

$id = $myts->addSlashes($id);
$id = str_replace("/", "", $id);

$coach = new TCCoach($id);

if ($coach->isNew()){
	redirect_header(TC_URL,1,_MS_TC_ERRNOEXISTIS);
	die();
}

$xoopsOption['template_main'] = "coach_coach.html";
include 'header.php';

$tpl->assign('coach_title', $coach->name());
$tpl->assign('lang_comment', _MS_TC_COMMENT);
$tpl->assign('lang_data', _MS_TC_DATA);
$tpl->assign('lang_name', _MS_TC_NAME);
$tpl->assign('lang_team', _MS_TC_TEAM);
$tpl->assign('lang_date', _MS_TC_DATE);
$tpl->assign('lang_bio', _MS_TC_BIO);

$link = TC_URL.'/'.($mc['urlmode'] ? 'coach/'.$coach->nameId().'/' : 'coach.php?id='.$coach->id());
$tpl->assign('coach', array('id'=>$coach->id(),'name'=>$coach->name(),'image'=>$coach->image(),
		'date'=>formatTimestamp($coach->created(),'string'),'bio'=>$coach->bio(),'link'=>$link));

$teams = $coach->teams();
$st = '';
foreach ($teams as $team){
	$tlink = TC_URL.'/'.($mc['urlmode'] ? 't/'.$team->nameId().'/' : 'team.php?id='.$team->id());
	$cat = $team->category(true);
	$st .= $st == '' ? "<a href='$tlink'>".$team->name()." <em>(".$cat->name().")</em></a>" : ", <a href='$tlink'>".$team->name()." <em>(".$cat->name().")</em></a>";
}
$tpl->assign('teams', $st);

$tpl->assign('xoops_pagetitle', sprintf(_MS_TC_PTITLE, $coach->name()));
$location = "<a href='".TC_URL."'>".$xoopsModule->name()."</a> &raquo; ".$coach->name();
$tpl->assign('coach_location', $location);

include 'footer.php';
  
?>

?>