<?php
// $Id$
// --------------------------------------------------------
// Professional Works
// Manejo de Portafolio de Trabajos
// CopyRight © 2008. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.exmsystem.net
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

function pw_categories_show($options){
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
	
	$db =& Database::getInstance();
	$result = $db->query("SELECT * FROM ".$db->prefix("pw_categos")." ORDER BY name");
	
	$block = array();
	
	while($row = $db->fetchArray($result)){
		$ret = array();
		$ret['name'] = $row['name'];
		$ret['link'] = ($mc['urlmode'] ? XOOPS_URL.$mc['htbase'].'/cat/'.$row['id_cat'] : XOOPS_URL.'/modules/works/catego.php?id='.$row['id_cat']);
		$block['categos'][] = $ret;
	}
	
	return $block;
}

?>