<?php
// $Id$
// --------------------------------------------------------
// Gallery System
// Manejo y creación de galerías de imágenes
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

class GSTag extends EXMObject
{
	public function __construct($id=null){
		$this->db =& Database::getInstance();
        $this->_dbtable = $this->db->prefix("gs_tags");
        $this->setNew();
        $this->initVarsFromTable();
        
        if (!isset($id)) return;
        
        if (is_numeric($id)){
        	if (!$this->loadValues($id)) return;
		} else {
			$this->primary = 'tag';
			if ($this->loadValues($id)) $this->unsetNew();
			$this->primary = 'id_tag';
			return;
		}
        
        $this->unsetNew();
	}
	
	public function id(){
		return $this->getVar('id_tag');
	}
	
	public function tag(){
		return $this->getVar('tag');
	}
	public function setTag($tag){
		return $this->setVar('tag', $tag);
	}
	
	public function hits(){
		return $this->getVar('hits');
	}
	public function setHits($value){
		return $this->setVar('hits', $value);
	}
	public function addHit(){
		$db =& $this->db;
		if (!$db->queryF("UPDATE ".$db->prefix("gs_tags")." SET hits=hits+1 WHERE id_tag='".$this->id()."'")){
			$this->addError($db->error());
			return;
		}
		
		return true;
	}
	
	public function url(){
		global $mc;
		
		$url = XOOPS_URL.'/modules/galleries/';
		$url .= $mc['urlmode'] ? 'explore/tags/tag/'.$this->tag().'/' : 'explore.php?by=explore/tags/tag/'.$this->tag();
		return $url;
		
	}
	
	public function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		} else {
			return $this->updateTable();
		}
	}
	
	public function delete(){
		$db =& $this->db;
		$sql = "DELETE FROM ".$db->prefix("gs_tags")." WHERE id_tag='".$this->id()."'";
		if (!$db->queryF($sql)){
			$this->addError($db->error());
			return false;
		}
		if (!$this->deleteFromTable()) return false;
		
		return true;
	}
	
}

?>
