<?php
// $Id$
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','championships');
include 'header.php';

function m_show_championships(){
    global $xoopsModule, $xoopsSecurity;
    
    $page = rmc_server_var($_REQUEST,'page', 1);
    $page = $page<=0 ? 1 : $page;
    $limit = 15;
    
    $db = Database::getInstance();
    
    //Barra de Navegación
    $sql = "SELECT COUNT(*) FROM ".$db->prefix('mch_champs');
    
    list($num)=$db->fetchRow($db->query($sql));

    $tpages = ceil($num/$limit);
    $page = $page > $tpages ? $tpages : $page; 

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('champ.php?page={PAGE_NUM}');
    
    $champs = array();
    $sql = str_replace('COUNT(*)','*', $sql);
    $sql .= ' ORDER BY start DESC,name ASC';
    $result = $db->query($sql);

    $timef = new RMTimeFormatter('',"%M% %d%, %Y%");
    
    while ($row = $db->fetchArray($result)){
        $champ = new MCHChampionship();
        $champ->assignVars($row);
        
        $champs[] = array(
            'id'            => $champ->id(),
            'link'          => $champ->permalink(),
            'name'          => $champ->getVar('name'),
            'nameid'          => $champ->getVar('nameid'),
            'start'          => $timef->format($champ->getVar('start')),
            'end'          => $timef->format($champ->getVar('end')),
            'description'        => $champ->getVar('description')
        );
    }
    
    $form = new RMForm('','','');
    $editor = new RMFormEditor('','description', '98%', '200px', '', 'html');
    $start = new RMFormDate('','start',time());
    $end = new RMFormDate('','end',time());
    
    MCHFunctions::toolbar();
    xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; ".__('Championships','match'));
    RMTemplate::get()->assign('xoops_pagetitle', __('Championships','match'));
    RMTemplate::get()->add_style('admin.css', 'match');
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
    RMTemplate::get()->add_local_script('admin_match.js','match');
    RMTemplate::get()->add_head("<script type='text/javascript'>\nvar mch_message='".__('Do you really want to delete selected championships?','match')."';\n
        var mch_select_message = '".__('You must select some championships before to execute this action!','match')."';</script>");
    xoops_cp_header();
    
    $match_extra_options = RMEvents::get()->run_event('match.more.options');
    
    include RMTemplate::get()->get_template("admin/mch_champs.php", 'module', 'match');
    xoops_cp_footer();
    
}


function m_save_championship($edit = 0){
    global $xoopsModule, $xoopsModuleConfig, $xoopsSecurity;
    
    $query = '';
    foreach ($_POST as $k => $v){
        $$k = $v;
        if ($k == 'XOOPS_TOKEN_REQUEST' || $k=='action' || $k=='sbt') continue;
        $query .= $query=='' ? "$k=".urlencode($v) : "&$k=".urlencode($v);
    }
    
    $action = $edit ? '?action=edit&id='.$id : '?action=new&';
    
    if (!$xoopsSecurity->check()){
        redirectMsg('champ.php?action='.($edit ? 'edit&id='.$id : 'new').'&'.$query, __('Session token expired!','match'), 1);
        die();
    }
    
    if($name=='' || $start<=0 || $end<=0){
        redirectMsg('champ.php?action='.($edit ? 'edit&id='.$id : 'new').'&'.$query, __('Please fill all required data!','match'), 1);
    }
    
    if ($edit){
        
        if ($id<=0){
            redirectMsg('./champ.php',__('Championship ID not valid!','match'),1);
            die();
        }

        //Verificamos que el trabajo exista
        $champ = new MCHChampionship($id);
        if ($champ->isNew()){
            redirectMsg('./champ.php',__('Specified championship does not exists!','match'),1);
            die();
        }
        
        
    }else{
        $champ = new MCHChampionship();
    }
    
    $db = Database::getInstance();
    // Check if work exists already
    if ($edit){
        $sql = "SELECT COUNT(*) FROM ".$db->prefix("mch_champs")." WHERE name='$name' and id_champ<>'$id'";
    } else {
        $sql = "SELECT COUNT(*) FROM ".$db->prefix("mch_champs")." WHERE name='$name'";
    }
    list($num)=$db->fetchRow($db->query($sql));
    if ($num>0){
        redirectMsg("champ.php".$action.$query, __('A championship with same name already exists!','match'), 1);
        die();
    }
    
    //Genera $nameid Nombre identificador
    $found=false; 
    $i = 0;
    if ($name!=$champ->getVar('name') || empty($nameid)){
        do{
            $nameid = TextCleaner::sweetstring($name).($found ? $i : '');
                $sql = "SELECT COUNT(*) FROM ".$db->prefix('mch_champs'). " WHERE nameid = '$nameid'";
                list ($num) =$db->fetchRow($db->queryF($sql));
                if ($num>0){
                    $found =true;
                    $i++;
                }else{
                    $found=false;
                }
        }while ($found==true);
    }

    $champ->setVar('name', $name);
    $champ->setVar('nameid', $nameid);
    $champ->setVar('description', $description);
    $champ->setVar('start', $start);
    $champ->setVar('end', $end);
    
    if (!$champ->save()){
        redirectMsg('./champ.php'.$action.$query,__('Errors ocurred while trying to update database!','match').$champ->errors(),1);
        die();
    }else{    
        redirectMsg('./champ.php?id='.$champ->id(),__('Championship saved successfully!','match'),0);
        die();
    }
    
}


function m_championship_form($edit = 0){
    global $xoopsModule, $xoopsModuleConfig;
    
    MCHFunctions::toolbar();
    RMTemplate::get()->assign('xoops_pagetitle',$edit?__('Edit Championship','match'):__('Add Championship','match'));
    xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; 
        <a href='champ.php'>".__('Championships','match').'</a> &raquo; '.($edit ? __('Edit Championship','match') : __('Add Championship','match')));
    xoops_cp_header();

    $id = rmc_server_var($_REQUEST, 'id', 0);
    

    if ($edit){
        //Verificamos si la categoría es válida
        if ($id<=0){
            redirectMsg('./champ.php',__('Provide a championship ID!','match'),1);
            die();
        }

        //Verificamos si la categoría existe
        $champ = new MCHChampionship($id);
        if ($champ->isNew()){
            redirectMsg('./champ.php',__('Specified championship was not found!','match'),1);
            die();
        }
    }

    
    $form = new RMForm($edit ? __('Edit Championship','match') : __('Add Championship','match'),'frmNew','champ.php');

    $form->addElement(new RMFormText(__('Name','match'), 'name', 50, 200, $edit ? $champ->getVar('name') : ''), true);

    if ($edit) $form->addElement(new RMFormText(__('Short name','match'), 'nameid', 50, 200, $champ->getVar('nameid')), true);
    
    $form->addElement(new RMFormEditor(__('Description','match'), 'description', '100%','250px', $edit ? $champ->getVar('description','e') : ''));
    
    $form->addElement(new RMFormDate(__('Start date','match'), 'start', $edit ? $champ->getVar('start') : 0), true);
    $form->addElement(new RMFormDate(__('End date','match'), 'end', $edit ? $champ->getVar('end') : 0), true);
    
    $form->addElement(new RMFormHidden('action', $edit ? 'saveedit' : 'save'));
    if ($edit) $form->addElement(new RMFormHidden('id', $champ->id()));
    $ele = new RMFormButtonGroup();
    $ele->addButton('sbt', $edit ? __('Save Changes!','match') : __('Add Now!','match'), 'submit');
    $ele->addButton('cancel', _CANCEL, 'button', 'onclick="window.location=\'champ.php\';"');
    $form->addElement($ele);
    
    $form = RMEvents::get()->run_event('match.form.championships', $form);
    
    $form->display();
    
    RMTemplate::get()->add_style('admin.css', 'match');
    
    xoops_cp_footer();
}


function m_delete_championships(){
    global $xoopsModule, $xoopsSecurity;

    $ids = rmc_server_var($_POST, 'ids', array());
    
    //Verificamos que nos hayan proporcionado una categoría para eliminar
    if (empty($ids)){
        redirectMsg('./champ.php',__('No championships selected!','match'),1);
        die();
    }

    if (!$xoopsSecurity->check()){
        redirectMsg('./champ.php',__('Session token expired!','match'), 1);
        die();
    }
    
    $db = Database::getInstance();

    $errors = '';
    foreach ($ids as $k){
        //Verificamos si la categoría es válida
        if ($k<=0){
            $errors.=sprintf(__('Championship id "%s" is not valid!','match'), $k);
            continue;
        }

        //Verificamos si la categoría existe
        $champ = new MCHChampionship($k);
        if ($champ->isNew()){
            $errors.=sprintf(__('Championship "%s" does not exists!','match'), $k);
            continue;
        }
            
        RMEvents::get()->run_event('match.delete.championship', $cat);
            
        if (!$champ->delete()){
            $errors.=sprintf(__('Championship "%s" could not be deleted!','match'),$champ->getVar('name'));
        }
        
    }
    
    if ($errors!=''){
        redirectMsg('./champ.php',__('Errors ocurred while trying to delete championships','match').'<br />'.$errors,1);
        die();
    }else{
        redirectMsg('./champ.php',__('Database updated successfully!','match'),0);
        die();
    }
    
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    
    case 'save':
        m_save_championship();
        break;
    case 'saveedit':
        m_save_championship(1);
        break;
    case 'edit':
        m_championship_form(1);
        break;
    case 'delete':
        m_delete_championships();
        break;
    default:
        m_show_championships();
        break;
    
}
