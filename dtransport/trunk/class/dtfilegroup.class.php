<?php
// $Id: dtfilegroup.class.php 24 2008-02-07 15:45:24Z BitC3R0 $
// --------------------------------------------------------------
// D-Transport
// CopyRight  2007 - 2008. Red México
// Autor: gina
// http://www.redmexico.com.mx
// http://www.exmsystem.com
// --------------------------------------------
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License as
// published by the Free Software Foundation; either version 2 of
// the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public
// License along with this program; if not, write to the Free
// Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
// MA 02111-1307 USA
// --------------------------------------------------------------
// @copyright:  2007 - 2008. Red México
// @author: gina


class DTFileGroup extends EXMObject
{
	
	function __construct($id=null){

		$this->db =& Database::getInstance();
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
