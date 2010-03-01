<?php
// $Id$
// --------------------------------------------------------
// Professional Works
// Manejo de Portafolio de Trabajos
// CopyRight © 2008. Red México
// Autor: gina
// http://www.redmexico.com.mx
// http://www.exmsystem.org
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
// --------------------------------------------------------
// @copyright: 2008 Red México

/**
* @desc Clase para tipos de cliente
**/
class PWType extends EXMObject
{

	public function __construct($id=null){
		
		$this->db =& Database::getInstance();
		$this->_dbtable = $this->db->prefix("pw_types");
		$this->setNew();
		$this->initVarsFromTable();
		
		 if ($id==null) return;
        

		if (is_numeric($id)){
			
			if (!$this->loadValues($id)) return;
			$this->unsetNew();
		}else{
			$this->primary="type";
			if ($this->loadValues($id)) $this->unsetNew();
			$this->primary="id_type";
		}
		
	}	

	public function id(){
		return $this->getVar('id_type');
	}

	/**
	* @desc Tipo de cliente
	**/
	public function type(){
		return $this->getVar('type');
	}

	public function setType($type){
		return $this->setVar('type',$type);
	}

	/**
	* @desc Fecha de creación
	**/
	public function created(){
		return $this->getVar('created');
	}

	public function setCreated($created){
		return $this->setVar('created',$created);
	}


	public function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		} else {
			return $this->updateTable();
		}
	}

	public function delete(){
		return $this->deleteFromTable();
	}

}
?>
