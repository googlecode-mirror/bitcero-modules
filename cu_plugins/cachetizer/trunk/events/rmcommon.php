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
    
    /**
    * This event init the cache engine
    */
    public function eventRmcommonPluginsLoaded($plugins){
		
		include_once XOOPS_ROOT_PATH.'/modules/rmcommon/plugins/cachetizer/cachetizer-plugin.php';
		$plugin = new CachetizerCUPlugin();
		
		if (!$plugin->get_config('enabled'))
			return $plugins;
		
		include_once XOOPS_ROOT_PATH.'/modules/rmcommon/class/functions.php';
		$url = RMFunctions::current_url();
		
		if(!is_dir(XOOPS_CACHE_PATH.'/cachetizer/files'))
			mkdir(XOOPS_CACHE_PATH.'/cachetizer/files', 511);
		
		$file = XOOPS_CACHE_PATH.'/cachetizer/files/'.md5($url);
		if (file_exists($file)){
			ob_end_clean();
			echo file_get_contents($file);
			die();
		}
		
    }
    
    /**
    * This event save the current page if is neccesary
    */
    public function eventRmcommonEndFlush($output){
		
		$url = RMFunctions::current_url();
		$file = XOOPS_CACHE_PATH.'/cachetizer/files/'.md5($url);

		if (!file_exists($file)){
			file_put_contents($file, $output);
		}
		
		return $output;
		
    }
    
}
