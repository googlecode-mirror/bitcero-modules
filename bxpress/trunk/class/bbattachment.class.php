<?php
// $Id: bbattachment.class.php 45 2007-12-15 03:17:26Z BitC3R0 $
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
* @desc Clase para el manejo de archivos adjuntos de mensajes
*/
class BBAttachment extends EXMObject
{
	private $dir = '';
	
	public function __construct($id = null, $dir = ''){
		$this->db =& Database::getInstance();
        $this->_dbtable = $this->db->prefix("exmbb_attachments");
        $this->setNew();
        $this->initVarsFromTable();
        
        if ($dir=='' || !file_exists($dir)){
        	global $xoopsModuleConfig;
        	$dir = $xoopsModuleConfig['attachdir'];
        }
        
        $this->dir = $dir;
        
        if (!isset($id)) return;
        
        if (!$this->loadValues($id)) return;
        
        $this->unsetNew();

	}
	
	public function id(){
		return $this->getVar('attach_id');
	}
	
	public function post(){
		return $this->getVar('post_id');
	}
	public function setPost($value){
		return $this->setVar('post_id', $value);
	}
	
	public function file(){
		return $this->getVar('file');
	}
	public function setFile($value){
		return $this->setVar('file', $value);
	}
	
	public function name(){
		return $this->getVar('name');
	}
	public function setName($value){
		return $this->setVar('name', $value);
	}
	
	public function mime(){
		return $this->getVar('mimetype');
	}
	public function setMime($value){
		return $this->setVar('mimetype', $value);
	}
	
	public function date(){
		return $this->getVar('date');
	}
	public function setDate($value){
		return $this->setVar('date', $value);
	}
	
	public function size(){		
		return @filesize($this->dir.'/'.$this->file());
	}
	
	/**
	* @desc Obtiene la URL del icono correcto para el nombre de archivo
	*/
	public function getIcon(){
		if (file_exists(XOOPS_ROOT_PATH.'/modules/exmbb/images/ftypes/'.$this->extension().'.png')){
			return XOOPS_URL.'/modules/exmbb/images/ftypes/'.$this->extension().'.png';
		} else {
			return XOOPS_URL.'/modules/exmbb/images/ftypes/default.png';
		}
	}
	
	public function downloads(){
		return $this->getVar('downloads');
	}
	public function setDownloads($value){
		return $this->setVar('downloads', $value);
	}
	
	public function addDownload(){
		$this->setDownloads($this->downloads() + 1);
	}
	
	public function extension(){
		$pos = strrpos(strtolower($this->file()),'.');
		if ($pos<=0) return;
		return substr($this->file(), $pos+1);
	}
	
	public function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		} else {
			return $this->updateTable();
		}
	}
	
	public function delete(){
		
		@unlink($this->dir.'/'.$this->file());
		
		return $this->deleteFromTable();
		
	}
	
}
?>
