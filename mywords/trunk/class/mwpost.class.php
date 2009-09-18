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
	 * Inidica si continua el texto
	 */
	private $homenext = false;
	/**
	* Meta data container
	*/
	private $metas = array();
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
	* Get meta data from post
	*/
	public function fields(){
		
	}
	
	public function getText($full=true){
		if ($full){
			return str_replace('[home]','<a name="moreMwPost"></a>',$this->getVar('texto'));
		} else {
			return $this->getVar('texto');
		}
	}
	public function setStatus($value){
		return $this->setVAr('estado',$value);
	}
	public function getStatus(){
		return $this->getVar('estado');
	}
	public function setComments($value){
		return $this->setVar('comentarios', $value);
	}
	public function getComments(){
		return $this->getVar('comentarios');
	}
	public function setTrackBacks($value){
		$rtn = '';
		if (is_array($value)){
			foreach ($value as $k){
				if ($rtn==''){
					$rtn .= $k;
				} else {
					$rtn .= " $k";
				}
			}
		} else {
			$rtn = $value;
		}
		return $this->setVar('toping', $rtn);
	}
	public function getTrackBacks($asArray=true){
		if ($asArray){
			return explode(" ", $this->getVar('toping'));
		} else {
			return $this->getVar('toping');
		}
	}
	public function addPinged($uri){
		if (trim($uri)=='') return;
		return $this->setVar('pinged', $this->getVar('pinged') . " " . $uri);
	}
	public function getPinged($asArray = true){
		if ($asArray){
			return explode(" ", $this->getVar('pinged'));
		} else {
			return $this->getVar('pinged');
		}
	}
	/**
	 * Incrementa en uno el numero de comentarios
	 */
	public function addComent(){
		$this->setComments($this->getComments() + 1);
		$this->update();
	}
	/**
	 * Obtiene el objeto usuario a partir del autor
	 */
	public function &getAuthorUser(){
		$user = new XoopsUser($this->getAuthor());
		return $user;
	}
	/**
	 * Establece si el artículo recibe pings de trackbacks
	 */
	public function setAllowPings($value){
		return $this->setVar('allowpings', $value);
	}
	public function getAllowPings(){
		return $this->getVar('allowpings');
	}
	/**
	 * Funciones para el control de lecturas
	 */
	public function addReads(){
		$this->setVar('lecturas', $this->getVar('lecturas') + 1);
		$this->db->queryF("UPDATE ".$this->db->prefix("mw_posts")." SET lecturas='".($this->getVar('lecturas'))."' 
				WHERE id_post='".$this->getID()."'");
	}
	public function getReads(){
		return $this->getVar('lecturas');
	}
	/**
	 * Devuelve el texto de avance para el artículo
	 * @param int $words Número máximo de palabras
	 * @return string
	 */
	public function getHomeText(){
		$rtn = array();
		$rtn = explode("[home]", $this->getText(false));
		$this->homenext = true;
		return $rtn[0];
	}
	/**
	 * Indica si el texto solo es el avance y continúa
	 * @return bool
	 */
	public function moretext(){
		return $this->homenext;
	}
	/**
	 * Obtiene las catgorías a las que pertenece el artículo
	 * @param int Tipo de Resultado (0 Todos los indices, 1 solo ID, 2 Permalinks)
	 * @return string, array
	 */
	public function categos($as = 0){
		global $mc;
		
		$tbl1 = $this->db->prefix("mw_categos");
		$tbl2 = $this->db->prefix("mw_catpost");
		
		if (empty($this->categos)){	
			$result = $this->db->query("SELECT a.* FROM $tbl1 a,$tbl2 b WHERE b.post='".$this->getID()."' AND a.id_cat=b.cat GROUP BY b.cat");
			$rtn = array();
			while ($row = $this->db->fetchArray($result)){
				$this->lcats[] = $row;
				$this->categos[] = $row['id_cat'];
			}
		}
		if ($as==0){ return $this->lcats; }
		if ($as==1){ return $this->categos; }
		// Generamos la lista junto con enlaces
		$categos = '';
		foreach ($this->lcats as $row){
			$catego = new MWCategory();
			$catego->assignVars($row);
			if ($categos==''){
				$categos .= "<a href='".mw_get_url();
			} else {
				$categos .= ", <a href='".mw_get_url();
			}
			$categos .= ($mc['permalinks']==1 ? '?cat='.$catego->getID() : ($mc['permalinks']==2 ? 'category/'.$catego->getPath() : 'category/'.$catego->getID()))."'>".$catego->getName()."</a>";
		}
		return $categos;
	}
	/**
	 * Se asigna una nueva categoría al artículo
	 * @param int $cat Identificador de la categoría
	 */
	public function addToCatego($cat){
		if (empty($this->categos)) $this->categos();
		if (in_array($cat, $this->categos)) return;
		$this->categos[] = $cat;
	}
	/**
	 * Se asignan múltiples categorías al post
	 * @param array $cats Identificadores de las categorías
	 */
	public function addToCategos($cats){
		if (!is_array($cats)) return;
		
		$this->categos = array();
		
		foreach ($cats as $k){
			if (!is_numeric($k)) continue;
			if (!in_array($k, $this->categos) )$this->categos[] = $k;
		}
	}
	/**
	 * Devuelve los nombres de las categorías a las que pertenece
	 * el post actual
	 * @param bool $asList Detemina si se muestra en forma de lista o de array
	 * @param string $delimiter Delimitador para la lista
	 * @return string or array
	 */
	public function getCategoNames($asList=true, $delimiter=','){
		$sql = "SELECT * FROM ".$this->db->prefix("mw_categos")." WHERE";
		$where = '';
		foreach ($this->categos() as $k){
			if ($where==''){
				$where .= " id_cat='$k'";
			} else {
				$where .= " OR id_cat='$k'";
			}
		}
		$sql .= $where;
		$result = $this->db->query($sql);
		$rtn = $asList ? '' : array();
		while ($row = $this->db->fetchArray($result)){
			if ($asList){
				if ($rtn == ''){
					$rtn .= $row['nombre'];
				} else {
					$rtn .= "$delimiter $row[nombre]";
				}
			} else {
				$rtn[] = $row['nombre'];
			}
		}
		return $rtn;
	}
	/**
	 * Obtiene el enlace permanente al artículo
	 */
	public function getPermaLink(){
		global $mc;
		$day = date('d', $this->getDate());
		$month = date('m', $this->getDate());
		$year = date('Y', $this->getDate());
		$rtn = mw_get_url();
		$rtn .= $mc['permalinks']==1 ? '?post='.$this->getID() : ($mc['permalinks']==2 ? "$day/$month/$year/".$this->getFriendTitle()."/" : "post/".$this->getID());
		return $rtn;
	}
	/**
	 * Obtiene el texto que se enviará en los tracbacks
	 */
	public function setExcerpt($text){
		return $this->setVar('excerpt', $text);
	}
	public function getExcerpt(){
		return $this->getVar('excerpt');
	}
	/**
	 * Obtiene la imágen para el bloque (si existe)
	 */
	public function setBlockImage($value){
		$this->setVar('blockimg', $value);
	}
	public function getBlockImage(){
		return $this->getVar('blockimg');
	}
	/**
	 * Comprobamos si un usuario tiene permisos de lectura
	 * para un artículo
	 */
	public function readAccess($xu=null){
		global $xoopsUser;
		
		if ($xu==null){
			$xu = $xoopsUser;
		}
		
		$grupos = isset($xu) && get_class($xu)=='XoopsUser' ? $xu->getGroups() : array(0);
		$ret = false;
		
		foreach ($this->categos() as $k){
			$catego = new MWCategory();
			$catego->assignVars($k);
			if ($catego->groupsWithAccess($grupos)) $ret = true;
		}
		
		return $ret;
		
	}
	
	public function html(){
		return $this->getVar('dohtml');
	}
	public function setHTML($value){
		return $this->setVar('dohtml', intval($value));
	}
	
	public function smiley(){
		return $this->getVar('dosmiley');
	}
	public function setSmiley($value){
		return $this->setVar('dosmiley', intval($value));
	}
	
	public function xcode(){
		return $this->getVar('doxcode');
	}
	public function setXCode($value){
		return $this->setVar('doxcode', intval($value));
	}
	
	public function br(){
		return $this->getVar('dobr');
	}
	public function setBR($value){
		return $this->setVar('dobr', intval($value));
	}
	
	public function doimage(){
		return $this->getVar('doimage');
	}
	public function setDoImage($value){
		return $this->setVar('doimage', intval($value));
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
		
		$this->saveCategos();
		$this->saveMetas();
		
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
		$this->saveCategos();
		$this->saveMetas();
		return true;
	}
	
	/**
	* Save existing meta
	*/
	private function saveMetas(){
		$this->db->queryF("DELETE FROM ".$this->db->prefix("mw_meta")." WHERE post='".$this->getID()."'");
		if (empty($this->metas)) return true;
		$sql = "INSERT INTO ".$this->db->prefix("mw_meta")." (`name`,`value`,`post`) VALUES ";
		$values = '';
		foreach ($this->metas as $name => $value){
			$values .= ($values=='' ? '' : ',')."('".MyTextSanitizer::addSlashes($name)."','".MyTextSanitizer::addSlashes($value)."','".$this->getID()."')";
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
	public function saveCategos(){
		if (empty($this->categos)) return true;
		$this->db->queryF("DELETE FROM ".$this->db->prefix("mw_catpost")." WHERE post='".$this->getID()."'");
		$sql = "INSERT INTO ".$this->db->prefix("mw_catpost")." (`post`,`cat`) VALUES ";
		foreach ($this->categos as $k){
			$sql .= "('".$this->getID()."','$k'), ";
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
	 * Elimina un artículo y todos sus comentarios de
	 * la base de datos.
	 */
	public function delete(){
		global $xoopsModule;
		$sql = "DELETE FROM ".$this->db->prefix("mw_trackbacks")." WHERE post='".$this->getID()."'";
		if (!$this->db->queryF($sql)){
			$this->addError($this->db->error());
		}
		$sql = "DELETE FROM ".$this->db->prefix("mw_catpost")." WHERE post='".$this->getID()."'";
		if (!$this->db->queryF($sql)){
			$this->addError($this->db->error());
		}
		
		if ($this->getBlockImage()!=''){
			@unlink(XOOPS_UPLOAD_PATH.'/mywords/'.$this->getBlockImage());
		}
		
		$this->deleteFromTable();
		if ($this->errors()!=''){ return false; } else { return true; }
		
	}
	/**
	 * Devuelve el numero de trackbacks recibidos
	 */
	public function setTBCount($value){
		return $this->setVAr('trackbacks', $value);
	}
	public function getTBCount(){
		return $this->getVar('trackbacks');
	}
	/**
	 * Obtenemos todos los trackbacks
	 * @param bool $result Inidica si los comentarios son devueltos como un resultado de MySQL
	 * @return array
	 */
	public function loadTrackbacks($result=false){
		$res = $this->db->query("SELECT * FROM ".$this->db->prefix("mw_trackbacks")." WHERE post='".$this->getID()."'");
		if ($result) return $res;
		$ret = array();
		while ($row = $this->db->fetchArray($res)){
			$ret[] = $row;
		}
		return $ret;
	}
}

?>