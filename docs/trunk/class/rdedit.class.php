<?php
// $Id$
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RDEdit extends RMObject{

	function __construct($id=null, $sec = null){

		$this->db =& Database::getInstance();
		$this->_dbtable = $this->db->prefix("rd_edits");
		$this->setNew();
		$this->initVarsFromTable();

		if ($id==null && $sec == null) return;
		
		if ($id!=null){
			if ($this->loadValues($id)) $this->unsetNew();
			return;
		}
		
		if ($sec!=null){
			$this->primary = 'id_sec';
			if ($this->loadValues($sec)) $this->unsetNew();
			$this->primary = 'id_edit';
			return;
		}
	
	}


	public function id(){
		return $this->getVar('id_edit');
	}
	
	public function save(){
		
		if ($this->isNew()){
			// Comprobamos que no exista un registro para la misma sección
			$result = $this->db->query("SELECT id_edit FROM ".$this->_dbtable." WHERE id_sec='".$this->section()."'");
			if ($this->db->getRowsNum($result)>0){
				list($id) = $this->db->fetchRow($result);
				$this->setVar('id_edit', $id);
				return $this->updateTable();
			} else {
				return $this->saveToTable();
			}
		}
		else{
			return $this->updateTable();
		}		
	}

	public function delete(){
		
		return $this->deleteFromTable();
		
	}

}
?>