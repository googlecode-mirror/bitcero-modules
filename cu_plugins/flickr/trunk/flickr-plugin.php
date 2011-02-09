<?php
// $Id$
// --------------------------------------------------------------
// MyFlickr Plugin for Common Utilities
// Plugin to show flickr photos wherever you want in XOOPS.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

require_once 'lib/class.flickr.php';

class FlickrCUPlugin extends RMIPlugin
{
    
    function __construct(){
        
        // Load language
        load_plugin_locale('flickr', '', 'rmcommon');
        
        $this->info = array(
            'name'           => __('MyFlickr', 'topdf'),
            'description'    => __('Plugin to show flickr photos wherever you want.','flickr'),
            'version'        => '1.0.0.0',
            'author'         => 'Eduardo Cortés',
            'email'          => 'i.bitcero@gmail.com',
            'web'            => 'http://redmexico.com.mx',
            'dir'            => 'flickr'
        );
        
    }
    
    public function options(){
        
        include 'options.php';
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
    
    public function get(){
        static $class;
        
        if(!isset($class)){
            $class = new flickr($this->settings('apikey'));
        }
        
        return $class;
    }
    
}