<?php
// $Id$
// --------------------------------------------------------------
// MiniShop 3
// Module for catalogs
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function shop_widget_info(){
    global $edit, $id, $product;
    
    $widget['title'] = __('Product Information','shop');
    
    ob_start();
    
    include RMTemplate::get()->get_template('widgets/shop_w_info.php', 'module', 'shop');
    
    $widget['content'] = ob_get_clean();
    
    return $widget;
    
}


function shop_widget_image(){
    global $edit, $id, $product;
    
    $widget['title'] = __('Product Image','shop');
    
    ob_start();
    
    include RMTemplate::get()->get_template('widgets/shop_w_image.php', 'module', 'shop');
    
    $widget['content'] = ob_get_clean();
    
    return $widget;
    
}
