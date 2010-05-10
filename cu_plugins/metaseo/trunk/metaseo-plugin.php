<?php
// $Id$
// --------------------------------------------------------------
// MetaSEO plugin for Common Utilities
// Optimize searchs by adding meta description and keywords to you rtemplate
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class MetaseoCUPlugin extends RMIPlugin
{
	function __construct(){
		
		// Load language
		load_plugin_locale('metaseo', '', 'rmcommon');
		
		$this->info = array(
			'name'			=> __('Meta Optimizer', 'recaptcha'),
			'description'	=> __('Plugin to optimize search engines by adding meta description and keywords to your theme heads','recaptcha'),
			'version'		=> '1.0.0.0',
			'author'		=> 'Eduardo Cortés',
			'email'			=> 'i.bitcero@gmail.com',
			'web'			=> 'http://redmexico.com.mx',
			'dir'			=> 'metaseo'
		);
		
	}
	
	public function options(){
        
        require 'include/options.php';
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
	
}