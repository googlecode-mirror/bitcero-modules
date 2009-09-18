<?php
// $Id: search.func.php 13 2009-08-31 00:45:24Z i.bitcero $
// --------------------------------------------------------
// RMSOFT Natural Press
// Módulo para la administración y publicación de artículos
// CopyRight © 2007. Red México Soft
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.xoopsmexico.net
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
// @copyright: 2005 - 2007 Red México Soft
// @author: BitC3R0
// @package: RMSOFT Natural Press

if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}

/**
 * Función para realizar búsquedas
 */
function mywords_search($qa, $andor, $limit, $offset, $userid){
	global $xoopsUser;
	$db =& Database::getInstance();
	
	include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwpost.class.php';
	$util =& RMUtils::getInstance();
	$mc =& $util->moduleConfig('mywords');
	
	$sql = "SELECT * FROM ".$db->prefix("mw_posts");
	if ( is_array($qa) && $count = count($qa) ) {
		$adds = '';
		for($i=0;$i<$count;$i++){
			$adds .= $adds=='' ? "(titulo LIKE '%$qa[$i]%' OR titulo_amigo LIKE '%$qa[$i]%')" : " $andor (titulo LIKE '%$qa[$i]%' OR titulo_amigo LIKE '%$qa[$i]%')";
		}
	}
	
	$sql .= $adds!='' ? " WHERE ".$adds : '';
	if ($userid>0){
		$sql .= ($adds!='' ? " AND " : " WHERE ")."autor='$userid'";
	}
	$sql .= " ORDER BY modificado DESC";

	$i = 0;
	$result = $db->query($sql);
	$ret = array();
	while ($row = $db->fetchArray($result)){
		$post = new MWPost();
		$post->assignVars($row);
		$ret[$i]['image'] = "images/iconsearch.png";
		$day = date('d', $row['fecha']);
		$month = date('m', $row['fecha']);
		$year = date('Y', $row['fecha']);
		$link = $mc['permalinks']==1 ? '?post='.$row['id_post'] : ($mc['permalinks']==2 ? "$day/$month/$year/".$row['titulo_amigo']."/" : "post/".$row['id_post']);
		$ret[$i]['link'] = $link;
		$ret[$i]['title'] = $post->getTitle();
		$ret[$i]['time'] = $post->getModDate();
		$ret[$i]['uid'] = $post->getAuthor();
		$ret[$i]['desc'] = substr($util->filterTags($post->getHomeText()), 0, 150);
		$i++;
	}
	
	return $ret;
	
}

?>