<?php
// $Id: ahresource.class.php 409 2010-05-13 18:00:22Z i.bitcero $
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2


class RDResource extends RMObject{
	
	/**
	* Stores the references existing for this resource
	* 
	* @var array
	*/
	private $references = array();
	/**
	* Stores the figures existing for this resource
	* 
	* @var array
	*/
	private $figures = array();
	
	function __construct($id=null){

		$this->db =& Database::getInstance();
		$this->_dbtable = $this->db->prefix("pa_resources");
		$this->setNew();
		$this->initVarsFromTable();

		$this->setVarType('editores', XOBJ_DTYPE_ARRAY);
		$this->setVarType('groups', XOBJ_DTYPE_ARRAY);
		
				
		if ($id==null) return;
		
		if (is_numeric($id)){
			if (!$this->loadValues($id)) return;
			$this->unsetNew();
		}else{
			$this->primary="nameid";
			if ($this->loadValues($id)) $this->unsetNew();
			$this->primary="id_res";
		}	
	
	}


	public function id(){
		return $this->getVar('id_res');
	}
	
	/**
	* Get the references for this resource
	* @return array
	*/
	public function get_references(){
		
		if (!empty($this->references)) return $this->references;
		
		$db = $this->db;
		
		$sql = "SELECT * FROM ".$db->prefix("pa_references")." WHERE id_res='".$this->id()."'";
		$result = $db->query($sql);
		$refs = array();
		while ($row = $db->fetchArray($result)){
			$refs[] = $row;
		}
		
		return $refs;
	}
	
	/**
	* Get the figures for this resource
	* @return array
	*/
	public function get_figures(){
		
		if (!empty($this->figures)) return $this->figures;
		
		$db = $this->db;
		
		$sql = "SELECT * FROM ".$db->prefix("pa_figures")." WHERE id_res='".$this->id()."'";
		$result = $db->query($sql);
		$figs = array();
		while ($row = $db->fetchArray($result)){
			$this->figures[] = $row;
		}
		
		return $this->figures;
	}
	
	public function addRead(){
		if ($this->isNew()) return;
		return $this->db->queryF("UPDATE ".$this->db->prefix("pa_resources")." SET `reads`=`reads`+1 WHERE id_res='".$this->id()."'");
	}
	
	public function addVote($rate){
		if ($this->isNew()) return;
		return $this->db->queryF("UPDATE ".$this->db->prefix("pa_resources")." SET `votes`=`votes`+1, `rating`='".($this->rating()+$rate)."' WHERE id_res='".$this->id()."'");
		$this->setRating($this->rating()+$rate);
	}
	
	/**
	* @desc Determina si usuario tiene permiso para acceder a la publicación
	* @param int array $gid Id(s) de Grupo(s)
	**/	
	public function isAllowed($gid){
    
		$groups = $this->groups();
		
		if (in_array(0, $groups)) return true;

		if (!is_array($gid)){
			if ($gid == XOOPS_GROUP_ADMIN) return true;
			return in_array($gid, $groups);
		}

		if (in_array(XOOPS_GROUP_ADMIN,$gid)) return true;
				
		foreach ($gid as $k){

			if (in_array($k, $groups)) return true;
		}
		
		return false;

	}


	/**
	* @desc Determina si un usuario es editor de un recurso
	* @param int $uid Id de usuario
	**/	
	public function isEditor($uid){
    
		$editors = $this->editors();
				
		return in_array($uid,$editors);

	}


	/**
	* @desc Determina si usuario tiene permiso para crear nueva publicación
	* @param int array $gid  Ids de grupos a que pertenece usuario
	* @param int array $groups Ids de grupos con permiso a crear publicación
	**/	
	public function isAllowedNew($gid,$groups){
		
		if (!is_array($gid)){
			if ($gid == XOOPS_GROUP_ADMIN) return true;
			return in_array($gid, $groups);
		}

		if (in_array(XOOPS_GROUP_ADMIN,$gid)) return true;
				
		foreach ($gid as $k){

			if (in_array($k, $groups)) return true;
		}
		
		return false;

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

		$ret=false;		

		//Elimina secciones pertenecientes a la publicación
		$sql="DELETE FROM ".$this->db->prefix('pa_sections')." WHERE id_res='".$this->id()."'";
		$result = $this->db->queryF($sql);

		if (!$result) return $ret;	
		
		//Elimina Referencias pertenecientes a la publicación
		$sql="DELETE FROM ".$this->db->prefix('pa_references')." WHERE id_res='".$this->id()."'";
		$result=$this->db->queryF($sql);
			
		if (!$result) return $ret;
	
		//Elimina imágenes pertenecientes a la publicación
		$sql="DELETE FROM ".$this->db->prefix('pa_figures')." WHERE id_res='".$this->id()."'";
		$result=$this->db->queryF($sql);
					
		if (!$result) return $ret;
		
		@unlink(XOOPS_UPLOAD_PATH.'/ahelp/'.$this->image());

		$ret=$this->deleteFromTable();
											
		return $ret;	
		
	}
	

}
