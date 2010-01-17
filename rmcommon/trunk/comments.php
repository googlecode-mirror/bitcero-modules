<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include_once '../../include/cp_header.php';
require_once XOOPS_ROOT_PATH.'/modules/rmcommon/admin_loader.php';
define('RMCLOCATION','comments');

function show_comments(){
    
    $db = Database::getInstance();
    
    $sql = "SELECT COUNT(*) FROM ".$db->prefix("rmc_comments");
    /**
     * Paginacion de Resultados
     */
    $page = rmc_server_var($_GET, 'page', 1);
    $limit = 20;
    list($num) = $db->fetchRow($db->query($sql));
    
    $tpages = ceil($num / $limit);
    $page = $page > $tpages ? $tpages : $page;

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('comments.php?page={PAGE_NUM}');
    
    $sql = "SELECT * FROM ".$db->prefix("rmc_comments")." ORDER BY posted DESC LIMIT $start,$limit";
    $result = $db->query($sql);
    $comments = array();
    while($row = $db->fetchArray($result)){
        $com = new RMComment();
        $com->assignVars($row);
        $comments[] = $com;
    }
    
    xoops_cp_header();
    RMFunctions::create_toolbar();
    
    RMTemplate::get()->add_script('include/js/jquery.checkboxes.js');
    include RMTemplate::get()->get_template('rmc_comments.php','module','rmcommon');
    xoops_cp_footer();
    
}

$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    
    default:
        show_comments();
        break;
}