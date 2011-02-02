<?php
// $Id$
// --------------------------------------------------------------
// X.Themes
// Module for manage themes by Red Mexico
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// License: GPL v2
// --------------------------------------------------------------

/**
* This function enable the capacity of translate themes for Xoops
*/
function smarty_function_locale($params, &$smarty){
    global $xoopsConfig;
    
    $theme = $xoopsConfig['theme_set'];
    
    return __($params['t'], $theme);
    
}