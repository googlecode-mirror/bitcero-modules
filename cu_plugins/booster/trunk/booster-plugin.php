<?php
// $Id$
// --------------------------------------------------------------
// booster plugin for Common Utilities
// Speed up your Xoops web site with booster
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class BoosterCUPlugin extends RMIPlugin
{
    private $config;

    function __construct(){
        
        // Load language
        if(function_exists('load_plugin_locale')){
        	load_plugin_locale('booster', '', 'rmcommon');
        
	        $this->info = array(
	            'name'            => __('Xoops Booster', 'booster'),
	            'description'    => __('Plugin to speed up your Xoops Web site by reducing load times.','booster'),
	            'version'        => '1.0.0.0',
	            'author'        => 'Eduardo Cortés',
	            'email'            => 'i.bitcero@gmail.com',
	            'web'            => 'http://redmexico.com.mx',
	            'dir'            => 'booster',
	            'hasmain'       => true
	        );
		}
        
    }
    
    public function options(){
        
        return $options;
        
    }
    
    public function on_install(){
        return true;
    }
    
    public function on_uninstall(){
        return true;
    }
    
    public function on_update(){
        return true;
    }
    
    public function on_activate($q){
        return true;
    }
    
    /**
    * This function controls the plugin control panel
    */
    public function main(){
        
        include 'main.php';
        
    }
    
    private function write_file($config = array()){
		
		if (empty($config)){
			
			/*
            $mh = xoops_gethandler('module');
			$modules = $mh->getObjects();
			$prevent = array();
			foreach($modules as $mod){
				if($mod->getVar('hasadmin')){
					$prevent[] = 'modules/'.$mod->dirname().'/'.dirname($mod->getInfo('adminindex'));
				}
			}
            */
            
            $path = parse_url(XOOPS_URL);
            $path = $path['path'];
            
            $prevent = array(
                $path.'/user.php',
                $path.'/modules/rmcommon/post_comment.php'
            );
			
			$config = array(
				'enabled'		=> 1,
				'time'			=> 600,
				'prevent'		=> $prevent
			);
			
		}
		
		if(!is_dir(XOOPS_CACHE_PATH.'/booster'))
			mkdir(XOOPS_CACHE_PATH.'/booster/', 511);
		
		$file = XOOPS_CACHE_PATH.'/booster/config.cnf';
		file_put_contents($file, json_encode($config));
		
    }

    private function loadConfig(){
        if (!empty($config)) return;

        $file = XOOPS_CACHE_PATH.'/booster/config.cnf';
        if (!file_exists($file)){
			$this->write_file();
        }
        
        $this->config = json_decode(file_get_contents($file), true);
        
    }

    /**
    * Get a configuration item
    */
    public function get_config($name=''){
		
		$this->loadConfig();
		
		if($name=='')
			return $this->config;
		
		if(!isset($this->config[$name]))
			return false;
		
		return $this->config[$name];
		
    }
}
