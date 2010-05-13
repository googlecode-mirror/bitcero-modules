<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// CopyRight  2007 - 2008. Red México
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


class AHReference extends EXMObject{

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
