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
    private $name = '';
    /**
    * Constructor
    * 
    * @param string Name of instance
    * @param string Target uploader url
    * @param array SWFUploader settings
    * @return RMFlashUploader
    */
    public function __construct($name, $url, $settings = array()){
        $this->settings = array(
            'upload_url' => $url,
            'flash_url' => RMCURL.'/include/swfupload.swf'            
        );
        
        foreach ($settings as $key => $value){
            $this->settings[$key] = $value;
        }
        
        RMTemplate::get()->add_script(RMCURL.'/include/js/swfupload.js');
        
    }
    
    public function add_setting($name, $value){
        $this->settings[$name] = $value;
    }
    
    public function get_setting($name){
        if (!isset($this->settings[$name])) return false;
        
        return $this->settings[$name];
    }
    
    public function get_js_settings(){
        $ret = "<script type='text/javascript'>\n
        \$(document).ready(function(){
            var settings = {\n";
            $count = count($this->settings);
            $c = 0;
            foreach ($this->settings as $key => $value){
                $c++;
                $ret .= "$key: \"$value\"".($c>=$count ? '' : ',')."\n";
            }
        $ret .= "};\n });\n</script>";
        
        return $ret;
    }
}
