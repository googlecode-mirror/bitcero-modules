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
// @author: BitC3R0
// @copyright: 2007 - 2008 Red México

class BBCategory extends EXMObject
{

	private $_tbl = '';
	private $_found = false;
	private $_grupos = array();

	function __construct($id=null){
		$this->db =& Database::getInstance();
        $this->_dbtable = $this->db->prefix("exmbb_categories");
        $this->setNew();
        $this->initVarsFromTable();
        
        $this->setVarType('groups', XOBJ_DTYPE_ARRAY);
		
        if (!isset($id)) return;
		/**
		 * Cargamos los datos de la categoría seleccionada
		 */
        if (is_numeric($id)){
		    if (!$this->loadValues($id)) return;     
            $this->unsetNew();
        } else {
            $this->primary = 'friendname';
            if ($this->loadValues($id)) $this->unsetNew();
            $this->primary = 'id_cat';   
        }
        
	}
	/**
    * @desc Métodos para acceder a las propiedades
    */
    function id(){
        return $this->getVar('id_cat');   
    }
    
    function title(){
        return $this->getVar('title');
    }
    function setTitle($value){
        return $this->setVar('title', $value);   
    }
    
    function description(){
        return $this->getVar('description');
    }
    function setDescription($value){
        return $this->setVar('description', $value);   
    }
    
    function order(){
        return $this->getVar('order');
    }
    function setOrder($value){
        return $this->setVar('order', $value);   
    }
    
    function status(){
        return $this->getVar('status');
    }
    function setStatus($value){
        return $this->setVar('status', $value);   
    }
    
    function showDesc(){
        return $this->getVar('showdesc');
    }
    function setShowDesc($value){
        return $this->setVar('showdesc', $value);   
    }
    
    function groups(){
        return $this->getVar('groups');
    }
    function setGroups($value){
        return $this->setVar('groups', $value);   
    }
    
    function friendName(){
        return $this->getVar('friendname');
    }
    function setFriendName($value){
        return $this->setVar('friendname', $value);   
    }
    /**
    * @desc Obtiene los foros que pertenecen a esta categoría
    */
    
    /**
    * @desc Comprueba que un grupo tenga permisos de acceso a esta
    * categoría
    * @param int $gid Id del Grupo
    * @param array $gid Array con ids de grupos
    * @return bool
    */
    function groupAllowed($gid){
        $groups =& $this->getVar('groups');
        
        if (in_array(0, $groups)) return true;
        
        if (!is_array($gid)) return in_array($gid, $groups);
        foreach($gid as $id){
            if (in_array($id, $groups)) return true;
        }
        
        return false;
    }
    
    /**
    * @desc Almacena los valores de la categoría
    */
    function save(){
        if ($this->isNew()){
            return $this->saveToTable();
        } else {
            return $this->updateTable();   
        }
    }



    /**
    * @desc Elimina las categorías
    **/
     function delete(){
	
	//Eliminamos foros que pertenecen a la categoria
	$sql="DELETE FROM ".$this->db->prefix('exmbb_forums')." WHERE cat=".$this->id();
	$result=$this->db->queryF($sql);
	
	if (!$result) return false;
	
	return $this->deleteFromTable();
	
     }
    
}

/**
* @desc Manejador para las categorías
*/
class BBCategoryHandler
{
    private $db;
    private $table = '';
    
    function __construct(){
        $this->db =& Database::getInstance();
        $this->table = $this->db->prefix("exmbb_categories");
    }
    
    /**
    * @desc Obtiene la lista de categorías en un array para utilizar en un campo RMSelect
    */
    public function getForSelect(){
        
        $result = $this->db->query("SELECT id_cat, title FROM $this->table ORDER BY `order`");
        $rtn = array();
        while (list($id,$title) = $this->db->fetchRow($result)){
            $rtn[$id] = $title;
        }
        return $rtn;
    }
    /**
    * @desc Obtiene las categorías especificadas
    * @param int $active Categorías activas o inactivas (1 o 0, 2 Todas);
    */
    public function getObjects($active = 1){
        $db =& Database::getInstance();
        $sql = "SELECT * FROM ".$db->prefix("exmbb_categories");
        if ($active==1 || $active==0){
            $sql .= " WHERE status='$active'";
        }
        $sql .= " ORDER BY `order`";
        $result = $db->query($sql);
        $categos = array();
        while ($row = $db->fetchArray($result)){
            $catego = new BBCategory();
            $catego->assignVars($row);
            $categos[] = $catego;
        }
        return $categos;
    }
    
}

?>
