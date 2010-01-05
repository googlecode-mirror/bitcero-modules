<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class MWTag extends RMObject
{
	public function __construct($id = null){
        $this->db =& Database::getInstance();
        $this->_dbtable = $this->db->prefix("mw_tags");
        $this->setNew();
        $this->initVarsFromTable();
        
        if ($id==null) return;
    
        if ($this->loadValues($id)){
            $this->unsetNew();
            return true;
        }
        
        $this->primary = 'shortname';
        
        if ($this->loadValues($id)){
            $this->unsetNew();
            $this->primary = 'id_tag';
            return true;
        }
        
        $this->primary = 'id_tag';
               
    }
    
    public function id(){
		return $this->getVar('id_tag');
    }
    
    /**
    * This function 
    * 
    */
    public function update_posts(){
		$sql = "SELECT COUNT(*) FROM ".$this->db->prefix("mw_tagspost")." WHERE tag='".$this->id()."'";
		list($num) = $this->db->fetchRow($this->db->query($sql));
		$this->setVar('posts', $num);
		$this->updateTable();
    }
    
    function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		} else {
			return $this->updateTable();
		}
    }
    
}
