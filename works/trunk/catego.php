<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$xoopsOption['template_main'] = 'pw_catego.html';
$xoopsOption['module_subpage'] = 'category';
include 'header.php';

$mc =& $xoopsModuleConfig;

if ($id==''){
	redirect_header(PW_URL.'/', 2, _MS_PW_ERRIDC);
	die();
}

//Verificamos si la categoría existe
$cat = new PWCategory($id);
if ($cat->isNew()){
	redirect_header(PW_URL.'/', 2, _MS_PW_ERRIDC);
	die();
}

// Category
$tpl->assign('category', array('id'=>$cat->id(),'title'=>$cat->name(),'name'=>$cat->nameId(),'desc'=>$cat->desc()));

//Barra de Navegación
$sql = "SELECT COUNT(*) FROM ".$db->prefix('pw_works')." WHERE public=1 AND catego='".$cat->id()."'";

list($num)=$db->fetchRow($db->query($sql));

$page = isset($vars[3]) ? $vars[3] : '';
$limit = $mc['num_recent'];
$limit = $limit<=0 ? 10 : $limit;

if ($page > 0){ $page -= 1; }
$start = $page * $limit;
$tpages = (int)($num / $limit);
if($num % $limit > 0) $tpages++;
$pactual = $page + 1;
if ($pactual>$tpages){
	$rest = $pactual - $tpages;
	$pactual = $pactual - $rest + 1;
	$start = ($pactual - 1) * $limit;
}
	
if ($tpages > 1) {
	$url = $mc['urlmode'] ? 'category/'.$cat->nameId() : 'catego.php?id='.$cat->nameId();

	$nav = new PWPageNav($num, $limit, $start, 'pag',$url, 0);
	$tpl->assign('worksNavPage', $nav->renderNav(4, 1));
}

$showmax = $start + $limit;
$showmax = $showmax > $num ? $num : $showmax;
$tpl->assign('lang_showing', sprintf(_MS_PW_SHOWING, $start + 1, $showmax, $num));
$tpl->assign('limit',$limit);
$tpl->assign('pag',$pactual);
//Fin de barra de navegación



$sql = "SELECT * FROM ".$db->prefix('pw_works')." WHERE public=1 AND catego='".$cat->id()."'";
$sql.= " LIMIT $start,$limit";
$result = $db->query($sql);

// Numero de resultados en esta página
$t = $db->getRowsNum($result);
$tpl->assign('page_total', $t);
$tpl->assign('per_col', ceil($t/2));

$categos = array();
$clients = array();
while ($row = $db->fetchArray($result)){
	$work = new PWWork();
	$work->assignVars($row);

	if (!isset($categos[$work->category()])) $categos[$work->category()] = new PWCategory($work->category());

	if (!isset($clients[$work->client()])) $clients[$work->client()] = new PWClient($work->client());

	$link = PW_URL.($mc['urlmode'] ? '/'.$work->title_id().'/' : '/work.php?id='.$work->id());
	$link_cat = PW_URL.($mc['urlmode'] ? '/category/'.$categos[$work->category()]->nameId().'/' : '/catego.php?id='.$categos[$work->category()]->nameId());

	$tpl->append('works',array('id'=>$work->id(),'title'=>$work->title(),'desc'=>$work->descShort(),
	'catego'=>$categos[$work->category()]->name(),'client'=>$clients[$work->client()]->name(),'link'=>$link,
	'created'=>formatTimeStamp($work->created(),'s'),'image'=>XOOPS_UPLOAD_URL.'/works/ths/'.$work->image(),
	'rating'=>PWFunctions::rating($work->rating()),'featured'=>$work->mark(),'linkcat'=>$link_cat));
}
$tpl->assign('lang_works',sprintf(_MS_PW_WORKS,$cat->name()));
$tpl->assign('xoops_pagetitle', sprintf(_MS_PW_WORKS,$cat->name())." &raquo; ".$mc['title']);
$tpl->assign('lang_catego',_MS_PW_CATEGO);
$tpl->assign('lang_date',_MS_PW_DATE);
$tpl->assign('lang_client',_MS_PW_CLIENT);
$tpl->assign('lang_rating',_MS_PW_RATING);
$thSize = $mc['image_ths'];
$tpl->assign('width',$thSize[0]+20);
$tpl->assign('lang_featured', _MS_PW_FEAT);

PWFunctions::makeHeader();
include 'footer.php';
