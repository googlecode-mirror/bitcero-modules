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
            'paramName'             => 'files[]',
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

class RMUploadHandler
{
    private $options;
    
    function __construct($options=null) {
        $this->options = array(
            'upload_dir' => dirname(__FILE__).'/files/',
            'upload_url' => dirname($_SERVER['PHP_SELF']).'/files/',
            'param_name' => 'files',
            // The php.ini settings upload_max_filesize and post_max_size
            // take precedence over the following max_file_size setting:
            'max_file_size' => null,
            'min_file_size' => 1,
            'accept_file_types' => '/.+$/i',
            'max_number_of_files' => null,
            'discard_aborted_uploads' => true
        );
        if ($options) {
            $this->options = array_merge_recursive($this->options, $options);
        }
    }
    
    private function get_file_object($file_name) {
        $file_path = $this->options['upload_dir'].$file_name;
        if (is_file($file_path) && $file_name[0] !== '.') {
            $file = new stdClass();
            $file->name = $file_name;
            $file->size = filesize($file_path);
            $file->url = $this->options['upload_url'].rawurlencode($file->name);
            foreach($this->options['image_versions'] as $version => $options) {
                if (is_file($options['upload_dir'].$file_name)) {
                    $file->{$version.'_url'} = $options['upload_url']
                        .rawurlencode($file->name);
                }
            }

            return $file;
        }
        return null;
    }
    
    private function get_file_objects() {
        return array_values(array_filter(array_map(
            array($this, 'get_file_object'),
            scandir($this->options['upload_dir'])
        )));
    }

        
    private function has_error($uploaded_file, $file, $error) {
        if ($error) {
            return $error;
        }
        if (!preg_match($this->options['accept_file_types'], $file->name)) {
            return 'acceptFileTypes';
        }
        if ($uploaded_file && is_uploaded_file($uploaded_file)) {
            $file_size = filesize($uploaded_file);
        } else {
            $file_size = $_SERVER['CONTENT_LENGTH'];
        }
        if ($this->options['max_file_size'] && (
                $file_size > $this->options['max_file_size'] ||
                $file->size > $this->options['max_file_size'])
            ) {
            return 'maxFileSize';
        }
        if ($this->options['min_file_size'] &&
            $file_size < $this->options['min_file_size']) {
            return 'minFileSize';
        }
        if (is_int($this->options['max_number_of_files']) && (
                count($this->get_file_objects()) >= $this->options['max_number_of_files'])
            ) {
            return 'maxNumberOfFiles';
        }
        return $error;
    }
    
    private function handle_file_upload($uploaded_file, $name, $size, $type, $error) {
        $file = new stdClass();
        $file->name = basename(stripslashes($name));
        $file->size = intval($size);
        $file->type = $type;
        $error = $this->has_error($uploaded_file, $file, $error);
        if (!$error && $file->name) {
            if ($file->name[0] === '.') {
                $file->name = substr($file->name, 1);
            }
            $file_path = $this->options['upload_dir'].$file->name;
            $append_file = is_file($file_path) && $file->size > filesize($file_path);
            clearstatcache();
            if ($uploaded_file && is_uploaded_file($uploaded_file)) {
                // multipart/formdata uploads (POST method uploads)
                if ($append_file) {
                    file_put_contents(
                        $file_path,
                        fopen($uploaded_file, 'r'),
                        FILE_APPEND
                    );
                } else {
                    move_uploaded_file($uploaded_file, $file_path);
                }
            } else {
                // Non-multipart uploads (PUT method support)
                file_put_contents(
                    $file_path,
                    fopen('php://input', 'r'),
                    $append_file ? FILE_APPEND : 0
                );
            }
            $file_size = filesize($file_path);
            if ($file_size === $file->size) {
                $file->url = $this->options['upload_url'].rawurlencode($file->name);
            } else if ($this->options['discard_aborted_uploads']) {
                unlink($file_path);
                $file->error = 'abort';
            }
            $file->size = $file_size;
            $file->delete_type = 'DELETE';
        } else {
            $file->error = $error;
        }
        return $file;
    }
    
    public function get() {
        $file_name = isset($_REQUEST['file']) ?
            basename(stripslashes($_REQUEST['file'])) : null; 
        if ($file_name) {
            $info = $this->get_file_object($file_name);
        } else {
            $info = $this->get_file_objects();
        }
    
        return $info;
    }
    
    public function post() {
        $upload = isset($_FILES[$this->options['param_name']]) ?
            $_FILES[$this->options['param_name']] : array(
                'tmp_name' => null,
                'name' => null,
                'size' => null,
                'type' => null,
                'error' => null
            );
        $info = array();
        if (is_array($upload['tmp_name'])) {
            foreach ($upload['tmp_name'] as $index => $value) {
                $info[] = $this->handle_file_upload(
                    $upload['tmp_name'][$index],
                    isset($_SERVER['HTTP_X_FILE_NAME']) ?
                        $_SERVER['HTTP_X_FILE_NAME'] : $upload['name'][$index],
                    isset($_SERVER['HTTP_X_FILE_SIZE']) ?
                        $_SERVER['HTTP_X_FILE_SIZE'] : $upload['size'][$index],
                    isset($_SERVER['HTTP_X_FILE_TYPE']) ?
                        $_SERVER['HTTP_X_FILE_TYPE'] : $upload['type'][$index],
                    $upload['error'][$index]
                );
            }
        } else {
            $info[] = $this->handle_file_upload(
                $upload['tmp_name'],
                isset($_SERVER['HTTP_X_FILE_NAME']) ?
                    $_SERVER['HTTP_X_FILE_NAME'] : $upload['name'],
                isset($_SERVER['HTTP_X_FILE_SIZE']) ?
                    $_SERVER['HTTP_X_FILE_SIZE'] : $upload['size'],
                isset($_SERVER['HTTP_X_FILE_TYPE']) ?
                    $_SERVER['HTTP_X_FILE_TYPE'] : $upload['type'],
                $upload['error']
            );
        }
        
        return $info;
    }
    
}

