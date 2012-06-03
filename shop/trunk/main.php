<?php
// $Id$
// --------------------------------------------------------------
// MiniShop 3
// Module for catalogs
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$xoopsOption['template_main'] = 'shop_index.html';
include 'header.php';

$db = XoopsDatabaseFactory::getDatabaseConnection();

$sql = "SELECT COUNT(*) FROM ".$db->prefix("shop_products");
list($num) = $db->fetchRow($db->query($sql));

$limit = $xoopsModuleConfig['numxpage'];
$tpages = ceil($num / $limit);
$page = $page > $tpages ? $tpages : $page;
$p = $page>0 ? $page-1 : $page;
$start = $num<=0 ? 0 : $p * $limit;

$nav = new RMPageNav($num, $limit, $page, 5);
$nav->target_url(ShopFunctions::get_url().($xoopsModuleConfig['urlmode'] ? 'page/{PAGE_NUM}/' : '?page={PAGE_NUM}'));
$xoopsTpl->assign('pagenav', $nav->render(false));

$result = $db->query("SELECT * FROM ".$db->prefix("shop_products")." ORDER BY ".($xoopsModuleConfig['sort']?' `name`':' id_product DESC')." LIMIT $start,$limit");

include 'include/product-data.php';

$categories = array();
ShopFunctions::categos_list($categories);

array_walk($categories, 'shop_dashed');

$xoopsTpl->assign('categories_list', $categories);
$xoopsTpl->assign('columns', $xoopsModuleConfig['columns']);
$xoopsTpl->assign('lang_instock', __('In stock','shop'));
$xoopsTpl->assign('lang_outstock', __('Out of stock','shop'));
$xoopsTpl->assign('lang_selcat', __('Select category...','shop'));
$xoopsTpl->assign('xoops_pagetitle', $xoopsModuleConfig['modtitle']);

RMTemplate::get()->add_style('main.css', 'shop');

include 'footer.php';