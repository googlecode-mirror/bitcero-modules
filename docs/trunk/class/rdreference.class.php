<?php
// $Id$
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RDReference extends RMObject{

	function __construct($id=null){

		$this->db =& Database::getInstance();
		$this->_dbtable = $this->db->prefix("rd_references");
		$this->setNew();
		$this->initVarsFromTable();

		if ($id==null) return;
		
		if (is_numeric($id)){
			
			if (!$this->loadValues($id)) return;
			$this->unsetNew();
		}	
	
	}


	public function id(){
		return $this->getVar('id_ref');
	}


	public function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		}else{
			return $this->updateTable();
		}
	}


	public function delete(){
		return $this->deleteFromTable();

	}

}
