<?php
// $Id$
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------


class MCHCategory extends RMObject
{
	public function __construct($id=null){
		
		$this->db =& XoopsDatabaseFactory::getDatabaseConnection();
		$this->_dbtable = $this->db->prefix("mch_categories");
		$this->setNew();
		$this->initVarsFromTable();
		if ($id==''){
			return;
		}
		
		if (is_numeric($id)){
			if ($this->loadValues($id)) $this->unsetNew();
			return;
		} else {
			$this->primary = "nameid";
			if ($this->loadValues($id)) $this->unsetNew();
			$this->primary = "id_cat";
			return;
		}
		
	}
	
	public function id(){
		return $this->getVar('id_cat');
	}
    
    /**
    * Get subcategories
    * 
    * @param bool Return objects or array
    */
    public function subcategories($objects = false){
        
        $sql = "SELECT * FROM ".$this->db->prefix("mch_categories")." WHERE parent=".$this->id()." ORDER BY name";
        $result = $db->query($sql);
        if($db->getRowsNum($result)<=0) return;
        
        $ret = array();
        $i = 0;
        while($db->fetchArray($result)){
            if($objects){
                $ret[$i] = new MCHCategory();
                $ret[$i]->assignVars($row);
                $i++;
            } else {
                $ret[] = $row;
            }
        }
        
        return $ret;
        
    }
	
	/**
	* Get the category link formated
	*/
	public function permalink(){
		global $xoopsModule, $xoopsModuleConfig;
		
		if (isset($xoopsModule) && $xoopsModule->dirname()=='match'){
			$mc =& $xoopsModuleConfig;
		} else {
			$mc = RMUtilities::module_config('match');
		}
		
		$link = XOOPS_URL.'/';
		if ($mc['urlmode']){
			$link .= trim($mc['htbase'], '/').'/?category='.$this->id();
		} else {
			$link .= 'modules/match/index.php?cat='.$this->id();
		}
		
		return $link;
		
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
