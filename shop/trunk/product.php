<?php
// $Id$
// --------------------------------------------------------------
// MiniShop 3
// Module for catalogs
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$product = new ShopProduct($id);

if($product->isNew()){
    ShopFunctions::error404();
}

$xoopsOption['template_main'] = 'shop_product.html';
include 'header.php';

$tf = new RMTimeFormatter(0, '%d%/%M%/%Y%');

// Product data
$xoopsTpl->assign('product', array(
    'name' => $product->getVar('name'),
    'description' => $product->getVar('description'),
    'price' => sprintf(__('Price: <strong>%s</strong>','shop'), sprintf($xoopsModuleConfig['format'], number_format($product->getVar('price'), 2))),
    'type' => $product->getVar('type'),
    'stock' => $product->getVar('available'),
    'image' => $product->getVar('image'),
    'created' => sprintf(__('Since: <strong>%s</strong>','shop'), $tf->format($product->getVar('created'))),
    'updated' => $product->getVar('modified')>0?sprintf(__("Updated: <strong>%s</strong>",'shop'), $tf->format($product->getVar('modified'))):'',
    'link' => $product->permalink(),
    'metas' => $product->get_meta(),
    'images' => $product->get_images()
));

$options = array(
    '<a href="'.ShopFunctions::get_url().($xoopsModuleConfig['urlmode']?'contact/'.$product->getVar('nameid').'/':'?contact='.$product->id()).'">'.__('Request Information','shop').'</a>'
);

$options = RMEvents::get()->run_event('shop.product.options', $options);
$xoopsTpl->assign('options', $options);

$xoopsTpl->assign('product_details', 1);
$xoopsTpl->assign('xoops_pagetitle', $product->getVar('name').' &raquo; '.$xoopsModuleConfig['modtitle']);
$xoopsTpl->assign('lang_instock', __('In stock','shop'));
$xoopsTpl->assign('lang_outstock', __('Out of stock','shop'));

RMTemplate::get()->add_style('main.css', 'shop');

// Now minishop will use lightbox plugin for Common Utilities
if (RMFunctions::plugin_installed('lightbox')){
    RMLightbox::get()->add_element('.prod_thumbs a');
    RMLightbox::get()->render();
}

include 'footer.php';
