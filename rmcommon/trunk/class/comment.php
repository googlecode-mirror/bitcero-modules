<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RMComment extends RMObject
{
    /**
    * User owner
    */
    private $user = array();
    
    public function __construct($id=null){
        $this->db =& Database::getInstance();
        $this->_dbtable = $this->db->prefix("rmc_comments");
        $this->setNew();
        $this->initVarsFromTable();
        if ($id==null){
            return;
        }
        
        if (!$this->loadValues($id)) return false;
        
        $this->load_user();
        
        $this->unsetNew();
        
    }
    
    public function id(){
        return $this->getVar('id_com');
    }
    
    private function load_user(){
        
        $db = $this->db;
        
        $sql = "SELECT * FROM ".$db->prefix("rmc_comusers")." WHERE id_user=".$this->getVar('user');
        $result = $db->query($sql);
        if ($db->getRowsNum($resul)<=0) return;
        
        $row = $db->fetchArray($result);
        $this->user = $row;
        
    }
    
    /**
    * Get a data from user array
    * 
    * @param string $name Field name
    * @return mixed
    */
    public function userVar($name){
        if (!isset($this->user[$name])) return;
        
        return $this->user[$name];
    }
    
    
    
}
