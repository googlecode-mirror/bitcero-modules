<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RMFlashUploader
{
    private $settings = array();
    public $name = '';
    /**
    * Constructor
    * 
    * @param string Name of instance
    * @param string Target uploader url (e.g. uploader.php)
    * @param array SWFUploader settings
    * @return RMFlashUploader
    */
    public function __construct($name, $url, $settings = array()){
        // Generate settings for uploadify
        $this->settings = array(
            'uploader' => RMCURL.'/include/uploadify.swf',
            'script' => $url,
            'cancelImg' => RMCURL.'/images/cancel.png',
            'scriptData' => array(),
            'multi' => false,
            'fileExt' => '*.*',
            'fileDesc' => __('All Files','rmcommon'),
            'sizeLimit' => 524288,
            'queueSizeLimit' => 10,
            'buttonText' => __('Select Files','rmcommon'),
            'checkScript' => '',
            'fileDataName' => 'Filedata',
            'method' => 'POST',
            'scriptAccess' => 'sameDomain',
            'folder' => '',
            'queueID' => '',
            'auto' => false,
            'simUploadLimit' => 1,
            'buttonImg' => '',
            'hideButton' => false,
            'rollover' => false,
            'width' => 110,
            'height' => 30,
            'wmode' => 'opaque',
            'onInit' => '',
            'onSelect' => '',
            'onSelectOnce' => '',
            'onCancel' => '',
            'onClearQueue' => '',
            'onQueueFull' => '',
            'onError' => '',
            'onOpen' => '',
            'onProgress' => '',
            'onComplete' => '',
            'onAllComplete' => '',
            'onCheck' => ''
        );
        
        foreach ($settings as $key => $value){
            if (!isset($this->settings[$key])) continue;
            $this->settings[$key] = $value;
        }
        
        $this->name = $name;
        
    }
    
    public function add_setting($name, $value){
    	
        if (!isset($this->settings[$name])) return false;
        $this->settings[$name] = $value;
        return true;
        
    }
    
    public function get_setting($name){
        if (!isset($this->settings[$name])) return false;
        
        return $this->settings[$name];
    }
    
    /**
    * Add several settings items at once
    * 
    * @param array $settings
    */
    public function add_settings($settings){
        foreach ($settings as $key => $value){
            if (!isset($this->settings[$key])) continue;
            $this->settings[$key] = $value;
        }
    }
    
    public function settings(){
        return $this->settings;
    }
    
    public function render(){
        
        RMTemplate::get()->add_local_script('swfobject.js', 'rmcommon', 'include');
        RMTemplate::get()->add_local_script('jquery.uploadify.js', 'rmcommon', 'include');
        RMTemplate::get()->add_style('uploadify.css', 'rmcommon');
        
        ob_start();
        include RMTemplate::get()->get_template('uploadify.js.php', 'module', 'rmcommon');
        $script = ob_get_clean();
        return $script;
    }
}
