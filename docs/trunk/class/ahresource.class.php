<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2


class AHResource extends EXMObject{
	
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

	public function title(){
		return $this->getVar('title');
	}

	public function setTitle($title){
		return $this->setVar('title',$title);
	}

	public function desc(){
		return $this->getVar('description');
	}

	public function setDesc($desc){
		return $this->setVar('description',$desc);
	}

	public function image(){
		return $this->getVar('image');
	}

	public function setImage($image){
		return $this->setVar('image',$image);
	}

	public function created(){
		return $this->getVar('created');
	}

	public function setCreated($created){
		return $this->setVar('created',$created);
	}

	public function modified(){
		return $this->getVar('modified');
	}

	public function setModified($modified){
		return $this->setVar('modified',$modified);
	}

	public function editors(){
		return $this->getVar('editores');
	}

	public function setEditors($editores){
		return $this->setVar('editores',$editores);
	}

	public function approveEditors(){
		return $this->getVar('editor_approve');
	}
	
	public function setApproveEditors($approve){
		return $this->setVar('editor_approve',$approve);
	}

	public function approved(){
		return $this->getVar('approved');
	}

	public function setApproved($approved){
		return $this->setVar('approved',$approved);
	}

	public function groups(){
		return $this->getVar('groups');
	}

	public function setGroups($groups){
		return $this->setVar('groups',$groups);
	}

	public function isPublic(){
		return $this->getVar('public');
	}

	public function setPublic($public){
		return $this->setVar('public',$public);
	}

	public function quick(){
		return $this->getVar('quick');
	}

	public function setQuick($quick){
		return $this->setVar('quick',$quick);
	}


	public function nameId(){
		return $this->getVar('nameid');
	}
	
	public function setNameId($name){
		return $this->setVar('nameid',$name);
	}

	//Mostrar índice a usuarios sin permiso de ver publicación
	public function showIndex(){
		return $this->getVar('show_index');

	}

	public function setShowIndex($showindex){
		return $this->setVar('show_index',$showindex);
	}

	//Id de usuario que crea el recurso
	public function owner(){
		return $this->getVar('owner');
	}

	public function setOwner($owner){
		return $this->setVar('owner',$owner);

	}

	//Nombre de usuario que crea el recurso
	public function owname(){
		return $this->getVar('owname');

	}

	public function setOwname($owname){
		return $this->setVar('owname',$owname);
	}
	
	// Featured
	public function featured(){
		return $this->getVar('featured');
	}
	public function setFeatured($value){
		return $this->setVar('featured', $value);
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
	
	/**
	* @desc Lecturas
	*/
	public function reads(){
		return $this->getVar('reads');
	}
	public function setReads($value){
		return $this->setVar('reads', $value);
	}
	public function addRead(){
		if ($this->isNew()) return;
		return $this->db->queryF("UPDATE ".$this->db->prefix("pa_resources")." SET `reads`=`reads`+1 WHERE id_res='".$this->id()."'");
	}
	
	// Votos
	public function votes(){
		return $this->getVar('votes');
	}
	public function setVotes($value){
		return $this->setVar('votes', $value);
	}
	public function addVote($rate){
		if ($this->isNew()) return;
		return $this->db->queryF("UPDATE ".$this->db->prefix("pa_resources")." SET `votes`=`votes`+1, `rating`='".($this->rating()+$rate)."' WHERE id_res='".$this->id()."'");
		$this->setRating($this->rating()+$rate);
	}
	
	
	
	// rating
	public function rating(){
		return $this->getVar('rating');
	}
	public function setRating($value){
		return $this->setVar('rating', $value);
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
