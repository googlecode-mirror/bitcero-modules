<?php
// $Id: controller.php 15 2009-09-11 18:16:01Z i.bitcero $
// --------------------------------------------------------------
// I.Themes
// Module for manage themes by Red Mexico
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// License: GPL v2
// --------------------------------------------------------------

defined('XOOPS_ROOT_PATH') or die("");

/**
* Next are standar functions for all themes or templates.
* 
* Is possible add new functionallities to I.Themes by adding new plugins
* directly on "plugins" directory
* 
* The correct way for call functions from this controller (including plugins)
* from you theme is:
* 
* <{xthemes_process action="function_name" data="array_of_parameters"}>
*/

include_once XOOPS_ROOT_PATH.'/modules/xthemes/include/functions.php';
include_once XOOPS_ROOT_PATH.'/modules/xthemes/class/theme.php';

class XThemesController
{
	/**
	* Cache for configurations
	* @var mixed
	*/
	private $configs = array();
	/**
	* Cache for objects
	*/
	private $objects = array();
	
	/**
	* Singleton
	*/
	public function get(){
		static $instance;
		
		if (!isset($instance)){
			$instance = new XThemesController();
		}
		
		return $instance;
		
	}
	
	/**
	* Gets the theme configuration as an array
	*/
	public function get_theme_config($name=''){
		global $xoopsConfig;
		
		if ($name!=''){
			$theme = $name;
		} else {
			$theme = $xoopsConfig['theme_set'];
		}
		
		$theme = preg_replace('/\s+/', '', $theme);
		$theme = str_replace('-','',$theme);
		$var = $theme.'Config';
		
		if (isset($this->configs[$theme])){
			return $this->configs[$theme];
		}
		
		if (false === ($object = xt_is_valid($theme)))
			return;
		
		$this->configs[$theme] = xt_get_current_config($theme);
		return $this->configs[$theme];
	}
	
	/**
	* Get theme configuration and assign to a smarty var
	* 
	* @param object $smarty
	* @return none
	*/
	function get_config(&$smarty, $params){
		global $xoopsConfig;
		
		if (isset($params['theme'])){
			$theme = $params['theme'];
		} else {
			$theme = $xoopsConfig['theme_set'];
		}
		
		$var = preg_replace('/\s+/', '', $theme);
		$var = str_replace('-','',$var);
		$var .= 'Config';
		
		if (isset($this->configs[$theme])){
			$smarty->assign($var, $this->configs[$theme]);
			return;
		}
		
		if (false === ($object = xt_is_valid($theme)))
			return;
		
		$this->configs[$theme] = xt_get_current_config($theme);
		$smarty->assign($var, $this->configs[$theme]);
		
	}

	/**
	* Load and run a plugin
	*/
	function load_plugin(&$smarty, $params){
		
		$dir = strtolower($params['plugin']);
		$do = $params['do'];
		
		$pdir = ITPATH.'/plugins/'.$dir;
		
		if (!is_file($pdir.'/xthemes_plugin_'.$dir.'.php'))
			return;
        
        if (!xt_plugin_installed($dir))
            return;
		
		include_once $pdir.'/xthemes_plugin_'.$dir.'.php';
		$class = "xthemes".ucfirst($dir);
		if (!class_exists($class)) return;
		
		$plugin = new $class($smarty, array_slice($params, 1));
		return $plugin->execute();
		
	}

	/**
	* This function allows you to include a file
	*/
	public function get_file(&$smarty, $params){
		global $xoopsConfig;
		$file = $params['file'];

		if (is_file(XOOPS_THEME_PATH.'/'.$xoopsConfig['theme_set'].'/'.$file))
			include_once XOOPS_THEME_PATH.'/'.$xoopsConfig['theme_set'].'/'.$file;
		elseif (is_file($file))
			include_once $file;
		else
			return;
		
	}
	
	/**
	* Run third party function
	*/
	public function run_function(&$smarty, $params){
		
		global $xoopsConfig;
		$file = $params['file'];
	
		if (is_file(XOOPS_THEME_PATH.'/'.$xoopsConfig['theme_set'].'/'.$file))
			include_once XOOPS_THEME_PATH.'/'.$xoopsConfig['theme_set'].'/'.$file;
		elseif (is_file($file))
			include_once $file;
		else
			return;
		
		if (function_exists($params['function']))
			return $params['function']($smarty, $params);
		
	}
    
    /**
    * Run method from theme
    */
    public function run_method(&$smarty, $params){
        global $xoopsConfig;
        $theme = $xoopsConfig['theme_set'];
        $theme = preg_replace('/\s+/', '', $theme);
        $theme = str_replace('-','',$theme);
        $method = $params['method'];
        
        if (!isset($this->objects[$theme])){
			if (false === ($this->objects[$theme] = xt_is_valid($xoopsConfig['theme_set'])))
            	return;
        }
        if (!method_exists($this->objects[$theme], $method))
            return;
        
        return $this->objects[$theme]->$method($smarty, $params);
        
    }
    
    /**
    * Generate script tags for jQuery
    */
    public function get_jquery(&$smarty, $params){
		
		$ret = '<script type="text/javascript" src="'.RMCURL.'/include/js/jquery.min.js"></script>';
		if ($params['ui']){
			$ret .= '<script type="text/javascript" src="'.RMCURL.'/include/js/jquery-ui.min.js"></script>';
		}
		
		return $ret;
    }
	
}