<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Module for advanced image galleries management
// Author: Eduardo Cortés
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function xoops_module_pre_install_mywords(&$mod){
    
    xoops_setActiveModules();
    
    $mods = xoops_getActiveModules();
    
    if(!in_array("rmcommon", $mods)){
        $mod->setErrors('MyWords could not be instaled if <a href="http://www.redmexico.com.mx/w/common-utilities/" target="_blank">Common Utilities</a> has not be installed previously!<br />Please install <a href="http://www.redmexico.com.mx/w/common-utilities/" target="_blank">Common Utilities</a>.');
        return false;
    }
    
    return true;
    
}

function xoops_module_update_mywords($mod, $pre){

    global $xoopsDB;

    return $xoopsDB->queryF("ALTER TABLE ".$xoopsDB->prefix("`mw_posts`")." CHANGE `image` `image` TEXT NOT NULL DEFAULT ''");

}