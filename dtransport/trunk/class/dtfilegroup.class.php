<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------


class DTFileGroup extends RMObject
{
	
	function __construct($id=null){

		$this->db =& XoopsDatabaseFactory::getDatabaseConnection();
		$this->_dbtable = $this->db->prefix("dtrans_groups");
		$this->setNew();
		$this->initVarsFromTable();

		if ($id==null) return;
		
		if (is_numeric($id)){
			
			if (!$this->loadValues($id)) return;
			$this->unsetNew();
		}	

	}
	

	public function id(){
		return $this->getVar('id_group');
	}

	/**
	* @desc Nombre del grupo
	**/
	public function name(){
		return $this->getVar('name');
	}

	public function setName($name){
		return $this->setVar('name',$name);
	}
	
	/**
	* @desc Id del elemento al que pertenece el grupo
	**/
	public function software(){
		return $this->getVar('id_soft');
	}

	public function setSoftware($software){
		return $this->setVar('id_soft',$software);
	}

	
	public function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		}
		else{
			return $this->updateTable();
		}		

	}
	
	/**
	* @desc Obtiene los archivos del grupo
	* @param bool True devuelve objetos {@link DTFile}
	* @return array
	*/
	public function files($obj = false){
		$sql = "SELECT * FROM ".$this->db->prefix("dtrans_files")." WHERE `group`='".$this->id()."'";
		$result = $this->db->query($sql);
		$files = array();
		while ($row = $this->db->fetchArray($result)){
			if ($obj){
				$file = new DTFile();
				$file->assignVars($row);
				$files[] = $file;
			} else {
				$files[] = $row;
			}
		}
		
		return $files;
	}

	public function delete(){
		
		$sql="UPDATE ".$this->db->prefix('dtrans_files')." SET `group`=0 WHERE `group`=".$this->id(); 
		$result=$this->db->queryF($sql);

		if (!$result) return false;		

		return $this->deleteFromTable();
	}


}
?>
