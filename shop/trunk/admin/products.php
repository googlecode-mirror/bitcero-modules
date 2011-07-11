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
    
    $db = Database::getInstance();
    
    $page = rmc_server_var($_REQUEST,'page', 1);
    $limit = 15;
    $bname = rmc_server_var($_REQUEST,'bname', '');
    
    //Barra de Navegación
    $sql = "SELECT COUNT(*) FROM ".$db->prefix('shop_products');
    if ($bname!=''){
        $sql .= " WHERE name LIKE '%$bname%'";
    }
    
    list($num)=$db->fetchRow($db->query($sql));

    $tpages = ceil($num/$limit);
    $page = $page > $tpages ? $tpages : $page; 

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('products.php?page={PAGE_NUM}&amp;bname='.$bname);

    $sql = str_replace("COUNT(*)", '*', $sql);
    $sql.= " ORDER BY id_product DESC LIMIT $start, $limit"; 
    $result = $db->query($sql);
    $products = array(); //Container
    
    $tf = new RMTimeFormatter('','%M%/%d%/%Y% - %h%:%i%');
    
    while ($row = $db->fetchArray($result)){
        $product = new ShopProduct();
        $product->assignVars($row);

        $products[] = array(
            'id'=>$product->id(),
            'name'=>$product->getVar('name'),
            'image'=>$product->getVar('image')!=''?XOOPS_UPLOAD_URL.'/minishop/ths/'.$product->getVar('image'):'',
            'price'=>$product->getVar('price'),
            'type'=>$product->getVar('type')?__('Digital','shop'):__('Normal','shop'),
            'stock'=>$product->getVar('available'),
            'created'=>$product->getVar('created')?$tf->format($product->getVar('created')) : '',
            'modified'=>$product->getVar('modified')>0 ? $tf->format($product->getVar('modified')) : ''
        );

    }
    
    $products = RMEvents::get()->run_event("shop.list.products", $products, $start, $limit);
    
    RMTemplate::get()->add_style('admin.css', 'shop');
    RMTemplate::get()->add_local_script('admin.js', 'shop');
    RMTemplate::get()->add_head('<script type="text/javascript">
    var shop_select_message = "'.__('Select at least one product in order to run this action!','shop').'";
    var shop_message = "'.__('Do you really wish to delete selected products?','shop').'";
    </script>');
    
    // Show GUI
    xoops_cp_header();
    
    include RMTemplate::get()->get_template('admin/shop_products.php', 'module', 'shop');
    
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
    $form->setExtra('enctype="multipart/form-data"');
    $form->addElement(new RMFormText(__('Name','shop'), 'name', 50, 200, $edit ? $product->getVar('name') : ''), true);
    $form->addElement(new RMFormEditor(__('Description','shop'),'description','100%', '250px', $edit ? $product->getVar("description",'e') : '', $xoopsModuleConfig['editor']), true);
    $form->addElement(new RMFormText(__('Price','shop'), 'price', 10, 100, $edit ? $product->getVar('price') : ''));
    
    $categories = array();
    ShopFunctions::categos_list($categories);
    if($edit){
        $pcats = $product->get_categos();
    } else {
        $pcats = array();
    }
    
    $cats = new RMFormCheck('');
    foreach($categories as $c){
        $cats->addOption(str_repeat("&#8212;",$c['indent']).' '.$c['name'], 'cats[]', $c['id_cat'], in_array($c['id_cat'], $pcats) ? 1 : 0);
    }
    
    $form->addElement(new RMFormLabel(__('Categories','shop'), '<div class="cats_field">'.$cats->render().'</div>'));
    
    $ele = new RMFormRadio(__('Type','shop'), 'type', 1);
    $ele->addOption(__('Normal','shop'), 0, 1);
    $ele->addOption(__('Digital', 'shop'), 1, 0);
    $form->addElement($ele);
    unset($ele);
    $form->addElement(new RMFormYesNo(__('Available','shop'), 'available', 1));
    $form->addElement(new RMFormFile(__('Default image', 'shop'), 'image'));
    if($edit && $product->getVar('image')!=''){
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
    global $xoopsSecurity, $xoopsUser, $xoopsModule, $xoopsModuleConfig;
    
    $q = '';
    
    foreach ($_POST as $k => $v){
        $$k = $v;
        if($k=='XOOPS_TOKEN_REQUEST' || $k=='action') continue;
        $q .= $q==''?"$k=".rawurlencode($v):"&$k=".rawurlencode($v);
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
    
    // Add Metas
    foreach($meta_name as $k => $v){
        $product->add_meta($v, $meta_value[$k]);
    }

    $product->add_categories($cats, true);
    
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

/**
* @desc Elimina de la base de datos la información del trabajo
**/
function shop_delete_products(){

    global $xoopsSecurity, $xoopsModule;

    $ids = rmc_server_var($_POST, 'ids', 0);
    $page = rmc_server_var($_POST, 'page', 1);
    $bname = rmc_server_var($_POST, 'bname', '');
    
    $ruta = "page=$page&bname=$bname";

    //Verificamos que nos hayan proporcionado un trabajo para eliminar
    if (!is_array($ids)){
        redirectMsg('products.php?'.$ruta, __('You must select one product at least!','shop'),1);
        die();
    }

     if (!$xoopsSecurity->check()){
        redirectMsg('products.php?'.$ruta, __('Session token expired!','shop'), 1);
        die();
     }

     $errors = '';
     foreach ($ids as $k){
        //Verificamos si el trabajo es válido
        if ($k<=0){
            $errors.=sprintf(__('Product ID "%s" is not valid!','shop'), $k);
            continue;
        }

        //Verificamos si el trabajo existe
        $product = new ShopProduct($k);
        if ($product->isNew()){
            $errors.=sprintf(__('Product with ID "%s" does not exists!','shop'), $k);
            continue;
        }
        
        if (!$product->delete()){
            $errors.=sprintf(__('Product "%s" could not be deleted!','shop'),$product->title());
        }
     }
    
    if ($errors!=''){
        redirectMsg('products.php?'.$ruta,__('Errors ocurred while trying to delete products','shop').'<br />'.$errors,1);
        die();
    }else{
        redirectMsg('products.php?'.$ruta,__('Prodducts deleted successfully!','shop'),0);
        die();
    }

}

/**
* Products Images management
*/
function shop_show_images(){

    global $xoopsModule, $db, $xoopsSecurity;

    $id = rmc_server_var($_REQUEST, 'id', 0);
    $page = rmc_server_var($_REQUEST, 'page', 0);
    $bname = rmc_server_var($_REQUEST, 'bname', 0);

    //Verificamos que el trabajo sea válido
    if ($id<=0){
        redirectMsg('products.php', __('Provided product ID is not valid!','shop'),1);
        die();
    }

    //Verificamos que el trabajo exista
    $product = new ShopProduct($id);
    if ($product->isNew()){
        redirectMsg('products.php', __('Specified product does not exists!','admin_work'),1);
        die();
    }
    
    $db = Database::getInstance();

    $sql = "SELECT * FROM ".$db->prefix('shop_images')." WHERE product='".$product->id()."'";
    $result = $db->query($sql);
    $images = array();
    while($row = $db->fetchArray($result)){
        $img = new ShopImage();
        $img->assignVars($row);

        $images[] = array(
            'id'=>$img->id(),
            'title'=>$img->getVar('title'),
            'file'=>$img->getVar('file'),
            'product'=>$img->getVar('product'),
            'desc'=>$img->getVar('description')
        );
    }
    
    $images = RMEvents::get()->run_event('shop.list.images', $images, $product);
    $form_fields = '';
    $form_fields = RMEvents::get()->run_event('shop.images.form.fields', $form_fields, $product);

    ShopFunctions::include_required_files();
    xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; ".__('Product Images','shop'));
    RMTemplate::get()->assign('xoops_pagetitle', $product->getVar('name').' &raquo; Product Images','shop');
    RMTemplate::get()->add_style('admin.css', 'shop');
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
    RMTemplate::get()->add_head("<script type='text/javascript'>\nvar shop_message='".__('Do you really want to delete selected images?','shop')."';\n
        var shop_select_message = '".__('You must select an image before to execute this action!','shop')."';</script>");
    xoops_cp_header();
    
    include RMTemplate::get()->get_template("admin/shop_images.php", 'module', 'shop');
    
    xoops_cp_footer();
}

/**
* @desc Formulario de creación/edición de Imágenes
**/
function shop_form_images($edit = 0){

    global $xoopsModule, $xoopsModuleConfig;

    $id = rmc_server_var($_REQUEST, 'id', 0);
    $work = rmc_server_var($_REQUEST, 'work', 0);
    $page = rmc_server_var($_REQUEST, 'page', 0);

    $ruta = "&page=$page";
    
    //Verificamos que el trabajo sea válido
    if ($work<=0){
        redirectMsg('./works.php', __('You must specify a work ID!', 'works'),1);
        die();
    }

    //Verificamos que el trabajo exista
    $work = new PWWork($work);
    if ($work->isNew()){
        redirectMsg('./works.php', __('Specified work does not exists!', 'works'),1);
        die();
    }

    
    if ($edit){
        //Verificamos que la imagen sea válida
        if ($id<=0){
            redirectMsg('./images.php?work='.$work->id().$ruta,__('You must specify an image ID!', 'works'),1);
            die();
        }

        //Verificamos que la imagen exista
        $img = new PWImage($id);
        if ($img->isNew()){
            redirectMsg('./images.php?work='.$work->id().$ruta,__('Specified image does not exists!', 'works'),1);
            die();
        }
    }


    PWFunctions::toolbar();
    RMTemplate::get()->assign('xoops_pagetitle', $work->title().' &raquo; '.__('Work Images','works'));
    xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; <a href='./images.php?work=".$work->id()."'>".__('Work Images','works')."</a> &raquo;".($edit ? __('Edit Image','works') : __('Add Image','works')));
    xoops_cp_header();

    $form = new RMForm($edit ? __('Edit Image','works') : __('Add Image','works'),'frmImg','images.php');
    $form->setExtra("enctype='multipart/form-data'");

    $form->addElement(new RMFormText(__('Title','works'),'title',50,100,$edit ? $img->title() : ''), true);
    $form->addElement(new RMFormFile(__('Image file','works'),'image',45, $xoopsModuleConfig['size_image']*1024), $edit ? false : true);
    if ($edit){
        $form->addElement(new RMFormLabel(__('Current image file','works'),"<img src='".XOOPS_UPLOAD_URL."/works/ths/".$img->image()."' />"));
    }

    $form->addElement(new RMFormTextArea(__('Description','works'),'desc',4,50,$edit ? $img->desc() : ''));
    
    $form->addElement(new RMFormHidden('op',$edit ? 'saveedit' : 'save'));
    $form->addElement(new RMFormHidden('id',$id));
    $form->addElement(new RMFormHidden('work',$work->id()));
    $form->addElement(new RMFormHidden('page',$page));
    $form->addElement(new RMFormHidden('limit',$limit));

    $ele = new RMFormButtonGroup();
    $ele->addButton('sbt', _SUBMIT, 'submit');
    $ele->addButton('cancel', _CANCEL, 'button', 'onclick="window.location=\'images.php?work='.$work->id().$ruta.'\';"');
    $form->addElement($ele);

    $form->display();

    xoops_cp_footer();
}

/**
* @desc Almacena las imágenes en la base de datos
**/
function shop_save_image($edit = 0){
    global $xoopsModuleConfig, $xoopsSecurity;

    foreach ($_POST as $k => $v){
        $$k = $v;
    }

    $ruta = "&page=$page";

    //Verificamos que el trabajo sea válido
    if ($id<=0){
        redirectMsg('./products.php',__('You must specify a work ID!', 'works'),1);
        die();
    }

    //Verificamos que el trabajo exista
    $work = new PWWork($work);
    if ($work->isNew()){
        redirectMsg('./works.php',__('Specified work does not exists!', 'works'),1);
        die();
    }

    if (!$xoopsSecurity->check()){
        redirectMsg('./images.php?work='.$work->id().$ruta,__('Session token expired!', 'works'), 1);
        die();
    }

    if ($edit){
        //Verificamos que la imagen sea válida
        if ($id<=0){
            redirectMsg('./images.php?work='.$work->id().$ruta,__('You must specify an image ID!', 'works'),1);
            die();
        }

        //Verificamos que la imagen exista
        $img = new PWImage($id);
        if ($img->isNew()){
            redirectMsg('./images.php?work='.$work->id().$ruta,__('Specified image does not exists!', 'works'),1);
            die();
        }
    }else{
        $img = new PWImage();
    }

    $img->setTitle($title);
    $img->setDesc(substr($desc,0,100));
    $img->setWork($work->id());
    
    //Imagen
    include_once RMCPATH.'/class/uploader.php';
    $folder = XOOPS_UPLOAD_PATH.'/works';
    $folderths = XOOPS_UPLOAD_PATH.'/works/ths';
    if ($edit){
        $image = $img->image();
        $filename=$img->image();
    }
    else{
        $filename = '';
    }

    //Obtenemos el tamaño de la imagen
    $thSize = $xoopsModuleConfig['image_ths'];
    $imgSize = $xoopsModuleConfig['image'];

    $up = new RMFileUploader($folder, $xoopsModuleConfig['size_image']*1024, array('jpg','png','gif'));

    if ($up->fetchMedia('image')){

    
        if (!$up->upload()){
            redirectMsg('./images.php?op='.($edit ? 'edit' : 'new').'&work='.$work->id().$ruta,$up->getErrors(), 1);
            die();
        }
                    
        if ($edit && $img->image()!=''){
            @unlink(XOOPS_UPLOAD_PATH.'/works/'.$img->image());
            @unlink(XOOPS_UPLOAD_PATH.'/works/ths/'.$img->image());
            
        }

        $filename = $up->getSavedFileName();
        $fullpath = $up->getSavedDestination();
        // Redimensionamos la imagen
        $redim = new RMImageResizer($fullpath, $fullpath);
        switch ($xoopsModuleConfig['redim_image']){
            
            case 0:
                //Recortar miniatura
                $redim->resizeWidth($imgSize[0]);
                $redim->setTargetFile($folderths."/$filename");                
                $redim->resizeAndCrop($thSize[0],$thSize[1]);
                break;    
            case 1: 
                //Recortar imagen grande
                $redim->resizeWidthOrHeight($imgSize[0],$imgSize[1]);
                $redim->setTargetFile($folderths."/$filename");
                $redim->resizeWidth($thSize[0]);            
                break;
            case 2:
                //Recortar ambas
                $redim->resizeWidthOrHeight($imgSize[0],$imgSize[1]);
                $redim->setTargetFile($folderths."/$filename");
                $redim->resizeAndCrop($thSize[0],$thSize[1]);
                break;
            case 3:
                //Redimensionar
                $redim->resizeWidth($imgSize[0]);
                $redim->setTargetFile($folderths."/$filename");
                $redim->resizeWidth($thSize[0]);
                break;                
        }

    }

    
    $img->setImage($filename);
    
    RMEvents::get()->run_event('works.save.image', $img);
    
    if (!$img->save()){
        redirectMsg('./images.php?work='.$work->id().$ruta,__('Errors ocurred while trying to save the image', 'works').'<br />'.$img->errors(),1);
        die();
    }else{    
        redirectMsg('./images.php?work='.$work->id().$ruta,__('Database updated successfully!', 'works'),0);
        die();

    }
}

/**
* @desc Elimina de la base de datos las imagenes especificadas
**/
function deleteImages(){
    global $xoopsSecurity, $xoopsModule;
    
    $ids = rmc_server_var($_REQUEST, 'ids', 0);
    $work = rmc_server_var($_REQUEST, 'work', 0);
    $page = rmc_server_var($_REQUEST, 'page', 0);

    $ruta = "&page=$page";

    //Verificamos que nos hayan proporcionado una imagen para eliminar
    if (!is_array($ids)){
        redirectMsg('./images.php?work='.$work.$ruta, __('You must select an image to delete!', 'works'),1);
        die();
    }

    if (!$xoopsSecurity->check()){
        redirectMsg('./images.php?work='.$work.$ruta,__('Session token expired!', 'works'), 1);
        die();
    }

    $errors = '';
    foreach ($ids as $k){
        //Verificamos si la imagen es válida
        if ($k<=0){
            $errors.=sprintf(__('Image ID "%s" is not valid!', 'works'), $k);
            continue;
        }

            //Verificamos si la imagen existe
            $img = new PWImage($k);
            if ($img->isNew()){
                $errors.=sprintf(__('Image with ID "%s" does not exists!', 'works'), $k);
                continue;
            }
        
            if (!$img->delete()){
                $errors.=sprintf(__('Image "%s" could not be deleted!', 'works'),$img->title());
            }
        }
    
        if ($errors!=''){
            redirectMsg('./images.php?work='.$work.$ruta,__('Errors ocurred while trying to delete images', 'works').'<br />'.$errors,1);
            die();
        }else{
            redirectMsg('./images.php?work='.$work.$ruta,__('Images deleted successfully!', 'works'),0);
            die();
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
    
    case 'saveedit':
        shop_save_product(1);
        break;
        
    case 'delete':
        shop_delete_products();
        break;
    
    case 'images':
        shop_show_images();
        break;
    
    case 'editimage':
        shop_form_images(1);
        break;
    
    case 'saveimage':
        shop_save_image();
        break;
           
    default:
        shop_show_products();
        break;
    
}
