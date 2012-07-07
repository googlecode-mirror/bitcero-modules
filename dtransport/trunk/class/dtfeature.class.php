<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class DTFeature extends RMObject
{

	function __construct($id=null){

		$this->db =& XoopsDatabaseFactory::getDatabaseConnection();
		$this->_dbtable = $this->db->prefix("dtrans_features");
		$this->setNew();
		$this->initVarsFromTable();

		if ($id==null) return;
		
		if (is_numeric($id)){
			
			if (!$this->loadValues($id)) return;
			$this->unsetNew();
		}else{
			$this->primary="nameid";
			if ($this->loadValues($id)) $this->unsetNew();
			$this->primary="id_feat";
		}	

	}

	/**
	* @desc Id del elemento a que pertenece la característica
	**/
	public function software(){
		return $this->getVar('id_soft');	
	}

	public function setSoftware($software){
		return $this->setVar('id_soft',$software);
	}

	public function title(){
		return $this->getVar('title');
	}

	public function setTitle($title){
		return $this->setVar('title',$title);
	}

	public function content(){
		return $this->getVar('content');
	}

	public function setContent($content){
		return $this->setVar('content',$content);
	}

	/**
	* @desc Fecha de Creación de la característica
	**/
	public function created(){
		return $this->getVar('created');
	}

	public function setCreated($value){
		return $this->setVar('created',$value);
	}

	/**
	* @desc Fecha de modificación/creación de la característica
	**/
	public function modified(){
		return $this->getVar('modified');
	}

	public function setModified($modified){
		return $this->setVar('modified',$modified);
	}

	/**
	* @desc Nombre corto de la caracteristica
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
		return $this->deleteFromTable();
	}

}

