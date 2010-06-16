<?php
// $Id$
// --------------------------------------------------------------
// booster plugin for Common Utilities
// Speed up your Xoops web site with booster
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class BoosterPluginRmcommonPreload
{
    public function eventRmcommonModulesMenu($menu){
        if (isset($menu['rmcommon'])){
            $menu['rmcommon']['options'][] = array(
                'title' => 'Booster',
                'link'  => RMCURL.'/plugins.php?p=booster'
            );
        }
        
        return $menu;
    }
    
    public function eventRmcommonCurrentModuleMenu($menu){
        global $xoopsModule;
        
        if($xoopsModule->getVar('dirname')!='rmcommon') return $menu;
        
        $option = array(
            'title'=>'Booster',
            'link' => 'plugins.php?p=booster',
            'icon' => RMCURL.'/plugins/booster/images/cache.png',
            'location' => 'booster',
            'options' => array(
                array('title'=>__('Clean cache','booster'),'link'=>'plugins.php?p=booster&amp;action=clean'),
                array('title'=>__('View files','booster'),'link'=>'plugins.php?p=booster&amp;action=view')
            )
        );
        
        $menu[] = $option;
        
        return $menu;
        
    }
    
    public function eventRmcommonCreateToolbar(){
        
        RMTemplate::get()->add_tool(__('Booster','rmcommon'), 'plugins.php?p=booster', RMCURL.'/plugins/booster/images/cache.png', 'booster');
        
    }
    
    /**
    * This event init the cache engine
    */
    public function eventRmcommonPluginsLoaded($plugins){
		
		include_once XOOPS_ROOT_PATH.'/modules/rmcommon/plugins/booster/booster-plugin.php';
		$plugin = new boosterCUPlugin();
		
		if (!$plugin->get_config('enabled'))
			return $plugins;
        
		include_once XOOPS_ROOT_PATH.'/modules/rmcommon/class/functions.php';
		$url = RMFunctions::current_url();
        
        $path = parse_url($url);
        // Pages to exclude
        $prevent = $plugin->get_config('prevent');

        if (in_array($path['path'], $prevent))
            return $plugins;

		if(!is_dir(XOOPS_CACHE_PATH.'/booster/files'))
			mkdir(XOOPS_CACHE_PATH.'/booster/files', 511);
		
		$file = XOOPS_CACHE_PATH.'/booster/files/'.md5($url.$_COOKIE['xoops_user']);
		if (file_exists($file.'.html')){
			
            $time = time() - filemtime($file.'.html');
			
            if($time>=$plugin->get_config('time')){
                unlink($file.'.html');
                unlink($file.'.meta');
                return $plugins;
            }

			ob_end_clean();
			echo file_get_contents($file.'.html');
			$plugin->delete_expired();
            die();
		}

        return $plugins;
		
    }
    
    /**
    * This event save the current page if is neccesary
    */
    public function eventRmcommonEndFlush($output){
		global $xoopsUser;

        $plugin = RMFunctions::load_plugin('booster');
        
        if(!$plugin->get_config('enabled'))
            return $output;
        
        if (defined('BOOSTER_NOTSAVE'))
        	return $output;

		$url = RMFunctions::current_url();
        
        $path = parse_url($url);
        
        if ($plugin->is_excluded($url))
        	return $output;

		$file = XOOPS_CACHE_PATH.'/booster/files/'.md5($url.$_COOKIE['xoops_user']);
        $data = array(
            'uri' => $url,
            'created' => time()
        );
		
		$pos = strpos($output, '<div id="xo-logger-output">');
		if ($pos!==FALSE)
			file_put_contents($file.'.html', substr($output, 0, $pos));
		else
			file_put_contents($file.'.html', $output);
			
        file_put_contents($file.'.meta', json_encode($data));
		
		$plugin->delete_expired();
		return $output;
		
    }
    
    public function eventRmcommonCommentSaved($com, $ret){
        
        $file = XOOPS_CACHE_PATH.'/booster/files/'.md5($ret.$_COOKIE['xoops_user']);
        
        @unlink($file.'.html');
        @unlink($file.'.meta');
        
    }
    
    /**
    * This event must be triggered when you wish to update cache for a specific url
    * Specially util to use in your modules or another components
    */
    public function eventRmcommonUpdateCache($url){
        
        $file = XOOPS_CACHE_PATH.'/booster/files/'.md5($ret);
        
        @unlink($file.'.html');
        @unlink($file.'.meta');
        
    }
    
    public function eventRmcommonRedirectHeader($url, $time, $message, $addredir, $allowext){
		
		define('BOOSTER_NOTSAVE', 1);
		
    }
    
}
