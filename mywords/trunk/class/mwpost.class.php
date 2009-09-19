<?php
// $Id: mwpost.class.php 42 2009-09-15 20:39:58Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: bitc3r0@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class MWPost extends RMObject
{
	private $myts = '';
	/**
	 * Categorias a las que pertenece el artículo
	 */
	private $categos = array();
	private $lcats = array();
	/**
	* Meta data container
	*/
	private $metas = array();
    /**
    * Tags container
    */
    private $tags = array();
	/**
	 * Constructor de la clase
	 * Carga los valores de un post específico o prepara
	 * las variables para la creación de uno nuevo
	 * Se puede establecer el id del post o bien la fecha y
	 * título amigable del post.
	 * @param int $id Identificador numérico del post
	 * @param date $date Fecha del post
	 * @param string $titulo Titulo amigable del post
	 */
	function __construct($id=null){
		$this->db =& Database::getInstance();
		$this->myts =& MyTextSanitizer::getInstance();
		$this->_dbtable = $this->db->prefix("mw_posts");
		$this->setNew();
		$this->initVarsFromTable();
		
		if ($id==null) return;
	
		if ($this->loadValues($id)){
			$this->unsetNew();
			return true;
		} else {
			return;
		}		
		
	}
	/**
	 * Funciones para manipular los datos del envío
	 */
	public function id(){
		return $this->getVar('id_post');
	}
	
    /**
    * Get content for current post according to given options
    * 
    * @param bool Indicates if only text before <!--more--> tag is returned
    * @param bool Indicates wich page will be returned. If there are only a page then return all
    * @return string
    */
	public function content($advance=true, $page=0){
		
        $content = $this->getVar('content', 'n');
        $content = explode("<!--nextpage-->", $content);
        
        $pages = count($content);
        
        if ($advance){
            $advance = explode('<!--more-->', $content[0]);
            $advance = str_replace("<!--nextpage-->","", $advance);
            return TextCleaner::getInstance()->to_display($advance);
        }
        
        if ($page>0){
            $page--;
            if ($pages<=1) return TextCleaner::getInstance()->to_display(str_replace("<!--more-->","",$content[0]));
            
            if (($pages-1)<=$page) return TextCleaner::getInstance()->to_display(str_replace("<!--more-->","",$content[$pages-1]));
            
            return TextCleaner::getInstance()->to_display(str_replace("<!--more-->","",$content[$page]));
        }
        
        $content = str_replace("<!--more-->","",$content[0]);
        
	}
	
	/**
	 * Incrementa en uno el numero de comentarios
	 */
	public function add_comment(){
		$this->setVar('comments', $this->getVar('comments') + 1);
        $this->db->queryF("UPDATE ".$this->db->prefix("mw_posts")." SET comments='".($this->getVar('comments'))."' 
                WHERE id_post='".$this->id()."'");
	}
	
	
	/**
	 * Funciones para el control de lecturas
	 */
	public function add_read(){
		$this->setVar('reads', $this->getVar('reads') + 1);
		$this->db->queryF("UPDATE ".$this->db->prefix("mw_posts")." SET reads='".($this->getVar('reads'))."' 
				WHERE id_post='".$this->id()."'");
	}

	/**
	 * Obtiene las catgorías a las que pertenece el artículo
	 * @param bool Indicates if returns all data fro categories. False returns only ids
	 * @return string, array
	 */
	public function get_categos($all = true){
		global $mc;
		
		$tbl1 = $this->db->prefix("mw_categos");
		$tbl2 = $this->db->prefix("mw_catpost");
		
		if (empty($this->categos)){	
			$result = $this->db->query("SELECT a.* FROM $tbl1 a,$tbl2 b WHERE b.post='".$this->id()."' AND a.id_cat=b.cat GROUP BY b.cat");
			$rtn = array();
			while ($row = $this->db->fetchArray($result)){
				$this->lcats[] = $row;
				$this->categos[] = $row['id_cat'];
			}
		}
        
        if ($all)
            return $this->lcats;
        else
            return $this->categos;
		
	}
	/**
	 * Add a new category
	 * @param int|array Category ID or array with categories ID
	 */
	public function add_categories($cat){
		if (empty($this->categos)) $this->get_categos();
        
        if (!is_array($cat)) $cat = array($cat);
        
        foreach($cat as $id){
            if (in_array($id, $this->categos)) continue;
            $this->categos[] = $id;
        }
	}

	/**
	 * Devuelve los nombres de las categorías a las que pertenece
	 * el post actual
	 * @param bool $asList Detemina si se muestra en forma de lista o de array
	 * @param string $delimiter Delimitador para la lista
	 * @return string or array
	 */
	public function get_categories_names($asList=true, $delimiter=','){
		
        $rtn = $asList ? '' : array();
        
		foreach ($this->lcats as $cat){
			if ($asList){
				$rtn .= $rtn == '' ? $row['nombre'] : "$delimiter $cat[name]";
			} else {
				$rtn[] = $row['nombre'];
			}
		}
		return $rtn;
	}
    
    /**
    * Add Tags
    * @param array|string Tags to add
    */
    public function add_tags($tags){
        $tags = MWFunctions::add_tags($tags);

        $this->tags = $tags;
    }
    
	/**
	 * Obtiene el enlace permanente al artículo
	 */
	public function permalink(){
		global $mc;
		$day = date('d', $this->getVar('pubdate'));
		$month = date('m', $this->getVar('pubdate'));
		$year = date('Y', $this->getVar('pubdate'));
		$rtn = MWFunctions::get_url();
		$rtn .= $mc['permalinks']==1 ? '?post='.$this->id() : ($mc['permalinks']==2 ? "$day/$month/$year/".$this->getVar('shortname','n')."/" : "post/".$this->id());
		return $rtn;
	}
	
	/**
	* Meta data
	*/
	private function load_meta(){
		if (!empty($this->metas)) return;

		$result = $this->db->query("SELECT name,value FROM ".$this->db->prefix("mw_meta")." WHERE post='".$this->getID()."'");
		while($row = $this->db->fetchArray($result)){
			$this->metas[$row['name']] = $row['value'];
		}
	}
	
	/**
	* Get metas from post.
	* If a meta name has not been provided then return all metas
	* @param string Meta name
	* @return string|array
	*/
	public function get_meta($name=''){
		$this->load_meta();
		
		if (trim($name)=='')
			return $this->metas;
		
		if(!isset($this->metas[$name]))
			return false;
		
		return $this->metas[$name];
		
	}
	
	/**
	* Add or modify a field
	* @param string Meta name
	* @param mixed Meta value
	* @return none
	*/
	public function add_meta($name, $value){
		if (trim($name)=='' || trim($value)=='') return;
		
		$this->metas[$name] = $value;
	}
	
	/**
	 * Actualizamos los valores en la base de datos
	 */
	public function update(){
		
		if (!$this->updateTable()) return false;
		
		$this->save_categories();
		$this->save_metas();
		
		if ($this->errors()!='')
			return false;
		
		return true;
		
	}
	/**
	 * Guardamos los datos en la base de datos
	 */
	public function save(){	
		
		if (!$this->saveToTable()) return false;
		$this->setVar('id_post', $this->db->getInsertId());
		$this->save_categories();
		$this->save_metas();
        $this->save_tags();
		return true;
	}
	
	/**
	* Save existing meta
	*/
	private function save_metas(){
		$this->db->queryF("DELETE FROM ".$this->db->prefix("mw_meta")." WHERE post='".$this->id()."'");
		if (empty($this->metas)) return true;
		$sql = "INSERT INTO ".$this->db->prefix("mw_meta")." (`name`,`value`,`post`) VALUES ";
		$values = '';
		foreach ($this->metas as $name => $value){
			$values .= ($values=='' ? '' : ',')."('".MyTextSanitizer::addSlashes($name)."','".MyTextSanitizer::addSlashes($value)."','".$this->id()."')";
		}
		
		if ($this->db->queryF($sql.$values)){
			return true;
		} else {
			$this->addError($this->db->error());
			return false;
		}
	}
	/**
	 * Almacena las categorías a las que pertenece el artículo
	 */
	public function save_categories(){
		if (empty($this->categos)) return true;
		$this->db->queryF("DELETE FROM ".$this->db->prefix("mw_catpost")." WHERE post='".$this->id()."'");
		$sql = "INSERT INTO ".$this->db->prefix("mw_catpost")." (`post`,`cat`) VALUES ";
		foreach ($this->categos as $k){
			$sql .= "('".$this->id()."','$k'), ";
		}
		$sql = substr($sql, 0, strlen($sql) - 2);
		if ($this->db->queryF($sql)){
			return true;
		} else {
			$this->addError($this->db->error());
			return false;
		}
	}
    
    /**
    * Save tags
    * @return bool
    */
    public function save_tags(){
        if (!$this->isNew()){
            $this->db->queryF("DELETE FROM ".$this->db->prefix("mw_tagspost")." WHERE post='".$this->id()."'");
        }
        $sql = "INSERT INTO ".$this->db->prefix("mw_tagspost")." (`post`,`tag`) VALUES ";
        $sa = '';
        foreach ($this->tags as $tag){
            $sa .= $sa=='' ? "('$tag','".$this->id()."')" : ",('$tag','".$this->id()."')";
        }

        if ($this->db->queryF($sql.$sa)){
            return true;
        } else {
            $this->addError($this->db->error());
            return false;
        }
    
    }
	/**
	 * Elimina un artículo y todos sus comentarios de
	 * la base de datos.
	 */
	public function delete(){
		global $xoopsModule;
		$sql = "DELETE FROM ".$this->db->prefix("mw_catpost")." WHERE post='".$this->id()."'";
		if (!$this->db->queryF($sql)){
			$this->addError($this->db->error());
		}
        
        $sql = "DELETE FROM ".$this->db->prefix("mw_meta")." WHERE post='".$this->id()."'";
        if (!$this->db->queryF($sql)){
            $this->addError($this->db->error());
        }
		
		$this->deleteFromTable();
		if ($this->errors()!=''){ return false; } else { return true; }
		
	}

}
