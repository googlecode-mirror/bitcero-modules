<?php
// $Id$
// --------------------------------------------------------------
// Cachetizer plugin for Common Utilities
// Speed up your Xoops web site with cachetizer
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class CachetizerPluginRmcommonPreload
{
    public function eventRmcommonModulesMenu($menu){
        if (isset($menu['rmcommon'])){
            $menu['rmcommon']['options'][] = array(
                'title' => __('Cachetizer', 'cachetizer'),
                'link'  => RMCURL.'/plugins.php?p=cachetizer'
            );
        }
        
        return $menu;
    }
    
    public function eventRmcommonCurrentModuleMenu($menu){
        global $xoopsModule;
        
        if($xoopsModule->getVar('dirname')!='rmcommon') return $menu;
        
        $option = array(
            'title'=>__('Cache','cachetizer'),
            'link' => 'plugins.php?p=cachetizer',
            'icon' => RMCURL.'/plugins/cachetizer/images/cache.png',
            'location' => 'cachetizer'
        );
        
        $menu[] = $option;
        
        return $menu;
        
    }
    
    public function eventRmcommonCreateToolbar(){
        
        RMTemplate::get()->add_tool(__('Cache','rmcommon'), 'plugins.php?p=cachetizer', RMCURL.'/plugins/cachetizer/images/cache.png', 'cachetizer');
        
    }
    
}
