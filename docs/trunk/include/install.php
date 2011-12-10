<?php
// $Id$
// --------------------------------------------------------------
// RapidDocs
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function xoops_module_pre_install_docs(&$mod){
    
    xoops_setActiveModules();
    
    $mods = xoops_getActiveModules();
    
    if(!in_array("rmcommon", $mods)){
        $mod->setErrors('RapidDocs could not be instaled if <a href="http://www.redmexico.com.mx/w/common-utilities/" target="_blank">Common Utilities</a> has not be installed previously!<br />Please install <a href="http://www.redmexico.com.mx/w/common-utilities/" target="_blank">Common Utilities</a>.');
        return false;
    }
    
    return true;
    
}
