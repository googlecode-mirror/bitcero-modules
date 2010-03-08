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

include_once XOOPS_ROOT_PATH.'/modules/team/class/tcteam.class.php';
include_once XOOPS_ROOT_PATH.'/modules/team/class/tccategory.class.php';
include_once XOOPS_ROOT_PATH.'/modules/team/class/tcplayer.class.php';

function tc_block_players($options){
	global $xoopsModule;
	
	if ($xoopsModule && $xoopsModule->dirname()=='team'){
		global $xoopsModuleConfig;
		$mc =& $xoopsModuleConfig;
	} else {
		$util =& RMUtils::getInstance();
		$mc =& $util->moduleConfig('team');
	}
	
	$db =& Database::getInstance();
	
	$sql = "SELECT * FROM ".$db->prefix("coach_players");
	if ($options[0]>0){
		$sql .= " WHERE team='".$options[0]."'";
	}
	$sql .= " ORDER BY RAND() LIMIT 0,$options[1]";
	
	$result = $db->query($sql);
	$block = array();
	while ($row = $db->fetchArray($result)){
		$rtn = array();
		$player = new TCPlayer();
		$player->assignVars($row);
		$rtn['link'] = XOOPS_URL.'/modules/team/'.($mc['urlmode'] ? 'player/'.$player->nameId().'/' : 'player.php?id='.$player->id());
		$rtn['name'] = $player->name();
		$rtn['number'] = $player->number();
		$rtn['image'] = $player->image();
		$block['players'][] = $rtn;
	}
	
	$block['cols'] = $options[2];
	
	return $block;
	
}

function tc_block_players_edit($options, &$form){
	$form->addElement(new RMSubTitle(_AS_BKM_BOPTIONS,1));
	
	// Equipos
	$ele = new RMSelect(_BK_TC_TEAM, 'options[0]');
	$ele->addOption(0,_BK_TC_ALLTEAM, $options[0]>0 ? 0 : 1);
	$db =& Database::getInstance();
	$result = $db->query("SELECT * FROM ".$db->prefix("coach_teams")." ORDER BY name");
	while ($row = $db->fetchArray($result)){
		$team = new TCTeam();
		$team->assignVars($row);
		$cat = $team->category(true);
		$ele->addOption($row['id_team'],$row['name']." (".$cat->name().")", $row['id_team']==$options[0] ? 1 : 0);	}
	
	$form->addElement($ele);
	
	$form->addElement(new RMText(_BK_TC_NUMBER, 'options[1]', 10, 3, $options[1]));
	$form->addElement(new RMText(_BK_TC_COLNUMBER, 'options[2]', 10, 3, $options[2]));
	
	return $form;
}

?>