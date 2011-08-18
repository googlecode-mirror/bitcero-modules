<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include_once 'gsuser.class.php';

class GSImage extends RMObject
{
	
	/**
	* @desc Almacena las etiquetas asignadas a la imágen
	*/
	private $_tags = array();
	/**
	* @desc Determina el tipo de contenido de las etiquetas
	*/
	private $_tagtype = true;
	/**
	* @desc Almacena los albumes a los que pertenece la imágen
	*/
	private $_sets = array();
	/**
	* @desc Determina el tipo de contenido de los albumes
	*/
	private $_settype = false;
	
	/**
	* @desc Constructor de la clase
	*/
	function __construct($id=null){
		$this->db =& Database::getInstance();
        $this->_dbtable = $this->db->prefix("gs_images");
        $this->setNew();
        $this->initVarsFromTable();
        
        if ($id==null) return;
        
        if (!$this->loadValues(intval($id))) return;
        
        $this->unsetNew();
        $this->tags(true);
	}
	
	public function id(){
		return $this->getVar('id_image');
	}
	/**
	* @desc Devuelve el título de la imágen
	* @param bool Si no existe devuelve el nombre de archivo
	*/
	public function title($force=true){
		if (!$force) return $this->getVar('title');
		if ($this->getVar('title')==''){
			return $this->getVar('image');
		} else {
			return $this->getVar('title');
		}
	}
	public function setTitle($value){
		return $this->setVar('title', $value);
	}
	
	public function desc(){
		return $this->getVar('desc');
	}
	public function setDesc($value){
		return $this->setVar('desc', $value);
	}
	
	public function image(){
		return $this->getVar('image');
	}
	public function setImage($value){
		return $this->setVar('image', $value);
	}
	
	public function created(){
		return $this->getVar('created');
	}
	public function setCreated($value){
		return $this->setVar('created', $value);
	}
	
	public function modified(){
		return $this->getVar('modified');
	}
	public function setModified($value){
		return $this->setVar('modified', $value);
	}
	
	public function owner(){
		return $this->getVar('owner');
	}
	public function setOwner($value){
		return $this->setVar('owner', $value);
	}
	
	public function comments(){
		return $this->getVar('comments');
	}
	public function setComments($value){
		return $this->setVar('comments', $value);
	}
	public function addComment(){
		$db =& $this->db;
		if (!$db->queryF("UPDATE ".$db->prefix("gs_images")." SET comments=comments+1 WHERE id_image='".$this->id()."'")){
			$this->addError($db->error());
			return;
		}
		return true;
	}

	/**
	* @desc Número de visualizaciones de la imagen
	**/
	public function views(){
		return $this->getVar('views');
	}

	public function setViews($views){
		return $this->setVar('views',$views);
	}

	public function addViews(){
		$db =& $this->db;
		if (!$db->queryF("UPDATE ".$db->prefix("gs_images")." SET views=views+1 WHERE id_image='".$this->id()."'")){
			$this->addError($db->error());
			return;
		}
		return true;
	}
	
	public function userFormat(){
		return $this->getVar('user_format');
	}
	public function setUserFormat($value, $now=0){
		$this->setVar('user_format', $value);
		if ($now){
			$db =& $this->db;
			$sql = "UPDATE ".$db->prefix("gs_images")." SET `user_format`='$value' WHERE id_image='".$this->id()."'";
			return $this->db->queryF($sql);
		}
		return true;
	}
	
	public function searchFormat(){
		return $this->getVar('search_format');
	}
	public function setSearchFormat($value, $now=0){
		$this->setVar('search_format', $value);
		if ($now){
			$db =& $this->db;
			$sql = "UPDATE ".$db->prefix("gs_images")." SET `search_format`='$value' WHERE id_image='".$this->id()."'";
			return $this->db->queryF($sql);
		}
		return true;
	}
	
	public function setFormat(){
		return $this->getVar('set_format');
	}
	public function setSetFormat($value, $now=0){
		$this->setVar('set_format', $value);
		if ($now){
			$db =& $this->db;
			$sql = "UPDATE ".$db->prefix("gs_images")." SET `set_format`='$value' WHERE id_image='".$this->id()."'";
			return $this->db->queryF($sql);
		}
		return true;
	}
	
	public function bigSetFormat(){
		return $this->getVar('bigset_format');
	}
	public function setBigSetFormat($value, $now=0){
		$this->setVar('bigset_format', $value);
		if ($now){
			$db =& $this->db;
			$sql = "UPDATE ".$db->prefix("gs_images")." SET `bigset_format`='$value' WHERE id_image='".$this->id()."'";
			return $this->db->queryF($sql);
		}
		return true;
	}
	
	/**
	* @desc Establece las etiquetas de la imágen
	* Esta función debe ser utilizada con imágenes ya creadas
	* @param array Ids de las etiquetas
	* @return bool
	*/
	public function setTags($tags){
		if (!is_array($tags)) return;
		if ($this->isNew()) return false;
		
		$sql = "DELETE FROM ".$this->db->prefix("gs_tagsimages")." WHERE id_image='".$this->id()."'";
		if (!$this->db->queryF($sql)){
			$this->addError($this->db->error());
			return false;
		}
		
		$sql = "INSERT INTO ".$this->db->prefix("gs_tagsimages")." (`id_tag`,`id_image`) VALUES ";
		$sql1 = '';
		foreach ($tags as $tag){
			$sql1 .= $sql1=='' ? "('$tag','".$this->id()."')" : ",('$tag','".$this->id()."')";
		}
		if (!$this->db->queryF($sql.$sql1)){
			$this->addError($this->db->error());
			return false;
		}

		return true;
		
	}
	/**
	* @desc Elimina las etiquetas de una imágen
	*/
	public function clearTags(){
		if ($this->isNew()) return false;
		$sql = "DELETE FROM ".$this->db->prefix("gs_tagsimages")." WHERE id_image='".$this->id()."'";
		if (!$this->db->queryF($sql)){
			$this->addError($this->db->error());
			return false;
		}
		
		$this->_tags = array();
		$this->_tagtype = false;
		return true;
	}
	
	/**
	* @desc Devuelve las etiquetas asignadas a un imágen
	* @param bool $obj Determina si se obtienen los objetos GSTag
	* @return array
	*/
	public function tags($obj = true, $field = '*'){
		$db =& $this->db;
		
		if (empty($this->_tags) || $this->_tagtype!=$obj){
			$this->_tags = array();
			$tbl1 = $db->prefix("gs_tags");
			$tbl2 = $db->prefix("gs_tagsimages");
			
			$sql = "SELECT a.$field FROM $tbl1 a, $tbl2 b WHERE b.id_image='".$this->id()."' AND a.id_tag=b.id_tag";
			$result = $db->query($sql);
			
			while ($row = $db->fetchArray($result)){
				if ($obj){
					$tag = new GSTag();
					$tag->assignVars($row);
					$this->_tags[] = $tag;
				} else {
					$this->_tags[] = $row[$field];
				}
			}
		}
		
		return $this->_tags;
	}
	
	/**
	* @desc Devuelve los albumes a los que pertenece una imágen
	* @param bool $obj Determina si se obtienen los objetos GSSet
	* @return array
	*/
	public function sets($obj = true){
		$db =& $this->db;
		
		if (empty($this->_sets) || $this->_settype!=$obj){
			$this->_sets = array();
			$tbl1 = $db->prefix("gs_sets");
			$tbl2 = $db->prefix("gs_setsimages");
			
			$sql = "SELECT a.* FROM $tbl1 a, $tbl2 b WHERE b.id_image='".$this->id()."' AND a.id_set=b.id_set";
			$result = $db->query($sql);
			
			while ($row = $db->fetchArray($result)){
				if ($obj){
					$set = new GSSet();
					$set->assignVars($row);
					$this->_sets[] = $set;
				} else {
					$this->_sets[] = $row;
				}
			}
		}
		
		return $this->_sets;
	}
	
	/**
	* @desc Determina si la imágen existe en un album
	* @param int Id del Album
	* @return bool
	*/
	public function inSet($set){
		$sets = array();
		foreach ($this->sets() as $k){
			$sets[] = $k->id();
		}
		
		return in_array($set, $sets);
	}
	
	public function isPublic(){
		return $this->getVar('public');
	}
	public function setPublic($value){
		return $this->setVar('public', $value);
	}
	
    // Permalink
    public function permalink(){
        $mc = RMUtilities::module_config('galleries');
        $user = new GSUser($this->owner());
        
        return $user->userURL().($mc['urlmode'] ? 'img/'.$this->id().'/' : '&amp;img='.$this->id());
        
    }
    
	public function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		} else {			
			return $this->updateTable();
		}
	}
	
	public function delete(){
		global $mc;
		
		$user = new GSUser($this->owner(), 1);
		// Eliminamos los archivos
		@unlink($user->filesPath().'/ths/'.$this->image());
		@unlink($user->filesPath().'/'.$this->image());
		if (!$mc['deleteoriginal']) @unlink($mc['storedir'].'/originals/'.$this->image());
		// Falta eliminar de los formatos
		$rtn = true;
		
		$sql = "DELETE FROM ".$this->db->prefix("gs_tagsimages")." WHERE id_image='".$this->id()."'";
		if (!$this->db->queryF($sql)){
			$this->addError($this->db->error());
			$rtn = false;
		}
		
		$sql = "DELETE FROM ".$this->db->prefix("gs_setsimages")." WHERE id_image='".$this->id()."'";
		if (!$this->db->queryF($sql)){
			$this->addError($this->db->error());
			$rtn = false;
		}
		
		$user->quitPic();
		if (!$this->deleteFromTable()){
			return false;
		} else {
			return $rtn;
		}
		
	}
	
}
