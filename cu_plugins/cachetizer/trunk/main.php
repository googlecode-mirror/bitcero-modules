<?php
// $Id$
// --------------------------------------------------------------
// Cachetizer plugin for Common Utilities
// Speed up your Xoops web site with cachetizer
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','cachetizer');

function cache_show_options(){
    RMFunctions::create_toolbar();
    xoops_cp_header();
    
    include RMTemplate::get()->get_template('cache_index.php', 'plugin', 'rmcommon', 'cachetizer');
    
    xoops_cp_footer();
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    default:
        cache_show_options();
        break;
}