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
* Interface for Common Utilities plugins
*/

abstract class RMIPlugin
{
	protected $info = array();
	
    abstract public function on_install();
    abstract public function on_uninstall();
    abstract public function on_update();
    abstract public function on_activate($q);
    abstract public function options();
	
    function get_info($name){
        
        if (!isset($this->info[$name])) return '';
        
        return $this->info[$name];
        
    }
    
	public function info(){
		return $this->info;
	}
	
	
}
