<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class DTPlatform extends RMObject
{

	function __construct($id=null){

		$this->db =& Database::getInstance();
		$this->_dbtable = $this->db->prefix("dtrans_platforms");
		$this->setNew();
		$this->initVarsFromTable();

		if ($id==null) return;
		
		if (is_numeric($id)){
			
			if (!$this->loadValues($id)) return;
			$this->unsetNew();
		}

	
	}


	public function id(){
		return $this->getVar('id_platform');
	}

	public function name(){
		return $this->getVar('name');
	}

	public function setName($name){
		return $this->setVar('name',$name);
	}
	

	public function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		}
		else{
			return $this->updateTable();
		}

	}

	public function delete(){
			
		$sql="DELETE FROM ".$this->db->prefix('dtrans_platsoft')." WHERE id_platform=".$this->id();
		$result=$this->db->queryF($sql);

		if (!$result) return false;
			

		return $this->deleteFromTable();
	}


}
