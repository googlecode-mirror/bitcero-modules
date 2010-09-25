<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class GSPostcard extends RMObject
{
	function __construct($id=null){
		$this->db =& Database::getInstance();
        $this->_dbtable = $this->db->prefix("gs_postcards");
        $this->setNew();
        $this->initVarsFromTable();
        
        if ($id==null) return;
        
     /*   if (!$this->loadValues(intval($id))) return;
        
        $this->unsetNew();
	}*/

	if (is_numeric($id)){
        	if (!$this->loadValues($id)) return;
		} else {
			$this->primary = 'code';
			if ($this->loadValues($id)) $this->unsetNew();
			$this->primary = 'id_post';
			return;
		}
        
        $this->unsetNew();
	}
	
	
	public function id(){
		return $this->getVar('id_post');
	}
	
	public function title(){
		return $this->getVar('title');
	}
	public function setTitle($value){
		return $this->setVar('title', $value);
	}
	
	public function message(){
		return $this->getVar('msg');
	}
	public function setMessage($value){
		return $this->setVar('msg', $value);
	}
	
	public function image(){
		return $this->getVar('id_image');
	}
	public function setImage($value){
		return $this->setVar('id_image', $value);
	}
	
	public function date(){
		return $this->getVar('date');
	}
	public function setDate($value){
		return $this->setVar('date', $value);
	}
	
	public function toName(){
		return $this->getVar('toname');
	}
	public function setToName($value){
		return $this->setVar('toname', $value);
	}
	
	public function toEmail(){
		return $this->getVar('tomail');
	}
	public function setToEmail($value){
		return $this->setVar('tomail', $value);
	}
	
	/**
	* @desc Nombre del remitente
	*/
	public function name(){
		return $this->getVar('fromname');
	}
	public function setName($value){
		return $this->setVar('fromname', $value);
	}
	/**
	* @desc Email del Remitente
	*/
	public function email(){
		return $this->getVar('frommail');
	}
	public function setEmail($value){
		return $this->setVar('frommail', $value);
	}
	
	public function uid(){
		return $this->getVar('uid');
	}
	public function setUid($value){
		return $this->setVar('uid', $value);
	}
	
	public function ip(){
		return $this->getVar('ip');
	}
	public function setIp($value){
		return $this->setVar('ip', $value);
	}
	
	public function viewed(){
		return $this->getVar('view');
	}
	public function setViewed($value){
		return $this->setVar('view', $value);
	}

	/**
	* @desc Código de la postal
	**/
	public function code(){
		return $this->getVar('code');
	}

	public function setCode($code){
		return $this->setVar('code',$code);
	}
	
	public function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		} else {
			return $this->updateTable();
		}
	}
	
	public function url(){
		global $mc;
		
		$url = XOOPS_URL.'/modules/galleries/';
		$url .= $mc['urlmode'] ? "postcard/".$this->id().'/' : 'postcard.php?id='.$this->id();
		
		return $url;
		
	}
	
	public function delete(){
		return $this->deleteFromTable();
	}
	
}
