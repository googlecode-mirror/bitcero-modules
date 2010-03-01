<?php
// $Id$
// --------------------------------------------------------
// Professional Works
// Manejo de Portafolio de Trabajos
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


function pw_comments_show($options){
	global $xoopsModule, $xoopsModuleConfig;

	include_once XOOPS_ROOT_PATH.'/modules/works/class/pwwork.class.php';
	include_once XOOPS_ROOT_PATH.'/modules/works/class/pwclient.class.php';

	$db =& Database::getInstance();
	$util =& RMUtils::getInstance();
	if (isset($xoopsModule) && ($xoopsModule->dirname()=='works')){
		$mc =& $xoopsModuleConfig;
	}else{
		$mc =& $util->moduleConfig('works');
	}


	$sql = "SELECT * FROM ".$db->prefix('pw_works')." WHERE comment<>'' ORDER BY ".($options[1] ? " created DESC " : " RAND() ");
	$sql.= " LIMIT 0,".$options[0];
	$result = $db->query($sql);
	$clients = array();
	while ($row = $db->fetchArray($result)){
		$work = new PWWork();
		$work->assignVars($row);
		
		if (!isset($clients[$work->client()])) $clients[$work->client()] = new PWClient($work->client(), 1);
		$client =& $clients[$work->client()];
		
		$rtn = array();
		$rtn['client'] = $client->businessName();
		$rtn['link'] = XOOPS_URL.'/modules/works/'.($mc['urlmode'] ? 'work/'.$work->id() : 'work.php?id='.$work->id());
		$rtn['comment'] = $work->comment();
	
		$block['works'][] = $rtn;

	}
	
	return $block;

}


function pw_comments_edit($options, &$form){
	global $db;

	$form->addElement(new RMSubTitle(_AS_BKM_BOPTIONS, 1, 'head'));
	$form->addElement(new RMText(_BK_PW_NUMCOMMENTS,'options[0]',5,5,$options[0] ? $options[0] : 1),true);
	$ele = new RMSelect(_BK_PW_CTYPE,'options[1]');
	$ele->addOption(0,_BK_PW_CRAND,$options[1]==0 ? 1 : 0);
	$ele->addOption(1,_BK_PW_CRECENT, $options[1]==1 ? 1 :0);

	$form->addElement($ele,true);
}

?>
