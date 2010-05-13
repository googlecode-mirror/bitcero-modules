<?php
// $Id$
// --------------------------------------------------------------
// Foros EXMBB
// Módulo para el manejo de Foros en EXM
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.xoopsmexico.net
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
// --------------------------------------------------------------
// @copyright: 2007 - 2008 Red México

/**
* @desc Clase para el manejo de los anuncios en EXM BB
*/
class BBAnnouncement extends EXMObject
{
	public function __construct($id = null){
		
		$this->db =& Database::getInstance();
        $this->_dbtable = $this->db->prefix("exmbb_announcements");
        $this->setNew();
        $this->initVarsFromTable();
        
        if (!isset($id)) return;
        
        if (!$this->loadValues($id)) return;
        
        $this->unsetNew();
		
	}
	
	public function id(){
		return $this->getVar('id_an');
	}
	
	/**
	* @desc Obtiene el texto del anuncio
	* @param string Formato del texto devuelto
	* @return string
	*/
	public function text($format = 's'){
		return $this->getVar('text', $format);
	}
	public function setText($value){
		return $this->setVar('text', $value);
	}
	/**
	* @desc Id de quien creo el anuncio
	* @return int
	*/
	public function by(){
		return $this->getVar('by');
	}
	public function setBy($value){
		return $this->setVar('by', $value);
	}
	
	public function byName(){
		return $this->getVar('byname');
	}
	public function setByName($value){
		return $this->setVar('byname', $value);
	}
	
	public function date(){
		return $this->getVar('date');
	}
	public function setDate($value){
		return $this->setVar('date', $value);
	}
	
	public function expire(){
		return $this->getVar('expire');
	}
	public function setExpire($value){
		return $this->setVar('expire', $value);
	}
	
	public function where(){
		return $this->getVar('where');
	}
	public function setWhere($value){
		return $this->setVar('where', $value);
	}
	
	public function forum(){
		return $this->getVar('forum');
	}
	public function setForum($value){
		return $this->setVar('forum', $value);
	}
	
	public function html(){
		return $this->getVar('dohtml');
	}
	public function setHtml($value){
		return $this->setVar('dohtml', $value);
	}
	
	public function bbcode(){
		return $this->getVar('doxcode');
	}
	public function setBBCode($value){
		return $this->setVar('doxcode', $value);
	}
	
	public function doImage(){
		return $this->getVar('doimage');
	}
	public function setDoImage($value){
		return $this->setVar('doimage', $value);
	}
	
	public function wrap(){
		return $this->getVar('dobr');
	}
	public function setWrap($value){
		return $this->setVar('dobr', $value);
	}
	
	public function smiley(){
		return $this->getVar('dosmiley');
	}
	public function setSmiley($value){
		return $this->setVar('dosmiley', $value);
	}
	
	function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		} else {
			return $this->updateTable();
		}
	}
	
	function delete(){
		return $this->deleteFromTable();
	}
	
}
?>
