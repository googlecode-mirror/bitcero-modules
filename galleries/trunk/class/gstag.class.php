<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class GSTag extends RMObject
{
	public function __construct($id=null){
		$this->db = XoopsDatabaseFactory::getDatabaseConnection();
        $this->_dbtable = $this->db->prefix("gs_tags");
        $this->setNew();
        $this->initVarsFromTable();
        
        if (!isset($id)) return;
        
        if (is_numeric($id)){
        	if (!$this->loadValues($id)) return;
		} else {
			$this->primary = 'nameid';
			if ($this->loadValues($id)) $this->unsetNew();
			$this->primary = 'id_tag';
			return;
		}
        
        $this->unsetNew();
	}
	
	public function id(){
		return $this->getVar('id_tag');
	}
	
	public function tag(){
		return $this->getVar('tag');
	}
	public function setTag($tag){
        $this->setVar('nameid', TextCleaner::getInstance()->sweetstring($tag));
		return $this->setVar('tag', $tag);
	}
	
	public function hits(){
		return $this->getVar('hits');
	}
	public function setHits($value){
		return $this->setVar('hits', $value);
	}
	public function addHit(){
		$db =& $this->db;
		if (!$db->queryF("UPDATE ".$db->prefix("gs_tags")." SET hits=hits+1 WHERE id_tag='".$this->id()."'")){
			$this->addError($db->error());
			return;
		}
		
		return true;
	}
	
	public function url(){
		global $mc;
		
		$url = XOOPS_URL.'/modules/galleries/';
		$url .= $mc['urlmode'] ? 'explore/tags/tag/'.$this->tag().'/' : 'explore.php?by=explore/tags/tag/'.$this->tag();
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
		$sql = "DELETE FROM ".$db->prefix("gs_tags")." WHERE id_tag='".$this->id()."'";
		if (!$db->queryF($sql)){
			$this->addError($db->error());
			return false;
		}
		if (!$this->deleteFromTable()) return false;
		
		return true;
	}
	
}
