<?php
// $Id$
// --------------------------------------------------------------
// Rapid Docs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RDReference extends EXMObject{

	function __construct($id=null){

		$this->db =& Database::getInstance();
		$this->_dbtable = $this->db->prefix("pa_references");
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

	//Recurso al que pertenece la referencia
	public function resource(){
		return $this->getVar('id_res');
	}

	public function setResource($resource){
		return $this->setVar('id_res',$resource);
	}

	//Texto al que pertenece la referencia
	public function section(){
		return $this->getVar('id_sec');
	}

	public function setSection($text){
		return $this->setVar('id_sec',$text);
	}

	//Título de la referencia
	public function title(){
		return $this->getVar('title');
	}

	public function setTitle($title){
		return $this->setVar('title',$title);
	}

	//Texto de la referencia
	public function reference(){
		return $this->getVar('text');
	}

	public function setReference($reference){
		return $this->setVar('text',$reference);
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
?>
