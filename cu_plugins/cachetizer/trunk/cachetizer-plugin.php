<?php
// $Id$
// --------------------------------------------------------------
// Cachetizer plugin for Common Utilities
// Speed up your Xoops web site with cachetizer
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class CachetizerCUPlugin extends RMIPlugin
{
    function __construct(){
        
        // Load language
        load_plugin_locale('cachetizer', '', 'rmcommon');
        
        $this->info = array(
            'name'            => __('Cachetizer', 'cachetizer'),
            'description'    => __('Plugin to speed up your Xoops Web site by reducing load times.','cachetizer'),
            'version'        => '1.0.0.0',
            'author'        => 'Eduardo Cortés',
            'email'            => 'i.bitcero@gmail.com',
            'web'            => 'http://redmexico.com.mx',
            'dir'            => 'cachetizer',
            'hasmain'       => true
        );
        
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

}
