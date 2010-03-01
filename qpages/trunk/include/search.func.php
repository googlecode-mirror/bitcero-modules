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

if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}

/**
 * Función para realizar búsquedas
 */
function qpages_search($qa, $andor, $limit, $offset, $userid){
	global $xoopsUser, $mc;
	$db =& Database::getInstance();
	
	include_once XOOPS_ROOT_PATH.'/modules/qpages/class/qppage.class.php';
	$util =& RMUtils::getInstance();
	$mc =& $util->moduleConfig('qpages');
	
	$sql = "SELECT * FROM ".$db->prefix("qpages_pages");
	if ( is_array($qa) && $count = count($qa) ) {
		$adds = '';
		for($i=0;$i<$count;$i++){
			$adds .= $adds=='' ? "(titulo LIKE '%$qa[$i]%' OR titulo_amigo LIKE '%$qa[$i]%')" : " $andor (titulo LIKE '%$qa[$i]%' OR titulo_amigo LIKE '%$qa[$i]%')";
		}
	}
	
	$sql .= $adds!='' ? " WHERE $adds" : '';
	if ($userid>0){
		$sql .= ($adds!='' ? " AND " : " WHERE ")."uid='$userid'";
	}
	$sql .= " ORDER BY modificado DESC";

	$i = 0;
	$result = $db->query($sql);
	$ret = array();
	while ($row = $db->fetchArray($result)){
		$page = new QPPage();
		$page->assignVars($row);
		$ret[$i]['image'] = "images/iconsearch.png";
		$ret[$i]['link'] = $mc['links']==0 ? 'page.php?page='.$page->getFriendTitle() : $page->getFriendTitle().'/';
		$ret[$i]['title'] = $page->getTitle();
		$ret[$i]['time'] = $page->getDate();
		$ret[$i]['uid'] = $page->uid();
		$ret[$i]['desc'] = $page->getDescription();
		$i++;
	}
	return $ret;
	
}

?>