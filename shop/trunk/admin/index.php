<?php
// $Id$
// --------------------------------------------------------------
// MiniShop 3
// Module for catalogs
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','dashboard');
include 'header.php';

RMTemplate::get()->add_head('<script type="text/javascript">var xurl="'.XOOPS_URL.'";</script>');
RMTemplate::get()->add_local_script('dashmain.js', 'shop');
RMTemplate::get()->add_style('admin.css', 'shop');
RMTemplate::get()->add_style('dashboard.css', 'shop');

$db = Database::getInstance();

// Products count
$sql = "SELECT COUNT(*) FROM ".$db->prefix("shop_products");
list($prods_count) = $db->fetchRow($db->query($sql));

$sql = "SELECT COUNT(*) FROM ".$db->prefix("shop_categories");
list($cats_count) = $db->fetchRow($db->query($sql));

$sql = "SELECT * FROM ".$db->prefix("shop_products")." ORDER BY id_product DESC LIMIT 0, 5";
$result = $db->query($sql);
while($row = $db->fetchArray($result)){
    
    $prod = new ShopProduct();
    $prod->assignVars($row);
    $products[] = array(
        'id' => $prod->id(),
        'name' => $prod->getVar('name'),
        'link' => $prod->permalink(),
        'image' => $prod->getVar('image')
    );
    
}

ShopFunctions::include_required_files();

xoops_cp_header();

include RMTemplate::get()->get_template('admin/shop_dashboard.php','module','shop');

xoops_cp_footer();