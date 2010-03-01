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


function pw_works_show($options){
	global $xoopsModule, $xoopsModuleConfig;

	include_once XOOPS_ROOT_PATH.'/modules/works/class/pwwork.class.php';
	include_once XOOPS_ROOT_PATH.'/modules/works/class/pwclient.class.php';
	include_once XOOPS_ROOT_PATH.'/modules/works/class/pwcategory.class.php';

	$db =& Database::getInstance();
	$util =& RMUtils::getInstance();
	if (isset($xoopsModule) && ($xoopsModule->dirname()=='works')){
		$mc =& $xoopsModuleConfig;
	}else{
		$mc =& $util->moduleConfig('works');
	}

	$sql = "SELECT * FROM ".$db->prefix('pw_works');
	$sql1 = $options[1] ? " WHERE catego='".$options[1]."'" : '';
	$sql2 = $options[2] ? ($sql1=='' ? " WHERE " : " AND ")." client='".$options[2]."'" : ''; 
	$sql3 = '';
	switch($options[0]){
		case 0:
			$sql3 .= " ORDER BY RAND()";
			break;
		case 1:
			$sql3 .= ($sql1 || $sql2 ? " AND " : " WHERE ")." mark=1 ORDER BY RAND()";
			break;
		case 2: 
			$sql3 .= " ORDER BY created DESC ";
			break;
	}
	
	$sql3 .= " LIMIT 0,".$options[3];

	$result = $db->query($sql.$sql1.$sql2.$sql3);
	$clients = array();
	$categos = array();
	while($row = $db->fetchArray($result)){

		$work = new PWWork();
		$work->assignVars($row);

		if (!isset($clients[$work->client()])) $clients[$work->client()] = new PWClient($work->client(), 1);
		$client =& $clients[$work->client()];

		if (!isset($categos[$work->category()])) $categos[$work->category()] = new PWCategory($work->category(), 1);
		$cat =& $categos[$work->category()];
		
		$rtn = array();
		$rtn['title'] = $work->title();
		if ($options[6]) $rtn['desc'] = substr($work->descShort(),0,50);
		$rtn['link'] = XOOPS_URL.'/modules/works/'.($mc['urlmode'] ? 'work/'.$work->id() : 'work.php?id='.$work->id());
		$rtn['created'] = formatTimestamp($work->created(), 'string');
		if ($options[5]) $rtn['image'] = XOOPS_UPLOAD_URL.'/works/ths/'.$work->image();
		$linkcat = XOOPS_URL.'/modules/works/'.($mc['urlmode'] ? 'cat/'.$cat->nameId() : 'catego.php?id='.$cat->nameId());
		$rtn['cat'] = sprintf(_BK_PW_CAT,'<a href="'.$linkcat.'">'.$cat->name().'</a>');
		$rtn['client'] = sprintf(_BK_PW_USER,$client->businessName());
		$block['works'][] = $rtn;

	}
	
	$block['cols'] = $options[4];
	$block['showdesc'] = $options[6];
	$block['showimg'] = $options[5];
	return $block;
}


function pw_works_edit($options, &$form){
	global $db;


	include_once XOOPS_ROOT_PATH.'/modules/works/class/pwclient.class.php';
	include_once XOOPS_ROOT_PATH.'/modules/works/class/pwcategory.class.php';

	$form->addElement(new RMSubTitle(_AS_BKM_BOPTIONS, 1, 'head'));
	//Tipo de Trabajo
	$ele = new RMSelect(_BK_PW_TYPE,'options[0]');
	$ele->addOption(0,_BK_PW_RAND,$options[0]==0 ? 1 : 0);
	$ele->addOption(1,_BK_PW_FEATURED,$options[0]==1 ? 1 : 0);
	$ele->addOption(2,_BK_PW_RECENT,$options[0]==2 ? 1 : 0);

	$form->addElement($ele);

	//Obtenemos las categorías
	$ele = new RMSelect(_BK_PW_CATEGO,'options[1]');
	$ele->addOption(0,_BK_PW_ALLSCAT);
	$result = $db->query("SELECT * FROM ".$db->prefix('pw_categos')." WHERE active=1");
	while ($row = $db->fetchArray($result)){
		$cat = new PWCategory();
		$cat->assignVars($row);			

		$ele->addOption($cat->id(),$cat->name(),$options[1]==$cat->id() ? 1 : 0);
	}
	$form->addElement($ele,true);
	
	//Obtenemos los clientes
	$ele = new RMSelect(_BK_PW_CLIENT,'options[2]');
	$ele->addOption(0,_BK_PW_ALLSCLIENT);
	$result = $db->query("SELECT * FROM ".$db->prefix('pw_clients'));
	while ($row = $db->fetchArray($result)){
		$client = new PWClient();
		$client->assignVars($row);			

		$ele->addOption($client->id(),$client->name(),isset($ptions[2]) ? ($options[2]==$client->id() ? 1 : 0) : 0);
	}

	$form->addElement($ele,true);
	
	//Número de trabajos
	$form->addElement(new RMText(_BK_PW_NUMWORK,'options[3]',5,5,isset($options[3]) ? $options[3] : ''),true);
	$form->addElement(new RMText(_BK_PW_COLS,'options[4]',5,5,isset($options[4]) ? $options[4] : ''),true);
	$form->addElement(new RMYesno(_BK_PW_IMAGE,'options[5]',isset($options[5]) ? ($options[5] ? 1 : 0) : 0), true);
	$form->addElement(new RMYesno(_BK_PW_DESC,'options[6]',isset($options[6]) ? ($options[6] ? 1 : 0) : 0), true);

	return $form;

}




?>
