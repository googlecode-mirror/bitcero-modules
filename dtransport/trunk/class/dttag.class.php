<?php
// $Id: dttag.class.php 28 2008-02-14 23:56:22Z ginis $
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



class DTTag extends EXMObject
{

	
	function __construct($id=null){

		$this->db =& XoopsDatabaseFactory::getDatabaseConnection();
		$this->_dbtable = $this->db->prefix("dtrans_tags");
		$this->setNew();
		$this->initVarsFromTable();

		if ($id==null) return;
		
		if (is_numeric($id)){
			
			if (!$this->loadValues($id)) return;
			$this->unsetNew();
		}else{
			$this->primary="tag";
			if ($this->loadValues($id)) $this->unsetNew();
			$this->primary="id_tag";
		}
	}


	public function id(){
		return $this->getVar('id_tag');
	}	

	public function tag(){
		return $this->getVar('tag');
	}

	public function setTag($tag){
		return $this->setVar('tag',$tag);
	}

	public function hit(){
		return $this->getVar('hits');
	}

	public function setHit($hit){
		return $this->setVar('hits',$hit);
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
