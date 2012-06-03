<?php
// $Id$
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','roster');
include 'header.php';

function m_show_roster(){
    
    global $xoopsModule, $xoopsSecurity;
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    
    $id = rmc_server_var($_REQUEST, 'id', 0);
    if($id>0){
        MCHFunctions::page_from_item($id, 'player');
    }
    
    $page = rmc_server_var($_REQUEST,'page', 1);
    $page = $page<=0 ? 1 : $page;
    $limit = 15;
    $team = rmc_server_var($_REQUEST, 'team', 0);
    $age = rmc_server_var($_REQUEST, 'age', 0);
    $direction = rmc_server_var($_REQUEST, 'direction', 1);
    $search = rmc_server_var($_REQUEST, 'search', '');
    $filters = false;
    
    //Barra de Navegación
    $sql = "SELECT COUNT(*) FROM ".$db->prefix('mch_players');
    
    if($team>0 || $age>0 || $search!=''){
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
    
    if($age>0){
        $sql .= $and?' AND ':'';
        $sql .= $direction?'birth<='.(time()-($age*365*24*60*60)):'birth>='.(time()-($age*365*24*60*60));
        $and = true;
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
    $nav->target_url('roster.php?page={PAGE_NUM}&amp;team='.$team);
    
    $players = array();
    $sql = str_replace('COUNT(*)','*', $sql);
    $sql .= ' ORDER BY '.($team>0?'position,lastname,surname,name':'lastname,surname,name');
    $result = $db->query($sql);
    
    $cache_team = array();
    $timef = new RMTimeFormatter('',"%M% %d%, %Y%");
    
    while ($row = $db->fetchArray($result)){
        $player = new MCHPlayer();
        $player->assignVars($row);
        
        if(isset($cache_team[$player->getVar('team')])){
            $t = $cache_team[$player->getVar('team')];
        } else {
            $cache_team[$player->getVar('team')] = new MCHTeam($player->getVar('team'));
            $t = $cache_team[$player->getVar('team')];
        }
        
        $plage = (time()-$player->getVar('birth'))/(365*24*60*60);
        
        $players[] = array(
            'id'            => $player->id(),
            'link'          => $player->permalink(),
            'name'          => $player->getVar('name'),
            'lastname'          => $player->getVar('lastname'),
            'surname'          => $player->getVar('surname'),
            'nameid'        => $player->getVar('nameid'),
            'created'       => $timef->format($player->getVar('created')),
            'age'           => sprintf(__('%u years, %u months','match'), intval($plage), ($plage - intval($plage)) * 12),
            'birth'         => $timef->format($player->getVar('birth')),
            'position'      => MCHFunctions::position_name($player->getVar('position')),
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
    $players = RMEvents::get()->run_event('match.list.players', $players);

    MCHFunctions::toolbar();
    xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; ".__('Players','match'));
    RMTemplate::get()->assign('xoops_pagetitle', __('Players','match'));
    RMTemplate::get()->add_style('admin.css', 'match');
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
    RMTemplate::get()->add_local_script('admin_match.js','match');
    RMTemplate::get()->add_head("<script type='text/javascript'>\nvar mch_message='".__('Do you really want to delete selected players?','match')."';\n
        var mch_select_message = '".__('You must select some player before to execute this action!','match')."';</script>");
    xoops_cp_header();
    
    $match_extra_options = RMEvents::get()->run_event('match.more.options');
    
    include RMTemplate::get()->get_template("admin/mch_roster.php", 'module', 'match');
    xoops_cp_footer();
    
}

/**
* Form to create a new player
* 
* @param int Create (0) or edit (1) a player
*/
function m_players_form($edit=0){

    global $xoopsModule, $xoopsModuleConfig;
    
    MCHFunctions::toolbar();
    RMTemplate::get()->assign('xoops_pagetitle',$edit?__('Edit Player','match'):__('Add Player','match'));
    xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; 
        <a href='roster.php'>".__('Players','match').'</a> &raquo; '.($edit ? __('Edit Player','match') : __('Add Player','match')));
    xoops_cp_header();

    $id = rmc_server_var($_REQUEST, 'id', 0);
    

    if ($edit){
        //Verificamos si la categoría es válida
        if ($id<=0){
            redirectMsg('./roster.php',__('Provide a player ID!','match'),1);
            die();
        }

        //Verificamos si la categoría existe
        $player = new MCHPlayer($id);
        if ($player->isNew()){
            redirectMsg('./roster.php',__('Specified player was not found!','match'),1);
            die();
        }
    }

    
    $form = new RMForm($edit ? __('Edit Player','match') : __('Add Player','match'),'frmNew','roster.php');
    $form->setExtra('enctype="multipart/form-data"');

    $form->addElement(new RMFormText(__('Name(s)','match'), 'name', 50, 200, $edit ? $player->getVar('name') : ''), true);
    $form->addElement(new RMFormText(__('Lastname','match'), 'lastname', 50, 200, $edit ? $player->getVar('lastname') : ''), true);
    $form->addElement(new RMFormText(__('Surname','match'), 'surname', 50, 200, $edit ? $player->getVar('surname') : ''), true);

    if ($edit) $form->addElement(new RMFormText(__('Short name','match'), 'nameid', 50, 200, $player->getVar('nameid')), true);
    
    $form->addElement(new RMFormDate(__('Birthday','match'), 'birth', $edit ? $player->getVar('birth') : '', $xoopsModuleConfig['year_range']), 1);
    
    $form->addElement(new RMFormEditor(__('Biography','match'), 'bio', '100%','250px', $edit ? $player->getVar('bio','e') : ''));
    
    $form->addElement(new MCHTeamsField(__('Team','match'), 'team', $edit ? $player->getVar('team') : 0), true);
    
    $form->addElement(new RMFormDate(__('Registered','match'), 'created', $edit ? $player->getVar('created') : time(), $xoopsModuleConfig['year_range']), 1);
    
    $position = new RMFormSelect(__('Field position', 'match'), 'position', 0, $edit ? array($player->getVar('position')) : 0);
    $position->addOption(0, __('Select position...','match'));
    $position->addOption(1, __('Pitcher','match'));
    $position->addOption(2, __('Catcher','match'));
    $position->addOption(3, __('First base','match'));
    $position->addOption(4, __('Second base','match'));
    $position->addOption(5, __('Third base','match'));
    $position->addOption(6, __('Shortstop','match'));
    $position->addOption(7, __('Left field','match'));
    $position->addOption(8, __('Center field','match'));
    $position->addOption(9, __('Right field','match'));
    $form->addElement($position);
    
    $form->addElement(new RMFormFile(__('Picture','match'), 'photo', 45));
    
    if($edit){
        $form->addElement(new RMFormLabel(__('Current picture','match'), '<img src="'.MCH_UP_URL.'/players/ths/'.$player->getVar('photo').'" alt="'.$player->getVar('name').'" />'));
    }
    
    $form->addElement(new RMFormHidden('action', $edit ? 'saveedit' : 'save'));
    if ($edit) $form->addElement(new RMFormHidden('id', $player->id()));
    $ele = new RMFormButtonGroup();
    $ele->addButton('sbt', $edit ? __('Save Changes!','match') : __('Add Now!','match'), 'submit');
    $ele->addButton('cancel', _CANCEL, 'button', 'onclick="window.location=\'roster.php\';"');
    $form->addElement($ele);
    
    $form = RMEvents::get()->run_event('match.form.players', $form);
    
    $form->display();
    
    RMTemplate::get()->add_style('admin.css', 'match');
    
    xoops_cp_footer();
    
}


function m_save_player($edit){
    global $xoopsSecurity, $xoopsModuleConfig;
    
    $query = '';
    foreach ($_POST as $k => $v){
        $$k = $v;
        if ($k == 'XOOPS_TOKEN_REQUEST' || $k=='action' || $k=='sbt') continue;
        $query .= $query=='' ? "$k=".urlencode($v) : "&$k=".urlencode($v);
    }
    
    $action = $edit ? '?action=edit&id='.$id : '?action=new&';
    
    if (!$xoopsSecurity->check()){
        redirectMsg('roster.php?action='.($edit ? 'edit&id='.$id : 'new').'&'.$query, __('Session token expired!','match'), 1);
        die();
    }
    
    if($name=='' || $birth<=0 || $team<=0 || $lastname=='' || $surname==''){
        echo "$name<br />$birth<br />$team<br />$lastname<br />$surname";
        die();
        //redirectMsg('roster.php?action='.($edit ? 'edit&id='.$id : 'new').'&'.$query, __('Please fill all required data!','match'), 1);
    }

    if ($edit){
        
        if ($id<=0){
            redirectMsg('./roster.php',__('Player ID not valid!','match'),1);
            die();
        }

        //Verificamos que el trabajo exista
        $player = new MCHPlayer($id);
        if ($player->isNew()){
            redirectMsg('./roster.php',__('Specified player does not exists!','match'),1);
            die();
        }
        
        
    }else{
        $player = new MCHPlayer();
    }
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    // Check if work exists already
    if ($edit){
        $sql = "SELECT COUNT(*) FROM ".$db->prefix("mch_players")." WHERE name='$name' and team='$team' and id_player<>'$id'";
    } else {
        $sql = "SELECT COUNT(*) FROM ".$db->prefix("mch_players")." WHERE name='$name' and team='$team'";
    }
    list($num)=$db->fetchRow($db->query($sql));
    if ($num>0){
        redirectMsg("roster.php".$action.$query, __('A player with same name already exists!','match'), 1);
        die();
    }
    
    //Genera $nameid Nombre identificador
    $found=false; 
    $i = 0;
    if ($name!=$player->getVar('name') || empty($nameid)){
        do{
            $nameid = TextCleaner::sweetstring($lastname.' '.$surname.' '.$name).($found ? $i : '');
                $sql = "SELECT COUNT(*) FROM ".$db->prefix('mch_players'). " WHERE nameid = '$nameid'";
                list ($num) =$db->fetchRow($db->queryF($sql));
                if ($num>0){
                    $found =true;
                    $i++;
                }else{
                    $found=false;
                }
        }while ($found==true);
    }

    $player->setVar('name', $name);
    $player->setVar('lastname', $lastname);
    $player->setVar('surname', $surname);
    $player->setVar('nameid', $nameid);
    $player->setVar('bio', $bio);
    $player->setVar('team', $team);
    $player->setVar('created', $created>0?$created:time());
    $player->setVar('position', $position);
    $player->setVar('birth', $birth);
    
    //Logo
    include_once RMCPATH.'/class/uploader.php';
    $folder = XOOPS_UPLOAD_PATH.'/teams/players';
    if ($edit){
        $image = $player->getVar('photo');
        $filename = $player->getVar('photo');
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
            redirectMsg('./roster.php'.$action.$query,$up->getErrors(), 1);
            die();
        }
                    
        if ($edit && $player->getVar('photo')!=''){
            @unlink(XOOPS_UPLOAD_PATH.'/teams/players/'.$player->getVar('photo'));
            @unlink(XOOPS_UPLOAD_PATH.'/teams/players/ths/'.$player->getVar('photo'));
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

    
    $player->setVar('photo', $filename);
    
    if (!$player->save()){
        redirectMsg('./roster.php'.$action.$query,__('Errors ocurred while trying to update database!','match').$player->errors(),1);
        die();
    }else{    
        redirectMsg('./roster.php?id='.$player->id(),__('Player saved successfully!','match'),0);
        die();
    }
    
}

function m_delete_players(){
    
    global $xoopsModule, $xoopsSecurity;

    $ids = rmc_server_var($_POST, 'ids', array());
    
    //Verificamos que nos hayan proporcionado una categoría para eliminar
    if (empty($ids)){
        redirectMsg('./roster.php',__('No players selected!','match'),1);
        die();
    }

    if (!$xoopsSecurity->check()){
        redirectMsg('./roster.php',__('Session token expired!','match'), 1);
        die();
    }
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();

    $errors = '';
    foreach ($ids as $k){
        //Verificamos si la categoría es válida
        if ($k<=0){
            $errors.=sprintf(__('Player id "%s" is not valid!','match'), $k);
            continue;
        }

        //Verificamos si la categoría existe
        $player = new MCHPlayer($k);
        if ($player->isNew()){
            $errors.=sprintf(__('Player "%s" does not exists!','match'), $k);
            continue;
        }
            
        RMEvents::get()->run_event('match.delete.player', $cat);
            
        if (!$player->delete()){
            $errors.=sprintf(__('Player "%s" could not be deleted!','match'),$player->getVar('name'));
        }
        
    }
    
    if ($errors!=''){
        redirectMsg('./roster.php',__('Errors ocurred while trying to delete players','match').'<br />'.$errors,1);
        die();
    }else{
        redirectMsg('./roster.php',__('Database updated successfully!','match'),0);
        die();
    }
    
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    
    case 'save':
        m_save_player();
        break;
    case 'saveedit':
        m_save_player(1);
        break;
    case 'new':
        m_players_form();
        break;
    case 'edit':
        m_players_form(1);
        break;
    case 'delete':
        m_delete_players();
        break;
    default:
        m_show_roster();
        break;
    
}