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
* Class for manage categories from images manager
*/
class RMImageCategory extends RMObject
{
    public function __construct($id=null){
        $this->db =& Database::getInstance();
        $this->_dbtable = $this->db->prefix("rmc_img_cats");
        $this->setNew();
        $this->initVarsFromTable();
        $this->setVarType('groups', XOBJ_DTYPE_ARRAY);
        $this->setVarType('sizes', XOBJ_DTYPE_ARRAY);
        if ($id==null){
            return;
        }
        
        if ($this->loadValues($id)){
            $this->unsetNew();
        }

    }
    
    public function id(){
		return $this->getVar('id_cat');
    }
    
    public function save(){
        if ($this->isNew()){
            return $this->saveToTable();
        } else {
            return $this->updateTable();
        }
    }
    
}
