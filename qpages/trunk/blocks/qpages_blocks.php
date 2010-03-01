<?php
// $Id$
// --------------------------------------------------------
// Quick Pages
// Módulo para la publicación de páginas individuales
// CopyRight © 2007 - 2008. Red México
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
// @copyright: 2007 - 2008 Red México
// @author: BitC3R0

function qpagesBlockCategos(){
	global $xoopsConfig, $mc;
	
	include_once XOOPS_ROOT_PATH.'/modules/qpages/class/qpcategory.class.php';
	include_once XOOPS_ROOT_PATH.'/modules/qpages/include/general.func.php';
	
	$util = RMUtils::getInstance();
	
	$mc =& $util->moduleConfig('qpages');
	$db =& Database::getInstance();
	
	$block = array();
	$categos = array();
	qpArrayCategos($categos);
	
	foreach ($categos as $k){
		$catego = new QPCategory();
		$catego->assignVars($k);
		$rtn = array();
		$rtn['id'] = $catego->getID();
		$rtn['nombre'] = $catego->getName();
		$rtn['link'] = $catego->getLink();
		$rtn['ident'] = $k['saltos']>0 ? $k['saltos'] + 8 : 0;
		$block['categos'][] = $rtn;
	}
	
	return $block;
	
}

/**
 * Mostramos las página existentes
 */
function qpagesBlockPages($options){
	global $xoopsConfig;

	$util = RMUtils::getInstance();
	$db =& Database::getInstance();
	$mc =& $util->moduleConfig('qpages');
	
	$sql = "SELECT * FROM ".$db->prefix("qpages_pages");
	if ($options[0]>0){
		$sql .= " WHERE cat='$options[0]'";
	}
	
	$sql .= " ORDER BY fecha DESC LIMIT 0,$options[1]";
	$block = array();
	$result = $db->query($sql);
	while ($row = $db->fetchArray($result)){
		$rtn = array();
		$rtn['id'] = $row['id_page'];
		$rtn['titulo'] = $row['titulo'];
		$rtn['link'] = XOOPS_URL.'/modules/qpages/';
		$rtn['link'] .= $mc['links'] ? $row['titulo_amigo'] . '/' : "page.php?page=$row[titulo_amigo]";
		$block['pages'][] = $rtn;
	}
	
	return $block;
	
}

function qpagesBlockPagesEdit($options){
	
	include_once XOOPS_ROOT_PATH.'/modules/qpages/include/general.func.php';
	$categos = array();
	qpArrayCategos($categos);
	
	$form = _BS_QP_SELCATEGO . "<br />
			<select name='options[0]'><option value='0'".($options[0]==0 ? " selected='selected'" : '').">"._SELECT."</option>";
	foreach ($categos as $k){
		$form .= "<option value='$k[id_cat]'".($options[0]==$k['id_cat'] ? " selected='selected'" : '').">$k[nombre]</option>";
	}
	$form .= "</select><br /><br />";
	
	$form .= _BS_QP_NUMPAGES . "<br />
			<input type='text' name='options[1]' size='10' value='$options[1]' />";
	
	return $form;
}

?>