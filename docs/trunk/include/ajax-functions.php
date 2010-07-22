<?php
// $Id$
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include '../../../mainfile.php';

$mhandler = xoops_gethandler('module');
$xoopsModule = $mhandler->getByDirName('docs');

include '../header.php';

global $xoopsLogger;
$xoopsLogger->renderingEnabled = false;
error_reporting(0);
$xoopsLogger->activated = false;

/**
* Shows a list of existing resources in RapidDocs
*/
function resources_list(){
    global $xoopsUser, $xoopsModule;

    $db = Database::getInstance();
    //Navegador de páginas
    $sql = "SELECT COUNT(*) FROM ".$db->prefix('pa_resources');
    list($num)=$db->fetchRow($db->queryF($sql));
    
    $page = rmc_server_var($_REQUEST, 'page', 1);
    $limit = 20;

    $tpages = ceil($num/$limit);
    $page = $page > $tpages ? $tpages : $page; 

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('javascript:;" onclick="docsAjax.getSectionsList({PAGE_NUM});');

    //Fin navegador de páginas
    
    $sql="SELECT * FROM ".$db->prefix('pa_resources');
    if ($xoopsUser->isAdmin()){
        $sql .= " ORDER BY `created` DESC LIMIT $start,$limit";  
    } else {
        $sql .= " WHERE public=1 OR (public=0 AND owner=".$xoopsUser->uid().") ORDER BY `created` DESC LIMIT $start,$limit"; 
    }

    $result=$db->queryF($sql);
    $resources = array();
    
    while ($rows=$db->fetchArray($result)){
        $res = new RDResource();
        $res->assignVars($rows);
        
        $resources[] = array(
            'id'=>$res->id(),
            'title'=>$res->getVar('title')
        );
    }

    include RMTemplate::get()->get_template('ajax/rd_sections_list.php', 'module', 'docs');
    
}

/**
* Sends the note data in json format
*/
function send_note_foredit(){
    
    $id = rmc_server_var($_GET, 'id', 0);
    if($id<=0){
        echo json_encode(array('message'=>__('Note id not provided!','docs'),'error'=>1));
        die();
    }
    
    $ref = new RDReference($id);
    if ($ref->isNew()){
        echo json_encode(array('message'=>__('Specified note does not exists!','docs'),'error'=>1));
        die();
    }
    
    $ret = array(
        'id'=>$ref->id(),
        'title'=>$ref->getVar('title'),
        'res'=>$ref->getVar('id_res'),
        'text'=>$ref->getVar('text','e'),
        'error'=>0
    );
    
    echo json_encode($ret);
    die();
    
}


$action = rmc_server_var($_REQUEST, 'action', '');
switch($action){
    case 'resources-list':
        resources_list();
        break;
    case 'note-edit':
        send_note_foredit();
        break;
    default:
        break;
}