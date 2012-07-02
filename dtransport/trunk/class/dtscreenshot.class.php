<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class DTScreenshot extends RMObject
{
	

	function __construct($id=null){

		$this->db =& XoopsDatabaseFactory::getDatabaseConnection();
		$this->_dbtable = $this->db->prefix("dtrans_screens");
		$this->setNew();
		$this->initVarsFromTable();

		if ($id==null) return;
		
		if (is_numeric($id)){
			
			if (!$this->loadValues($id)) return;
			$this->unsetNew();
		}

	
	}


	public function id(){
		return $this->getVar('id_screen');
	}

	public function title(){
		return $this->getVar('title');
	}

	public function setTitle($title){
		return $this->setVar('title',$title);
	}

	public function desc(){
		return $this->getVar('desc');
	}

	public function setDesc($desc){
		return $this->setVar('desc',$desc);
	}
	
	/**
	* @desc Archivo de imagen almacenado localmente
	**/
	public function image(){
		return $this->getVar('image');
	}

	public function setImage($image){
		return $this->setVar('image',$image);
	}

	/**
	* @desc Número de accesos a la imagen
	**/
	public function hits(){
		return $this->getVar('hits');
	}

	public function setHits($hits){
		return $this->setVar('hits',$hits);
	}
	
	/**
	* @desc Fecha de modificación/creacion de la imagen
	**/
	public function date(){
		return $this->getVar('date');
	}

	public function setDate($time){
		return $this->setVar('date',$time);
	}

	/**
	* @desc Id del elemento al que pertenece la imagen
	**/
	public function software(){
		return $this->getVar('id_soft');
	}

	public function setSoftware($software){
		return $this->setVar('id_soft',$software);
	}

	
	public function save(){
		if ($this->isNew()){
			$ret= $this->saveToTable();
			
			if (!$ret) return false;
			
			$sw=new DTSoftware($this->software());
			return $sw->incrementScreens();	
					
		}
		else{
			return $this->updateTable();
		}		

	}

	public function delete(){
			
		@unlink(XOOPS_UPLOAD_PATH.'/dtransport/'.$this->image());
		@unlink(XOOPS_UPLOAD_PATH.'/dtransport/ths/'.$this->image());
		
		$sw=new DTSoftware($this->software());
		$sw->decrementScreens();	

		return $this->deleteFromTable();
	}

}
