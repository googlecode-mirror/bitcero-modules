<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------


class PWWork extends RMObject
{
    /**
    * Custom fields storage
    */
    private $metas = array();
     
	public function __construct($id=null){
		
		$this->db =& Database::getInstance();
		$this->_dbtable = $this->db->prefix("pw_works");
		$this->setNew();
		$this->initVarsFromTable();
		
		if ($id==null) return;
		
		if (is_numeric($id)){
			if (!$this->loadValues(intval($id))) return;
		} else {
			$this->primary = 'titleid';
			if (!$this->loadValues($id)){
				$this->primary = 'id_work';
				return;
			}
		}
		
        $this->unsetNew();
		
	}	

	public function id(){
		return $this->getVar('id_work');
	}

	/**
	* @desc Título
	**/
	public function title(){
		return $this->getVar('title');
	}

	public function setTitle($title){
		return $this->setVar('title',$title);
	}
	
	/**
	* Title ID
	*/
	public function title_id(){
		return $this->getVar('titleid');
	}
	public function set_title_id($title){
		return $this->setVar('titleid', $title);
	}

	/**
	* @desc Descripcion Corta
	**/
	public function descShort(){
		return $this->getVar('short');
	}

	public function setDescShort($desc){
		return $this->setVar('short',$desc);
	}

	/**
	* @desc Descripcion
	**/
	public function desc($format='s'){
		return $this->getVar('desc',$format);
	}

	public function setDesc($desc){
		return $this->setVar('desc',$desc);
	}

	/**
	* @desc Categoría
	**/
	public function category(){
		return $this->getVar('catego');
	}

	public function setCategory($cat){
		return $this->setVar('catego',$cat);
	}

	/**
	* @desc Cliente
	**/
	public function client(){
		return $this->getVar('client');
	}

	public function setClient($client){
		return $this->setVar('client',$client);
	}

	/**
	* @desc Comentario
	**/
	public function comment(){
		return $this->getVar('comment','n');
	}

	public function setComment($comment){
		return $this->setVar('comment',$comment);
	}

	/**
	* @desc  Nombre del sitio web
	**/
	public function nameSite(){
		return $this->getVar('site');
	}

	public function setNameSite($name){
		return $this->setVar('site',$name);
	}

	/**
	* @desc URL
	**/
	public function url(){
		return $this->getVar('url');
	}

	public function setUrl($url){
		return $this->setVar('url',$url);
	}

	/**
	* @desc Imagen
	**/
	public function image(){
		return $this->getVar('image');
	}

	public function setImage($image){
		return $this->setVar('image',$image);
	}


	/**
	* @desc Resaltado
	**/
	public function mark(){
		return $this->getVar('mark');
	}

	public function setMark($mark){
		return $this->setVar('mark',$mark);
	}


	/**
	* @desc Fecha de creación
	**/
	public function created(){
		return $this->getVar('created');
	}

	public function setCreated($date){
		return $this->setVar('created',$date);
	}

	/**
	* @desc Fecha de inicio del trabajo
	**/
	public function start(){
		return $this->getVar('date_start');
	}

	public function setStart($date){
		return $this->setVar('date_start',$date);
	}

	/**
	* @desc Período
	**/
	public function period(){
		return $this->getVar('period');
	}

	public function setPeriod($time){
		return $this->setVar('period',$time);
	}

	/**
	* @desc Costo
	**/
	public function cost(){
		return $this->getVar('cost');
	}

	public function setCost($cost){
		return $this->setVar('cost',$cost);
	}

	/**
	* @desc Publico
	**/
	public function isPublic(){
		return $this->getVar('public');
	}

	public function setPublic($public){
		return $this->setVar('public',$public);
	}

	/**
	* @desc Rating
	**/
	public function rating(){
		return $this->getVar('rating');
	}
	
	public function setRating($rating){
		return $this->setVar('rating',$rating);
	}
	
	/**
	* @desc Incrementar el número de visitas
	*/
	public function addView(){
		$sql = "UPDATE ".$this->db->prefix("pw_works")." SET views=views+1 WHERE id_work='".$this->id()."'";
		if (!$this->db->queryF($sql)){
			$this->addError($this->db->error());
			return false;
		}
		return true;
	}
	
	public function views(){
		return $this->getVar('views');
	}
	
	public function link(){
		global $xoopsModule, $xoopsModuleConfig;
		
		if(isset($xoopsModule) && $xoopsModule->dirname()=='works'){
			$mc = $xoopsModuleConfig;
		} else {
			$mc = RMUtilities::module_config('works');
		}
		
		$link = XOOPS_URL.'/';
		if ($mc['urlmode']){
			$link .= trim($mc['htbase'], '/').'/'.$this->title_id().'/';
		} else {
			$link .= 'modules/works/index.php?p=work&amp;id='.$this->id();
		}
		
		return $link;
	}
    
    public function get_metas(){
        
        if (empty($this->metas))
            $this->metas = PWFunctions::work_metas($this->id());
        
        return $this->metas;
        
    }

	public function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		} else {
			return $this->updateTable();
		}
	}

	public function delete(){

		//Eliminamos las imágenes
		@unlink(XOOPS_UPLOAD_PATH.'/works/'.$this->image());
		@unlink(XOOPS_UPLOAD_PATH.'/works/ths/'.$this->image());

		return $this->deleteFromTable();
	}

}
