<?php
// $Id$
// --------------------------------------------------------------
// Quick Pages
// Create simple pages easily and quickly
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$xoopsOption['template_main'] = 'qpages_index.html';
$xoopsOption['module_subpage'] = 'index';
require 'header.php';

$tpl->assign('page_title', $xoopsModule->name());
$location = '<a href="'.QP_URL.'" title="'.$xoopsModule->name().'">'.__('Main','qpages').'</a>';
$tpl->assign('page_location',$location);
$tpl->assign('xoops_pagetitle',$xoopsModule->name() . ' &raquo; ' . __('Main','qpages'));
$tpl->assign('home_text', $mc['texto']);

$result = $db->query("SELECT * FROM ".$db->prefix("qpages_categos")." WHERE parent='0' ORDER BY nombre ASC");
$categos = array();
qpArrayCategos($categos);

while ($k = $db->fetchArray($result)){
	
	$catego = new QPCategory();
	$catego->assignVars($k);
	$lpages = $catego->loadPages();
	$pages = array();
	foreach ($lpages as $p){
		$ret = array();
		$ret['titulo'] = $p['titulo'];
		$ret['desc'] = TextCleaner::getInstance()->clean_disabled_tags($p['desc']);
		$ret['link'] = $mc['links'] ? QP_URL.'/'.$p['titulo_amigo'].'/' : QP_URL.'/page.php?page='.$p['titulo_amigo'];
		$pages[] = $ret;
	}
	$link = $catego->getLink();
	$subcats = $catego->getSubcategos();
	$tpl->append('categos', array('id'=>$catego->getID(),'nombre'=>$catego->getName(),'desc'=>$catego->getDescription(),
			'pages'=>$pages,'pages_count'=>count($lpages), 'link'=>$link,
			'subcats'=>count($subcats)>0 ? $subcats : '','subcats_count'=>count($subcats)));
	
}

$tpl->assign('lang_subcats',__('Subcategories','qpages'));
$tpl->assign('lang_pagesin', __('Pages in this category','qpages'));

require 'footer.php';
