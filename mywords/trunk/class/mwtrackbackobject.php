<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class MWTrackbackObject extends RMObject
{
	public function __construct($id){
		
		$this->db =& Database::getInstance();
		$this->_dbtable = $this->db->prefix("mw_trackbacks");
		$this->setNew();
		$this->initVarsFromTable();
		if ($id==''){
			return;
		}
		
		if (is_numeric($id)){
			if ($this->loadValues($id)){
				$this->unsetNew();
			}
			return;
		}
		
	}
	
	public function id(){
		return $this->getVar('id_t');
	}
	
	public function save(){
		return $this->saveToTable();
	}
	
	public function update(){
		return $this->updateTable();
	}
	
	public function delete(){
		return $this->deleteFromTable();
	}
	
}
