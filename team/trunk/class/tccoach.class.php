<?php
// $Id$
// --------------------------------------------------------------
// Equipo Club Gallos
// Un modulo sencillo para el manejo de equipos
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class TCCoach extends RMObject
{
	private $_teams = array();
	private $_teamsid = array();
	
	public function __construct($id=null){
		$this->db =& XoopsDatabaseFactory::getDatabaseConnection();
		$this->_dbtable = $this->db->prefix("coach_coachs");
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
			$this->primary = "id_coach";
			return;
		}
	}
	
	public function id(){
		return $this->getVar('id_coach');
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
	
	public function bio($format = 's'){
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
	
	public function created(){
		return $this->getVar('created');
	}
	public function setCreated($value){
		return $this->setVar('created', $value);
	}
	
	public function role(){
		return $this->getVar('role');
	}
	public function setRole($value){
		return $this->setVar('role', $value);
	}
	
	/**
	* @desc Devuelve los equipos a los que pertenece el entrenador
	* @return array Objetos {@link TCTeam}
	*/
	public function teams($obj=true){
		if (empty($this->_teams)){
			$this->_teams = array();
			$this->_teamsid = array();
			$tbl1 = $this->db->prefix("coach_teams");
			$tbl2 = $this->db->prefix("coach_teamcoach");
			$sql = "SELECT a.* FROM $tbl1 a, $tbl2 b WHERE b.id_coach='".$this->id()."' AND a.id_team=b.id_team";
			$result = $this->db->query($sql);
			while ($row = $this->db->fetchArray($result)){
				$team = new TCTeam();
				$team->assignVars($row);
				$this->_teams[] = $team;
				$this->_teamsid[] = $team->id();
			}
		}
		
		return $obj ? $this->_teams : $this->_teamsid;
	}
	
	public function setTeams($teams){
		$this->_teamsid = $teams;
	}
	/**
	* @desc Agrega uno o varios equipos
	* @param int Id del Equipo o Array de ids
	*/
	public function addTeam($team){
		if (is_array($team)){
			foreach ($team as $k){
				if (!in_array($k, $this->_teamsid)) $this->_teamsid[] = $k;
			}
		} else {
			if (!in_array($team, $this->_teamsid)) $this->_teamsid[] = $team;
		}
	}
	
	public function save(){
		if ($this->isNew()){
			$rtn = $this->saveToTable();
		} else {
			$rtn = $this->updateTable();
		}
		
		if (!$rtn) return false;
		
		$this->db->queryF("DELETE FROM ".$this->db->prefix("coach_teamcoach")." WHERE id_coach='".$this->id()."'");
		
		if (empty($this->_teamsid)) return true;
		
		$sql = "INSERT INTO ".$this->db->prefix("coach_teamcoach")." (`id_coach`,`id_team`) VALUES ";
		$sql1 = '';
		foreach ($this->_teamsid as $k){
			$sql1 .= $sql1=='' ? "('".$this->id()."','$k')" : ", ('".$this->id()."','$k')";
		}
		
		$sql .= $sql1;
		
		if ($this->db->queryF($sql)){
			return true;
		} else {
			$this->addError($this->db->error());
			return false;
		}
		
	}
	
	public function delete(){
		
		if (!$this->deleteFromTable()) return false;
		
		// Eliminamos imágenes
		if ($this->image()!=''){
			@unlink(XOOPS_UPLOAD_PATH.'/teams/'.$this->image());
			@unlink(XOOPS_UPLOAD_PATH.'/teams/ths/'.$this->image());
		}
		
		// Eliminamos relaciones de equipo sy entrenador
		$sql = "DELETE FROM ".$this->db->prefix("coach_teamcoach")." WHERE id_team='".$this->id()."'";
		if ($this->db->queryF($sql)){
			return true;
		} else {
			$this->addError($this->db->error());
			return false;
		}
		
	}
	
}
?>
