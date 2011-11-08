<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include '../../../mainfile.php';

// Deactivate the logger
error_reporting(0);
$xoopsLogger->activated = false;

function gs_send_error($msg){
    
    echo '<div class="gs_error">'.$msg.'</div>';
    die();
    
}

$action = rmc_server_var($_POST, 'action', '');

$db = Database::getInstance();

if($action=='load_galleries'){
    
    // Load galleries list for inclusion
    $page = rmc_server_var($_POST,'page',1);
    if(!$xoopsSecurity->check()){
        gs_send_error(__('You are not authorized to do this action.','galleries'));
    }
    
    $sql = "SELECT COUNT(*) FROM ".$db->prefix("gs_sets")." WHERE public=1";
    if($xoopsUser) $sql .= " OR (public<>1 AND owner=".$xoopsUser->uid().")";
    
    list($num) = $db->fetchRow($db->query($sql));
    $limit = 15;
    $ptotal = ceil($num/$limit);
    $start = $page==1 ? 0 : $page*$limit-$limit;
    
    $sql = str_replace("COUNT(*)","*",$sql);
    $sql .= " ORDER BY id_set DESC LIMIT $start,$limit";
    
    $result = $db->query($sql);
    $galleries = array();
    
    while($row = $db->fetchArray($result)){
        
        $galleries[] = array(
            'title' => $row['title'],
            'date' => formatTimestamp($row['date'], "c")
        );
        
    }
    
    include RMTemplate::get()->get_template('other/gs_ajax_galleries.php', 'module', 'galleries');
    die();
    
}
