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

define('TC_LOCATION','categories');
include '../../mainfile.php';

$id = TCFunctions::get('id');
if ($id==''){
	redirect_header(XOOPS_URL.'/modules/team/', 1, _MS_TC_ERRID);
	die();
}

$myts =& MyTextSanitizer::getInstance();
$id = $myts->addSlashes($id);
$id = str_replace("/", "", $id);

$cat = new TCCategory($id);
if ($cat->isNew()){
	redirect_header(XOOPS_URL.'/modules/team/',1,_MS_TC_ERRNOEXISTIS);
	die();
}

$xoopsOption['template_main'] = "coach_category.html";
include 'header.php';

$tpl->assign('coach_title', $cat->name());
$tpl->assign('lang_comment', _MS_TC_COMMENT);
$tpl->assign('lang_in', _MS_TC_TEAMIN);
$tpl->assign('xoops_pagetitle', sprintf(_MS_TC_PTITLE, $cat->name()));

$location = "<a href='".TC_URL."'>".$xoopsModule->name()."</a> &raquo; ".sprintf(_MS_TC_PTITLE, $cat->name());
$tpl->assign('coach_location', $location);

$teams = $cat->teams();
foreach ($teams as $team){
	$link = TC_URL.'/'.($mc['urlmode'] ? 't/'.$team->nameId().'/' : 'team.php?id='.$team->id());
	$tpl->append('teams', array('id'=>$team->id(),'name'=>$team->name(),'image'=>$team->image(), 'link'=>$link,
		'desc'=>substr($util->filterTags($team->desc()), 0, 200)));
}

include 'footer.php';

?>