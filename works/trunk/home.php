<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('PW_LOCATION','index');

$xoopsOption['template_main'] = 'pw_index.html';
$xoopsOption['module_subpage'] = 'index';
include 'header.php';

PWFunctions::makeHeader();

//Barra de Navegación
$sql = "SELECT COUNT(*) FROM ".$db->prefix('pw_works')." WHERE public=1";
	
list($num)=$db->fetchRow($db->query($sql));
	

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
	$url = $mc['urlmode'] ? '' : 'index.php';

	$nav = new PWPageNav($num, $limit, $start, 'pag',$url, 0);
	$tpl->assign('worksNavPage', $nav->renderNav(4, 1));
}

$showmax = $start + $limit;
$showmax = $showmax > $num ? $num : $showmax;
$tpl->assign('lang_showing', sprintf(_MS_PW_SHOWING, $start + 1, $showmax, $num));
$tpl->assign('limit',$limit);
$tpl->assign('pag',$pactual);
//Fin de barra de navegación

//Obtenemos los trabajos recientes
$sql = "SELECT * FROM ".$db->prefix('pw_works')." WHERE public=1 ORDER BY created DESC LIMIT $start,".$mc['num_recent'];
$result = $db->query($sql);

// Numero de resultados en esta página
$t = $db->getRowsNum($result);
$tpl->assign('page_total', $t);
$tpl->assign('per_col', ceil($t/2));

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
	'catego'=>$categos[$recent->category()]->name(),'client'=>$clients[$recent->client()]->businessName(),'link'=>$link,
	'created'=>formatTimeStamp($recent->created(),'s'),'image'=>XOOPS_UPLOAD_URL.'/works/ths/'.$recent->image(),
	'rating'=>PWFunctions::rating($recent->rating()),'featured'=>$recent->mark(),'linkcat'=>$link_cat));
}


$tpl->assign('lang_recents',_MS_PW_RECENTS);
$tpl->assign('lang_catego',_MS_PW_CATEGO);
$tpl->assign('lang_date',_MS_PW_DATE);
$tpl->assign('lang_client',_MS_PW_CLIENT);
$tpl->assign('lang_featureds',_MS_PW_FEATUREDS);
$tpl->assign('lang_allsrecent',_MS_PW_ALLSRECENT);
$tpl->assign('lang_allsfeat',_MS_PW_ALLSFEAT);
$tpl->assign('lang_rating',_MS_PW_RATING);
$tpl->assign('link_recent',PW_URL.($mc['urlmode'] ? '/recent/' : '/recent.php'));
$tpl->assign('link_featured',PW_URL.($mc['urlmode'] ? '/featured/' : '/featured.php'));
$thSize = $mc['image_ths'];
$tpl->assign('width',$thSize[0]+10);
$tpl->assign('lang_featured', _MS_PW_FEAT);


include 'footer.php';
