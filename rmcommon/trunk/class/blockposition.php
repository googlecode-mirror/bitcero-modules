<?php
// $Id: blocks.php 825 2011-12-09 00:06:11Z i.bitcero $
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RMBlockPosition extends RMObject
{
    
    public function __construct($id=''){
        
        $this->db =& XoopsDatabaseFactory::getDatabaseConnection();
        $this->_dbtable = $this->db->prefix("rmc_blocks_positions");
        $this->setNew();
        $this->initVarsFromTable();
        
        if ($id=='') return;
        
        if ($this->loadValues($id)){
            $this->unsetNew();
            return;
        }
        
        $this->primary = 'tag';
        if($this->loadValues($id))
            $this->unsetNew();
            
        $this->primary = 'id_position';
        
    }
    
    public function save(){
        
        if($this->isNew())
            return $this->saveToTable();
        else
            return $this->updateTable();
        
    }
    
}
