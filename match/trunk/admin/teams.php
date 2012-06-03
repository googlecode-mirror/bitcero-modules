<?php
// $Id$
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','teams');
include 'header.php';

function m_show_teams(){
    
    global $xoopsModule, $xoopsSecurity;
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    
    $id = rmc_server_var($_REQUEST, 'id', 0);
    if($id>0){
        MCHFunctions::page_from_item($id, 'team');
    }
    
    $page = rmc_server_var($_REQUEST,'page', 1);
    $page = $page<=0 ? 1 : $page;
    $limit = 15;
    $category = rmc_server_var($_REQUEST, 'category', 0);
    
    //Barra de Navegación
    $sql = "SELECT COUNT(*) FROM ".$db->prefix('mch_teams');
    if ($category>0){
        $sql .= " WHERE category=$category";
    }
    
    list($num)=$db->fetchRow($db->query($sql));

    $tpages = ceil($num/$limit);
    $page = $page > $tpages ? $tpages : $page; 

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('teams.php?page={PAGE_NUM}');
    
    $teams = array();
    $result = $db->query("SELECT * FROM ".$db->prefix("mch_teams").($category>0 ? " WHERE category=$category" : '')." ORDER BY `wins`,active LIMIT $start,$limit");
    
    $cache_cat = array();
    
    while ($row = $db->fetchArray($result)){
        $team = new MCHTeam();
        $team->assignVars($row);
        
        if(isset($cache_cat[$team->getVar('category')])){
            $cat = $cache_cat[$team->getVar('category')];
        } else {
            $cache_cat[$team->getVar('category')] = new MCHCategory($team->getVar('category'));
            $cat = $cache_cat[$team->getVar('category')];
        }
        
        $date = new RMTimeFormatter(0, __('%M% %d%, %Y%', 'match'));
        
        $teams[] = array(
            'id'            => $team->id(),
            'link'          => $team->permalink(),
            'name'          => $team->getVar('name'),
            'active'        => $team->getVar('active'),
            'wins'         => $team->getVar('wins'),
            'nameid'        => $team->getVar('nameid'),
            'info'    => $team->getVar('info'),
            'created' => $date->format($team->getVar('created')),
            'category'  => array(
                'id' => $cat->id(),
                'name' => $cat->getVar('name'),
                'link' => $cat->permalink()
            )
        );
    }
    
    // Categories
    $categories = array();
    MCHFunctions::categories_tree($categories);
    
    // Event
    $teams = RMEvents::get()->run_event('match.list.teams', $teams);

    MCHFunctions::toolbar();
    xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; ".__('Teams','match'));
    RMTemplate::get()->assign('xoops_pagetitle', __('Teams','match'));
    RMTemplate::get()->add_style('admin.css', 'match');
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
    RMTemplate::get()->add_local_script('admin_match.js','match');
    RMTemplate::get()->add_head("<script type='text/javascript'>\nvar mch_message='".__('Do you really want to delete selected teams?\nAll players and coaches assigned to this team will be deleted also.\n\nIf you no want to lose data, then reassign players and coaches before to delete this team.','match')."';\n
        var mch_select_message = '".__('You must select some team before to execute this action!','match')."';</script>");
    xoops_cp_header();
    
    $match_extra_options = RMEvents::get()->run_event('match.more.options');
    
    include RMTemplate::get()->get_template("admin/mch_teams.php", 'module', 'match');
    xoops_cp_footer();
    
}


function m_teams_form($edit=0){

    global $mc, $xoopsModule, $db;
    
    MCHFunctions::toolbar();
    RMTemplate::get()->assign('xoops_pagetitle',$edit?__('Edit Team','match'):__('Add Team','match'));
    xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; 
        <a href='teams.php'>".__('Teams','match').'</a> &raquo; '.($edit ? __('Edit Team','match') : __('Add Team','match')));
    xoops_cp_header();

    $id = rmc_server_var($_REQUEST, 'id', 0);
    

    if ($edit){
        //Verificamos si la categoría es válida
        if ($id<=0){
            redirectMsg('./teams.php',__('Provide a team ID!','match'),1);
            die();
        }

        //Verificamos si la categoría existe
        $team = new MCHTeam($id);
        if ($team->isNew()){
            redirectMsg('./teams.php',__('Specified team was not found!','match'),1);
            die();
        }
    }

    
    $form = new RMForm($edit ? __('Edit Team','match') : __('Add Team','match'),'frmNew','teams.php');
    $form->setExtra('enctype="multipart/form-data"');

    $form->addElement(new RMFormText(__('Name','match'), 'name', 50, 150, $edit ? $team->getVar('name') : ''), true);

    if ($edit) $form->addElement(new RMFormText(__('Short name','match'), 'nameid', 50, 150, $team->getVar('nameid')), true);
    $form->addElement(new RMFormEditor(__('Team Information','match'), 'info', '100%','250px', $edit ? $team->getVar('info','e') : ''));
    $sel_cats = new RMFormSelect(__('Category:','match'), 'category', 0, $edit ? array($team->getVar('category')) : 0);
    $categories = array();
    MCHFunctions::categories_tree($categories, 0, 0, true, 0, '`name` ASC');
    $sel_cats->addOption(0, __('Select category...','match'), $edit ? ($team->getVar('category')==0 ? 1 : 0) : 1);
    foreach($categories as $catego){
        $sel_cats->addOption($catego['id'], str_repeat('&#151;', $catego['indent']).' '.$catego['name']);
    }
    $form->addElement($sel_cats, true);
    
    $form->addElement(new RMFormYesNo(__('Active','match'), 'active', $edit ? $team->getVar('active') : 1));
    
    $form->addElement(new RMFormDate(__('Registered on','match'), 'created', $edit ? $team->getVar('created') : time(), false));
    
    $form->addElement(new RMFormFile(__('Team logo','match'), 'logo', 45));
    
    if($edit){
        $form->addElement(new RMFormLabel(__('Current logo','match'), '<img src="'.MCH_UP_URL.'/'.$team->getVar('logo').'" alt="'.$team->getVar('name').'" />'));
    }
    
    $form->addElement(new RMFormHidden('action', $edit ? 'saveedit' : 'save'));
    if ($edit) $form->addElement(new RMFormHidden('id', $team->id()));
    $ele = new RMFormButtonGroup();
    $ele->addButton('sbt', $edit ? __('Save Changes!','match') : __('Add Now!','match'), 'submit');
    $ele->addButton('cancel', _CANCEL, 'button', 'onclick="window.location=\'teams.php\';"');
    $form->addElement($ele);
    
    $form = RMEvents::get()->run_event('match.form.teams', $form);
    
    $form->display();
    
    xoops_cp_footer();
    
}


function m_save_team($edit = 0){
    
    global $xoopsSecurity, $xoopsModuleConfig;
    
    $query = '';
    foreach ($_POST as $k => $v){
        $$k = $v;
        if ($k == 'XOOPS_TOKEN_REQUEST' || $k=='action' || $k=='sbt') continue;
        $query .= $query=='' ? "$k=".urlencode($v) : "&$k=".urlencode($v);
    }
    
    $action = $edit ? '?action=edit&id='.$id : '?action=new&';
    
    if (!$xoopsSecurity->check()){
        redirectMsg('teams.php?action='.($edit ? 'edit&id='.$id : 'new').'&'.$query, __('Session token expired!','match'), 1);
        die();
    }
    
    if($name=='' || $category<=0){
        redirectMsg('teams.php?action='.($edit ? 'edit&id='.$id : 'new').'&'.$query, __('Please fill all required data!','match'), 1);
    }

    if ($edit){
        //Verificamos que el trabajo sea válido
        if ($id<=0){
            redirectMsg('./teams.php',__('Team ID not valid!','match'),1);
            die();
        }

        //Verificamos que el trabajo exista
        $team = new MCHTeam($id);
        if ($team->isNew()){
            redirectMsg('./teams.php',__('Specified team does not exists!','match'),1);
            die();
        }
        
        
    }else{
        $team = new MCHTeam();
    }
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    // Check if work exists already
    if ($edit){
        $sql = "SELECT COUNT(*) FROM ".$db->prefix("mch_teams")." WHERE name='$name' and category='$category' and id_team<>'$id'";
    } else {
        $sql = "SELECT COUNT(*) FROM ".$db->prefix("mch_teams")." WHERE name='$name' and category='$category'";
    }
    list($num)=$db->fetchRow($db->query($sql));
    if ($num>0){
        redirectMsg("teams.php".$action.$query, __('A team with same name already exists!','match'), 1);
        die();
    }
    
    //Genera $nameid Nombre identificador
    $found=false; 
    $i = 0;
    if ($name!=$team->getVar('name') || empty($nameid)){
        do{
            $nameid = TextCleaner::sweetstring($name).($found ? $i : '');
                $sql = "SELECT COUNT(*) FROM ".$db->prefix('mch_teams'). " WHERE nameid = '$nameid'";
                list ($num) =$db->fetchRow($db->queryF($sql));
                if ($num>0){
                    $found =true;
                    $i++;
                }else{
                    $found=false;
                }
        }while ($found==true);
    }

    $team->setVar('name', $name);
    $team->setVar('nameid', $nameid);
    $team->setVar('info', $info);
    $team->setVar('category', $category);
    $team->setVar('active', $active);
    $team->setVar('created', $created);
    
    //Logo
    include_once RMCPATH.'/class/uploader.php';
    $folder = XOOPS_UPLOAD_PATH.'/teams';
    if ($edit){
        $image = $team->getVar('logo');
        $filename = $team->getVar('logo');
    }
    else{
        $filename = '';
    }

    //Obtenemos el tamaño de la imagen
    $imgSize = $xoopsModuleConfig['logo_size'];

    $up = new RMFileUploader($folder, $xoopsModuleConfig['logo_file_size']*1024, array('jpg','png','gif'));

    if ($up->fetchMedia('logo')){
    
        if (!$up->upload()){
            redirectMsg('./teams.php'.$action.$query,$up->getErrors(), 1);
            die();
        }
                    
        if ($edit && $team->getVar('logo')!=''){
            @unlink(XOOPS_UPLOAD_PATH.'/teams/'.$team->getVar('logo'));
            
        }

        $filename = $up->getSavedFileName();
        $fullpath = $up->getSavedDestination();
        // Redimensionamos la imagen
        $redim = new RMImageResizer($fullpath, $fullpath);
        //Redimensionar
        $redim->resizeWidth($imgSize);

    }

    
    $team->setVar('logo', $filename);
    
    if (!$team->save()){
        redirectMsg('./teams.php'.$action.$query,__('Errors ocurred while trying to update database!','match').$team->errors(),1);
        die();
    }else{    
        redirectMsg('./teams.php?id='.$team->id(),__('Team saved successfully!','match'),0);
        die();

    }
    
}


/**
* Deleting a category
*/
function m_delete_teams(){
    global $xoopsModule, $xoopsSecurity;

    $ids = rmc_server_var($_POST, 'ids', array());
    
    //Verificamos que nos hayan proporcionado una categoría para eliminar
    if (empty($ids)){
        redirectMsg('./teams.php',__('No teams selected!','match'),1);
        die();
    }

    if (!$xoopsSecurity->check()){
        redirectMsg('./teams.php',__('Session token expired!','match'), 1);
        die();
    }
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();

    $errors = '';
    foreach ($ids as $k){
        //Verificamos si la categoría es válida
        if ($k<=0){
            $errors.=sprintf(__('Team id "%s" is not valid!','match'), $k);
            continue;
        }

        //Verificamos si la categoría existe
        $team = new MCHTeam($k);
        if ($team->isNew()){
            $errors.=sprintf(__('Team "%s" does not exists!','match'), $k);
            continue;
        }
            
        RMEvents::get()->run_event('match.delete.team', $cat);
        
        $file = MCH_UP_PATH.'/'.$team->getVar('logo');
            
        if (!$team->delete()){
            $errors.=sprintf(__('Team "%s" could not be deleted!','match'),$k);
        }else{
            if(is_file($file))
                unlink($file);
        }
        
    }
    
    if ($errors!=''){
        redirectMsg('./teams.php',__('Errors ocurred while trying to delete teams','match').'<br />'.$errors,1);
        die();
    }else{
        redirectMsg('./teams.php',__('Database updated successfully!','match'),0);
        die();
    }
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    
    case 'save':
        m_save_team();
        break;
    case 'saveedit':
        m_save_team(1);
        break;
    case 'new':
        m_teams_form();
        break;
    case 'edit':
        m_teams_form(1);
        break;
    case 'delete':
        m_delete_teams();
        break;
    default:
        m_show_teams();
        break;
    
}