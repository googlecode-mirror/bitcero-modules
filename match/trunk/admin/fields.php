<?php
// $Id: champ.php 642 2011-06-04 19:52:38Z i.bitcero $
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','fields');
include 'header.php';

function m_show_fields(){
    global $xoopsModule, $xoopsSecurity;
    
    $page = rmc_server_var($_REQUEST,'page', 1);
    $page = $page<=0 ? 1 : $page;
    $limit = 15;
    
    $db = Database::getInstance();
    
    //Barra de Navegación
    $sql = "SELECT COUNT(*) FROM ".$db->prefix('mch_fields');
    
    list($num)=$db->fetchRow($db->query($sql));

    $tpages = ceil($num/$limit);
    $page = $page > $tpages ? $tpages : $page; 

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('fields.php?page={PAGE_NUM}');
    
    $fields = array();
    $sql = str_replace('COUNT(*)','*', $sql);
    $sql .= ' ORDER BY name';
    $result = $db->query($sql);
    
    while ($row = $db->fetchArray($result)){
        $field = new MCHField();
        $field->assignVars($row);
        
        $fields[] = array(
            'id'            => $field->id(),
            'name'          => $field->getVar('name'),
            'nameid'          => $field->getVar('nameid'),
            'description'        => $field->getVar('description')
        );
    }
    
    $form = new RMForm('','','');
    $editor = new RMFormEditor('','description', '98%', '200px', '', 'html');
    
    MCHFunctions::toolbar();
    xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; ".__('Fields','match'));
    RMTemplate::get()->assign('xoops_pagetitle', __('Fields','match'));
    RMTemplate::get()->add_style('admin.css', 'match');
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
    RMTemplate::get()->add_local_script('admin_match.js','match');
    RMTemplate::get()->add_head("<script type='text/javascript'>\nvar mch_message='".__('Do you really want to delete selected fields?','match')."';\n
        var mch_select_message = '".__('You must select some fields before to execute this action!','match')."';</script>");
    xoops_cp_header();
    
    $match_extra_options = RMEvents::get()->run_event('match.more.options');
    
    include RMTemplate::get()->get_template("admin/mch_fields.php", 'module', 'match');
    xoops_cp_footer();
    
}


function m_save_field($edit = 0){
    global $xoopsModule, $xoopsModuleConfig, $xoopsSecurity;
    
    $query = '';
    foreach ($_POST as $k => $v){
        $$k = $v;
        if ($k == 'XOOPS_TOKEN_REQUEST' || $k=='action' || $k=='sbt') continue;
        $query .= $query=='' ? "$k=".urlencode($v) : "&$k=".urlencode($v);
    }
    
    $action = $edit ? '?action=edit&id='.$id : '?action=new&';
    
    if (!$xoopsSecurity->check()){
        redirectMsg('fields.php?action='.($edit ? 'edit&id='.$id : 'new').'&'.$query, __('Session token expired!','match'), 1);
        die();
    }
    
    if($name==''){
        redirectMsg('fields.php?action='.($edit ? 'edit&id='.$id : 'new').'&'.$query, __('Please fill all required data!','match'), 1);
    }
    
    if ($edit){
        
        if ($id<=0){
            redirectMsg('./fields.php',__('Field ID not valid!','match'),1);
            die();
        }

        //Verificamos que el trabajo exista
        $field = new MCHField($id);
        if ($field->isNew()){
            redirectMsg('./champ.php',__('Specified field does not exists!','match'),1);
            die();
        }
        
        
    }else{
        $field = new MCHField();
    }
    
    $db = Database::getInstance();
    // Check if work exists already
    if ($edit){
        $sql = "SELECT COUNT(*) FROM ".$db->prefix("mch_fields")." WHERE name='$name' and id_field<>'$id'";
    } else {
        $sql = "SELECT COUNT(*) FROM ".$db->prefix("mch_fields")." WHERE name='$name'";
    }
    list($num)=$db->fetchRow($db->query($sql));
    if ($num>0){
        redirectMsg("fields.php".$action.$query, __('A field with same name already exists!','match'), 1);
        die();
    }
    
    //Genera $nameid Nombre identificador
    $found=false; 
    $i = 0;
    if ($name!=$field->getVar('name') || empty($nameid)){
        do{
            $nameid = TextCleaner::sweetstring($name).($found ? $i : '');
                $sql = "SELECT COUNT(*) FROM ".$db->prefix('mch_fields'). " WHERE nameid = '$nameid'";
                list ($num) =$db->fetchRow($db->queryF($sql));
                if ($num>0){
                    $found =true;
                    $i++;
                }else{
                    $found=false;
                }
        }while ($found==true);
    }

    $field->setVar('name', $name);
    $field->setVar('nameid', $nameid);
    $field->setVar('description', $description);
    
    if (!$field->save()){
        redirectMsg('./fields.php'.$action.$query,__('Errors ocurred while trying to update database!','match').$field->errors(),1);
        die();
    }else{    
        redirectMsg('./fields.php?id='.$field->id(),__('Field saved successfully!','match'),0);
        die();
    }
    
}


function m_field_form($edit = 0){
    global $xoopsModule, $xoopsModuleConfig;
    
    MCHFunctions::toolbar();
    RMTemplate::get()->assign('xoops_pagetitle',$edit?__('Edit Field','match'):__('Add Field','match'));
    xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; 
        <a href='fields.php'>".__('Fields','match').'</a> &raquo; '.($edit ? __('Edit Field','match') : __('Add Field','match')));
    xoops_cp_header();

    $id = rmc_server_var($_REQUEST, 'id', 0);
    

    if ($edit){
        //Verificamos si la categoría es válida
        if ($id<=0){
            redirectMsg('./fields.php',__('Provide a field ID!','match'),1);
            die();
        }

        //Verificamos si la categoría existe
        $field = new MCHField($id);
        if ($field->isNew()){
            redirectMsg('./fields.php',__('Specified field was not found!','match'),1);
            die();
        }
    }

    
    $form = new RMForm($edit ? __('Edit Field','match') : __('Add Field','match'),'frmNew','fields.php');

    $form->addElement(new RMFormText(__('Name','match'), 'name', 50, 200, $edit ? $field->getVar('name') : ''), true);

    if ($edit) $form->addElement(new RMFormText(__('Short name','match'), 'nameid', 50, 200, $field->getVar('nameid')), true);
    
    $form->addElement(new RMFormEditor(__('Description','match'), 'description', '100%','250px', $edit ? $field->getVar('description','e') : ''));
    
 
    $form->addElement(new RMFormHidden('action', $edit ? 'saveedit' : 'save'));
    if ($edit) $form->addElement(new RMFormHidden('id', $field->id()));
    $ele = new RMFormButtonGroup();
    $ele->addButton('sbt', $edit ? __('Save Changes!','match') : __('Add Now!','match'), 'submit');
    $ele->addButton('cancel', _CANCEL, 'button', 'onclick="window.location=\'fields.php\';"');
    $form->addElement($ele);
    
    $form = RMEvents::get()->run_event('match.form.fields', $form);
    
    $form->display();
    
    RMTemplate::get()->add_style('admin.css', 'match');
    
    xoops_cp_footer();
}


function m_delete_fields(){
    global $xoopsModule, $xoopsSecurity;

    $ids = rmc_server_var($_POST, 'ids', array());
    
    //Verificamos que nos hayan proporcionado una categoría para eliminar
    if (empty($ids)){
        redirectMsg('./fields.php',__('No fields selected!','match'),1);
        die();
    }

    if (!$xoopsSecurity->check()){
        redirectMsg('./fields.php',__('Session token expired!','match'), 1);
        die();
    }
    
    $db = Database::getInstance();

    $errors = '';
    foreach ($ids as $k){
        //Verificamos si la categoría es válida
        if ($k<=0){
            $errors.=sprintf(__('Field id "%s" is not valid!','match'), $k);
            continue;
        }

        //Verificamos si la categoría existe
        $field = new MCHField($k);
        if ($field->isNew()){
            $errors.=sprintf(__('Field "%s" does not exists!','match'), $k);
            continue;
        }
            
        RMEvents::get()->run_event('match.delete.field', $field);
            
        if (!$field->delete()){
            $errors.=sprintf(__('Field "%s" could not be deleted!','match'),$field->getVar('name'));
        }
        
    }
    
    if ($errors!=''){
        redirectMsg('./fields.php',__('Errors ocurred while trying to delete fields','match').'<br />'.$errors,1);
        die();
    }else{
        redirectMsg('./fields.php',__('Database updated successfully!','match'),0);
        die();
    }
    
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    
    case 'save':
        m_save_field();
        break;
    case 'saveedit':
        m_save_field(1);
        break;
    case 'edit':
        m_field_form(1);
        break;
    case 'delete':
        m_delete_fields();
        break;
    default:
        m_show_fields();
        break;
    
}
