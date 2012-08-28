<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage files for download in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$xoopsOption['template_main'] = 'dtrans_category.html';
$xoopsOption['module_subpage'] = 'category';
include 'header.php';

if($mc['permalinks']){

    if(!is_numeric($page))
        $dtfunc->error_404();

    if($path=='')
        redirect_header(DT_URL, 2, __('Category not specified!','dtransport'));

    $idp = 0; # ID de la categoria padre
    $path = explode("/", $path);
    foreach ($path as $k){

        if ($k=='') continue;

        $category = new DTCategory();
        $sql = "SELECT * FROM ".$db->prefix("dtrans_categos")." WHERE nameid='$k' AND parent='$idp'";
        $result = $db->query($sql);

        if ($db->getRowsNum($result)>0){
            $row = $db->fetchArray($result);
            $idp = $row['id_cat'];
            $category->assignVars($row);
        } else {
            $dtfunc->error_404();
        }

    }

} else {

    if($id<=0)
        redirect_header(DT_URL, 1, __("Specified category does not exists!",'dtransport'));

    if(!is_numeric($page))
        $page = 1;

    $category = new DTCategory($id);

}

// Descargas en esta categoría
$tbls = $db->prefix("dtrans_software");
$tblc = $db->prefix("dtrans_catsoft");
$sql = "SELECT COUNT(*) FROM $tbls as s, $tblc as c WHERE c.cat='".$category->id()."' AND s.id_soft=c.soft AND s.approved=1 AND s.delete=0";
list($num) = $db->fetchRow($db->query($sql));

$limit = $mc['xpage'];
$limit = $limit<=0 ? 10 : $limit;

$tpages = ceil($num / $limit);

if ($tpages<$page){
    header('location: '.$category->permalink());
    die();
}

$p = $page>0 ? $page-1 : $page;
$start = $num<=0 ? 0 : $p * $limit;

$nav = new RMPageNav($num, $limit, $page);
$nav->target_url($category->permalink().($mc['permalinks'] ? 'page/{PAGE_NUM}/' : '?page={PAGE_NUM}'));
$xoopsTpl->assign('pagenav', $nav->render(true));

// Seleccionamos los registros
$sql = str_replace('COUNT(*)', 's.*', $sql);
$sql .= " ORDER BY s.modified DESC";

$sql .= " LIMIT $start, $limit";

$result = $db->queryF($sql);

while ($row = $db->fetchArray($result)){
	$item = new DTSoftware();
	$item->assignVars($row);
	$xoopsTpl->append('download_items', array_merge($dtfunc->createItemData($item), array('category'=>$category->name())));
}

if($mc['inner_dest_download']){
    $xoopsTpl->assign('featured_items', $dtfunc->get_items($category->id(), 'featured', $mc['limit_destdown']));
    $xoopsTpl->assign('lang_incatego', __('In <a href="%s">%s</a>','dtransport'));
}

// Descargas el día
if($mc['inner_daydownload']){
    $xoopsTpl->assign('daily_items', $dtfunc->get_items($category->id(), 'daily', $mc['limit_daydownload']));
    $xoopsTpl->assign('daily_width', floor(100/($mc['limit_daydownload'])));
    $xoopsTpl->assign('lang_daydown', __('<strong>Day</strong> Downloads','dtransport'));
}

$xoopsTpl->assign('xoops_pagetitle', $category->name() . " &raquo; " . $xoopsModule->name());
$xoopsTpl->assign('lang_in', sprintf(__('<strong>Downloads in</strong> %s','dtransport'), $category->name()));

$xoopsTpl->assign('lang_featured',__('<strong>Featured</strong> Downloads','dtransport'));
$xoopsTpl->assign('lang_download', __('Download','dtransport'));

$tpl->add_xoops_style('main.css','dtransport');

// Datos de la categoría
$xoopsTpl->assign('category', array('id'=>$category->id(),'name'=>$category->name(),'link'=>$category->permalink()));
$xoopsTpl->assign('xoops_pagetitle', sprintf(__('Downloads in "%s"','dtransport'), $category->name()));
$dtfunc->makeHeader();

include 'footer.php';

