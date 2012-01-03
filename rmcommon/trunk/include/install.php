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
    $sql = "CREATE TABLE IF NOT EXISTS `".$db->prefix("rmc_blocks")."` (
          `bid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
          `element` varchar(50) NOT NULL,
          `element_type` varchar(20) NOT NULL,
          `options` text NOT NULL,
          `name` varchar(150) NOT NULL DEFAULT '',
          `description` varchar(255) NOT NULL,
          `canvas` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `weight` smallint(5) unsigned NOT NULL DEFAULT '0',
          `visible` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `type` varchar(6) NOT NULL,
          `content_type` varchar(20) NOT NULL,
          `isactive` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `dirname` varchar(50) NOT NULL DEFAULT '',
          `file` varchar(150) NOT NULL,
          `show_func` varchar(50) NOT NULL DEFAULT '',
          `edit_func` varchar(50) NOT NULL DEFAULT '',
          `template` varchar(150) NOT NULL,
          `bcachetime` int(10) NOT NULL,
          PRIMARY KEY (`bid`),
          KEY `element` (`element`),
          KEY `visible` (`visible`)
        ) ENGINE=MyISAM;";

    $db->queryF($sql);
    
    $sql = "CREATE TABLE IF NOT EXISTS `".$db->prefix('rmc_blocks_positions')."` (
          `id_position` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(150) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
          `tag` varchar(150) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
          `active` tinyint(1) NOT NULL DEFAULT '1',
          PRIMARY KEY (`id_position`),
          UNIQUE KEY `tag` (`tag`)
        ) ENGINE=MyISAM;";
    
    $db->queryF($sql);
    
    return true;
}
