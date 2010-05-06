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
 * Clase para el manejo de páginas
 */
class QPPage extends RMObject
{
	private $myts = '';
	private $metas = array();
	/**
	 * Constructor de la clase
	 * Carga los valores de un post específico o prepara
	 * las variables para la creación de uno nuevo
	 * Se puede establecer el id del post o bien la fecha y
	 * título amigable del post.
	 * @param int $id Identificador numérico del post
	 * @param string $int Titulo amigable del post
	 */
	function __construct($id=null){
		$this->db =& Database::getInstance();
		$this->myts =& MyTextSanitizer::getInstance();
		$this->_dbtable = $this->db->prefix("qpages_pages");
		$this->setNew();
		$this->initVarsFromTable();
		
		if ($id==null) return;
	
		if ($this->loadValues($id)){
			$this->unsetNew();
			return true;
		} else {
			$this->primary = "titulo_amigo";
			if ($this->loadValues($id))	$this->unsetNew();
			
			$this->primary = "id_page";
			return;
		}		
		
	}
	/**
	 * Funciones para manipular los datos del envío
	 */
	public function getID(){
		return $this->getVar('id_page');
	}
	public function setTitle($value){
		return $this->setVar('titulo', $value);
	}
	public function getTitle(){
		return $this->getVar('titulo');
	}
	public function setFriendTitle($value){
		return $this->setVAr('titulo_amigo', $value);
	}
	public function getFriendTitle(){
		return $this->getVar('titulo_amigo');
	}
	public function setAccess($value){
		return $this->setVar('acceso',$value);
	}
	public function getAccess(){
		return $this->getVar('acceso');
	}
	public function setDate($value){
		return $this->setVar('fecha', $value);
	}
	public function getDate(){
		return $this->getVar('fecha');
	}
	public function setModDate($value){
		return $this->setVar('modificado', $value);
	}
	public function getModDate(){
		return $this->getVar('modificado');
	}
	public function setText($value){
		return $this->setVar('texto', $value);
	}
	public function getText($format = 's'){
		return $this->getVar('texto', $format);
	}
	public function setCategory($value){
		return $this->setVar('cat',$value);
	}
	public function getCategory(){
		return $this->getVar('cat');
	}
	public function setDescription($value){
		return $this->setVar('desc', $value);
	}
	public function getDescription(){
		return strip_tags($this->getVar('desc'));
	}
	public function setOrder($value){
		return $this->setVar('porder', $value);
	}
	public function order(){
		return $this->getVar('porder');
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
	
	public function image(){
		return $this->getVar('doimage');
	}
	public function setImage($value){
		return $this->setVar('doimage', intval($value));
	}
	
	public function uid(){
		return $this->getVar('uid');
	}
	public function setUid($uid){
		return $this->setVar('uid', $uid);
	}
	
	public function type(){
		return $this->getVar('type');
	}
	public function setType($type){
		return $this->setVar('type', $type);
	}
	
	public function url(){
		return $this->getVar('url');
	}
	public function setURL($url){
		return $this->setVar('url', $url);
	}
	
	/**
	 * Funciones para el control de lecturas
	 */
	public function setReads($value){
		return $this->setVar('lecturas', $value);
	}
	public function addRead(){
		if ($this->db->queryF("UPDATE ".$this->_dbtable." SET lecturas=lecturas+1 WHERE id_page='".$this->getID()."'")){
			$this->setReads($this->getReads() + 1);
			return true;
		} else {
			return false;
		}
	}
	public function getReads(){
		return $this->getVar('lecturas');
	}
	/**
	 * Mostrar en el Menú
	 */
	public function setInMenu($value){
		return $this->setVar('menu', $value);
	}
	public function getInMenu(){
		return $this->getVar('menu');
	}
	/**
	 * Obtiene el enlace permanente al artículo
	 */
	public function getPermaLink(){
		global $mc;
		$rtn = QP_URL.'/';
		$rtn .= $mc['links']==0 ? 'page.php?page='.$this->getFriendTitle() : $this->getFriendTitle().'/';
		return $rtn;
	}
	/**
	 * Establecemos los grupos con acceso
	 * @param string $grupos Lista delimitada por comas con los identificadores de los grupos
	 * @param array $grupos Matriz con los identificadores de los grupos
	 * @return bool
	 */
	public function setGroups($grupos){
		if (is_array($grupos)){
			
			$gstring = '';
			foreach ($grupos as $k){
				$gstring .= $gstring=='' ? "$k" : ",$k";
			}
			
		} else {
			$gstring = str_replace(" ","", trim($grupos));
		}
		
		return $this->setVar('grupos', $gstring);
	}
	/**
	 * Obtenemos los identificadores de los grupos con acceso a esta página
	 * @param bool $asArray Inidica si los grupos se devuelven como una matriz o como una cadena
	 * @return array
	 * @return string
	 */
	public function getGroups($asArray=true){
		
		if ($asArray){
			$grupos = explode(",", $this->getVar('grupos'));
			return $grupos;
		} else {
			return $this->getVar('grupos');
		}
		
	}
	
	/**
	* Meta data
	*/
	private function load_meta(){
		if (!empty($this->metas)) return;

		$result = $this->db->query("SELECT name,value FROM ".$this->db->prefix("qpages_meta")." WHERE page='".$this->getID()."'");
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
	 
	
	/**
	 * Actualizamos los valores en la base de datos
	 */
	public function update(){
		
		if (!empty($this->metas)) $this->saveMetas();
		return $this->updateTable();
		
	}
	/**
	 * Guardamos los datos en la base de datos
	 */
	public function save(){	
		
		$return = $this->saveToTable();
		if ($return) $this->setVar('id_page', $this->db->getInsertId());
		if (!empty($this->metas)) $this->saveMetas();
		return $return;
	}
	/**
	 * Elimina un artículo y todos sus comentarios de
	 * la base de datos.
	 */
	public function delete(){
		return $this->deleteFromTable();
	}
	
	/**
	* Save existing meta
	*/
	private function saveMetas(){
		
		$this->db->queryF("DELETE FROM ".$this->db->prefix("qpages_meta")." WHERE page='".$this->getID()."'");
		if (empty($this->metas)) return true;
		$sql = "INSERT INTO ".$this->db->prefix("qpages_meta")." (`name`,`value`,`page`) VALUES ";
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
}
