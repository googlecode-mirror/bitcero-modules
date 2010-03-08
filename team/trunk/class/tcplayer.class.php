<?php
// $Id$
// --------------------------------------------------------------
// Equipo Club Gallos
// Un modulo sencillo para el manejo de equipos
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class TCPlayer extends RMObject
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
