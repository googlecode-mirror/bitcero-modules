<?php
// $Id$
// --------------------------------------------------------
// Professional Works
// Manejo de Portafolio de Trabajos
// CopyRight © 2008. Red México
// Autor: BitC3R0
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

class PWCategory extends EXMObject
{
	public function __construct($id=null){
		
		$this->db =& Database::getInstance();
		$this->_dbtable = $this->db->prefix("pw_categos");
		$this->setNew();
		$this->initVarsFromTable();
		if ($id==''){
			return;
		}
		
		if (is_numeric($id)){
			if ($this->loadValues($id)) $this->unsetNew();
			return;
		} else {
			$this->primary = "nameid";
			if ($this->loadValues($id)) $this->unsetNew();
			$this->primary = "id_cat";
			return;
		}
		
	}
	
	public function id(){
		return $this->getVar('id_cat');
	}
	
	public function name(){
		return $this->getVar('name');
	}
	public function setName($value){
		return $this->setVar('name', $value);
	}
	
	public function nameId(){
		return $this->getVar('nameid');
	}
	public function setNameId($value){
		return $this->setVar('nameid', $value);
	}
	
	public function desc($format='s'){
		return $this->getVar('desc', $format);
	}
	public function setDesc($value){
		return $this->setVar('desc', $value);
	}
	
	public function active(){
		return $this->getVar('active');
	}
	public function setActive($value){
		return $this->setVar('active', $value);
	}
	
	public function order(){
		return $this->getVar('order');
	}
	public function setOrder($value){
		return $this->setVar('order', $value);
	}
	
	public function parent(){
		return $this->getVar('parent');
	}


	public function setParent($parent){
		return $this->setVar('parent',$parent);
	}

	public function created(){
		return $this->getVar('created');
	}

	public function setCreated($created){
		return $this->setVar('created',$created);
	}

	/**
	* @desc Obtiene el total de trabajos de la categoría
	**/
	public function works(){
	
		$sql = "SELECT COUNT(*) FROM ".$this->db->prefix('pw_works')." WHERE catego='".$this->id()."'";
		list($num) = $this->db->fetchRow($this->db->query($sql));

		return $num;

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
