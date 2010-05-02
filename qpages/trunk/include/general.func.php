<?php
// $Id$
// --------------------------------------------------------------
// Quick Pages
// Create simple pages easily and quickly
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
 * Función para obtener las categorías en un array
 */
function qpArrayCategos(&$ret,$saltos=0,$parent=0, $exclude=null){
	
	$db = Database::getInstance();
	
	$result = $db->query("SELECT * FROM ".$db->prefix("qpages_categos")." WHERE parent='$parent' ORDER BY `id_cat`");
	
	while ($row = $db->fetchArray($result)){
		if (is_array($exclude) && (in_array($row['parent'], $exclude) || in_array($row['id_cat'], $exclude))){
			$exclude[] = $row['id_cat'];
		} else {
			$rtn = array();
			$rtn = $row;
			$rtn['saltos'] = $saltos;
			$ret[] = $rtn;
		}
		qpArrayCategos($ret, $saltos + 1, $row['id_cat'], $exclude);
	}
	
	return true;
	
}
/**
 * Esta función permite cargar una página específica y desplegar
 * toda su información
 */
function getHomePage($page){
	global $xoopsOption, $xoopsConfig;
	require_once XOOPS_ROOT_PATH.'/mainfile.php';
	
	$db =& Database::getInstance();
	
	$result = $db->query("SELECT * FROM ".$db->prefix("qpages_pages")." WHERE titulo_amigo='$page'");
	if ($db->getRowsNum($result)<=0) return;
	
	$row = $db->fetchArray($result);
	
	$xoopsOption['template_main'] = 'qpages_homepage.html';
	require_once XOOPS_ROOT_PATH.'/header.php';
	$tpl =& $xoopsTpl;
	$groups = explode(',',$row['grupos']);
	
	if (empty($xoopsUser)){
		if (!in_array(0, $groups)){
			return;
		}
	} else {
		$ok = false;
		foreach ($xoopsUser->getGroups() as $k){
			if ($ok) continue;
			if (in_array($k, $groups)){
				$ok = true;
			}
		}
		if (!$ok && !$xoopsUser->isAdmin()){
			return;
		}
	}
	
	$tpl->assign('xoops_pagetitle', $row['titulo']);
	$tpl->assign('page', array(
		'title'		=> $row['titulo'],
		'text'		=> MyTextSanitizer::displayTarea($row['texto'], $row['dohtml'], $row['dosmiley'], $row['doxcode'], $row['doimage'], $row['dobr']),
		'id'		=> $row['id_page'],
		'name'		=> $row['titulo_amigo']
	));
	
	require_once XOOPS_ROOT_PATH.'/footer.php';
}

function qp_get_metas(){
	$db = Database::getInstance();
	$result = $db->query("SELECT name FROM ".$db->prefix("qpages_meta")." GROUP BY name");
	$ret = array();
	while($row = $db->fetchArray($result)){
		$ret[] = $row['name'];
	}
	return $ret;
}

function qpages_toolbar(){
	RMTemplate::get()->add_tool(__('Dashboard','qpages'), './index.php', '../images/status.png', 'dashboard');
	RMTemplate::get()->add_tool(__('Categories','qpages'), './cats.php', '../images/cats.png', 'categories');
	RMTemplate::get()->add_tool(__('Pages','qpages'), './pages.php', '../images/pages.png', 'pages');
}