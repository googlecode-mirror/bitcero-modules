<?php
// $Id: rss.php 71 2009-02-11 12:01:22Z BitC3R0 $
// --------------------------------------------------------------
// Foros EXMBB
// Módulo para el manejo de Foros en EXM
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
// --------------------------------------------------------------
// @copyright: 2007 - 2008 Red México

function exmbb_rssdesc(){
	global $util;
	
	$mc =& $util->moduleConfig('exmbb');
	return $mc['rssdesc'];
}

/**
* @desc Muestra el menu de opciones para sindicación
* @param int $limit Limite de resultados solicitados. 0 Indica ilimitado
* @param bool $more Referencia. Debe devolver true si existen mas resultados que el límite deseado
* @return array
*/
function &exmbb_rssfeed($limit, &$more){
	global $db;
	$limit = $limit > 0 ? $limit-1 : 0;
	include_once XOOPS_ROOT_PATH.'/modules/exmbb/class/bbforum.class.php';
	
	$ret = array();
	$rtn = array();
	$ret['name'] = _MI_BB_RSSALL;
	$ret['desc'] = _MI_BB_RSSALLDESC;
	$ret['params'] = "show=all";
	$rtn[] = $ret;
	
	$sql = "SELECT COUNT(*) FROM ".$db->prefix("exmbb_forums")." ORDER BY cat, `order`";
	list($num) = $db->fetchRow($db->query($sql));
	if ($num>$limit && $limit > 0) $more = true;
	
	$sql = str_replace("COUNT(*)",'*', $sql);
	if ($limit> 0) $sql .= " LIMIT 0, $limit";
	$result = $db->query($sql);
	while ($row = $db->fetchArray($result)){
		$forum = new BBForum();
		$forum->assignVars($row);
		$ret = array();
		$ret['name'] = $forum->name();
		$ret['desc'] = $forum->description();
		$ret['params'] = "show=forum&amp;id=".$forum->id();
		$rtn[] = $ret;
	}
	return $rtn;
	
}

/**
* @desc Genera la información para mostrar la Sindicación
* @param int Limite de resultados
* @return Array
*/
function &exmbb_rssshow($limit){
	global $util, $mc;
	
	$db =& Database::getInstance();
	include_once XOOPS_ROOT_PATH.'/modules/exmbb/class/bbforum.class.php';
	include_once XOOPS_ROOT_PATH.'/modules/exmbb/class/bbpost.class.php';
	
	foreach ($_GET as $k => $v){
		$$k = $v;
	}
	
	$feed = array();		// Información General
	$ret = array();
	$mc =& $util->moduleConfig('exmbb');
	
	if ($show == 'all'){
		$feed['title'] = htmlspecialchars(_MI_BB_RSSALL);
		$feed['link'] = XOOPS_URL.'/modules/exmbb';
		$feed['description'] = htmlspecialchars($util->filterTags($mc['rssdesc']));
		
		$sql = "SELECT a.*, b.title FROM ".$db->prefix("exmbb_posts")." a,".$db->prefix("exmbb_topics")." b WHERE 
				a.approved='1' AND b.id_topic=a.id_topic ORDER BY a.post_time DESC LIMIT 0,$limit";
	} else{
		
		if ($id<=0) return;
		$forum = new BBForum($id);
		if ($forum->isNew()) return;
		
		$feed['title'] = htmlspecialchars(sprintf(_MI_BB_RSSNAMEFORUM, $forum->name()));
		$feed['link'] = XOOPS_URL.'/modules/exmbb/forum.php?id='.$forum->id();
		$feed['description'] = htmlspecialchars($forum->description());
		
		$sql = "SELECT a.*, b.title FROM ".$db->prefix("exmbb_posts")." a,".$db->prefix("exmbb_topics")." b WHERE a.id_forum='$id' AND a.approved='1' AND b.id_topic=a.id_topic ORDER BY a.post_time DESC LIMIT 0,$limit";
		
	}
	
	// Generamos los elementos
	$result = $db->query($sql);
	$posts = array();
	while ($row = $db->fetchArray($result)){
		$post = new BBPost();
		$post->assignVars($row);
		$rtn = array();
		$rtn['title'] = htmlspecialchars($row['title']);
		$rtn['link'] = htmlspecialchars(XOOPS_URL.'/modules/exmbb/topic.php?pid='.$post->id()."#p".$post->id(), ENT_QUOTES);
		$rtn['description'] = utf8_encode(($post->text()));
		$rtn['date'] = formatTimestamp($post->date());
		$posts[] = $rtn;
	}

	
	$ret = array('feed'=>$feed, 'items'=>$posts);
	return $ret;
	
}
?>
