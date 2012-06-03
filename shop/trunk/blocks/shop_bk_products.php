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

function shop_bk_products_show($options){
    
    include_once XOOPS_ROOT_PATH.'/modules/shop/class/shopproduct.class.php';
    include_once XOOPS_ROOT_PATH.'/modules/shop/class/shopfunctions.php';
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $limit = $options[2]>0?$options[2]:5;
    $ord = $options[0]<=0?' `created` DESC':'RAND()';
    
    $mc = RMUtilities::module_config('shop', 'format');
    
    if($options[1]!=''){
        
        $tp = $db->prefix("shop_products");
        $tc = $db->prefix("shop_categories");
        $tcp = $db->prefix("shop_catprods");
        
        $sql = "SELECT p.* FROM $tp as p, $tcp as r WHERE r.cat=$options[1] AND p.id_product=r.product GROUP BY r.product";
        
    } else {
        
        $sql = "SELECT * FROM ".$db->prefix("shop_products");
        
    }
    
    $sql .= " ORDER BY $ord LIMIT 0,$limit";
    $result = $db->query($sql);
    
    $products = array();
    
    while($row = $db->fetchArray($result)){
        $prod = new ShopProduct();
        $prod->assignVars($row);
        $products[] = array(
            'id' => $prod->id(),
            'name' => $prod->getVar('name'),
            'link' => $prod->permalink(),
            'price' => sprintf($mc, number_format($prod->getVar('price'), 2)),
            'type' => $prod->getVar('type')?__('Digital','shop'):'Product',
            'stock' => $prod->getVar('available'),
            'image' => $prod->getVar('image')
        );
    }
    
    $block['products'] = $products;
    unset($prod, $products, $sql, $result, $row, $ord, $limit, $db);
    
    $block['show_image'] = $options[3];
    $block['width'] = $options[4];
    $block['height'] = $options[5];
    $block['display'] = $options[6];
    $block['name'] = in_array("name", $options);
    $block['price'] = in_array("price", $options);
    $block['type'] = in_array("type", $options);
    $block['stock'] = in_array("stock", $options);
    
    $block['lang_stock'] = __('In stock','shop');
    $block['lang_nostock'] = __('Out of stock','shop');
    
    // Add styles
    RMTemplate::get()->add_style('blocks.css', 'shop');
    
    return $block;
    
}

function shop_bk_products_edit($options){
    
    $form = '</td></tr><tr><th colspan="2">'.__('Block Options','shop').'</th></tr>';
    $form .= '<tr><td class="head"><strong>'.__('Listing type:','shop').'</strong></td><td class="odd">';
    $form .= '<label><input type="radio" name="options[0]" value="0"'.($options[0]==0?' checked="checked"':'').' /> '.__('Recent products','shop').'</label>';
    $form .= '<label><input type="radio" name="options[0]" value="1"'.($options[0]==1?' checked="checked"':'').' /> '.__('Random products','shop').'</label></td></tr>';
    $form .= '<tr><td class="head">'.__('From category:','shop').'</td><td class="odd">';
    $form .= '<select name="options[1]">';
    $form .= '<option value=""'.($options[1]==''?' selected="selected"':'').'>'.__('All categories','shop').'</option>';
    
    $categories = array();
    include_once XOOPS_ROOT_PATH.'/modules/shop/class/shopfunctions.php';
    include_once XOOPS_ROOT_PATH.'/modules/shop/class/shopcategory.class.php';
    ShopFunctions::categos_list($categories);
    
    foreach($categories as $cat){
        
        $form .= '<option value="'.$cat['id_cat'].'"'.($options[1]==$cat['id_cat']?' selected="selected"':'').'>'.str_repeat("&#151;",$cat['indent']).' '.$cat['name'].'</option>';
        
    }
    
    $form .= '</select></td></tr>';
    $form .= '<tr><td class="head">'.__('Number of products:','shop').'</td><td class="odd">';
    $form .= '<input type="text" name="options[2]" value="'.$options['2'].'" size="5" /></td></tr>';
    $form .= '<tr><td class="head" valign="top">'.__('Show images:','shop').'</td><td class="odd">';
    $form .= '<label><input type="radio" name="options[3]" value="1"'.($options[3]==1?' checked="checked"':'').' onchange="$(\'#shop-images-options\').slideDown(\'fast\');" /> '.__('Yes','shop').'</label>';
    $form .= '<label><input type="radio" name="options[3]" value="0"'.($options[3]==0?' checked="checked"':'').' onchange="$(\'#shop-images-options\').slideUp(\'fast\');" /> '.__('No','shop').'</label>';
    $form .= '<div class="even" id="shop-images-options"'.($options[3]==0?' style="display: none;"':'').'>';
    $form .= '<table border="0" style="width: auto;"><tr class="even"><td><strong>'.__('Image width:','shop').'</strong></td><td>';
    $form .= '<input type="text" size="5" name="options[4]" value="'.$options[4].'" /></td></tr>';
    $form .= '<tr class="even"><td><strong>'.__('Image height:','shop').'</strong></td><td><input type="text" name="options[5]" size="5" value="'.$options[5].'" /></tr>';
    $form .= '</table></div></tr></td>';
    $form .= '<tr><td class="head">'.__('Display mode:','shop').'</td><td class="odd">';
    $form .= '<label><input type="radio" name="options[6]" value="1"'.($options[6]==1?' checked="checked"':'').' />'.__('Grid','shop').'</label>';
    $form .= '<label><input type="radio" name="options[6]" value="0"'.($options[6]==0?' checked="checked"':'').' />'.__('List','shop').'</label></td></tr>';
    $form .= '<tr><td class="head" valign="top">'.__('Data to show:','shop').'</td><td class="odd">';
    $form .= '<label><input type="checkbox" name="options[7]" value="name"'.(in_array('name', $options)?' checked="checked"':'').' />'.__('Product name','shop').'</label><br />';
    $form .= '<label><input type="checkbox" name="options[8]" value="price"'.(in_array('price', $options)?' checked="checked"':'').' />'.__('Product price','shop').'</label><br />';
    $form .= '<label><input type="checkbox" name="options[9]" value="type"'.(in_array('type', $options)?' checked="checked"':'').' />'.__('Product type','shop').'</label><br />';
    $form .= '<label><input type="checkbox" name="options[10]" value="stock"'.(in_array('stock', $options)?' checked="checked"':'').' />'.__('Product availability','shop').'</label></td></tr>';
    $form .= '<tr><td colspan="2" style="background: #ccc; font-size: 2px;">&nbsp;';
    
    return $form;
    
}
