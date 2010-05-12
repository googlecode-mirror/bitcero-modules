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
        
        $path = parse_url($url);
        $prevent = $plugin->get_config('prevent');
        
        if (in_array($path['path'], $prevent))
            return $plugins;
		
		if(!is_dir(XOOPS_CACHE_PATH.'/cachetizer/files'))
			mkdir(XOOPS_CACHE_PATH.'/cachetizer/files', 511);
		
		$file = XOOPS_CACHE_PATH.'/cachetizer/files/'.md5($url).'.html';
		if (file_exists($file)){
            
            $time = time() - filemtime($file);
            if($time>=$plugin->get_config('time')){
                unlink($file);
                die();
                return $plugins;
            }
                
			ob_end_clean();
			echo file_get_contents($file);
			die();
		}
		
    }
    
    /**
    * This event save the current page if is neccesary
    */
    public function eventRmcommonEndFlush($output){
		
        $plugin = RMFunctions::load_plugin('cachetizer');
        
        if(!$plugin->get_config('enabled'))
            return $output;
        
		$url = RMFunctions::current_url();
        
        $path = parse_url($url);
        $prevent = $plugin->get_config('prevent');
        
        if (in_array($path['path'], $prevent))
            return $output;
        
        
		$file = XOOPS_CACHE_PATH.'/cachetizer/files/'.md5($url);
        $data = array(
            'uri' => $url,
            'created' => time()
        );

		if (!file_exists($file)){
			file_put_contents($file.'.html', $output);
            file_put_contents($file.'.meta', json_encode($data));
		}
		
		return $output;
		
    }
    
}
