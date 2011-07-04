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
    
    ShopFunctions::include_required_files();
    RMTemplate::get()->assign('xoops_pagetitle', $edit ? sprintf(__('Edit "%s"','shop'), $product->getVar('name')) : __('Create Product','shop'));
    RMTemplate::get()->add_style('admin.css', 'shop');
    RMTemplate::get()->add_local_script('dashboard.js', 'shop');
    
    $form = new RMForm($edit ? __('Edit Product', 'shop') : __('New Product','shop'),'frmProduct','products.php');
    $form->addElement(new RMFormText(__('Name','shop'), 'name', 50, 200, $edit ? $product->getVar('name') : ''), true);
    $form->addElement(new RMFormEditor(__('Description','shop'),'description','100%', '250px', $edit ? $product->getVar("description",'e') : '', $xoopsModuleConfig['editor']), true);
    $form->addElement(new RMFormText(__('Price','shop'), 'price', 10, 100, $edit ? $product->getVar('price') : ''));
    
    $categories = array();
    ShopFunctions::categos_list($categories);
    
    $cats = new RMFormCheck('');
    foreach($categories as $c){
        $cats->addOption(str_repeat("&#8212;",$c['indent']).' '.$c['name'], 'cats[]', $c['id_cat']);
    }
    
    $form->addElement(new RMFormLabel(__('Categories','shop'), '<div class="cats_field">'.$cats->render().'</div>'));
    
    $ele = new RMFormRadio(__('Type','shop'), 'type', 1);
    $ele->addOption(__('Normal','shop'), 0, 1);
    $ele->addOption(__('Digital', 'shop'), 1, 0);
    $form->addElement($ele);
    unset($ele);
    $form->addElement(new RMFormYesNo(__('Available','shop'), 'available', 1));
    $form->addElement(new RMFormFile(__('Default image', 'shop'), 'image'));
    if($edit){
        $form->addElement(new RMFormLabel(__('Current Image','shop'), '<img src="'.XOOPS_UPLOAD_URL.'/minishop/ths/'.$product->getVar('image').'" />'));
    }
    
    $metas = $edit ? $product->get_meta() : array();
    
    ob_start();
    include RMTemplate::get()->get_template('admin/shop_products_form.php', 'module', 'shop');
    $metas_tpl = ob_get_clean();
    
    $form->addElement(new RMFormLabel(__('Custom Fields','shop'), $metas_tpl));
    
    $buts = new RMFormButtonGroup('');
    $buts->addButton('cancel', __('Cancel','shop'), 'button', 'onclick="window.location.href=\'products.php\'"');
    $buts->addButton('sbt', $edit ? __('Save Changes','shop') : __('Create Product','shop'), 'submit');
    $form->addElement($buts);
    
    $form->addElement(new RMFormHidden('action', $edit ? 'saveedit' : 'save'));
    if ($edit) $form->addElement(new RMFormHidden('id', $product->id()));
    
    $form->display();
    
    xoops_cp_footer();    
    
}


function shop_save_product($edit=0){
    global $xoopsSecurity, $xoopsUser, $xoopsModule;
    
    $q = '';
    
    foreach ($_POST as $k => $v){
        $$k = $v;
        if($k=='XOOPS_TOKEN_REQUEST' || $k=='action') continue;
        $q = $q==''?"$k=".rawurlencode($v):"&$k=".rawurlencode($str);
    }
    
    $q = "action=".($edit?'edit&id='.$id:'new').'&'.$q;
    
    if (!$xoopsSecurity->check()){
        redirectMsg("products.php?$q", __('Session token expired!','shop'), 1);
        die();
    }
    
    if ($edit){
        if ($id<=0){
            redirectMsg("products.php", __('Product ID has not been provided','shop'), 1);
            die();
        }
        
        $product = new ShopProduct($id);
        if ($product->isNew()){
            redirectMsg("products.php", __('Specified product does not exists!','shop'), 1);
            die();
        }
    } else {
        $product = new ShopProduct();
    }
    
    if ($name=='' || $description=='' || empty($cats)){
        redirectMsg("products.php?$q", __('Please fill al required data!','shop'), 1);
        die();
    }
    
    if (!isset($nameid)){
        $nameid = TextCleaner::getInstance()->sweetstring($name);
    }
    
    /**
     * Comprobamos que no exista otra página con el mismo título
     */
    $db = Database::getInstance();
    $sql = "SELECT COUNT(*) FROM ".$db->prefix("shop_products")." WHERE nameid='$nameid'";
    
    $sql .= $edit ? " AND id_product<>".$product->id() : '';
    
    list($num) = $db->fetchRow($db->query($sql));
    if ($num>0){
        
        redirectMsg('products.php?'.$q, __('Another product with same name already exists!','shop'), 1);
        die();

    }
    
    #Guardamos los datos del Post
    $product->setVar('name', $name);
    $product->setVar('nameid', $nameid);
    $product->setVar('description', $description);
    $product->setVar('price', $price);
    $product->setVar('type', $type);
    $product->setVar('available', $available);
    if(!$edit) $product->setVar('created', $created);
    if($edit) $product->setVar('modified', $modified);
    
    // Add Metas
    foreach($meta_name as $k => $v){
        $product->add_meta($v, $meta_value[$k]);
    }

    $product->add_categories($cats);
    
    //Imagen
    include_once RMCPATH.'/class/uploader.php';
    $folder = XOOPS_UPLOAD_PATH.'/minishop';
    $folderths = XOOPS_UPLOAD_PATH.'/minishop/ths';
    if ($edit){
        $image = $product->getVar('image');
        $filename=$product->getVar('image');
    }
    else{
        $filename = '';
    }

    //Obtenemos el tamaño de la imagen
    $thSize = explode("|", $xoopsModuleConfig['thssize']);
    $imgSize = explode("|", $xoopsModuleConfig['imgsize']);

    $up = new RMFileUploader($folder, $xoopsModuleConfig['maxsize']*1024, array('jpg','png','gif'));

    if ($up->fetchMedia('image')){

    
        if (!$up->upload()){
            redirectMsg('./products.php',$up->getErrors(), 1);
            die();
        }
                    
        if ($edit && $product->getVar('image')!=''){
            @unlink(XOOPS_UPLOAD_PATH.'/minishop/'.$product->getVar('image'));
            @unlink(XOOPS_UPLOAD_PATH.'/minishop/ths/'.$product->getVar('image'));
            
        }

        $filename = $up->getSavedFileName();
        $fullpath = $up->getSavedDestination();
        // Redimensionamos la imagen
        $redim = new RMImageResizer($fullpath, $fullpath);
        
        if($xoopsModuleConfig['imgredim']){
            $redim->resizeAndCrop($imgSize[0],$imgSize[1]);
        } else {
            $redim->resizeWidthOrHeight($imgSize[0],$imgSize[1]);
        }
        
        $redim->setTargetFile($folderths."/$filename"); 
        
        if($xoopsModuleConfig['thsredim']){
            $redim->resizeAndCrop($thSize[0],$thSize[1]);
        } else {
            $redim->resizeWidthOrHeight($thSize[0],$thSize[1]);
        }

    }

    
    $product->setVar('image', $filename);
    
    if ($product->save()){
        redirectMsg("products.php", __('Database updated successfully!','shop'), 0);
    } else {
        redirectMsg("products.php?$q", __('Errors ocurred while trying to update database','shop') . "<br />" . $product->errors(), 1);
    }
    
    
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    
    case 'new':
        shop_new_product();
        break;
    
    case 'edit':
        shop_new_product(1);
        break;
    
    case 'save':
        shop_save_product();
        break;
    
    case 'new':
        shop_save_product(1);
        break;
           
    default:
        shop_show_products();
        break;
    
}
