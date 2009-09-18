<?php
// $Id: mwbookmark.class.php 16 2009-09-13 01:38:59Z i.bitcero $
// --------------------------------------------------------
// MyWords
// Manejo de Artículos
// Author: BitC3R0
// http://www.redmexico.com.mx
// http://www.exmsystem.net
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

class MWBookmark extends RMObject
{
    function __construct($id=null){
        $this->db =& Database::getInstance();
        $this->_dbtable = $this->db->prefix("mw_bookmarks");
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
        
    }
    
    public function id(){
        return $this->getVar('id_book');
    }
    
    public function name(){
        return $this->getVar('title');
    }
    public function setName($value){
        return $this->setVar('title', $value);
    }
    
    public function url(){
        return $this->getVar('url');   
    }
    public function setUrl($value){
        return $this->setVar('url', formatURL($value));
    }
    
    public function icon(){
        return $this->getVar('icon');   
    }
    public function setIcon($value){
        return $this->setVar('icon', $value);
    }
    
    public function active(){
        return $this->getVar('active');   
    }
    public function setActive($value){
        return $this->setVar('active', $value);
    }
    
    public function text(){
        return $this->getVar('alt');   
    }
    public function setText($value){
        return $this->setVar('alt', $value);
    }
    
    /**
    * @desc Crea el enlace para agregar el elemento a la red social
    */
    public function link($title, $url, $desc=''){
        $link = str_replace('{TITLE}', urlencode($title), $this->url());
        $link = str_replace('{URL}', urlencode($url), $link);
        $link = str_replace('{DESC}', urlencode($desc), $link);
        return $link;
    }
    
    function save(){
        if($this->isNew()){
            return $this->saveToTable();
        } else {
            return $this->updateTable();
        }
    }
    
    function delete(){
        
        // eliminamos el archivo de icono
        @unlink(XOOPS_UPLOAD_PATH.'/mywords/icons/'.$this->icon());
        
        return $this->deleteFromTable();
        
    }
    
}
?>
