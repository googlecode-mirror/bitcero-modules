<?php
// $Id: mwcategory.class.php 48 2009-09-17 14:53:50Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
 * Clase para el manejo de categorías
 */
class MWCategory extends RMObject
{
	
	function __construct($id=''){
		$this->db =& Database::getInstance();
		$this->_dbtable = $this->db->prefix("mw_categories");
		$this->setNew();
		$this->initVarsFromTable();
		if ($id==''){
			return;
		}
		
		if (is_numeric($id)){
			if ($this->loadValues($id)){
				$this->unsetNew();
			}
			return;
		}
		
		$this->primary = 'shortcut';
		if ($this->loadValues($id)) $this->unsetNew();
		$this->primary = 'id_cat';
			
	}
	/**
	 * Funciones para asignar valores a las variables
	 */
	function id(){
		return $this->getVar('id_cat');
	}
	
	function loadPosts(){
		$result = $this->db->query("SELECT COUNT(*) FROM ".$this->db->prefix("mw_catpost")." WHERE cat='".$this->getID()."'");
		list($num) = $this->db->fetchRow($result);
		$this->setPosts($num);
	}
	/**
	 * Obtiene la ruta completa de la categor?a basada en nombres
	 */
	function path(){
		if ($this->getParent()==0) return $this->getVar('shortname','n').'/';
		$parent = new MWCategory($this->getVar('parent','n'));
		return $parent->getPath() . $this->getVar('shortname').'/';
	}
	/**
	 * Obtiene el enlace a la categor?a
	 */
	public function getLink(){
		global $mc;
		$link = mw_get_url();
		$link .= ($mc['permalinks']==1 ? '?cat='.$this->getID() : ($mc['permalinks']==2 ? 'category/'.$this->getPath() : 'category/'.$this->id()));
		return $link;
	}

	/**
	 * Guardamos los valores en la base de datos
	 */
	function save(){
		if ($this->isNew()){
            return $this->saveToTable();
        } else {
            return $this->updateTable();
        }
	}
	/**
	 * Elimina de la base de datos la categor?a actual
	 */
	function delete(){
		$this->db->queryF("UPDATE ".$this->db->prefix("mw_categories")." SET parent='".$this->getVar('parent','n')."' WHERE parent='".$this->id()."'");
		$this->db->queryF("DELETE FROM ".$this->db->prefix("mw_categories")." WHERE id_cat='".$this->id()."'");
		return $this->deleteFromTable();
	}
}
