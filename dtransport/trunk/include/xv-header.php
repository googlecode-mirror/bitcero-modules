<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
 * Es necesario verificar si existe Common Utilities o si ha sido instalado
 * para evitar problemas en el sistema
 */
$amod = xoops_getActiveModules();
if(!in_array("rmcommon",$amod)){
    $error = "<strong>WARNING:</strong> D-Transport requires %s to be installed!<br />Please install %s before trying to use D-Transport";
    $error = str_replace("%s", '<a href="http://www.redmexico.com.mx/w/common-utilities/" target="_blank">Common Utilities</a>', $error);
    xoops_error($error);
    $error = '%s is not installed! This might cause problems with functioning of D-Transport and entire system. To solve, install %s or uninstall D-Transport and then delete module folder.';
    $error = str_replace("%s", '<a href="http://www.redmexico.com.mx/w/common-utilities/" target="_blank">Common Utilities</a>', $error);
    trigger_error($error, E_USER_WARNING);
    echo "<br />";
}

if(function_exists("load_mod_locale")) load_mod_locale ('dtransport');

if (!function_exists("__")){
    function __($text, $d){
        return $text;
    }
}
