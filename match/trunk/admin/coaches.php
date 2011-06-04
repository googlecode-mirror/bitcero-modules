<?php
// $Id$
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','coaches');
include 'header.php';

function m_show_roster(){
    
    global $xoopsModule, $xoopsSecurity;
    
    $db = Database::getInstance();
    
    $id = rmc_server_var($_REQUEST, 'id', 0);
    if($id>0){
        MCHFunctions::page_from_item($id, 'player');
    }
    
    $page = rmc_server_var($_REQUEST,'page', 1);
    $page = $page<=0 ? 1 : $page;
    $limit = 15;
    $team = rmc_server_var($_REQUEST, 'team', 0);
    $search = rmc_server_var($_REQUEST, 'search', '');
    $filters = false;
    
    //Barra de Navegación
    $sql = "SELECT COUNT(*) FROM ".$db->prefix('mch_coaches');
    
    if($team>0 || $search!=''){
        $sql .= " WHERE ";
        $filters = true;
    }
    
    $and = false;
    if ($team>0){
        $sql .= " team=$team";
        $and = true;
        $t = new MCHTeam($team);
        if(!$t->isNew()){
            $team_data = array(
                'name' => $t->getVar('name')
            );
        }
        unset($t);
    }
    
    if($search!=''){
        $sql .= $and?' AND ':'';
        $sql .= "(name LIKE '%$search%' OR lastname LIKE '%$search%' OR surname LIKE '%$search%')";
    }
    
    list($num)=$db->fetchRow($db->query($sql));

    $tpages = ceil($num/$limit);
    $page = $page > $tpages ? $tpages : $page; 

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('coaches.php?page={PAGE_NUM}&amp;team='.$team);
    
    $coaches = array();
    $sql = str_replace('COUNT(*)','*', $sql);
    $sql .= ' ORDER BY '.($team>0?'charge,lastname,surname,name':'lastname,surname,name');
    $result = $db->query($sql);
    
    $cache_team = array();
    $timef = new RMTimeFormatter('',"%M% %d%, %Y%");
    
    while ($row = $db->fetchArray($result)){
        $coach = new MCHCoach();
        $coach->assignVars($row);
        
        if(isset($cache_team[$coach->getVar('team')])){
            $t = $cache_team[$coach->getVar('team')];
        } else {
            $cache_team[$coach->getVar('team')] = new MCHTeam($coach->getVar('team'));
            $t = $cache_team[$coach->getVar('team')];
        }
        
        $coaches[] = array(
            'id'            => $coach->id(),
            'link'          => $coach->permalink(),
            'name'          => $coach->getVar('name'),
            'lastname'          => $coach->getVar('lastname'),
            'surname'          => $coach->getVar('surname'),
            'nameid'        => $coach->getVar('nameid'),
            'created'       => $timef->format($coach->getVar('created')),
            'charge'      => MCHFunctions::charge_name($coach->getVar('charge')),
            'team'  => array(
                'id' => $t->id(),
                'name' => $t->getVar('name'),
                'link' => $t->permalink()
            )
        );
    }
    
    // Categories
    $teams = MCHFunctions::all_teams(false);
    
    // Event
    $coaches = RMEvents::get()->run_event('match.list.coaches', $coaches);

    MCHFunctions::toolbar();
    xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; ".__('Coaches','match'));
    RMTemplate::get()->assign('xoops_pagetitle', __('Coaches','match'));
    RMTemplate::get()->add_style('admin.css', 'match');
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
    RMTemplate::get()->add_local_script('admin_match.js','match');
    RMTemplate::get()->add_head("<script type='text/javascript'>\nvar mch_message='".__('Do you really want to delete selected coaches?','match')."';\n
        var mch_select_message = '".__('You must select some coach before to execute this action!','match')."';</script>");
    xoops_cp_header();
    
    $match_extra_options = RMEvents::get()->run_event('match.more.options');
    
    include RMTemplate::get()->get_template("admin/mch_coaches.php", 'module', 'match');
    xoops_cp_footer();
    
}

/**
* Form to create a new player
* 
* @param int Create (0) or edit (1) a player
*/
function m_coaches_form($edit=0){

    global $xoopsModule, $xoopsModuleConfig;
    
    MCHFunctions::toolbar();
    RMTemplate::get()->assign('xoops_pagetitle',$edit?__('Edit Coach','match'):__('Add Coach','match'));
    xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; 
        <a href='coaches.php'>".__('Coaches','match').'</a> &raquo; '.($edit ? __('Edit Coach','match') : __('Add Coach','match')));
    xoops_cp_header();

    $id = rmc_server_var($_REQUEST, 'id', 0);
    

    if ($edit){
        //Verificamos si la categoría es válida
        if ($id<=0){
            redirectMsg('./coaches.php',__('Provide a coach ID!','match'),1);
            die();
        }

        //Verificamos si la categoría existe
        $coach = new MCHCoach($id);
        if ($coach->isNew()){
            redirectMsg('./coaches.php',__('Specified coach was not found!','match'),1);
            die();
        }
    }

    
    $form = new RMForm($edit ? __('Edit Coach','match') : __('Add Coach','match'),'frmNew','coaches.php');
    $form->setExtra('enctype="multipart/form-data"');

    $form->addElement(new RMFormText(__('Name(s)','match'), 'name', 50, 200, $edit ? $coach->getVar('name') : ''), true);
    $form->addElement(new RMFormText(__('Lastname','match'), 'lastname', 50, 200, $edit ? $coach->getVar('lastname') : ''), true);
    $form->addElement(new RMFormText(__('Surname','match'), 'surname', 50, 200, $edit ? $coach->getVar('surname') : ''), true);

    if ($edit) $form->addElement(new RMFormText(__('Short name','match'), 'nameid', 50, 200, $coach->getVar('nameid')), true);
    
    $form->addElement(new RMFormEditor(__('Biography','match'), 'bio', '100%','250px', $edit ? $coach->getVar('bio','e') : ''));
    
    $form->addElement(new MCHTeamsField(__('Team','match'), 'team', $edit ? $coach->getVar('team') : 0), true);
    
    $form->addElement(new RMFormDate(__('Registered','match'), 'created', $edit ? $coach->getVar('created') : time(), $xoopsModuleConfig['year_range']), 1);
    
    $charges = new RMFormSelect(__('Charge', 'match'), 'charge', 0, $edit ? array($coach->getVar('charge')) : 0);
    $charges->addOption(0, __('Select position...','match'));
    $charges->addOption(1, __('Manager','match'));
    $charges->addOption(2, __('Coach','match'));
    $charges->addOption(3, __('Assistant','match'));
    $form->addElement($charges);
    
    $form->addElement(new RMFormFile(__('Picture','match'), 'photo', 45));
    
    if($edit){
        $form->addElement(new RMFormLabel(__('Current picture','match'), '<img src="'.MCH_UP_URL.'/coaches/ths/'.$coach->getVar('photo').'" alt="'.$coach->getVar('name').'" />'));
    }
    
    $form->addElement(new RMFormHidden('action', $edit ? 'saveedit' : 'save'));
    if ($edit) $form->addElement(new RMFormHidden('id', $coach->id()));
    $ele = new RMFormButtonGroup();
    $ele->addButton('sbt', $edit ? __('Save Changes!','match') : __('Add Now!','match'), 'submit');
    $ele->addButton('cancel', _CANCEL, 'button', 'onclick="window.location=\'coaches.php\';"');
    $form->addElement($ele);
    
    $form = RMEvents::get()->run_event('match.form.coaches', $form);
    
    $form->display();
    
    RMTemplate::get()->add_style('admin.css', 'match');
    
    xoops_cp_footer();
    
}


function m_save_coach($edit){
    global $xoopsSecurity, $xoopsModuleConfig;
    
    $query = '';
    foreach ($_POST as $k => $v){
        $$k = $v;
        if ($k == 'XOOPS_TOKEN_REQUEST' || $k=='action' || $k=='sbt') continue;
        $query .= $query=='' ? "$k=".urlencode($v) : "&$k=".urlencode($v);
    }
    
    $action = $edit ? '?action=edit&id='.$id : '?action=new&';
    
    if (!$xoopsSecurity->check()){
        redirectMsg('coaches.php?action='.($edit ? 'edit&id='.$id : 'new').'&'.$query, __('Session token expired!','match'), 1);
        die();
    }
    
    if($name=='' || $team<=0 || $lastname=='' || $surname=='' || $charge<=0){
        redirectMsg('coaches.php?action='.($edit ? 'edit&id='.$id : 'new').'&'.$query, __('Please fill all required data!','match'), 1);
    }

    if ($edit){
        
        if ($id<=0){
            redirectMsg('./coaches.php',__('Coach ID not valid!','match'),1);
            die();
        }

        //Verificamos que el trabajo exista
        $coach = new MCHCoach($id);
        if ($coach->isNew()){
            redirectMsg('./coaches.php',__('Specified coach does not exists!','match'),1);
            die();
        }
        
        
    }else{
        $coach = new MCHCoach();
    }
    
    $db = Database::getInstance();
    // Check if work exists already
    if ($edit){
        $sql = "SELECT COUNT(*) FROM ".$db->prefix("mch_coaches")." WHERE name='$name' and team='$team' and id_coach<>'$id'";
    } else {
        $sql = "SELECT COUNT(*) FROM ".$db->prefix("mch_coaches")." WHERE name='$name' and team='$team'";
    }
    list($num)=$db->fetchRow($db->query($sql));
    if ($num>0){
        redirectMsg("coaches.php".$action.$query, __('A coach with same name already exists!','match'), 1);
        die();
    }
    
    //Genera $nameid Nombre identificador
    $found=false; 
    $i = 0;
    if ($name!=$coach->getVar('name') || empty($nameid)){
        do{
            $nameid = TextCleaner::sweetstring($lastname.' '.$surname.' '.$name).($found ? $i : '');
                $sql = "SELECT COUNT(*) FROM ".$db->prefix('mch_coaches'). " WHERE nameid = '$nameid'";
                list ($num) =$db->fetchRow($db->queryF($sql));
                if ($num>0){
                    $found =true;
                    $i++;
                }else{
                    $found=false;
                }
        }while ($found==true);
    }

    $coach->setVar('name', $name);
    $coach->setVar('lastname', $lastname);
    $coach->setVar('surname', $surname);
    $coach->setVar('nameid', $nameid);
    $coach->setVar('bio', $bio);
    $coach->setVar('team', $team);
    $coach->setVar('created', $created>0?$created:time());
    $coach->setVar('charge', $charge);
    
    //Logo
    include_once RMCPATH.'/class/uploader.php';
    $folder = XOOPS_UPLOAD_PATH.'/teams/coaches';
    if ($edit){
        $image = $coach->getVar('photo');
        $filename = $coach->getVar('photo');
    }
    else{
        $filename = '';
    }

    //Obtenemos el tamaño de la imagen
    $imgSize = $xoopsModuleConfig['photo_size'];
    $thSize = $xoopsModuleConfig['th_size'];

    $up = new RMFileUploader($folder, $xoopsModuleConfig['logo_file_size']*1024, array('jpg','png','gif'));

    if ($up->fetchMedia('photo')){
    
        if (!$up->upload()){
            redirectMsg('./coaches.php'.$action.$query,$up->getErrors(), 1);
            die();
        }
                    
        if ($edit && $coach->getVar('photo')!=''){
            @unlink(XOOPS_UPLOAD_PATH.'/teams/coaches/'.$coach->getVar('photo'));
            @unlink(XOOPS_UPLOAD_PATH.'/teams/coaches/ths/'.$coach->getVar('photo'));
        }

        $filename = $up->getSavedFileName();
        $fullpath = $up->getSavedDestination();
        // Redimensionamos la imagen
        $redim = new RMImageResizer($fullpath, $fullpath);
        //Redimensionar
        $redim->resizeWidth($imgSize);
        $redim->setTargetFile($folder.'/ths/'.$filename);
        $redim->resizeAndCrop($thSize, $thSize);

    }

    
    $coach->setVar('photo', $filename);
    
    if (!$coach->save()){
        redirectMsg('./coaches.php'.$action.$query,__('Errors ocurred while trying to update database!','match').$coach->errors(),1);
        die();
    }else{    
        redirectMsg('./coaches.php?id='.$coach->id(),__('Coach saved successfully!','match'),0);
        die();
    }
    
}

function m_delete_coaches(){
    
    global $xoopsModule, $xoopsSecurity;

    $ids = rmc_server_var($_POST, 'ids', array());
    
    //Verificamos que nos hayan proporcionado una categoría para eliminar
    if (empty($ids)){
        redirectMsg('./coaches.php',__('No coaches selected!','match'),1);
        die();
    }

    if (!$xoopsSecurity->check()){
        redirectMsg('./coaches.php',__('Session token expired!','match'), 1);
        die();
    }
    
    $db = Database::getInstance();

    $errors = '';
    foreach ($ids as $k){
        //Verificamos si la categoría es válida
        if ($k<=0){
            $errors.=sprintf(__('Coach id "%s" is not valid!','match'), $k);
            continue;
        }

        //Verificamos si la categoría existe
        $coach = new MCHCoach($k);
        if ($coach->isNew()){
            $errors.=sprintf(__('Coach "%s" does not exists!','match'), $k);
            continue;
        }
            
        RMEvents::get()->run_event('match.delete.coach', $cat);
            
        if (!$coach->delete()){
            $errors.=sprintf(__('Coach "%s" could not be deleted!','match'),$coach->getVar('name'));
        }
        
    }
    
    if ($errors!=''){
        redirectMsg('./coaches.php',__('Errors ocurred while trying to delete coaches','match').'<br />'.$errors,1);
        die();
    }else{
        redirectMsg('./coaches.php',__('Database updated successfully!','match'),0);
        die();
    }
    
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    
    case 'save':
        m_save_coach();
        break;
    case 'saveedit':
        m_save_coach(1);
        break;
    case 'new':
        m_coaches_form();
        break;
    case 'edit':
        m_coaches_form(1);
        break;
    case 'delete':
        m_delete_coaches();
        break;
    default:
        m_show_roster();
        break;
    
}