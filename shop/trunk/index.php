<?php
// $Id$
// --------------------------------------------------------------
// MiniShop 3
// Module for catalogs
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

require '../../mainfile.php';

// Switch page
extract(ShopFunctions::url_params());

if($category!=''){
    
    include 'category.php';
    
} elseif($id!=''){
    
    include 'product.php';

} elseif($contact!=''){
    
    include 'contact.php';

}elseif($category=='' && $id==''){
    
    include 'main.php';
    
} else {
    
    ShopFunctions::error404();
    
}

