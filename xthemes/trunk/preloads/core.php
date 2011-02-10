<?php
// $Id$
// --------------------------------------------------------------
// xThemes for XOOPS
// Module for manage themes by Red Mexico
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// License: GPL v2
// --------------------------------------------------------------

class XthemesCorePreload extends XoopsPreloadItem
{
    
    public function eventCoreClassTheme_blocksRetrieveBlocks($options){
        global $xoopsConfig;
        
        $dir = XOOPS_THEME_PATH.'/'.$xoopsConfig['theme_set'];
        if(is_file($dir.'/config/theme.php')){
            $options[0]->theme->template->plugins_dir[] = XOOPS_ROOT_PATH.'/modules/xthemes/smarty';
            $options[0]->theme->template->plugins_dir[] = $dir.'/plugins';
        }
        
        load_theme_locale($xoopsConfig['theme_set']);
        
        include_once XOOPS_ROOT_PATH.'/modules/xthemes/controller.php';
        XThemesController::get()->get_config($options[0]->theme->template, array());
        
    }
    
    public function eventCoreHeaderStart(){
        global $xoopsConfig;
        
        include XOOPS_ROOT_PATH.'/modules/xthemes/include/functions.php';
        include XOOPS_ROOT_PATH.'/modules/xthemes/class/theme.php';
        include XOOPS_ROOT_PATH.'/modules/xthemes/controller.php';
        
        if(FALSE == ($object = xt_is_valid($xoopsConfig['theme_set']))) return;
        
        load_theme_locale($xoopsConfig['theme_set']);
        
        if($object->get_info("jquery"))
            RMTemplate::get()->add_jquery();
        
    }
    
}
