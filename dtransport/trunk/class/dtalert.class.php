<?php
// $Id: dtalert.class.php 39 2008-03-03 22:13:59Z ginis $
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


class DTAlert extends EXMObject
{

	function __construct($id=null, $field=0){
		
		$this->db =& Database::getInstance();
		$this->_dbtable = $this->db->prefix("dtrans_alerts");
		$this->setNew();
		$this->initVarsFromTable();
		
		$id = intval($id);
		
		if ($id==null) return;
		
		if ($field){
			if ($this->loadValues($id)){
				$this->unsetNew();
				return true;
			}
		}else{
			$this->primary = 'id_soft';
			if ($this->loadValues($id)){
				$this->unsetNew();
			}
		}
		
		$this->primary = 'id_alert';
		return;

	}
	

	public function id(){
		return $this->getVar('id_alert');
	}	


	/**
	* @desc Id del elemento
	**/	
	public function software(){
		return $this->getVar('id_soft');
	}

	public function setSoftware($software){
		return $this->setVar('id_soft',$software);
	}

	/**
	* @desc Límite de días de inactividad del elemento para
	* enviar la alerta
	**/	
	public function limit(){
		return $this->getVar('limit');
	}

	public function setLimit($limit){
		return $this->setVar('limit',$limit);
	}

	/**
	* @desc Indica si la alerta se envía por email(0) 
	* o por mensaje privado(1)
	**/
	public function mode(){
		return $this->getVar('mode');
	}

	public function setMode($mode){
		return $this->setVar('mode',$mode);
	}

	/**
	* @desc Fecha de la última descarga del archivo
	**/
	public function lastActivity(){
		return $this->getVar('lastactivity');
	}

	public function setLastActivity($last){
		return $this->setVar('lastactivity',$last);
	}

	/**
	* @desc Fecha en que se envió la última alerta
	**/
	public function alerted(){

		return $this->getVar('alerted');
	}

	public function setAlerted($alert){
		return $this->setVar('alerted',$alert);
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
