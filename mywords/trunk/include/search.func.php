<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

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
	$util =& RMUtilities::get();
	$mc =& $util->module_config('mywords');
	
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