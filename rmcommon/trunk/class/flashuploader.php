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
            'flash_url' => RMCURL.'/include/swfupload.swf',
            'file_size_limit' => "100 MB",
            'file_types' => "*.*",
            'file_types_description' => "All Files",
            'file_upload_limit' => 100,
            'file_queue_limit' => 0,
            'debug' => false,
            // Button settings
            // 'button_image_url' => "images/TestImageNoText_65x29.png",
            // 'button_width' =>  "65",
            // 'button_height' => "29",
                button_placeholder_id: "spanButtonPlaceHolder",
                button_text: '<span class="theFont">Hello</span>',
                button_text_style: ".theFont { font-size: 16; }",
                button_text_left_padding: 12,
                button_text_top_padding: 3,
                
                // The event handler functions are defined in handlers.js
                file_queued_handler : fileQueued,
                file_queue_error_handler : fileQueueError,
                file_dialog_complete_handler : fileDialogComplete,
                upload_start_handler : uploadStart,
                upload_progress_handler : uploadProgress,
                upload_error_handler : uploadError,
                upload_success_handler : uploadSuccess,
                upload_complete_handler : uploadComplete,
                queue_complete_handler : queueComplete    // Queue plugin event
          
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
        $ret .= "};\n
        swfu = new SWFUpload(settings); });\n</script>";
        
        return $ret;
    }
}
