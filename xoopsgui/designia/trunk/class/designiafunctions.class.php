<?php
// $Id$
// --------------------------------------------------------------
// Designia v1.0
// Theme for Common Utilities 2
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class DesigniaFunctions
{
    /**
     * Get the menu for the current module
     */
    public function currentModuleMenu($m=''){
        
        global $xoopsModule, $xoopsUser;
        
        if(!is_a($xoopsModule, 'XoopsModule')){
            return false;
        } else {
            $mod = $xoopsModule;
        }
        // Check user
        if(!is_a($xoopsUser, 'XoopsUser')) return false;
        
        if(!$xoopsUser->isAdmin($mod->mid())) return false;
        
        $amenu = $mod->getAdminMenu();
        $amenu = RMEvents::get()->run_event('rmcommon.current.module.menu', $amenu);
        if ($amenu){
            foreach ($amenu as $menu){
                RMTemplate::get()->add_menu(
                    $menu['title'],
                    strpos($menu['link'], 'http://')!==FALSE && strpos($menu['link'], 'ftp://')!==FALSE ? $menu['link'] : XOOPS_URL.'/modules/'.$mod->getVar('dirname','n').'/'.$menu['link'],
                    isset($menu['icon']) ? $menu['icon'] : '', isset($menu['location']) ? $menu['location'] : '',
                    isset($menu['options']) ? $menu['options'] : null
                );
                //RMTemplate::get()->add_tool($menu['title'], $menu['link'], isset($menu['icon']) ? $menu['icon'] : '');
            }
        }
        
        if($mod->hasconfig()){
            RMTemplate::get()->add_menu(__('Settings','rmcommon'), XOOPS_URL.'/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod='.$mod->mid(), RMTHEMEURL.'/images/settings.png','');
        }
        
    }
    
    /**
     * Get the menu for a specified module
     */
    public function moduleMenu($m){
        
        global $xoopsModule, $xoopsUser;
        
        if(!is_a($xoopsModule, 'XoopsModule')){
            $mod = RMFunctions::load_module($m);
        } else {
            if($xoopsModule->dirname()==$m)
                $mod = $xoopsModule;
            else
                $mod = RMFunctions::load_module($m);
        }
        
        if(!is_a($mod, 'XoopsModule')) return false;
        
        // Check user
        if(!is_a($xoopsUser, 'XoopsUser')) return false;
        
        if(!$xoopsUser->isAdmin($mod->mid())) return false;
        
        $amenu = $mod->getAdminMenu();
        $amenu = RMEvents::get()->run_event($mod->dirname().'.module.menu', $amenu);
        if(empty($amenu)) return false;
        
        $return_menu = array();
        
        foreach ($amenu as $menu){
            $return_menu[] = array(
                'title' => $menu['title'],
                'link' => strpos($menu['link'], 'http://')!==FALSE && strpos($menu['link'], 'ftp://')!==FALSE ? $menu['link'] : XOOPS_URL.'/modules/'.$mod->getVar('dirname','n').'/'.$menu['link'],
                'icon' => isset($menu['icon']) ? (strpos($menu['icon'], 'http://')!==FALSE ? $menu['icon'] : XOOPS_URL.'/modules/'.$mod->dirname().'/'.$menu['icon']) : '',
                'location' => isset($menu['location']) ? $menu['location'] : '',
                'options' => isset($menu['options']) ? ($menu['options']) : null
            );
        }
        
        if($mod->hasconfig()){
            $return_menu[] = array(
                'title' => __('Options','rmcommon'),
                'link' => XOOPS_URL.'/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod='.$mod->mid(),
                'icon' => RMTHEMEURL.'/images/settings.png',''
            );
        }
        
        return $return_menu;
        
    }
    
    
    /**
     * Get the module icon
     */
    public function module_icon($module, $size = '16'){
        
        global $xoopsModule;
        
        if(!is_a($xoopsModule, 'XoopsModule')) return false;
        
        if($xoopsModule->dirname()!=$module){
            $mod = RMFunctions::load_module($module);
        } else {
            $mod = $xoopsModule;
        }
        
        $icon = $mod->getInfo('icon'.$size);
        $path = XOOPS_ROOT_PATH.'/modules/'.$mod->dirname().'/'.$icon;
        if(!is_file($path)){
            return DESIGNIA_URL.'/images/module'.$size.'.png';
        } else {
            return str_replace(XOOPS_ROOT_PATH, XOOPS_URL, $path);
        }
        
    }
}