<?php
// $Id$
// --------------------------------------------------------------
// bXpress
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function xoops_module_pre_install_bxpress(&$mod){
    
    xoops_setActiveModules();
    
    $mods = xoops_getActiveModules();
    
    if(!in_array("rmcommon", $mods)){
        $mod->setErrors('bXpress could not be instaled if <a href="http://www.redmexico.com.mx/w/common-utilities/" target="_blank">Common Utilities</a> has not be installed previously!<br />Please install <a href="http://www.redmexico.com.mx/w/common-utilities/" target="_blank">Common Utilities</a>.');
        return false;
    }
    
}
