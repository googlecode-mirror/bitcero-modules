<?php
// $Id$
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------


class MCHChampionship extends RMObject
{
	public function __construct($id=null){
		
		$this->db =& Database::getInstance();
		$this->_dbtable = $this->db->prefix("mch_champs");
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
			$this->primary = "id_champ";
			return;
		}
		
	}
	
	public function id(){
		return $this->getVar('id_champ');
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
			$link .= trim($mc['htbase'], '/').'/cs/'.$this->getVar('nameid').'/';
		} else {
			$link .= 'modules/match/index.php?p=cs&amp;id='.$this->getVar('nameid');
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
