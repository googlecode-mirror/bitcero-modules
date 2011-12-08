<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function xoops_module_pre_uninstall_galleries($mod){
    
    $dir = RMUtilities::module_config('galleries', 'storedir');
    
    if(is_dir($dir))
        RMUtilities::delete_directory($dir);
        
    return true;
    
}

function xoops_module_pre_install_galleries(&$mod){
    
    xoops_setActiveModules();
    
    $mods = xoops_getActiveModules();
    
    if(!in_array("rmcommon", $mods)){
        $mod->setErrors('MyGalleries could not be instaled if <a href="http://www.redmexico.com.mx/w/common-utilities/" target="_blank">Common Utilities</a> has not be installed previously!<br />Please install <a href="http://www.redmexico.com.mx/w/common-utilities/" target="_blank">Common Utilities</a>.');
        return false;
    }
    
}