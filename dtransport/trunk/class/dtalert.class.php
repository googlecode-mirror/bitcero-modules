<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------


class DTAlert extends RMObject
{

	function __construct($id=null, $field=0){
		
		$this->db =& XoopsDatabaseFactory::getDatabaseConnection();
		$this->_dbtable = $this->db->prefix("dtrans_alerts");
		$this->setNew();
		$this->initVarsFromTable();
		
		$id = intval($id);
		
		if ($id==null) return;
		
		if ($field){
			if ($this->loadValues($id)){
				$this->unsetNew();
				return true;
			}
		}else{
			$this->primary = 'id_soft';
			if ($this->loadValues($id)){
				$this->unsetNew();
			}
		}
		
		$this->primary = 'id_alert';
		return;

	}
	

	public function id(){
		return $this->getVar('id_alert');
	}	


	/**
	* @desc Id del elemento
	**/	
	public function software(){
		return $this->getVar('id_soft');
	}

	public function setSoftware($software){
		return $this->setVar('id_soft',$software);
	}

	/**
	* @desc Límite de días de inactividad del elemento para
	* enviar la alerta
	**/	
	public function limit(){
		return $this->getVar('limit');
	}

	public function setLimit($limit){
		return $this->setVar('limit',$limit);
	}

	/**
	* @desc Indica si la alerta se envía por email(0) 
	* o por mensaje privado(1)
	**/
	public function mode(){
		return $this->getVar('mode');
	}

	public function setMode($mode){
		return $this->setVar('mode',$mode);
	}

	/**
	* @desc Fecha de la última descarga del archivo
	**/
	public function lastActivity(){
		return $this->getVar('lastactivity');
	}

	public function setLastActivity($last){
		return $this->setVar('lastactivity',$last);
	}

	/**
	* @desc Fecha en que se envió la última alerta
	**/
	public function alerted(){

		return $this->getVar('alerted');
	}

	public function setAlerted($alert){
		return $this->setVar('alerted',$alert);
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
