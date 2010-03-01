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


class PWImage extends EXMObject
{

	public function __construct($id=null){
		
		$this->db =& Database::getInstance();
		$this->_dbtable = $this->db->prefix("pw_images");
		$this->setNew();
		$this->initVarsFromTable();
		
		 if ($id==null) return;
        
        	if (!$this->loadValues(intval($id))) return;
        
	        $this->unsetNew();
		
	}	

	public function id(){
		return $this->getVar('id_img');
	}

	/**
	* @desc Título
	**/
	public function title(){
		return $this->getVar('title');
	}

	public function setTitle($title){
		return $this->setVar('title',$title);
	}

	/**
	* @desc Descripcion
	**/
	public function desc(){
		return $this->getVar('desc');
	}

	public function setDesc($desc){
		return $this->setVar('desc',$desc);
	}

	/**
	* @desc Imagen
	**/
	public function image(){
		return $this->getVar('image');
	}

	public function setImage($image){
		return $this->setVar('image',$image);
	}

	/**
	* @desc Trabajo
	**/
	public function work(){
		return $this->getVar('work');
	}

	public function setWork($work){
		return $this->setVar('work',$work);
	}

	public function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		} else {
			return $this->updateTable();
		}
	}

	public function delete(){
		//Eliminamos las imágenes
		@unlink(XOOPS_UPLOAD_PATH.'/works/'.$this->image());
		@unlink(XOOPS_UPLOAD_PATH.'/works/ths/'.$this->image());


		return $this->deleteFromTable();
	}

} 
?>
