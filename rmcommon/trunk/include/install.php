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
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    
    $db->queryF("UPDATE ".$db->prefix("config")." SET conf_value='default' WHERE conf_name='cpanel'");
    
    return true;
    
}

function xoops_module_uninstall_rmcommon($mod){
    
    xoops_setActiveModules();
    return true;
    
}

function xoops_module_install_rmcommon($mod){
    // Restore previous configurations
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    
    $db->queryF("UPDATE ".$db->prefix("config")." SET conf_value='redmexico' WHERE conf_name='cpanel'");
    
    // Temporary solution
    $contents = file_get_contents(XOOPS_VAR_PATH.'/configs/xoopsconfig.php');
    $write = "if(file_exists(XOOPS_ROOT_PATH.'/modules/rmcommon/loader.php')) include_once XOOPS_ROOT_PATH.'/modules/rmcommon/loader.php';";
    if(strpos($contents,$write)!==FALSE) return true;
    
    $pos = strpos($contents, '<?php');
    
    file_put_contents(XOOPS_VAR_PATH.'/configs/xoopsconfig.php', substr($contents, $pos, 5)."\n".$write."\n".substr($contents, $pos+5));
    xoops_setActiveModules();
    
    return true;
}

function xoops_module_update_rmcommon($mod, $prev){
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    
    // Add tables to database
    $sql = "ALTER TABLE `".$db->prefix("rmc_blocks")."` ADD `content` TEXT NOT NULL AFTER `content_type`";

    return $db->queryF($sql);
    
}
