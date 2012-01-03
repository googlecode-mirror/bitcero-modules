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
* Class to handle images created from Image Manager
*/

class RMImage extends RMObject
{
	public function __construct($id=null){
		$this->db = XoopsDatabaseFactory::getDatabaseConnection();
        $this->_dbtable = $this->db->prefix("rmc_images");
        $this->setNew();
        $this->initVarsFromTable();
        if ($id==null){
            return;
        }
        
        if ($this->loadValues($id)){
            $this->unsetNew();
        }
	}
	
	public function id(){
		return $this->getVar('id_img');
	}
    
    public function save(){
        
        if ($this->isNew()){
            return $this->saveToTable();
        } else {
            return $this->updateTable();
        }
        
    }
    
    public function delete(){
		return $this->deleteFromTable();
    }
	
}
