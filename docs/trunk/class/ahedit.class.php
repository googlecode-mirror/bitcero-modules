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


class AHEdit extends EXMObject{

	function __construct($id=null, $sec = null){

		$this->db =& Database::getInstance();
		$this->_dbtable = $this->db->prefix("pa_edits");
		$this->setNew();
		$this->initVarsFromTable();

		if ($id==null && $sec == null) return;
		
		if ($id!=null){
			if ($this->loadValues($id)) $this->unsetNew();
			return;
		}
		
		if ($sec!=null){
			$this->primary = 'id_sec';
			if ($this->loadValues($sec)) $this->unsetNew();
			$this->primary = 'id_edit';
			return;
		}
	
	}


	public function id(){
		return $this->getVar('id_edit');
	}
	
	/**
	* Id de la Sección
	*/
	public function section(){
		return $this->getVar('id_sec');
	}
	public function setSection($id){
		return $this->setVar('id_sec', $id);
	}

	//Título de la sección
	public function title(){
		return $this->getVar('title');
	}

	public function setTitle($title){
		return $this->setVar('title',$title);
	}

	//Contenido de la sección
	public function content(){
		return $this->getVar('content');
	}

	public function setContent($value){
		return $this->setVar('content',$value);	
	}

	//Orden de la sección en la publicación
	public function order(){
		return $this->getVar('order');
	}

	public function setOrder($order){
		return $this->setVar('order',$order);
	}
	
	//Recurso al que pertenece la seccion
	public function resource(){
		return $this->getVar('id_res');
	}

	public function setResource($resource){
		return $this->setVar('id_res',$resource);
	}

	//Nombre idntificador	
	public function nameId(){
		return $this->getVar('nameid');
	}

	public function setNameId($name){
		return $this->setVar('nameid',$name);
	}

	//Secciń padre
	public function parent(){
		return $this->getVar('parent');
	}

	public function setParent($parent){
		return $this->setVar('parent',$parent);
	}
	
	// Usuario
	public function uid(){
		return $this->getVar('uid');
	}
	public function setUid($uid){
		return $this->setVar('uid', $uid);
	}
	
	public function uname(){
		return $this->getVar('uname');
	}
	public function setUname($uname){
		return $this->setVar('uname', $uname);
	}
	
	// Modificación y Creación
	public function created(){
		return $this->getVar('created');
	}
	public function setCreated($value){
		return $this->setVar('created', $value);
	}
	public function modified(){
		return $this->getVar('modified');
	}
	public function setModified($value){
		return $this->setVar('modified', $value);
	}
	
	public function save(){
		
		if ($this->isNew()){
			// Comprobamos que no exista un registro para la misma sección
			$result = $this->db->query("SELECT id_edit FROM ".$this->_dbtable." WHERE id_sec='".$this->section()."'");
			if ($this->db->getRowsNum($result)>0){
				list($id) = $this->db->fetchRow($result);
				$this->setVar('id_edit', $id);
				return $this->updateTable();
			} else {
				return $this->saveToTable();
			}
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