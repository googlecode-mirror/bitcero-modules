<?php
// $Id$
// --------------------------------------------------------------
// Contact
// A simple contact module for Xoops
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------


class CTMessage extends RMObject
{
    public function __construct($id=null){
        
        $this->db =& XoopsDatabaseFactory::getDatabaseConnection();
        $this->_dbtable = $this->db->prefix("contactme");
        $this->setNew();
        $this->initVarsFromTable();

        if ($id==null) return;
        
        if ($this->loadValues($id)) $this->unsetNew();
            
        return;
        
    }
    
    public function id(){
        return $this->getVar("id_msg");
    }
    
    public function save(){
        
        if($this->isNew()){
            return $this->saveToTable();
        } else {
            return $this->updateTable();
        }
        
    }
    
}
