<?php
// $Id$
// --------------------------------------------------------------
// Quick Pages
// Create simple pages easily and quickly
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

if (!defined("XOOPS_MAINFILE_INCLUDED")){
	require '../../mainfile.php';
	$header = array();
	foreach ($_REQUEST as $k => $v){
		$header[$k] = $v;
	}
}

$xoopsOption['template_main'] = 'qpages_categos.html';
$xoopsOption['module_subpage'] = 'catego';
require 'header.php';

if (isset($_REQUEST['cat'])){
	$path = explode('/',$_REQUEST['cat']);
} else {
	$path = explode('/',str_replace('category/','',$request));
}

$tbl = $db->prefix("qpages_categos");

$idp = 0; # ID de la categoria padre
$rutas = array();
foreach ($path as $k){
	if ($k=='' || substr($k, 0,1)=='?') continue;
	$sql = "SELECT id_cat FROM $tbl WHERE nombre_amigo='$k' AND parent='$idp'";
	$result = $db->query($sql);
	if ($db->getRowsNum($result)>0){
		list($idp) = $db->fetchRow($result);
		$rutas[] = new QPCategory($idp);
	} else {
		redirect_header(QP_URL, 2, __('Category not found!','qpages'));
		die();
	}
}

$catego = new QPCategory($idp);

if ($catego->isNew()){
	redirect_header(QP_URL, 2, __('Category not found!','qpages'));
	die();
}

// Asignamos datos de la categoría
$tpl->assign('qpcategory', array('id'=>$catego->getID(),'name'=>$catego->getName(),'nameid'=>$catego->getFriendName()));

$location = '<a href="'.QP_URL.'" title="'.$xoopsModule->name().'">'.__('Main page','qpages').'</a> ';
$pt = array(); // Titulo de la página
$pt[] = $xoopsModule->name();
foreach($rutas as $k){
	$location .= '&raquo; <a href="'.$k->getLink().'">'.$k->getName().'</a> ';
	$pt[] = $k->getName();
}

$pagetitle = '';
for ($i=count($pt)-1;$i>=0;$i--){
	$pagetitle .= $pagetitle=='' ? $pt[$i] : " &laquo; $pt[$i]";
}

$tpl->assign('page_location',$location);
$tpl->assign('page_title',$catego->getName());
$tpl->assign('xoops_pagetitle', $pagetitle);
$tpl->assign('lang_pages', sprintf(__('Pages in %s', 'qpages'), $catego->getName()));

/**
 * Cargamos las páginas
 */
$lpages = $catego->loadPages();
$pages = array();
foreach ($lpages as $p){
	$ret = array();
	$rp = new QPPage();
	$rp->assignVars($p);
	$tpl->append('pages', array('id'=>$rp->getID(),'link'=>$rp->getPermaLink(),'title'=>$rp->getTitle(),
				'modified'=>formatTimestamp($rp->getModDate(),'l'),
				'hits'=>$rp->getReads(),'desc'=>$rp->getDescription()));
}
$tpl->assign('page_count', count($lpages));
unset($pages);

// Subcategorias
$result = $db->query("SELECT * FROM ".$db->prefix("qpages_categos")." WHERE parent = ".$catego->getID());
$tpl->assign('subcats_count', $db->getRowsNum($result));
while ($k = $db->fetchArray($result)){
	
	$cat = new QPCategory();
	$cat->assignVars($k);
	$lpages = $cat->loadPages();
	$pages = array();
	foreach ($lpages as $p){
		$page = new QPPage();
		$page->assignVars($p);
		$ret = array();
		$ret['titulo'] = $page->getTitle();
		$ret['desc'] = $page->getDescription();
		$ret['link'] = $page->getPermaLink();
		$pages[] = $ret;
	}
	$link = $cat->getLink();
	$subcats = $cat->getSubcategos();
	$tpl->append('categos', array('id'=>$cat->getID(),'nombre'=>$cat->getName(),'desc'=>$cat->getDescription(),
			'pages_count'=>sprintf(__('%u pages','qpages'), count($lpages)),'link'=>$link,
			'subcats'=>count($subcats)>0 ? $subcats : '','subcats_count'=>count($subcats)));
	
}

$tpl->assign('lang_subcats',__('Subcategories','qpages'));
$tpl->assign('lang_page', __('Page','qpages'));
$tpl->assign('lang_modified', __('Last update','qpages'));
$tpl->assign('lang_hits', __('Reads','qpages'));

require 'footer.php';
