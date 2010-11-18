<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------


class PWCategory extends RMObject
{
	public function __construct($id=null){
		
		$this->db =& Database::getInstance();
		$this->_dbtable = $this->db->prefix("pw_categos");
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
	
	public function desc($format='s'){
		return $this->getVar('desc', $format);
	}
	public function setDesc($value){
		return $this->setVar('desc', $value);
	}
	
	public function active(){
		return $this->getVar('active');
	}
	public function setActive($value){
		return $this->setVar('active', $value);
	}
	
	public function order(){
		return $this->getVar('order');
	}
	public function setOrder($value){
		return $this->setVar('order', $value);
	}
	
	public function parent(){
		return $this->getVar('parent');
	}


	public function setParent($parent){
		return $this->setVar('parent',$parent);
	}

	public function created(){
		return $this->getVar('created');
	}

	public function setCreated($created){
		return $this->setVar('created',$created);
	}
	
	/**
	* Get the category link formated
	*/
	public function link(){
		global $xoopsModule, $xoopsModuleConfig;
		
		if (isset($xoopsModule) && $xoopsModule->dirname()=='works'){
			$mc =& $xoopsModuleConfig;
		} else {
			$mc = RMUtilities::module_config('works');
		}
		
		$link = XOOPS_URL.'/';
		if ($mc['urlmode']){
			$link .= trim($mc['htbase'], '/').'/category/'.$this->nameId().'/';
		} else {
			$link .= 'modules/works/index.php?page=category&amp;id='.$this->nameId();
		}
		
		return $link;
		
	}

	/**
	* @desc Obtiene el total de trabajos de la categoría
	**/
	public function works(){
	
		$sql = "SELECT COUNT(*) FROM ".$this->db->prefix('pw_works')." WHERE catego='".$this->id()."'";
		list($num) = $this->db->fetchRow($this->db->query($sql));

		return $num;

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
