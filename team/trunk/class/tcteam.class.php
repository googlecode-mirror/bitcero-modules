<?php
// $Id$
// --------------------------------------------------------------
// Equipo Club Gallos
// Un modulo sencillo para el manejo de equipos
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class TCTeam extends RMObject
{
	
	private $_coachs = array();
	private $_coachsid = array();
	
	public function __construct($id=null){
		$this->db =& XoopsDatabaseFactory::getDatabaseConnection();
		$this->_dbtable = $this->db->prefix("coach_teams");
		$this->setNew();
		$this->initVarsFromTable();
		if ($id==null){
			return;
		}
		
		if (is_numeric($id)){
			if ($this->loadValues(intval($id))) $this->unsetNew();
		}else{
			$this->primary = 'nameid';
			if ($this->loadValues($id)) $this->unsetNew();
			$this->primary = 'id_team';
		}
		return;
		
	}
	
	public function id(){
		return $this->getVar('id_team');
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
	
	public function desc($format = 's'){
		return $this->getVar('desc', $format);
	}
	public function setDesc($value){
		return $this->setVar('desc', $value);
	}
	
	public function category($obj=false){
		if (!$obj) return $this->getVar('cat');
		
		$cat = new TCCategory($this->getVar('cat'));
		return $cat;
		
	}
	public function setCategory($value){
		return $this->setVar('cat', $value);
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
	
	/**
	* @desc Devuelve los entrenadores del equipo
	* @return array Objetos {@link TCCoach}
	*/
	public function coachs($obj=true){
		if (empty($this->_coachs)){
			$this->_teams = array();
			$this->_teamsid = array();
			$tbl1 = $this->db->prefix("coach_coachs");
			$tbl2 = $this->db->prefix("coach_teamcoach");
			$sql = "SELECT a.* FROM $tbl1 a, $tbl2 b WHERE b.id_team='".$this->id()."' AND a.id_coach=b.id_coach";
			$result = $this->db->query($sql);
			while ($row = $this->db->fetchArray($result)){
				$coach = new TCCoach();
				$coach->assignVars($row);
				$this->_coachs[] = $coach;
				$this->_coachsid[] = $coach->id();
			}
		}
		
		return $obj ? $this->_coachs : $this->_coachsid;
	}
	
	public function setCoachs($coachs){
		$this->_coachsid = $coachs;
	}
	/**
	* @desc Agrega uno o varios equipos
	* @param int Id del Equipo o Array de ids
	*/
	public function addCoach($coach){
		if (is_array($coach)){
			foreach ($coach as $k){
				if (!in_array($k, $this->_coachsid)) $this->_coachsid[] = $k;
			}
		} else {
			if (!in_array($coach, $this->_coachsid)) $this->_coachsid[] = $coach;
		}
	}
	
	public function players($obj = true, $order=''){
		$sql = "SELECT * FROM ".$this->db->prefix("coach_players")." WHERE team='".$this->id()."'";
		if ($order!='') $sql .= " ORDER BY $order";
		$result = $this->db->query($sql);
		$players = array();
		while ($row = $this->db->fetchArray($result)){
			if ($obj){
				$player = new TCPlayer();
				$player->assignVars($row);
				$players[] = $player;
			} else {
				$players[] = $row['id_play'];
			}
		}
		
		return $players;
	}
	
	public function save(){
		if ($this->isNew()){
			$rtn = $this->saveToTable();
		} else {
			$rtn = $this->updateTable();
		}
		
		if (!$rtn) return false;
		
		$this->db->queryF("DELETE FROM ".$this->db->prefix("coach_teamcoach")." WHERE id_team='".$this->id()."'");
		
		if (empty($this->_coachsid)) return true;
		
		$sql = "INSERT INTO ".$this->db->prefix("coach_teamcoach")." (`id_coach`,`id_team`) VALUES ";
		$sql1 = '';
		foreach ($this->_coachsid as $k){
			$sql1 .= $sql1=='' ? "('$k','".$this->id()."')" : ", ('$k','".$this->id()."')";
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
