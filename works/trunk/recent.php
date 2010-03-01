<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$xoopsOption['template_main'] = 'pw_recent.html';
$xoopsOption['module_subpage'] = 'recent';
include 'header.php';

PWFunctions::makeHeader();

//Barra de Navegación
$sql = "SELECT COUNT(*) FROM ".$db->prefix('pw_works')." WHERE public=1";
	
list($num)=$db->fetchRow($db->query($sql));
	
$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 10;
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
	$nav = new XoopsPageNav($num, $limit, $start, 'pag', 'limit='.$limit, 0);
	$tpl->assign('recentNavPage', $nav->renderNav(4, 1));
}

$showmax = $start + $limit;
$showmax = $showmax > $num ? $num : $showmax;
$tpl->assign('lang_showing', sprintf(_MS_PW_SHOWING, $start + 1, $showmax, $num));
$tpl->assign('limit',$limit);
$tpl->assign('pag',$pactual);
//Fin de barra de navegación



$sql = "SELECT * FROM ".$db->prefix('pw_works')." WHERE public=1 ORDER BY created DESC";
$sql.= " LIMIT $start,$limit";
$result = $db->query($sql);
$categos = array();
$clients = array();
while ($row = $db->fetchArray($result)){
	$recent = new PWWork();
	$recent->assignVars($row);

	if (!isset($categos[$recent->category()])) $categos[$recent->category()] = new PWCategory($recent->category());

	if (!isset($clients[$recent->client()])) $clients[$recent->client()] = new PWClient($recent->client());

	$link = PW_URL.($mc['urlmode'] ? '/'.$recent->title_id().'/' : '/work.php?id='.$recent->id());
	$link_cat = PW_URL.($mc['urlmode'] ? '/category/'.$categos[$recent->category()]->nameId().'/' : '/catego.php?id='.$categos[$recent->category()]->nameId());

	$tpl->append('recents',array('id'=>$recent->id(),'title'=>$recent->title(),'desc'=>$recent->descShort(),
	'catego'=>$categos[$recent->category()]->name(),'client'=>$clients[$recent->client()]->name(),'link'=>$link,
	'created'=>formatTimeStamp($recent->created(),'s'),'image'=>XOOPS_UPLOAD_URL.'/works/ths/'.$recent->image(),
	'rating'=>PWFunctions::rating($recent->rating()),'featured'=>$recent->mark(),'linkcat'=>$link_cat));

}
$tpl->assign('lang_recents',_MS_PW_RECENTS);
$tpl->assign('xoops_pagetitle',_MS_PW_RECENTS." &raquo; ".$mc['title']);
$tpl->assign('lang_date',_MS_PW_DATE);
$tpl->assign('lang_catego',_MS_PW_CATEGO);
$tpl->assign('lang_client',_MS_PW_CLIENT);
$tpl->assign('lang_rating',_MS_PW_RATING);
$thSize = $mc['image_ths'];
$tpl->assign('width',$thSize[0]+20);
$tpl->assign('lang_featured', _MS_PW_FEAT);

include 'footer.php';
