<?php
// $Id$
// --------------------------------------------------------------
// MiniShop 3
// Module for catalogs
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------


define('RMCLOCATION','categories');
require('header.php');

/**
 * Muestra una lista de las categorías existentes
 */
function shop_show_categos(){
    global $xoopsModule, $xoopsSecurity;
    
    $row = array();
    ShopFunctions::categos_list($row);
    $categories = array();
    
    foreach ($row as $k){
        $catego = new ShopCategory();
        $catego->assignVars($k);
        $catego->loadProducts();
        $categories[] = array(
            'id_cat'=>$catego->id(),
            'description'=>$catego->getVar('description','n'),
            'products'=>$catego->getVar('products','n'), 
            'name'=>$catego->getVar('name','n'),
            'indent'=>$k['indent'],
            'shortname'=>$catego->getVar('shortname','n')
        );
    }
    
    // Categories pagination
    // Paginamos
    $limit = 15;
    if (count($categories)>$limit){
        
        $page = rmc_server_var($_GET, 'page', 1);
        $page = $page<1 ? 1 : $page;
        $limit_c = $page * ($limit-1);
        
        $limit_c = $limit_c>count($categories)-1 ? count($categories) - 1 : $limit_c;
        $tpages = ceil(count($categories)/3);
        
        if ($page>$tpages) $page=$tpages;
        
        $nav = new RMPageNav(count($categories), $limit, $page, 5);
        $nav->target_url('?page={PAGE_NUM}');
        
        $i = ($page-1)*$limit;

        while ($i<=$limit_c){
            
            $pcategories[] = $categories[$i];
            $i++;
            
        }
        
        $categories = $pcategories;
        
    }
    
    ShopFunctions::include_required_files();
    
    RMTemplate::get()->add_head(
        '<script type="text/javascript">
            function cat_del_confirm(cat, id){
      
              var string = "'.__('Do you really want to delete "%s"','shop').'";
                     string = string.replace("%s", cat);
                     var ret = confirm(string);
                     
                     if (ret){
                         $("#tblCats input[type=checkbox]").removeAttr("checked");
                         $("#cat-"+id).attr("checked","checked");
                         $("#cat-op").val("delete");
                         $("#tblCats").submit();
                    }
              
          }
      </script>'
    );
    
    RMTemplate::get()->set_help('http://redmexico.com.mx/docs/minishop/');
    
    xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '.__('Categories','shop'));
    xoops_cp_header();
    
    $desc = ''; $posts = ''; $parent = ''; $selcat = ''; $new = ''; $name=''; $shortcut = '';
    extract($_GET);
    include RMTemplate::get()->get_template('admin/shop_categories.php','module','shop');
    RMTemplate::get()->assign('xoops_pagetitle', __('Categories Management','shop'));
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
    
    xoops_cp_footer();
    
}

/**
 * Presenta un formulario para la creación de una nueva
 * categoría para los artículos
 */
function show_new_form($edit = 1){
    global $xoopsModule;
    
    $id = rmc_server_var($_GET, 'id', 0);
    
    if ($id<=0){
        redirectMsg('categories.php',  __('You must specify a valid category','shop'), 1);
        die();
    }
    // Cargamos la categoría
    $catego = new ShopCategory($id);
    // Si no existe entonces devolvemos un error
    if ($catego->isNew()){
        redirectMsg('categories.php', __('Specified category not exists!','shop'), 1);
        die();
    }
    
    ShopFunctions::include_required_files();
    xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '.__('Edit Category','shop'));
    xoops_cp_header();
    
    $cats = array();
    ShopFunctions::categos_list($cats, 0, 0, true, $id);
    
    $form = new RMForm($edit ? __('Edit Category','shop') : __('New Category','shop'), 'frmNew', 'categories.php');
    $form->styles('width: 30%;','odd');
    $form->addElement(new RMFormText(__('Category name','shop'), 'name', 50, 150, $catego->getVar('name')), true);
    $form->addElement(new RMFormText(__('Category slug','shop'), 'shortname', '', '150', $catego->getVar('shortname','n')));
    $form->addElement(new RMFormTextArea(__('Category description','shop'), 'desc', 5, 45, $catego->getVar('description','e')));
    $ele = new RMFormSelect(__('Category Parent','shop'), 'parent');
    $ele->addOption(0, _SELECT, $catego->getVar('parent')==0 ? 1 : 0);
    foreach ($cats as $k){
        $ele->addOption($k['id_cat'], str_repeat("-", $k['indent']) . ' ' . $k['name'], $catego->getVar('parent')==$k['id_cat'] ? 1 : 0);
    }
    $form->addElement($ele);
    
    $form->addElement(new RMFormHidden('action', 'saveedit'));
    $form->addElement(new RMFormHidden('id', $id));
    
    $ele = new RMFormButtonGroup('',' ');
    $ele->addButton('sbt', __('Update Category','shop'), 'submit');
    $ele->addButton('cancel', __('Cancel','shop'), 'button');
    $ele->setExtra('cancel', "onclick='history.go(-1);'");
    $form->addElement($ele);
    $form->display();
    
    xoops_cp_footer();
    
}

/**
 * Almacenamos la categoría en la base de datos
 */
function shop_save_category($edit = 0){
    global $xoopsSecurity;
    
    if (!$xoopsSecurity->check()){
        redirectMsg('categories.php', __('Sorry, session token expired!','shop'), 1);
        die();
    }
    
    $db = Database::getInstance();
    
    $query = '';
    foreach ($_POST as $k => $v){
        $$k = $v;
        if ($k=='action' || $k=='XOOPS_TOKEN_REQUEST') continue;
        $query .= $query=='' ? "$k=".urlencode($v) : '&'.$k.'='.urlencode($v);
    }
    
    $query = $edit ? '&action=edit' : '';
    
    if ($edit){
        if ($id<=0){
            redirectMsg('categories.php', __('You must specify a valid category','shop'), 1);
            die();
        }
        
        $catego = new ShopCategory($id);
        if ($catego->isNew()){
            redirectMsg('categories.php', __('Specified category not exists!','shop'), 1);
            die();
        }
    } else {
        $catego = new ShopCategory();
    }
        
    if ($name==''){
        redirectMsg('categories.php?'.$query, __('Please specify a name for this category!','shop'), 1);
        die();
    }
    
    $shortname = $shortname=='' ? TextCleaner::sweetstring($name) : $shortname;
    
    # Verificamos que no exista la categoría
    $result = $db->query("SELECT COUNT(*) FROM ".$db->prefix("shop_categories")." WHERE parent='$parent'".($edit ? " AND id_cat<>$id" : '')." AND (name='$name' OR shortname='$shortname')");
    list($num) = $db->fetchRow($result);
    
    if ($num>0){
        redirectMsg('categories.php?'.$query, __('There is already a category with the same name!','shop'), 1);
        die();
    }
    
    # Si todo esta bien guardamos la categoría
    $catego->setVar('name',$name);
    $catego->setVar('shortname',$shortname);
    $catego->setVar('description',$desc);
    $catego->setVar('parent',$parent);
    if (!$edit) $catego->setVar('products',0);
    
    $result = $catego->save();
    
    if ($result){
        redirectMsg('categories.php', __('Category created succesfully!','shop'), 0);
    } else {
        redirectMsg('categories.php?'.$query, __('There was an error!','shop'). "<br />" . $catego->errors(), 1);
    }
    
}
/**
 * Elimina una categoría de la base de datos.
 * Las subcategorías pertenecientes a esta categoría no son eliminadas,
 * sino que son asignadas a la categoría superior.
 */
function shop_delete_category(){
    global $xoopsSecurity, $xoopsModule;
    
    $cats = rmc_server_var($_POST, 'cats', array());
    
    if (empty($cats)){
        redirectMsg('categories.php', __('You must select one category at least!','shop'), 1);
        die();
    }
    
    if(!$xoopsSecurity->check()){
        redirectMsg('categories.php', __("Session token expired!", 'mw_categories'), 1);
        die();
    }
    
    $db = Database::getInstance();
    $sql = "SELECT * FROM ".$db->prefix("shop_categories")." WHERE id_cat IN (".implode(",", $cats).")";
    $result = $db->query($sql);
    
    while($row = $db->fetchArray($result)){
        $cat = new ShopCategory();
        $cat->assignVars($row);
        if (!$cat->delete()){
            showMessage(__('Category "%s" could not be deleted','shop'), 1);
        }
    }
    
    redirectMsg('categories.php', __('Database updated!', 'shop'), 0);
    
}

$action = rmc_server_var($_REQUEST, 'action', '');

switch ($action){
    case 'save':
        shop_save_category();
        break;
    case 'saveedit':
        shop_save_category(1);
        break;
    case 'edit':
        show_new_form(1);
        break;
    case 'delete':
        shop_delete_category();
        break;
    default:
        shop_show_categos();
        break;
}

