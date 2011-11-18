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
    $search = rmc_server_var($_POST,'search','');
    
    if(!$xoopsSecurity->check()){
        gs_send_error(__('You are not authorized to do this action.','galleries'));
    }
    
    $sql = "SELECT COUNT(*) FROM ".$db->prefix("gs_sets")." WHERE ";
    if($search!='') $sql .= "title LIKE '%$search%' AND (public=1"; else $sql .= 'public=1';
    if($xoopsUser) $sql .= " OR (public<>1 AND owner=".$xoopsUser->uid().")";
    $sql .= $search!='' ? ')' : '';
    
    list($num) = $db->fetchRow($db->query($sql));
    $limit = 14;
    $ptotal = ceil($num/$limit);
    $start = $page==1 ? 0 : $page*$limit-$limit;
    
    $nav = new RMPageNav($num, $limit, $page);
    $nav->set_template(RMTemplate::get()->get_template("other/gs_navpage.php", 'module', 'galleries'));
    
    $sql = str_replace("COUNT(*)","*",$sql);
    $sql .= " ORDER BY id_set DESC LIMIT $start,$limit";
    
    $result = $db->query($sql);
    $galleries = array();
    
    while($row = $db->fetchArray($result)){
        
        $galleries[] = array(
            'id' => $row['id_set'],
            'title' => $row['title'],
            'date' => formatTimestamp($row['date'], "c")
        );
        
    }
    
    include RMTemplate::get()->get_template('other/gs_ajax_galleries.php', 'module', 'galleries');
    die();

}elseif($action=='load_images'){
    
    // Load galleries list for inclusion
    $page = rmc_server_var($_POST,'page',1);
    $search = rmc_server_var($_POST,'search','');
    
    if(!$xoopsSecurity->check()){
        gs_send_error(__('You are not authorized to do this action.','galleries'));
    }
    
    $sql = "SELECT COUNT(*) FROM ".$db->prefix("gs_images")." WHERE ";
    if($search!='') $sql .= "title LIKE '%$search%' AND public=2"; else $sql .= 'public=2';
    
    list($num) = $db->fetchRow($db->query($sql));
    $limit = 15;
    $ptotal = ceil($num/$limit);
    $start = $page==1 ? 0 : $page*$limit-$limit;
    
    $nav = new RMPageNav($num, $limit, $page);
    $nav->set_template(RMTemplate::get()->get_template("other/gs_navpage.php", 'module', 'galleries'));
    
    $sql = str_replace("COUNT(*)","*",$sql);
    $sql .= " ORDER BY created DESC LIMIT $start,$limit";
    
    $result = $db->query($sql);
    
    include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsfunctions.class.php';
    include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsset.class.php';
    include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsuser.class.php';
    include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsimage.class.php';
    include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gstag.class.php';
    $images = GSFunctions::process_image_data($result);
    
    include RMTemplate::get()->get_template('other/gs_ajax_images.php', 'module', 'galleries');
    die();
    
}elseif($action=='load_structure'){
    
    include RMTemplate::get()->get_template('other/gs_for_editor.php','module','galleries');
    die();
    
}elseif($action=='load_included'){
    
    if(!$xoopsSecurity->checkReferer()) die();
    
    $set = rmc_server_var($_POST,'set',0);
    $page = rmc_server_var($_POST,'page',1);
    $limit = rmc_server_var($_POST,'limit',0);
    $full = true;
    
    if($set<=0 || $limit<=0) die();
    
    include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsfunctions.class.php';
    $data = GSFunctions::load_images($set,$limit,$page);
        
    if(empty($data)) die();
        
    $images = $data['images'];
   
    // Pagination
    $page = $data['current'];
    $limit = $data['limit'];
    $num = $data['total'];
    $set = $data['set'];
        
    $nav = new RMPageNav($num, $limit, $page);
    $nav->set_template(RMTemplate::get()->get_template("other/gs_nav_included.php","module","galleries"));
    $nav->target_url($set['id'].','.$limit);
    
  
    include RMTemplate::get()->get_template('other/gs_gals_inclusion.php', 'module', 'galleries');

    
    die();
    
}
