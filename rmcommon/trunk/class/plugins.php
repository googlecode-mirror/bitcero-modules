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
* Class to manage plugins objects
*/

class RMPlugin extends RMObject
{
	public function __construct($id=null){
		
		$this->db =& Database::getInstance();
        $this->_dbtable = $this->db->prefix("rmc_plugins");
        $this->setNew();
        $this->initVarsFromTable();
        
        if ($id==null){
            return;
        }
        
        if ($this->loadValues($id)){
			$this->unsetNew();
			return true;
        }
        
        $this->primary = 'dir';
        if ($this->loadValues($id)){
			$this->unsetNew();
        }
        
        $this->primary = 'id_plugin';
		
	}
	
	public function id(){
		return $this->getVar('id_plugin');
	}
	
}
