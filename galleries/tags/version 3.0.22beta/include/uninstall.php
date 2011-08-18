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
