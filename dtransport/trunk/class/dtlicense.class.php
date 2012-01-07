<?php
// $Id: dtlicense.class.php 13 2008-01-23 15:23:41Z BitC3R0 $
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


class DTLicense extends EXMObject
{

	function __construct($id=null){

		$this->db =& Database::getInstance();
		$this->_dbtable = $this->db->prefix("dtrans_licences");
		$this->setNew();
		$this->initVarsFromTable();

		if ($id==null) return;
		
		if (is_numeric($id)){
			
			if (!$this->loadValues($id)) return;
			$this->unsetNew();
		}

	
	}


	public function id(){
		return $this->getVar('id_lic');
	}

	public function name(){
		return $this->getVar('name');
	}

	public function setName($name){
		return $this->setVar('name',$name);
	}

	public function link(){
		return $this->getVar('link');
	}

	public function setLink($link){
		return $this->setVar('link',$link);
	}

	public function type(){
		return $this->getVar('type');
	}

	public function setType($type){
		return $this->setVar('type',$type);
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

		$sql="DELETE FROM ".$this->db->prefix('dtrans_licsoft')." WHERE id_lic=".$this->id();
		$result=$this->db->queryF($sql);

		if (!$result) return false;
			
		return $this->deleteFromTable();
	}


}
?>
