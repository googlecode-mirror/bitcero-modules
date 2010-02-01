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
	
	abstract public function get_info($name);
	
	public function info(){
		return $this->info;
	}
	
	
}
