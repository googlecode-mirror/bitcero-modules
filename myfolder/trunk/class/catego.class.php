<?php
// $Id$
// --------------------------------------------------------------
// MyFolder
// Advanced Portfolio System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include_once XOOPS_ROOT_PATH.'/modules/rmmf/class/object.class.php';

class MFCategory extends RMObject
{
	var $_tbl = '';
	
	function MFCategory($id=null){
		
		$this->db = XoopsDatabaseFactory::getDatabaseConnection();
		
		if (is_null($id)){ return; }
		
		$this->_tbl = $this->db->prefix("rmmf_categos");
		
		$result = $this->db->query("SELECT * FROM $this->_tbl WHERE id_cat='$id'");
		if ($this->db->getRowsNum($result)<=0){ $this->initVar('found', false); return; }
		
		$row = $this->db->fetchArray($result);
		
		foreach ($row as $k => $v){
			$this->initVar($k, $v);
		}
		
		$this->initVar('found',true);
		
	}
	
	/**
	 * Obtenemos el numero de trabajos en esta categor?a
	 */
	function getWorksNumber(){
		$result = $this->db->query("SELECT COUNT(*) FROM ".$this->db->prefix("rmmf_works")." WHERE catego='".$this->getVar('id_cat')."'");
		list($num) = $this->db->fetchRow($result);
		return $num;
	}
}
