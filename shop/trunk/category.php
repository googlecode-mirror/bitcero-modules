<?php
// $Id$
// --------------------------------------------------------------
// MiniShop 3
// Module for catalogs
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$xoopsOption['template_main'] = 'shop_category.html';
include 'header.php';

$db = Database::getInstance();

$tbl1 = $db->prefix("shop_categories");
$tbl2 = $db->prefix("shop_catprods");
$tbl3 = $db->prefix("shop_products");

if (!is_numeric($category)){
    
    $idp = 0; # ID de la categoria padre
    $path = explode("/", $category);
    foreach ($path as $k){
        if ($k=='') continue;
        $sql = "SELECT id_cat FROM $tbl1 WHERE shortname='$k' AND parent='$idp'";
        $result = $db->query($sql);
        if ($db->getRowsNum($result)>0) list($idp) = $db->fetchRow($result);    
    }
    $category = $idp;
    
}

$catego = new ShopCategory($category);
if ($catego->isNew()){
    redirect_header(ShopFunctions::get_url(), 1, __('Specified category could not be found','shop'));
    die();
}

// Category data
$xoopsTpl->assign('category', array('id'=>$catego->id(),'name'=>$catego->getVar('name')));
$xoopsTpl->assign('lang_prodsincat', sprintf(__('Products in &#8216;%s&#8217; Category','shop'), $catego->getVar('name')));

$limit = $xoopsModuleConfig['numxpage'];
list($num) = $db->fetchRow($db->query("SELECT COUNT($tbl2.product) FROM $tbl2, $tbl3 WHERE $tbl2.cat='$category' 
        AND $tbl3.id_product=$tbl2.product"));

$page = isset($page) && $page>0 ? $page : 1;

$tpages = ceil($num / $limit);
$page = $page > $tpages ? $tpages : $page;
$p = $page>0 ? $page-1 : $page;
$start = $num<=0 ? 0 : $p * $limit;

$xoopsTpl->assign('page', $page);

$nav = new RMPageNav($num, $limit, $page, 5);
$nav->target_url($catego->permalink() . ($xoopsModuleConfig['urlmode'] ? 'page/{PAGE_NUM}/' : '&page={PAGE_NUM}'));
$xoopsTpl->assign('pagenav', $nav->render(false));

$result = $db->query("SELECT $tbl3.* FROM $tbl2, $tbl3 WHERE $tbl2.cat='$category' 
        AND $tbl3.id_product=$tbl2.product ORDER BY ".($xoopsModuleConfig['sort']?" $tbl3.`name`":" $tbl3.id_product DESC")." LIMIT $start,$limit");

include 'include/product-data.php';

$categories = array();
ShopFunctions::categos_list($categories);

array_walk($categories, 'shop_dashed');

$xoopsTpl->assign('categories_list', $categories);
$xoopsTpl->assign('columns', $xoopsModuleConfig['columns']);
$xoopsTpl->assign('lang_instock', __('In stock','shop'));
$xoopsTpl->assign('lang_outstock', __('Out of stock','shop'));
$xoopsTpl->assign('lang_selcat', __('Select category...','shop'));
$xoopsTpl->assign('xoops_pagetitle', $catego->getVar('name').' - '.$xoopsModuleConfig['modtitle']);

RMTemplate::get()->add_style('main.css', 'shop');

include 'footer.php';
