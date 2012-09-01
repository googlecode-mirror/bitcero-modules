<?php
// $Id: index.php 1005 2012-07-12 05:40:46Z i.bitcero $
// --------------------------------------------------------------
// D-Transport
// Manage files for download in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$xoopsOption['template_main'] = 'dtrans_explore.html';
$xoopsOption['module_subpage'] = 'explore-'.$explore;

$ev = RMEvents::get();
$accepted = $ev->run_event('dtransport.exploring.accepted', array('mine','recent','popular','rated','publisher'));


if(!isset($explore) || !in_array($explore, $accepted)){
    if($mc['permalinks'])
        $dtfunc->error_404();
    else
        redirect_header(DT_URL, 1, __('Invalid parameters','dtransport'));
}

// Comprobamos el usuario
if(!$xoopsUser && $explore=='mine')
    redirect_header(DT_URL, 1, __('You are not authorized to view this section!','dtransport'));

$titles = array(
    'mine' => __('My Downloads','dtransport'),
    'recent' => __('Recent Downloads','dtransport'),
    'popular' => __('Most Downloaded','dtransport'),
    'rated' => __('Best Rated Downloads','dtransport'),
    'publisher' => __('Downloads of %s','dtransport')
);
$titles = $ev->run_event('dtransport.exploring.titles', $titles);

include 'header.php';

// Preparamos la consulta SQL
$sql = "SELECT COUNT(*) FROM ".$db->prefix("dtrans_software")." WHERE approved=1 AND `delete`=0";
switch($explore){
    case 'mine':
        $sql .= " AND uid=".$xoopsUser->uid()." ORDER BY `created`,`modified` DESC";
        break;
    case 'recent':
        $sql .= " ORDER BY `created` DESC";
        break;
    case 'popular':
        $sql .= " ORDER BY `hits` DESC";
        break;
    case 'rated':
        $sql .= " ORDER BY rating,votes DESC";
        break;
    case 'publisher':
        $sql = " AND uid=".$publisher->uid()." ORDER BY `created`,`modified` DESC";
        break;
}

$sql = $ev->run_event("dtransport.explore.count", $sql);

list($num) = $db->fetchRow($db->query($sql));

$limit = $mc['xpage'];
$limit = $limit<=0 ? 10 : $limit;
$tpages = ceil($num / $limit);

if ($tpages<$page && $tpages>0){
    header('location: '.DT_URL.($mc['permalinks']?'/'.$explore:'/?p=explore&f='.$explore));
    die();
}

$p = $page>0 ? $page-1 : $page;
$start = $num<=0 ? 0 : $p * $limit;

$nav = new RMPageNav($num, $limit, $page);
$nav->target_url(DT_URL.($mc['permalinks']?'/'.$explore.'/page/{PAGE_NUM}/':'/?p=explore&amp;f='.$explore.'&amp;page={PAGE_NUM}'));
$xoopsTpl->assign('pagenav', $nav->render(true));

$sql = str_replace("COUNT(*)",'*',$sql);
$sql .= " LIMIT $start,$limit";
$result = $db->query($sql);

while($row = $db->fetchArray($result)){
    $item = new DTSoftware();
    $item->assignVars($row);
    $xoopsTpl->append('items', $dtfunc->createItemData($item));
}

if($mc['inner_dest_download'])
    $xoopsTpl->assign('featured_items', $dtfunc->get_items(0, 'featured', $mc['limit_destdown']));

// Descargas el día
if($mc['inner_daydownload']){
    $xoopsTpl->assign('lang_daydown', __('<strong>Day</strong> Downloads','dtransport'));
    $xoopsTpl->assign('daily_items', $dtfunc->get_items(0, 'daily', $mc['limit_daydownload']));
    $xoopsTpl->assign('daily_width', floor(100/($mc['limit_daydownload'])));
}

// Idioma
$xoopsTpl->assign('lang_featured',__('<strong>Featured</strong> Downloads','dtransport'));
$xoopsTpl->assign('lang_download', __('Download','dtransport'));
$xoopsTpl->assign('lang_in', __('<strong>Available</strong> Downloads','dtransport'));

$dtfunc->makeHeader($titles[$explore]);

include 'footer.php';
