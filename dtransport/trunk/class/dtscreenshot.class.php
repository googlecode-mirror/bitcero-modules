<?php
// $Id: dtscreenshot.class.php 19 2008-01-24 23:10:54Z ginis $
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


class DTScreenshot extends EXMObject
{
	

	function __construct($id=null){

		$this->db =& XoopsDatabaseFactory::getDatabaseConnection();
		$this->_dbtable = $this->db->prefix("dtrans_screens");
		$this->setNew();
		$this->initVarsFromTable();

		if ($id==null) return;
		
		if (is_numeric($id)){
			
			if (!$this->loadValues($id)) return;
			$this->unsetNew();
		}

	
	}


	public function id(){
		return $this->getVar('id_screen');
	}

	public function title(){
		return $this->getVar('title');
	}

	public function setTitle($title){
		return $this->setVar('title',$title);
	}

	public function desc(){
		return $this->getVar('desc');
	}

	public function setDesc($desc){
		return $this->setVar('desc',$desc);
	}
	
	/**
	* @desc Archivo de imagen almacenado localmente
	**/
	public function image(){
		return $this->getVar('image');
	}

	public function setImage($image){
		return $this->setVar('image',$image);
	}

	/**
	* @desc Número de accesos a la imagen
	**/
	public function hits(){
		return $this->getVar('hits');
	}

	public function setHits($hits){
		return $this->setVar('hits',$hits);
	}
	
	/**
	* @desc Fecha de modificación/creacion de la imagen
	**/
	public function modified(){
		return $this->getVar('modified');
	}

	public function setModified($modified){
		return $this->setVar('modified',$modified);
	}

	/**
	* @desc Id del elemento al que pertenece la imagen
	**/
	public function software(){
		return $this->getVar('id_soft');
	}

	public function setSoftware($software){
		return $this->setVar('id_soft',$software);
	}

	
	public function save(){
		if ($this->isNew()){
			$ret= $this->saveToTable();
			
			if (!$ret) return false;
			
			$sw=new DTSoftware($this->software());
			return $sw->incrementScreens();	
					
		}
		else{
			return $this->updateTable();
		}		

	}

	public function delete(){
			
		@unlink(XOOPS_UPLOAD_PATH.'/dtransport/'.$this->image());
		@unlink(XOOPS_UPLOAD_PATH.'/dtransport/ths/'.$this->image());
		
		$sw=new DTSoftware($this->software());
		$sw->decrementScreens();	

		return $this->deleteFromTable();
	}

}
?>
