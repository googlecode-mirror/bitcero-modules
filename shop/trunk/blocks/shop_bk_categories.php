<?php
// $Id$
// --------------------------------------------------------------
// MiniShop 3
// Module for catalogs
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('shop');

function shop_bk_categories_show($options){
    
    include_once XOOPS_ROOT_PATH.'/modules/shop/class/shopcategory.class.php';
    include_once XOOPS_ROOT_PATH.'/modules/shop/class/shopfunctions.php';
    $categories = array();
    ShopFunctions::categos_list($categories);
    
    array_walk($categories, 'shop_dashed');
    
    RMTemplate::get()->add_style('blocks.css','shop');
    
    return $categories;
    
}
