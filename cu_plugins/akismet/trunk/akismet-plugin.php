<?php
// $Id$
// --------------------------------------------------------------
// Akismet plugin for Common Utilities
// Integrate Akismet API in Common Utilities
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// see: http://akismet.com
// --------------------------------------------------------------

class AkismetCUPlugin extends RMIPlugin
{
	public function __construct(){
		// Load language
        load_plugin_locale('akismet', '', 'rmcommon');
        
        $this->info = array(
            'name'            => __('Akismet Plugin', 'avatars'),
            'description'    => __('Maintan SPAM aware from your comments and your posts','akismet'),
            'version'        => '1.0.0.0',
            'author'        => 'Eduardo Cortés',
            'email'            => 'i.bitcero@gmail.com',
            'web'            => 'http://redmexico.com.mx',
            'dir'            => 'akismet'
        );
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
    
    public function options(){
        
        require 'include/options.php';
        return $options;
        
    }
}