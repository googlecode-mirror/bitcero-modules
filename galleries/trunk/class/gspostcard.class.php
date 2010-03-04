<?php
// $Id$
// --------------------------------------------------------
// Gallery System
// Manejo y creación de galerías de imágenes
// CopyRight © 2008. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.exmsystem.org
// --------------------------------------------
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License as
// published by the Free Software Foundation; either version 2 of
// the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public
// License along with this program; if not, write to the Free
// Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
// MA 02111-1307 USA
// --------------------------------------------------------
// @copyright: 2008 Red México

class GSPostcard extends EXMObject
{
	function __construct($id=null){
		$this->db =& Database::getInstance();
        $this->_dbtable = $this->db->prefix("gs_postcards");
        $this->setNew();
        $this->initVarsFromTable();
        
        if ($id==null) return;
        
     /*   if (!$this->loadValues(intval($id))) return;
        
        $this->unsetNew();
	}*/

	if (is_numeric($id)){
        	if (!$this->loadValues($id)) return;
		} else {
			$this->primary = 'code';
			if ($this->loadValues($id)) $this->unsetNew();
			$this->primary = 'id_post';
			return;
		}
        
        $this->unsetNew();
	}
	
	
	public function id(){
		return $this->getVar('id_post');
	}
	
	public function title(){
		return $this->getVar('title');
	}
	public function setTitle($value){
		return $this->setVar('title', $value);
	}
	
	public function message(){
		return $this->getVar('msg');
	}
	public function setMessage($value){
		return $this->setVar('msg', $value);
	}
	
	public function image(){
		return $this->getVar('id_image');
	}
	public function setImage($value){
		return $this->setVar('id_image', $value);
	}
	
	public function date(){
		return $this->getVar('date');
	}
	public function setDate($value){
		return $this->setVar('date', $value);
	}
	
	public function toName(){
		return $this->getVar('toname');
	}
	public function setToName($value){
		return $this->setVar('toname', $value);
	}
	
	public function toEmail(){
		return $this->getVar('tomail');
	}
	public function setToEmail($value){
		return $this->setVar('tomail', $value);
	}
	
	/**
	* @desc Nombre del remitente
	*/
	public function name(){
		return $this->getVar('fromname');
	}
	public function setName($value){
		return $this->setVar('fromname', $value);
	}
	/**
	* @desc Email del Remitente
	*/
	public function email(){
		return $this->getVar('frommail');
	}
	public function setEmail($value){
		return $this->setVar('frommail', $value);
	}
	
	public function uid(){
		return $this->getVar('uid');
	}
	public function setUid($value){
		return $this->setVar('uid', $value);
	}
	
	public function ip(){
		return $this->getVar('ip');
	}
	public function setIp($value){
		return $this->setVar('ip', $value);
	}
	
	public function viewed(){
		return $this->getVar('view');
	}
	public function setViewed($value){
		return $this->setVar('view', $value);
	}

	/**
	* @desc Código de la postal
	**/
	public function code(){
		return $this->getVar('code');
	}

	public function setCode($code){
		return $this->setVar('code',$code);
	}
	
	public function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		} else {
			return $this->updateTable();
		}
	}
	
	public function url(){
		global $mc;
		
		$url = XOOPS_URL.'/modules/galleries/';
		$url .= $mc['urlmode'] ? "postcard/".$this->id().'/' : 'postcard.php?id='.$this->id();
		
		return $url;
		
	}
	
	public function delete(){
		return $this->deleteFromTable();
	}
	
}

?>
