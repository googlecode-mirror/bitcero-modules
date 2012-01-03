<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class GSUser extends RMObject
{
	private $eUser = null;
	private $_fpath = '';
	private $_furl = '';
	private $_url = '';
	private $_friends = null;
	
	
	public function __construct($id=null, $col=0){
		$this->db = XoopsDatabaseFactory::getDatabaseConnection();
        $this->_dbtable = $this->db->prefix("gs_users");
        $this->setNew();
        $this->initVarsFromTable();
        
        if ($col) $this->primary='uid'; 
        
        if (!isset($id)) return;
        
        if (is_numeric($id)){
        	if (!$this->loadValues($id)) return;
        	$this->unsetNew();
		} else {
			$this->primary = 'uname';
			if ($this->loadValues($id)) $this->unsetNew();
			$this->primary = 'id_user';
		}
        
        $this->eUser = new XoopsUser($this->getVar('uid'));
        $this->primary = 'id_user';
        
	}
	
	public function id(){
		return $this->getVar('id_user');
	}
	
	public function uid(){
		return $this->getVar('uid');
	}
	public function setUid($value){
		return $this->setVar('uid', $value);
	}
	
	public function uname(){
		return $this->getVar('uname');
	}
	public function setUname($value){
		return $this->setVar('uname', $value);
	}
	
	public function quota(){
		return $this->getVar('quota');
	}
	public function setQuota($value){
		return $this->setVar('quota', $value);
	}
	
	public function pics(){
		return $this->getVar('pics');
	}
	public function setPics($value){
		return $this->setVar('pics', $value);
	}
	
	public function sets(){
		return $this->getVar('sets');
	}
	public function setSets($value){
		return $this->setVar('sets', $value);
	}
	public function addSet(){
		$db =& $this->db;
		return $db->queryF("UPDATE ".$db->prefix("gs_users")." SET sets=sets+1 WHERE id_user='".$this->id()."'");
	}
	public function quitSet(){
		$db =& $this->db;
		return $db->queryF("UPDATE ".$db->prefix("gs_users")." SET sets=sets-1 WHERE id_user='".$this->id()."'");
	}	
	
	public function addPic(){
		$db =& $this->db;
		return $db->queryF("UPDATE ".$db->prefix("gs_users")." SET pics=pics+1 WHERE id_user='".$this->id()."'");
	}
	public function quitPic(){
		$db =& $this->db;
		return $db->queryF("UPDATE ".$db->prefix("gs_users")." SET pics=pics-1 WHERE id_user='".$this->id()."'");
	}
	
	public function date(){
		return $this->getVar('date');
	}
	public function setDate($value){
		return $this->setVar('date', $value);
	}
	
	public function blocked(){
		return $this->getVar('blocked');
	}
	public function setBlocked($value){
		return $this->setVar('blocked', $value);
	}
	
	public function userVar($var,$format='s'){
		return $this->eUser->getVar($var,$format);
	}

	
	/**
	* @desc Obtiene todos los amigos del usuario
	**/
	public function friends(){
		if ($this->_friends) return $this->_friends;

		$sql = "SELECT uid FROM ".$this->db->prefix('gs_friends')." WHERE gsuser='".$this->uid()."'";
		$result = $this->db->query($sql);
		while (list($uid) = $this->db->fetchRow($result)){
			$this->_friends[]=$uid;	
		}

		return $this->_friends;
	}


	/**
	* @desc Determina si se trata de un amigo
	**/
	public function isFriend($uid){

		$friends = $this->friends();
		if (@!in_array($uid,$friends)){
			return false;
		}

		return true;
	}

	
	/**
	* @desc Obtiene la ruta física al directorio del usuario
	* @return string
	*/
	public function filesPath(){
		global $xoopsModule, $xoopsModuleConfig;
		
		if ($this->_fpath!='') return $this->_fpath;
		
		if (isset($xoopsModule) && $xoopsModule->dirname()=='galleries'){
			$mc =& $xoopsModuleConfig;
		} else {
			$mc =& RMUtilities::get()->module_config('galleries');
		}
		$mc['storedir'] = substr($mc['storedir'], strlen($mc['storedir']) - 1)=='/' ? substr($mc['storedir'], 0, strlen($mc['storedir']) - 1) : $mc['storedir'];
		$this->_fpath = $mc['storedir'].'/'.$this->uname();

		return $this->_fpath;
	}
	/**
	* @desc Obtiene la url para ver una imágen
	*/
	public function filesURL(){
		global $xoopsModule, $xoopsModuleConfig;
		
		if ($this->_furl!='') return $this->_furl;
		
		if (isset($xoopsModule) && $xoopsModule->dirname()=='galleries'){
			$mc =& $xoopsModuleConfig;
		} else {
			$mc =& RMUtilities::get()->module_config('galleries');
		}
	
		$url = str_replace(XOOPS_ROOT_PATH, XOOPS_URL, $this->filesPath());
		
		$this->_furl = $url;
		
		return $this->_furl;
		
	}
	/**
	* @desc Obtiene la URL al usuario
	* @return string
	*/
	public function userURL(){
		global $xoopsModule, $xoopsModuleConfig;
		
		if ($this->_url!='') return $this->_url;
		
		if (isset($xoopsModule) && $xoopsModule->dirname()=='galleries'){
			$mc =& $xoopsModuleConfig;
		} else {
			$mc =& RMUtilities::get()->module_config('galleries');
		}
		
		$url = GSFunctions::get_url();
		$url .= $mc['urlmode'] ? 'usr/'.$this->uname()."/" : "?usr=".$this->uname();
		$this->_url = $url;
		return $url;
	}
	/**
	* @desc Determina el total de espacio usado en disco
	*/
	public function usedQuota(){
		$path = $this->filesPath();
		
		return GSFunctions::folderSize($path);
		
	}
	
	public function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		} else{
			return $this->updateTable();
		}
	}
	
	public function delete(){
		
		
		//Eliminamos las imágenes
		$sql = "SELECT * FROM ".$this->db->prefix('gs_images')." WHERE owner='".$this->uid()."'";
		$result = $this->db->query($sql);
		while ($rows = $this->db->fetchArray($result)){
			$img = new GSImage();
			$img->assignVars($rows);

			$img->delete();
		}

		RMUtilities::delete_directory($this->filesPath());
		
		//Eliminamos los albumes
		$sql = "SELECT * FROM ".$this->db->prefix('gs_sets')." WHERE owner='".$this->uid()."'";
		$result = $this->db->query($sql);
		while ($rows = $this->db->fetchArray($result)){
			$set = new GSSet();
			$set->assignVars($rows);

			$set->delete();
		}

		return $this->deleteFromTable();
	}
	
}
