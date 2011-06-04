<?php
// $Id$
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------
define('RMCLOCATION','categories');
include 'header.php';

function m_show_categories(){
    global $xoopsModule, $xoopsModuleConfig, $xoopsSecurity;
    
    define('RMCSUBLOCATION','categories');
    
    $categories = array();
    MCHFunctions::categories_tree($categories, 0, 0, true, 0, '`name` ASC');
    
    // Event
    $categories = RMEvents::get()->run_event('match.list.categories', $categories);

    
    MCHFunctions::toolbar();
    xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; ".__('Categories','match'));
    RMTemplate::get()->assign('xoops_pagetitle', __('Categories','match'));
    RMTemplate::get()->add_style('admin.css', 'match');
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
    RMTemplate::get()->add_local_script('admin_match.js', 'match');
    RMTemplate::get()->add_head("<script type='text/javascript'>\nvar mch_message='".__('Do you really want to delete selected categories?','match')."';\n
        var mch_select_message = '".__('You must select some category before to execute this action!','match')."';</script>");
    xoops_cp_header();
    
    include RMTemplate::get()->get_template("admin/mch_categories.php", 'module', 'match');
    xoops_cp_footer();
}

/**
* Show the form to edit or create a category
* @param int edit
*/
function m_categories_form($edit = 0){
    global $mc, $xoopsModule, $db;
    
    MCHFunctions::toolbar();
    RMTemplate::get()->assign('xoops_pagetitle',$edit?__('Edit Category','match'):__('Add Category','match'));
    xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; 
        <a href='categories.php'>".__('Categories','match').'</a> &raquo; '.($edit ? __('Edit Category','match') : __('Add Category','match')));
    xoops_cp_header();

    $id = rmc_server_var($_REQUEST, 'id', 0);
    

    if ($edit){
        //Verificamos si la categoría es válida
        if ($id<=0){
            redirectMsg('./categories.php',__('Provide a category ID!','match'),1);
            die();
        }

        //Verificamos si la categoría existe
        $cat = new MCHCategory($id);
        if ($cat->isNew()){
            redirectMsg('./categories.php',__('Specified category was not found!','match'),1);
            die();
        }
    }

    
    $form = new RMForm($edit ? __('Edit Category','match') : __('Add Category','match'),'frmNew','categories.php');

    $form->addElement(new RMFormText(__('Name','match'), 'name', 50, 150, $edit ? $cat->getVar('name') : ''), true);

    if ($edit) $form->addElement(new RMFormText(__('Short name','match'), 'nameid', 50, 150, $cat->getVar('nameid')), true);
    $form->addElement(new RMFormEditor(__('Description','match'), 'desc', '100%','250px', $edit ? $cat->getVar('description','e') : ''));
    $sel_cats = new RMFormSelect(__('Parent category:','match'), 'parent', 0, $edit ? $cat->getVar('parent') : 0);
    $categories = array();
    MCHFunctions::categories_tree($categories, 0, 0, true, $edit ? $cat->id() : 0, '`name` ASC');
    $sel_cats->addOption(0, __('Select category...','match'), $edit ? ($cat->getVar('parent')==0 ? 1 : 0) : 1);
    foreach($categories as $catego){
        $sel_cats->addOption($catego['id'], str_repeat('&#151;', $catego['indent']).' '.$catego['name']);
    }
    $form->addElement($sel_cats);
    
    $form->addElement(new RMFormYesNo(__('Enable category','match'), 'active', $edit ? $cat->getVar('active') : 1));
    
    $form->addElement(new RMFormHidden('action', $edit ? 'saveedit' : 'save'));
    if ($edit) $form->addElement(new RMFormHidden('id', $cat->id()));
    $ele = new RMFormButtonGroup();
    $ele->addButton('sbt', $edit ? __('Save Changes!','match') : __('Add Now!','match'), 'submit');
    $ele->addButton('cancel', _CANCEL, 'button', 'onclick="window.location=\'categos.php\';"');
    $form->addElement($ele);
    
    $form = RMEvents::get()->run_event('match.form.categories', $form);
    
    $form->display();
    
    xoops_cp_footer();
}

/**
* Save a new or edited category
*/
function m_save_category($edit = 0){
    global $xoopsSecurity;
    
    foreach ($_POST as $k => $v){
        $$k = $v;
    }
    
    if (!$xoopsSecurity->check()){
        redirectMsg('./categories.php?action='.($edit ? 'edit&id='.$id : 'new'), __('Session token expired!', 'match'), 1);
        die();
    }
    
    $db = Database::getInstance();

    if ($edit){
        //Verificamos si la categoría es válida
        if ($id<=0){
            redirectMsg('./categories.php?action=edit&id='.$id,__('Wrong category ID!','match'),1);
            die();
        }

        //Verificamos si la categoría existe
        $cat = new MCHCategory($id);
        if ($cat->isNew()){
            redirectMsg('./categories.php?action=edit&id='.$id,__('Specified category does not exists!','match'),1);
            die();
        }

        //Verificamos el nombre de la categoría
        $sql = "SELECT COUNT(*) FROM ".$db->prefix('mch_categories')." WHERE name='$name' AND id_cat<>'$id'";
        list($num) = $db->fetchRow($db->query($sql));
        if ($num>0){
            redirectMsg('./categories.php?action=edit&id='.$id,__('A category with same name already exists!','match'),1);
            die();
        }

        if ($nameid){

            $sql="SELECT COUNT(*) FROM ".$db->prefix('mch_categories')." WHERE nameid='$nameid' AND id_cat<>'".$id."'";
            list($num)=$db->fetchRow($db->queryF($sql));
            if ($num>0){
                redirectMsg('./categories.php?action=edit&id='.$id,__('There are already a category with same name id!','match'),1);
                die();
            }

        }


    }else{
        $cat = new MCHCategory();
    }

    //Genera $nameid Nombre identificador
    $found=false; 
    $i = 0;
    if ($name!=$cat->getVar('name') || empty($nameid)){
        do{
            $nameid = TextCleaner::sweetstring($name).($found ? $i : '');
                $sql = "SELECT COUNT(*) FROM ".$db->prefix('mch_categories'). " WHERE nameid = '$nameid'";
                list ($num) =$db->fetchRow($db->queryF($sql));
                if ($num>0){
                    $found =true;
                    $i++;
                }else{
                    $found=false;
                }
        }while ($found==true);
    }


    $cat->setVar('name',$name);
    $cat->setVar('description', $desc);
    $cat->setVar('active',$active);
    $cat->setVar('nameid', $nameid);
    $cat->setVar('parent', $parent);
    
    $cat = RMEvents::get()->run_event('match.save.category', $cat);
    
    if (!$cat->save()){
        redirectMsg('./categories.php',__('Errors ocurred while trying to update database!','match').'<br />'.$cat->errors(),1);
        die();
    }else{
        redirectMsg('./categories.php',__('Database updated successfully!','match'),0);
        die();
    }
}

/**
* Deleting a category
*/
function m_delete_category(){
    global $xoopsModule, $xoopsSecurity;

    $ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
    $ok = isset($_POST['ok']) ? intval($_POST['ok']) : 0;
    
    //Verificamos que nos hayan proporcionado una categoría para eliminar
    if (!is_array($ids) && ($ids<=0)){
        redirectMsg('./categories.php',__('No categories selected!','match'),1);
        die();
    }
    
    if (!is_array($ids)){
        $catego = new MCHCategory($ids);
        $ids = array($ids);
    }

    if (!$xoopsSecurity->check()){
        redirectMsg('./categories.php',__('Session token expired!','match'), 1);
        die();
    }
    
    $db = Database::getInstance();

    $errors = '';
    foreach ($ids as $k){
        //Verificamos si la categoría es válida
        if ($k<=0){
            $errors.=sprintf(__('Category id "%s" is not valid!','match'), $k);
            continue;
        }

        //Verificamos si la categoría existe
        $cat = new MCHCategory($k);
        if ($cat->isNew()){
            $errors.=sprintf(__('Category "%s" does not exists!','match'), $k);
            continue;
        }
            
        RMEvents::get()->run_event('match.delete.category', $cat);
            
        if (!$cat->delete()){
            $errors.=sprintf(__('Category "%s" could not be deleted!','match'),$k);
        }else{
            $sql = "UPDATE ".$db->prefix('mch_categories')." SET parent='".$cat->getVar('parent')."' WHERE parent='".$cat->id()."'";
            $result = $db->queryF($sql);
        }
        
    }
    
    if ($errors!=''){
        redirectMsg('./categories.php',__('Errors ocurred while trying to delete categories','match').'<br />'.$errors,1);
        die();
    }else{
        redirectMsg('./categories.php',__('Database updated successfully!','match'),0);
        die();
    }
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    
    case 'save':
        m_save_category();
        break;
    case 'saveedit':
        m_save_category(1);
        break;
    case 'new':
        m_categories_form();
        break;
    case 'edit':
        m_categories_form(1);
        break;
    case 'delete':
        m_delete_category();
        break;
    default:
        m_show_categories();
        break;
    
}
