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

load_mod_locale('qpages');

$xoopsOption['template_main'] = 'qpages_page.html';
$xoopsOption['module_subpage'] = 'page';

if (isset($_REQUEST['page'])){
    $nombre = explode('/',$_REQUEST['page']);
} else {
    $nombre = explode('/',$request);
}

$nombre[0] = TextCleaner::sweetstring($nombre[0]);

$page = new QPPage($nombre[0]);
if ($page->isNew() || $page->getAccess()==0){
	redirect_header(QP_URL, 2, _MS_QP_NOTFOUNDPAGE);
	die();
}

if (!in_array(0, $page->getGroups())){
	if (empty($xoopsUser)){
		redirect_header(QP_URL, 2, _MS_QP_NOALLOWED);
		die();
	} else {
		$ok = false;
		foreach ($xoopsUser->getGroups() as $k){
			if ($ok) continue;
			if (in_array($k, $page->getGroups())){
				$ok = true;
			}
		}
		if (!$ok && !$xoopsUser->isAdmin()){
			redirect_header(QP_URL, 2, _MS_QP_NOALLOWED);
			die();
		}
	}
}

if ($page->type()){
	header('location: '.$page->url());
	die();
}

$catego = new QPCategory($page->getCategory());

require 'header.php';

// Asignamos datos de la categoría
$tpl->assign('qpcategory', array('id'=>$catego->getID(),'name'=>$catego->getName(),'nameid'=>$catego->getFriendName()));

$idp = 0; # ID de la categoria padre
$rutas = array();
$path = explode('/',$catego->getPath());
$tbl = $db->prefix("qpages_categos");
foreach ($path as $k){
	if ($k=='') continue;
	$sql = "SELECT id_cat FROM $tbl WHERE nombre_amigo='$k' AND parent='$idp'";
	$result = $db->query($sql);
	if ($db->getRowsNum($result)>0){
		list($idp) = $db->fetchRow($result);
		$rutas[] = new QPCategory($idp);
	}
}

$location = '<a href="'.QP_URL.'" title="'.$xoopsModule->name().'">'.$xoopsModule->name().'</a> ';
$pt = array(); // Titulo de la página
$pt[] = $xoopsModule->name();
foreach($rutas as $k){
	$location .= '&raquo; <a href="'.$k->getLink().'">'.$k->getName().'</a> ';
	$pt[] = $k->getName();
}
$location .= '&raquo; '.$page->getTitle();
$pt[] = $page->getTitle();
$pagetitle = '';
for ($i=count($pt)-1;$i>=0;$i--){
	$pagetitle .= $pagetitle=='' ? $pt[$i] : " &laquo; $pt[$i]";
}

$tpl->assign('page_location',$location);
$tpl->assign('xoops_pagetitle', $pagetitle);
$page->addRead();
$tpl->assign('page', array(
	'title'		=> $page->getTitle(),
	'text'		=> $page->getText(),
	'id'		=> $page->getID(),
	'name'		=> $page->getFriendTitle(),
	'mod_date'	=> sprintf(__('Last update: %s', 'qpages'), formatTimestamp($page->getModDate(),'c')),
	'reads'		=> sprintf(__('Read %u times','qpages'), $page->getReads()),
	'metas'		=> $page->get_meta()
));

// Páginas relacionadas
if ($mc['related']){
	$sql = "SELECT * FROM ".$db->prefix("qpages_pages")." WHERE cat='".$catego->getID()."' AND id_page<>'".$page->getID()."' ORDER BY RAND() DESC LIMIT 0,$mc[related_num]";
	$result = $db->query($sql);
	$tpl->assign('related_num', $db->getRowsNum($result));
	while ($row = $db->fetchArray($result)){
		$rp = new QPPage();
		$rp->assignVars($row);
		$tpl->append('related', array('id'=>$rp->getID(),'link'=>$rp->getPermaLink(),'title'=>$rp->getTitle(),
				'modified'=>formatTimestamp($rp->getModDate(),'c'),
				'hits'=>$rp->getReads(),'desc'=>$rp->getDescription()));
	}
}

$tpl->assign('show_related', $mc['related']);
$tpl->assign('lang_related', __('Related Pages','qpages'));
$tpl->assign('lang_page', __('Page','qpages'));
$tpl->assign('lang_modified', __('Last modification','qpages'));
$tpl->assign('lang_hits', __('Hits','qpages'));

RMEvents::get()->run_event('qpages.view.page', $page);

require 'footer.php';
