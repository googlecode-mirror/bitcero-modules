<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class GSSet extends RMObject
{
	
	private $_pics;
	
	public function __construct($id=null){
		$this->db =& Database::getInstance();
        $this->_dbtable = $this->db->prefix("gs_sets");
        $this->setNew();
        $this->initVarsFromTable();
        
        if (!isset($id)) return;
        
        if (!$this->loadValues($id)) return;
        
        $this->unsetNew();
	}
	
	public function id(){
		return $this->getVar('id_set');
	}
	
	public function title(){
		return $this->getVar('title');
	}
	public function setTitle($value){
		return $this->setVar('title', $value);
	}
	
	public function date(){
		return $this->getVar('date');
	}
	public function setDate($value){
		return $this->setVar('date', $value);
	}
	
	public function ispublic(){
		return $this->getVar('public');
	}
	public function setPublic($value){
		return $this->setVar('public', $value);
	}
	
	public function pics(){
		return $this->getVar('pics');
	}
	public function setPics($value){
		return $this->setVar('pics', $value);
	}
	
	public function getPics($order = ''){
		if (empty($this->_pics)){
			$db =& $this->db;
			$result = $db->query("SELECT id_image FROM ".$db->prefix("gs_setsimages")." WHERE id_set='".$this->id()."'".($order!='' ? " ORDER BY $order" : ''));
			while(list($img)=$db->fetchRow($result)){
				$this->_pics[] = $img;
			}
		}
		
		return $this->_pics;
	}
	
	/**
	* @desc Comprueba si una imágen pertenece al album
	* @param int Id de la imágen
	* @return bool
	*/
	public function belongToSet($img){
		if ($img<=0) return false;
		
		$this->getPics();
		
		return in_array($img, $this->_pics);
		
	}
	
	/**
	* @desc Incrementa el contador de imágenes de un album
	* Si se pasa el parámetro id entonces la imágen es asignada en
	* la tabla de relaciones
	* @param int Id de la imágen
	* return bool
	*/
	public function addPic($id=0){
		$db =& $this->db;
		if (!$db->queryF("UPDATE ".$db->prefix("gs_sets")." SET pics=pics+1 WHERE id_set='".$this->id()."'")){
			$this->addError($db->error());
			return;
		}
		
		if ($id<=0) return true;
		
		if ($this->belongToSet($id)) return true;
		
		if (!$db->queryF("INSERT INTO ".$db->prefix("gs_setsimages")." (`id_set`,`id_image`) VALUES ('".$this->id()."','$id')")){
			$this->addError($db->error());
			return false;
		}
		
		return true;
		
	}
	public function quitPic($id=0){
		$db =& $this->db;
		if (!$db->queryF("UPDATE ".$db->prefix("gs_sets")." SET pics=pics-1 WHERE id_set='".$this->id()."'")){
			$this->addError($db->error());
			return;
		}
		
		if ($id<=0) return true;
		
		$db->queryF("DELETE FROM ".$db->prefix("gs_setsimages")." WHERE id_img='$id' AND id_set='".$this->id()."'");
		
		return true;
	}
	
	public function hits(){
		return $this->getVar('hits');
	}
	public function setHits($value){
		return $this->setVar('hits', $value);
	}
	public function addHit(){
		$db =& $this->db;
		if (!$db->queryF("UPDATE ".$db->prefix("gs_sets")." SET hits=hits+1 WHERE id_set='".$this->id()."'")){
			$this->addError($db->error());
			return;
		}
		
		return true;
	}
	
	public function owner(){
		return $this->getVar('owner');
	}
	public function setOwner($value){
		return $this->setVar('owner', $value);
	}
	
	public function uname(){
		return $this->getVar('uname');
	}
	public function setUname($value){
		return $this->setVar('uname', $value);
	}
	
	public function url(){
		global $xoopsModule, $xoopsModuleConfig;
		
		if (isset($xoopsModule) && $xoopsModule->dirname()=='galleries'){
			$mc =& $xoopsModuleConfig;
		} else {
			$util =& RMUtils::getInstance();
			$mc =& $util->moduleConfig('galleries');
		}
		
		$url = XOOPS_URL.'/modules/galleries/';
		$url .= $mc['urlmode'] ? 'usr/'.$this->uname()."/set/".$this->id().'/' : "user.php?id=usr/".$this->uname()."/set/".$this->id();
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
		$sql = "SELECT * FROM ".$db->prefix("gs_setsimages")." WHERE id_set='".$this->id()."'";
		
		if (!$this->deleteFromTable()) return false;
		
		if (!$this->db->queryF($sql)){
			$this->addError($this->db->error());
			return false;
		}
		
		return true;
	}
	
}
