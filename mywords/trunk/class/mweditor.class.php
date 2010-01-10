<?php
// $Id: editor.class.php 99 2010-01-06 16:59:21Z BitC3R0 $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class MWEditor extends RMObject
{
    private $xuser;
    
	public function __construct($id = null){
		
		$this->db =& Database::getInstance();
        $this->_dbtable = $this->db->prefix("mw_editors");
        $this->setNew();
        $this->initVarsFromTable();
        $this->setVarType('privileges', XOBJ_DTYPE_ARRAY);
        
        $id = intval($id);
        
        if ($id==null || $id<=0) return;
        
        if (!$this->loadValues($id)) return;
        
        $this->unsetNew();
                
	}
    
    public function id(){
        return $this->getVar('id_editor');
    }
    
    public function posts(){
        
        $sql = "SELECT COUNT(*) FROM ".$this->db->prefix("mw_posts")." WHERE author=".$this->id();
        list($num) = $this->db->fetchRow($this->db->query($sql));
        return $num;
        
    }
    
    public function data($name){
        
        if (!$this->xuser){
            $this->xuser = new XoopsUser($this->getVar('uid'));
        }
        
        return $this->xuser->getVar($name);
        
    }
    
    public function permalink(){
		$mc = RMUtilities::get()->module_config('mywords');
		$rtn = MWFunctions::get_url();
		$rtn .= $mc['permalinks']==1 ? '?editor='.$this->id() : ($mc['permalinks']==2 ? "editor/".$this->getVar('shortname','n')."/" : "editor/".$this->id());
		return $rtn;
    }
    
    public function save(){
        if ($this->isNew()){
            return $this->saveToTable();
        } else {
            return $this->updateTable();
        }
    }
    
}