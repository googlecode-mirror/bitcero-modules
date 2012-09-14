<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include_once XOOPS_ROOT_PATH.'/modules/mywords/include/general.func.php';

/**
* @desc Función para obtener la descripción de la sindicación
* @return string
*/
function mywords_rssdesc(){
	global $util;
	
	$mc =& $util->moduleConfig('mywords');
	return $mc['rssdesc'];
	
}

/**
* @desc Muestra el menu de opciones para sindicación
* @param int $limit Limite de resultados solicitados. 0 Indica ilimitado
* @param bool $more Referencia. Debe devolver true si existen mas resultados que el límite deseado
* @return array
*/
function &mywords_rssfeed($limit, &$more){
	global $db;
	$limit = $limit > 0 ? $limit-1 : 0;
	include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwcategory.class.php';
	
	$ret = array();
	$rtn = array();
	$ret['name'] = _MI_MW_RSSALL;
	$ret['desc'] = _MI_MW_RSSALLDESC;
	$ret['params'] = "show=all";
	$rtn[] = $ret;
	
	$sql = "SELECT COUNT(*) FROM ".$db->prefix("mw_categos")." ORDER BY parent ASC";
	list($num) = $db->fetchRow($db->query($sql));
	if ($num>$limit && $limit > 0) $more = true;
	
	$sql = str_replace("COUNT(*)",'*', $sql);
	if ($limit> 0) $sql .= " LIMIT 0, $limit";
	$result = $db->query($sql);
	while ($row = $db->fetchArray($result)){
		$cat = new MWCategory();
		$cat->assignVars($row);
		$ret = array();
		$ret['name'] = $cat->getName();
		$ret['desc'] = $cat->getDescription();
		$ret['params'] = "show=cat&amp;id=".$cat->getFriendName();
		$rtn[] = $ret;
	}
	return $rtn;
	
}

/**
* @desc Genera la información para mostrar la Sindicación
* @param int Limite de resultados
* @return Array
*/
function &mywords_rssshow($limit){
	global $util, $mc;
	
	$db = XoopsDatabaseFactory::getDatabaseConnection();
	include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwcategory.class.php';
	include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwpost.class.php';
	
	foreach ($_GET as $k => $v){
		$$k = $v;
	}
	
	$feed = array();		// Información General
	$ret = array();
	$mc =& $util->moduleConfig('mywords');
	
	if ($show == 'all'){
		$feed['title'] = htmlspecialchars(_MI_MW_RSSNAME);
		$feed['link'] = XOOPS_URL.'/modules/mywords';
		$feed['description'] = htmlspecialchars($util->filterTags($mc['rssdesc']));
		
		$sql = "SELECT * FROM ".$db->prefix("mw_posts")." WHERE aprovado='1' AND estado>'0' ORDER BY modificado DESC LIMIT 0,$limit";
	} else{
		
		if ($id=='') return;
		$cat = new MWCategory($id);
		if ($cat->isNew()) return;
		
		$feed['title'] = sprintf(_MI_MW_RSSNAMECAT, $cat->getName());
		$feed['link'] = $cat->getLink();
		$feed['description'] = $cat->getDescription();
		
		$sql = "SELECT b.* FROM ".$db->prefix("mw_catpost")." a,".$db->prefix("mw_posts")." b WHERE a.cat='".$cat->getID()."' AND b.id_post=a.post AND b.aprovado='1' AND b.estado>'0' ORDER BY b.modificado DESC LIMIT 0,$limit";
		
	}
	
	// Generamos los elementos
	$result = $db->query($sql);
	$posts = array();
	while ($row = $db->fetchArray($result)){
		$post = new MWPost();
		$post->assignVars($row);
		$rtn = array();
		$rtn['title'] = $post->getTitle();
		$rtn['link'] = $post->getPermaLink();
		$rtn['description'] = xoops_utf8_encode(htmlspecialchars($post->getHomeText(), ENT_QUOTES));
		$rtn['pubDate'] = formatTimestamp($post->getDate());
		$posts[] = $rtn;
	}
	
	$ret = array('feed'=>$feed, 'items'=>$posts);
	return $ret;
	
}
