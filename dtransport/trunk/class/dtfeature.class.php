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


class DTFeature extends EXMObject
{

	function __construct($id=null){

		$this->db =& XoopsDatabaseFactory::getDatabaseConnection();
		$this->_dbtable = $this->db->prefix("dtrans_features");
		$this->setNew();
		$this->initVarsFromTable();

		if ($id==null) return;
		
		if (is_numeric($id)){
			
			if (!$this->loadValues($id)) return;
			$this->unsetNew();
		}else{
			$this->primary="nameid";
			if ($this->loadValues($id)) $this->unsetNew();
			$this->primary="id_feat";
		}	

	}

	public function id(){
		return $this->getVar('id_feat');
	}

	/**
	* @desc Id del elemento a que pertenece la característica
	**/
	public function software(){
		return $this->getVar('id_soft');	
	}

	public function setSoftware($software){
		return $this->setVar('id_soft',$software);
	}

	public function title(){
		return $this->getVar('title');
	}

	public function setTitle($title){
		return $this->setVar('title',$title);
	}

	public function content(){
		return $this->getVar('content');
	}

	public function setContent($content){
		return $this->setVar('content',$content);
	}

	/**
	* @desc Fecha de Creación de la característica
	**/
	public function created(){
		return $this->getVar('created');
	}

	public function setCreated($value){
		return $this->setVar('created',$value);
	}

	/**
	* @desc Fecha de modificación/creación de la característica
	**/
	public function modified(){
		return $this->getVar('modified');
	}

	public function setModified($modified){
		return $this->setVar('modified',$modified);
	}

	/**
	* @desc Nombre corto de la caracteristica
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
