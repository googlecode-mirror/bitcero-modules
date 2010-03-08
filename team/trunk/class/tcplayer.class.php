<?php
// $Id$
// --------------------------------------------------------
// The Coach
// Manejo de Integrantes de Equipos Deportivos
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

class TCPlayer extends EXMObject
{
	public function __construct($id=null){
		$this->db =& Database::getInstance();
		$this->_dbtable = $this->db->prefix("coach_players");
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
			$this->primary = "id_play";
			return;
		}
	}
	
	public function id(){
		return $this->getVar('id_play');
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
	
	public function birth(){
		return $this->getVar('birth');
	}
	public function setBirth($value){
		return $this->setVar('birth', $value);
	}
	
	public function age(){
		return date('Y',time()) - date('Y',$this->birth());
	}
	
	public function number(){
		return $this->getVar('number');
	}
	public function setNumber($value){
		return $this->setVar('number', $value);
	}
	
	public function bio($format='s'){
		return $this->getVar('bio', $format);
	}
	public function setBio($value){
		return $this->setVar('bio', $value);
	}
	
	public function image(){
		return $this->getVar('image');
	}
	public function setImage($value){
		return $this->setVar('image', $value);
	}
	
	public function date(){
		return $this->getVar('date');
	}
	public function setDate($value){
		return $this->setVar('date', $value);
	}
	
	public function team(){
		return $this->getVar('team');
	}
	public function setTeam($value){
		return $this->setVar('team', $value);
	}
	
	function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		} else {
			return $this->updateTable();
		}
	}
	
	function delete(){
		if (!$this->deleteFromTable()) return false;
		
		if ($this->image()!=''){
			@unlink(XOOPS_UPLOAD_PATH.'/teams/players/ths/'.$this->image());
			@unlink(XOOPS_UPLOAD_PATH.'/teams/players/'.$this->image());
		}
		
		return true;
	}
	
}

?>