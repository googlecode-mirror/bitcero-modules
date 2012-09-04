<?php
// $Id$
// --------------------------------------------------------------
// Minifier Plugin for Common Utilities
// Minify all scripts and styles sheets added trough RMTemplate
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class MinifierCUPlugin extends RMIPlugin
{
    public function __construct(){
        
        // Load language
        load_plugin_locale('minifier', '', 'rmcommon');
        
        $this->info = array(
            'name'            => __('Minifier Plugin', 'minifier'),
            'description'    => __('This plugin allows to minify scripts and stylesheets automatically.','lightbox'),
            'version'        => '0.1.0.0',
            'author'        => 'Eduardo Cortés',
            'email'            => 'i.bitcero@gmail.com',
            'web'            => 'http://www.redmexico.com.mx',
            'dir'            => 'minifier'
        );
        
    }
    
    public function on_install(){
        
        if(!is_dir(XOOPS_CACHE_PATH.'/minifier'))
            mkdir(XOOPS_CACHE_PATH.'/minifier', 511);
        
        return true;
    }
    
    public function on_uninstall(){
        
        $rmu = RMUtilities::get();
        $rmu->delete_directory(XOOPS_CACHE_PATH.'/minifier');
        return true;
        
    }
    
    public function on_update(){
        return true;
    }
    
    public function on_activate($q){
        return true;
    }
    
    public function options(){
        
        include 'options.php';
        
        return $options;       
    }
}
