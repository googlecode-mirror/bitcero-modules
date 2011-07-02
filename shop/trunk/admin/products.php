<?php
// $Id$
// --------------------------------------------------------------
// MiniShop 3
// Module for catalogs
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','products');
require 'header.php';

/**
* Show the list of existing products with options to mage them
*/
function shop_show_products(){
    global $xoopsModuleConfig, $xoopsConfig, $xoopsSecurity;
    
    
    // Show GUI
    xoops_cp_header();
    
    xoops_cp_footer();
    
}

/**
* Form to create or edit products
*
* @param int Indicates if we are editing an existing product or creating new one
*/
function shop_new_product($edit = 0){
    global $xoopsModuleConfig, $xoopsModule, $xoopsConfig, $xoopsSecurity;
    
    if($edit){
        $id = rmc_server_var($_REQUEST, 'id', 0);
        if($id<=0){
            redirectMsg('products.php', __('You must provide a valid product ID!','shop'), 1);
            die();
        }
        
        $product = new ShopProduct($id);
        if($product->isNew()){
            redirectMsg('products.php', __('Specified product does not exists!','shop'), 1);
            die();
        }
        
    }
    
    define('RMCSUBLOCATION','new_product');
    xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '.($edit ? sprintf(__('Editing %s','shop'), $product->getVar('name')) : __('Create Product','shop')));
    xoops_cp_header();
    
    RMTemplate::get()->assign('xoops_pagetitle', $edit ? sprintf(__('Edit "%s"','shop'), $product->getVar('name')) : __('Create Product','shop'));
    RMTemplate::get()->add_style('admin.css', 'shop');
    RMTemplate::get()->add_local_script('dashboard.js', 'shop');
    
    $form = new RMForm('','','');
    $editor = new RMFormEditor('','description','100%', '250px', $edit ? $product->getVar("description",'e') : '', $xoopsModuleConfig['editor']);
    
    $metas = $edit ? $product->get_meta() : array();
    
    ShopFunctions::include_required_files();
    
    include RMTemplate::get()->get_template('admin/shop_products_form.php','module','shop');
    
    xoops_cp_footer();    
    
}


function shop_upload_image(){
    global $xoopsModuleConfig, $xoopsConfig;
    
    $options = array(
        'upload_dir' => SHOP_UPPATH,
        'upload_url' => SHOP_UPURL,
        'param_name' => 'image',
        'max_file_size' => $xoopsModuleConfig['maxsize']*1024,
        'min_file_size' => 1,
        'accept_file_types' => '/.+$/i',
        'max_number_of_files' => 1,
        'discard_aborted_uploads' => true
    );
    
    $up = new RMUploadHandler($options);
    
    $info = $up->post();
    
    header('Vary: Accept');
    if (isset($_SERVER['HTTP_ACCEPT']) &&
        (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
        header('Content-type: application/json');
    } else {
        header('Content-type: text/plain');
    }
    echo json_encode($info); die();
    
    
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    
    case 'new':
        shop_new_product();
        break;
    
    case 'upload':
        // Deactivate the logger
        error_reporting(0);
        $xoopsLogger->activated = false;
        shop_upload_image();
        break;
        
    default:
        shop_show_products();
        break;
    
}
