<?php
// $Id$
// --------------------------------------------------------------
// Minicol4 3
// Module for catalogs
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('shop','');
include_once XOOPS_ROOT_PATH.'/modules/rmcommon/loader.php';

$adminmenu[] = array(
    'title'=>__('Dashboard','shop'),
    'link'=>"admin/index.php",
    'icon'=>"../images/dashboard.png",
    'location'=>"dashboard"
);

$adminmenu[] = array(
    'title'=>__('Categories','shop'),
    'link'=>"admin/categories.php",
    'icon'=>"../images/category.png",
    'location'=>"categories",
    'options' => array(
        array('title'=>__('List all', 'shop'),'link'=>'categories.php', 'selected'=>'categories'),
        array('title'=>__('Add Category', 'shop'),'link'=>'categories.php?action=new', 'selected'=>'newcategory')
    )
);

$adminmenu[] = array(
    'title'=>__('Products','shop'),
    'link'=>"admin/products.php",
    'icon'=>"../images/product.png",
    'location'=>"products",
    'options' => array(
        array('title'=>__('List all', 'shop'),'link'=>'products.php', 'selected'=>'products'),
        array('title'=>__('Add Product', 'shop'),'link'=>'products.php?action=new', 'selected'=>'newproduct')
    )
);