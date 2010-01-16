<?php
// $Id$
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
        
        if (!$this->loadValues($id)) return false;
        
        $this->unsetNew();
        
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
