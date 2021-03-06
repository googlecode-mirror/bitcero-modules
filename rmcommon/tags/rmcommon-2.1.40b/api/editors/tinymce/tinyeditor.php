<?php
// $Id: tinyeditor.php 825 2011-12-09 00:06:11Z i.bitcero $
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* This files allow to exigent people to control every aspect of tinymce
* from exmsystem
*/
class TinyEditor
{
    public $configuration = array();
    
    function getInstance(){
        static $instance;
        if (!isset($instance)) {
            $instance = new TinyEditor();
        }
        return $instance;
    }
    
    // Configuración
    public function add_config($names,$values, $replace=false){
    	if (is_array($names) && is_array($values)){
	    	foreach ($names as $i => $name){
	            // Replace if needed
	            if ($replace){
	            	$this->configuration[$name] = $values[$i];
				} else {
		            // Not replace, verify...
	            	$this->configuration[$name] = isset($this->configuration[$name]) ? ",".$values[$i] : $values[$i];
				}
	    	}
	    } else {
	        if ($replace || !isset($this->configuration[$names]))
	        	$this->configuration[$names] = $values;
	        else
	        	$this->configuration[$names] .= isset($this->configuration[$names]) ? ",$values" : $values;
	    }
	
    }
    
    public function remove_config($name){
        
        if (empty($this->configuration)) return;
        
        unset($this->configuration[$name]);
        
    }
    
    public function get_js(){
        global $exmConfig;
        $rtn = '<script language="javascript" type="text/javascript" src="'.RMCURL.'/api/editors/tinymce/tiny_mce.js"></script>
                <script language="javascript" type="text/javascript">
                    tinyMCE.init({';
                    $configs = ''; $i = 0;
                    foreach ($this->configuration as $name => $value){
                        $i++;
                        $configs .= $name.' : "'.$value.'"'.($i==count($this->configuration) ? '' : ',')."\n"; 
                    }
                    $rtn .= $configs . '});
                </script>';
        
        return $rtn;
    }
}
