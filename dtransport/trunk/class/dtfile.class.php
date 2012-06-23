<?php
// $Id$
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

class DTFile extends EXMObject
{

	function __construct($id=null){

		$this->db =& XoopsDatabaseFactory::getDatabaseConnection();
		$this->_dbtable = $this->db->prefix("dtrans_files");
		$this->setNew();
		$this->initVarsFromTable();

		if ($id==null) return;
		
		if (is_numeric($id)){
			
			if (!$this->loadValues($id)) return;
			$this->unsetNew();
		}

	
	}


	public function id(){
		return $this->getVar('id_file');
	}	

	/**
	* @desc Id del elemento al que pertenece el archivo
	**/
	public function software(){
		return $this->getVar('id_soft');
	}

	public function setSoftware($software){
		return $this->setVar('id_soft',$software);
	}
	
	
	/**
	* @desc Archivo para descargar. Puede ser una URL
	**/
	public function file(){
		return $this->getVar('file');
	}

	public function setFile($file){
		return $this->setVar('file',$file);
	}
	
	/**
	* @desc Indica si se trata de un archivo remoto o un archivo local
	**/
	public function remote(){
		return $this->getVar('remote');
	}

	public function setRemote($remote){

		return $this->setVar('remote',$remote);
	}

	/**
	* @desc Número de descargas del archivo.
	* Debe ser igual al campo de hits en los elementos
	**/
	public function hits(){
		return $this->getVar('hits');
	}
	
	public function setHits($hits){
		return $this->setVar('hits',$hits);
	}
	public function addHit(){
		$sql = "UPDATE ".$this->db->prefix("dtrans_files")." SET hits=hits+1 WHERE id_file='".$this->id()."'";
		return $this->db->queryF($sql);
	}

	/**
	* @desc Id del grupo de archivos al que pertenece este archivo
	**/
	public function group(){
		return $this->getVar('group');	
	}

	public function setGroup($group){
		return $this->setVar('group',$group);
	}

	/**
	* @desc Archivo por defecto a descargar
	**/
	public function isDefault(){
		return $this->getVar('default');
	}

	public function setDefault($default){
		return $this->setVar('default',$default);
	}

	/**
	* @desc Tamaño del archivo
	**/
	public function size(){
		return $this->getVar('size');
	}

	public function setSize($size){
		return $this->setVar('size',$size);
	}
	
	public function date(){
		return $this->getVar('date');
	}
	public function setDate($value){
		return $this->setVar('date', $value);
	}
	
	public function mime(){
		return $this->getVar('mime');
	}
	public function setMime($value){
		return $this->setVar('mime', $value);
	}
	
	public function title(){
		$title = $this->getVar('title');
		return $title!='' ? $title : $this->getVar('file');
	}
	
	public function setTitle($title){
		return $this->setVar('title', $title);
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
		global $xoopsModuleConfig;

		@unlink($xoopsModuleConfig['directory_insecure']."/".$this->file());
		return $this->deleteFromTable();
	}


}
?>