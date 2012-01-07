<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Módulo para la administración de descargas
// http://www.redmexico.com.mx
// http://www.exmsystem.net
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
// @copyright: 2007 - 2008 Red México

class DTCategory extends EXMObject
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
		return $this->deleteFromTable();
	}

}
?>
