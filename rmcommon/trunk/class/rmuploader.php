<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* This class allows to upload multiple files at once
* using jQuery File Upload Plugin
*/
class RMUploader
{
    /**
    * All optionc for jquery plugin
    */
    private $options = array();
    /**
    * URL to send data
    */
    private $url = '';
    /**
    * Id for element
    */
    private $id = 'rmupload';
    /**
    * Constructor
    * 
    * @param string HTML ID for element
    * @param string URL to upload files. This can be overwrited for url option.
    * @param array Array of pair name => values with options for jquery plugin
    */
    function __construct($id, $url, $options=array()){
        
        $this->id = $id!=''?$id:'rmupload';
        $this->url = $url;
        
        $this->options = array(
            // AJAX options
            'url'                   => '',
            'type'                  => '\'POST\'',
            'dataType'              => '\'json\'',
            // General options
            'namespace'             => '\'fileupload\'',
            'dropZone'              => '$("#'.$this->id.'")',
            'fileInput'             => '',
            'replaceFileInput'      => 'true',
            'paramName'             => '',
            'singleFileUploads'     => 'true',
            'sequentialUploads'     => 'true',
            'forceIframeTransport'  => 'false',
            'multipart'             => 'true',
            'maxChunkSize'          => '',
            'uploadedBytes'         => '',
            'recalculateProgress'   => 'true',
            'formData'              => '',
            // Callback Options
            'add'                   => '',
            'send'                  => '',
            'done'                  => '',
            'fail'                  => '',
            'always'                => '',
            'progress'              => '',
            'progressall'           => '',
            'start'                 => '',
            'stop'                  => '',
            'change'                => '',
            'drop'                  => '',
            'dragover'              => '',
            // Options for UI version
            'autoUpload'            => 'false',
            'maxNumberOfFiles'      => '',
            'maxFileSize'           => '',
            'minFileSize'           => '1',
            'acceptFileTypes'       => '', //eg. \'/(\.|\/)(gif|jpe?g|png)$/i\'
            'previewFileTypes'      => '', //eg. \'/^image\/(gif|jpeg|png)$/\'
            'previewMaxWidth'       => '80',
            'previewMaxHeight'      => '80',
            'previewAsCanvas'       => 'true',
            'uploadTemplate'        => '$(\'#'.$this->id().'-template-upload\')',
            'downloadTemplate'      => '$(\'#'.$this->id().'-template-download\')',
            // Addtionsl callback options for UI
            'destroy'               => ''
        );
        
        foreach($options as $name => $value){
            if(!isset($this->options[$name])) continue;
            
            $this->options[$name] = $value;
            
        }
        
        // Add scripts
        RMTemplate::get()->add_local_script('jquery.tmpl.min.js', 'rmcommon', 'include');
        RMTemplate::get()->add_local_script('jquery.iframe-transport.js', 'rmcommon', 'include');
        RMTemplate::get()->add_local_script('jquery.fileupload.js', 'rmcommon', 'include');
        RMTemplate::get()->add_local_script('jquery.fileupload-ui.js', 'rmcommon', 'include');
        RMTemplate::get()->add_xoops_style('jquery.fileupload-ui.css', 'rmcommon');
        
    }
    
    public function id(){
        return $this->id;
    }
    
    /**
    * Get or sets current settings
    * 
    */
    public function settings(){
        
        $num = func_num_args();
        
        if($num>2){
            trigger_error(__('Incorrect number of arguments passes to RMUploader::settings()','shop'));
            return false;
        }
        
        if($num==0){
            return $this->options;
        }
        
        if($num==1){
            $name = func_get_arg(0);
            if(isset($this->options[$name]))
                return $this->options[$name];
            else
                return null;
        }
        
        if(isset($this->options[$name])){
            $this->options[$name] = func_get_arg(1);
            return true;
        }else{
            return false;
        }
        
    }
    
    /**
    * Generates uploader
    * @param string template path
    */
    public function render($tpl = ''){
        
        ob_start();
        include RMTemplate::get()->get_template('other/rm_uploader.php', 'module', 'rmcommon');
        RMTemplate::get()->add_head(ob_get_clean());
        
        include $tpl!='' ? $tpl : RMTemplate::get()->get_template('other/rm_upload_form.php', 'module', 'rmcommon');
        
    }
    
}