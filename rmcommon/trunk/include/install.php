<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function xoops_module_pre_uninstall_rmcommon($mod){
    
    // Restore previous configurations
    $db = Database::getInstance();
    
    $db->queryF("UPDATE ".$db->prefix("config")." SET conf_value='default' WHERE conf_name='cpanel'");
    
    return true;
    
}

function xoops_module_install_rmcommon($mod){
    // Restore previous configurations
    $db = Database::getInstance();
    
    $db->queryF("UPDATE ".$db->prefix("config")." SET conf_value='redmexico' WHERE conf_name='cpanel'");
    
    return true;
}
