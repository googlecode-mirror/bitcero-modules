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

$tpl->assign('works_subpage', 'index');

//Barra de Navegación
$sql = "SELECT COUNT(*) FROM ".$db->prefix('pw_works')." WHERE public=1";
	
list($num)=$db->fetchRow($db->query($sql));

$limit = $mc['num_recent'];
$limit = $limit<=0 ? 10 : $limit;
if (!isset($page)) $page = rmc_server_var($_GET, 'page', 1);

$tpages = ceil($num/$limit);
$page = $page > $tpages ? $tpages : $page; 
$start = $num<=0 ? 0 : ($page - 1) * $limit;
$start = $start<0 ? 0 : $start;

$nav = new RMPageNav($num, $limit, $page, 5);
$url = $xoopsModuleConfig['urlmode'] ? XOOPS_URL.rtrim($xoopsModuleConfig['htbase'],'/').'/page/{PAGE_NUM}/' : XOOPS_URL.'/modules/works/?page={PAGE_NUM}';
$nav->target_url($url);
$tpl->assign('navpage', $nav->render(false));

//Fin de barra de navegación

//Obtenemos los trabajos recientes
$sql = "SELECT * FROM ".$db->prefix('pw_works')." WHERE public=1 ORDER BY created DESC LIMIT $start,$limit";
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

	$tpl->append('works',array(
        'id'=>$recent->id(),
        'title'=>$recent->title(),
        'desc'=>$recent->descShort(),
	    'catego'=>$categos[$recent->category()]->name(),
        'client'=>$clients[$recent->client()]->businessName(),
        'link'=>$link,
	    'created'=>formatTimeStamp($recent->created(),'s'),
        'image'=>XOOPS_UPLOAD_URL.'/works/ths/'.$recent->image(),
	    'rating'=>PWFunctions::rating($recent->rating()),
        'featured'=>$recent->mark(),
        'linkcat'=>$link_cat,
        'metas' => $recent->get_metas()
    ));
}


$tpl->assign('lang_works',__('Our Work','works'));
$tpl->assign('lang_catego',__('Cetegory:','works'));
$tpl->assign('lang_date',__('Date:','works'));
$tpl->assign('lang_client',__('Customer:','works'));
$tpl->assign('lang_allsrecent',__('View all recent works','works'));
$tpl->assign('link_recent',PW_URL.($mc['urlmode'] ? '/recent/' : '/recent.php'));
$tpl->assign('link_featured',PW_URL.($mc['urlmode'] ? '/featured/' : '/featured.php'));
$thSize = $mc['image_ths'];
$tpl->assign('width',$thSize[0]+10);
$tpl->assign('lang_featured', __('Featured','works'));


include 'footer.php';
