<?php
// $Id: commentuser.php 825 2011-12-09 00:06:11Z i.bitcero $
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RMCommentUser extends RMObject
{
    public function __construct($id=null){
        $this->db =& Database::getInstance();
        $this->_dbtable = $this->db->prefix("rmc_comusers");
        $this->setNew();
        $this->initVarsFromTable();
        if ($id==null){
            return;
        }
        
        if ($this->loadValues($id)){
			$this->unsetNew();
			return;
        }
        
        $this->primary = 'email';
        if ($this->loadValues($id)){
			$this->unsetNew();
			$this->primary = 'id_user';
			return;
        }
        
        $this->primary = 'id_user';
		return;
        
    }
    
    public function id(){
        return $this->getVar('id_user');
    }
    
    public function save(){
        if($this->isNew()){
            return $this->saveToTable();
        } else {
            return $this->updateTable();
        }
    }
    
}
