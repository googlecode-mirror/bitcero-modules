<?php
// $Id$
// --------------------------------------------------------------
// Equipo Club Gallos
// Un modulo sencillo para el manejo de equipos
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class TCCategory extends RMObject
{
	
	private $teams = array();
	
	public function __construct($id=null){
		
		$this->db =& Database::getInstance();
		$this->_dbtable = $this->db->prefix("coach_categos");
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
			$this->primary = "id_cat";
			return;
		}
		
	}
	
	public function id(){
		return $this->getVar('id_cat');
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
	
	public function desc(){
		return $this->getVar('desc');
	}
	public function setDesc($value){
		return $this->setVar('desc', $value);
	}
	
	public function teams(){
		
		if (empty($this->teams)){
		
			$result = $this->db->query("SELECT * FROM ".$this->db->prefix("coach_teams")." WHERE cat='".$this->id()."'");
		
			while ($row = $this->db->fetchArray($result)){
				$team = new TCTeam();
				$team->assignVars($row);
				$this->teams[] = $team;
			}
		}
		
		return $this->teams;
	}
	
	public function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		} else {
			return $this->updateTable();
		}
	}
	
	public function delete(){
		return $this->deleteFromTable();
	}
	
}

?>