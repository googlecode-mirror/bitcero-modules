<?php
// $Id: mchchampionship.class.php 642 2011-06-04 19:52:38Z i.bitcero $
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------


class MCHRoleItem extends RMObject
{
	public function __construct($id=null){
		
		$this->db =& Database::getInstance();
		$this->_dbtable = $this->db->prefix("mch_role");
		$this->setNew();
		$this->initVarsFromTable();
		if ($id==''){
			return;
		}
		
		if ($this->loadValues($id)) $this->unsetNew();
		return;
		
	}
	
	public function id(){
		return $this->getVar('id_role');
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
