<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class DTCategory extends RMObject
{
		
	function __construct($id=null){

		$this->db =& Database::getInstance();
		
		$this->_dbtable = $this->db->prefix("dtrans_categos");
				
		$this->setNew();
		$this->initVarsFromTable();
	
		if ($id==null) return;
		
		if (!$this->loadValues($id)) return;
		$this->unsetNew();
			
	}

	public function id(){
		return $this->getVar('id_cat');
	}	

	/**
	* @desc Nombre de la categoria
	**/	
	public function name(){
		return $this->getVar('name');
	}

	public function setName($name){
		return $this->setVar('name',$name);
	}
	
	/**
	* @desc Descripcion de la categoria
	**/	
	public function desc(){
		return $this->getVar('desc');
	}

	public function setDesc($desc){
		return $this->setVar('desc',$desc);
	}

	/**
	* @desc Categoría padre 
	**/
	public function parent(){
		return $this->getVar('parent');
	}
	public function setParent($parent){
		return $this->setVar('parent',$parent);
	}

	/**
	* @desc Categoría activa
	**/	
	public function active(){
		return $this->getVar('active');
	}

	public function setActive($active){
		return $this->setVar('active',$active);
	}

	/**
	* @desc Nombre corto
	**/
	public function nameId(){
		return $this->getVar('nameid');
	}

	public function setNameId($nameid){
		return $this->setVar('nameid',$nameid);
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
        
        $this->db->queryF("UPDATE ".$this->_dbtable." SET parent=".$this->parent()." WHERE parent=".$this->id());
        
		return $this->deleteFromTable();
	}

}
