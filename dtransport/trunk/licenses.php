<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage files for download in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$xoopsOption['template_main'] = 'dtrans_tags.html';
$xoopsOption['module_subpage'] = 'licenses';

if ($lic==''){
    redirect_header(DT_URL, 1, __('Platform not specified!','dtransport'));
    die();
}

include 'header.php';

$lic = new DTLicense($lic);

// Descargas en esta etiqueta
$sql = "SELECT COUNT(*) FROM ".$db->prefix('dtrans_licsoft')." AS a INNER JOIN ".$db->prefix('dtrans_software')." b ON (a.id_lic=".$lic->id()." AND a.id_soft=b.id_soft) WHERE b.approved='1' ANd b.delete=0";
list($num) = $db->fetchRow($db->query($sql));

$limit = $mc['xpage'];
$limit = $limit<=0 ? 10 : $limit;

$tpages = ceil($num / $limit);

if ($tpages<$page && $tpages>0){
    header('location: '.DT_URL.($mc['permalinks']?'/license/'.$lic->nameId():'/?p=license&lic='.$lic->id()));
    die();
}

$p = $page>0 ? $page-1 : $page;
$start = $num<=0 ? 0 : $p * $limit;

$nav = new RMPageNav($num, $limit, $page);
$nav->target_url(DT_URL.($mc['permalinks']?'/license/'.$lic->nameId().'/page/{PAGE_NUM}/':'/?p=license&amp;lic='.$lic->id().'&amp;page={PAGE_NUM}'));
$xoopsTpl->assign('pagenav', $nav->render(true));

// Seleccionamos los registros
$sql = str_replace('COUNT(*)', 'b.*', $sql);
$sql .= " ORDER BY created DESC";

$sql .= " LIMIT $start, $limit";

$result = $db->query($sql);

while ($row = $db->fetchArray($result)){
    $item = new DTSoftware();
    $item->assignVars($row);
    $xoopsTpl->append('download_items', $dtfunc->createItemData($item));
}

// Datos de la etiqueta
$xoopsTpl->assign('license', array('id'=>$lic->id(),'name'=>$lic->name(),'link'=>$lic->permalink()));

$tpl->add_xoops_style('main.css','dtransport');

$dtfunc->makeHeader();
$xoopsTpl->assign('xoops_pagetitle', sprintf(__('Downloads licensed as %s', 'dtransport'), $lic->name()));

if($mc['inner_dest_download']){
    $xoopsTpl->assign('featured_items', $dtfunc->items_by(array($lic->id()), 'licenses', 0, 'featured', 0, $mc['limit_destdown']));
    $xoopsTpl->assign('lang_incatego', __('In <a href="%s">%s</a>','dtransport'));
    $xoopsTpl->assign('lang_featured',__('<strong>Featured</strong> Downloads','dtransport'));
}

// Descargas el día
if($mc['inner_daydownload']){
    $xoopsTpl->assign('daily_items', $dtfunc->items_by($lic->id(), 'licenses', 0, 'daily', 0, $mc['limit_daydownload']));
    $xoopsTpl->assign('daily_width', floor(100/($mc['limit_daydownload'])));
    $xoopsTpl->assign('lang_daydown', __('<strong>Day</strong> Downloads','dtransport'));
}

$xoopsTpl->assign('lang_download', __('Download','dtransport'));
$xoopsTpl->assign('lang_in', sprintf(__('<strong>Downloads</strong> licenced as "%s"', 'dtransport'), $lic->name()));

include 'footer.php';

