<?php
// $Id$
// --------------------------------------------------------------
// Quick Pages
// Create simple pages easily and quickly
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
 * Clase para el manejo de categorías
 */
class QPCategory extends EXMObject
{
	
	function __construct($id=''){
		$this->db =& Database::getInstance();
		$this->_dbtable = $this->db->prefix("qpages_categos");
		$this->setNew();
		$this->initVarsFromTable();
		if ($id==''){
			return;
		}
		
		if (is_numeric($id)){
			if (!$this->loadValues($id))
				return;
		} else {
			$this->primary = 'nombre_amigo';
			if (!$this->loadValues(MyTextSanitizer::addSlashes($id)))
				return;
		}
		
		$this->primary = 'id_cat';
		$this->unsetNew();
			
	}
	/**
	 * Funciones para asignar valores a las variables
	 */
	function getID(){
		return $this->getVar('id_cat');
	}
	function setName($value){
		return $this->setVar('nombre', $value);
	}
	function getName(){
		return $this->getVar('nombre');
	}
	function setDescription($value){
		return $this->setVar('descripcion', $value);
	}
	function getDescription(){
		return $this->getVar('descripcion');
	}
	function setParent($value){
		return $this->setVar('parent', $value);
	}
	function getParent(){
		return $this->getVar('parent');
	}
	function setFriendName($value){
		return $this->setVar('nombre_amigo', $value);
	}
	function getFriendName(){
		return $this->getVar('nombre_amigo');
	}
	function loadPages($acceso=1){
		$result = $this->db->query("SELECT * FROM ".$this->db->prefix("qpages_pages")." WHERE cat='".$this->getID()."' AND acceso='$acceso' ORDER BY porder");
		$ret = array();
		while ($row = $this->db->fetchArray($result)){
			$ret[] = $row;
		}
		return $ret;
	}
	/**
	 * Obtiene la ruta completa de la categoría basada en nombres
	 */
	function getPath(){
		if ($this->getParent()==0) return $this->getFriendName().'/';
		$parent = new QPCategory($this->getParent());
		return $parent->getPath() . $this->getFriendName().'/';
	}
	/**
	 * Obtiene el enlace a la categoría
	 */
	public function getLink(){
		global $mc, $xoopsModule;
		$link = QP_URL.'/';
		$link .= $mc['links'] ? 'category/'.$this->getPath() : 'catego.php?cat='.urlencode($this->getPath());
		return $link;
	}
	/**
	 * Obtenemos las subcategorías
	 */
	public function getSubcategos(){
		global $mc, $xoopsModule;
		$result = $this->db->query("SELECT * FROM ".$this->_dbtable." WHERE parent='".$this->getID()."'");
		$cats = array();
		while ($row = $this->db->fetchArray($result)){
			$ret = array();
			$ret['id'] = $row['id_cat'];
			$catego = new QPCategory();
			$catego->assignVars($row);
			$ret['nombre'] = $catego->getName();
			$ret['link'] = $catego->getLink();
			$ret['desc'] = $catego->getDescription();
			$cats[] = $ret;
		}
		return $cats;
	}
	/**
	 * Guardamos los valores en la base de datos
	 */
	function save(){
		if ($this->saveToTable()){
			$this->setVar('id_cat', $this->db->getInsertId());
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Actualizamos los valores de la base de datos
	 */
	function update(){
		return $this->updateTable();
	}
	/**
	 * Elimina de la base de datos la categoría actual
	 */
	function delete(){
		$this->db->queryF("DELETE FROM ".$this->db->prefix("qpages_pages")." WHERE cat='".$this->getID()."'");
		$this->db->queryF("UPDATE ".$this->db->prefix("qpages_categos")." SET parent='".$this->getParent()."' WHERE parent='".$this->getID()."'");
		return $this->deleteFromTable();
	}
}
