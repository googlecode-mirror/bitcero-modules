<?php
// $Id$
// --------------------------------------------------------------
// MiniShop 3
// Module for catalogs
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$tf = new RMTimeFormatter(0, '%d%/%M%/%Y%');

while($row = $db->fetchArray($result)){
    
    $product = new ShopProduct();
    $product->assignVars($row);
    
    $xoopsTpl->append('products', array(
        'id' => $product->id(),
        'name' => $product->getVar('name'),
        'nameid' => $product->getVar('nameid'),
        'price' => sprintf(__('Price: <strong>%s</strong>','shop'), sprintf($xoopsModuleConfig['format'], number_format($product->getVar('price'), 2))),
        'type' => $product->getVar('type'),
        'stock' => $product->getVar('available'),
        'image' => SHOP_UPURL.'/ths/'.$product->getVar('image'),
        'created' => sprintf(__('Since: <strong>%s</strong>','shop'), $tf->format($product->getVar('created'))),
        'updated' => $product->getVar('modified')>0?sprintf(__("Updated: <strong>%s</strong>",'shop'), $tf->format($product->getVar('modified'))):'',
        'link' => $product->permalink(),
        'metas' => $product->get_meta()
    ));
    
}
