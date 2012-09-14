<?php
// $Id$
// --------------------------------------------------------------
// AYAH plugin for Common Utilities
// Allows to integrate "Are You A Human" with Common Utilities
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class AyahCUPlugin extends RMIPlugin
{
    
    function __construct(){
        
        // Load language
        load_plugin_locale('ayah', '', 'rmcommon');
        
        $this->info = array(
            'name'            => __('AYAH Plugin', 'ayah'),
            'description'    => __('Plugin to insert a "Are You A Human" field on comments and other forms','ayah'),
            'version'        => '0.0.1.0',
            'author'        => 'Eduardo Cortés',
            'email'            => 'i.bitcero@gmail.com',
            'web'            => 'http://www.xoopsmexico.net',
            'dir'            => 'ayah'
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
    
    private function set_config(){
        
        $config = RMFunctions::get()->plugin_settings('ayah', true);
        
        if(!defined('AYAH_PUBLISHER_KEY'))
            define( 'AYAH_PUBLISHER_KEY', $config['publisher']);
        
        if(!defined('AYAH_SCORING_KEY'))
            define( 'AYAH_SCORING_KEY', $config['scoring']);
        
    }
    
    public function show(){
        $config = RMFunctions::get()->plugin_settings('ayah', true);
        $this->set_config();
        include_once(RMCPATH.'/plugins/ayah/include/ayah.php');
        $ayah = new AYAH();
        $ayah->debug_mode($config['debug']);
        $field = $ayah->getPublisherHTML();
        
        return $field;
    }
    
    public function check(){
        $config = RMFunctions::get()->plugin_settings('recaptcha', true);
        $this->set_config();
        include_once(RMCPATH.'/plugins/ayah/include/ayah.php');
        $ayah = new AYAH();
        $ayah->debug_mode($config['debug']);
        $resp = $ayah->scoreResult();

        return $resp;
    }
    
}