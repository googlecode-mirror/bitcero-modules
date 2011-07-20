<?php
// $Id$
// --------------------------------------------------------------
// MiniShop 3
// Module for catalogs
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class ShopCategory extends RMObject
{
    
    function __construct($id=''){
        $this->db =& Database::getInstance();
        $this->_dbtable = $this->db->prefix("shop_categories");
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
        
        $this->primary = 'shortcut';
        if ($this->loadValues($id)) $this->unsetNew();
        $this->primary = 'id_cat';
            
    }
    /**
     * Funciones para asignar valores a las variables
     */
    function id(){
        return $this->getVar('id_cat');
    }
    
    function loadProducts(){
        $result = $this->db->query("SELECT COUNT(*) FROM ".$this->db->prefix("shop_catprods")." WHERE cat='".$this->id()."'");
        list($num) = $this->db->fetchRow($result);
        $this->setVar('products', $num);
    }
    /**
     * Obtiene la ruta completa de la categor?a basada en nombres
     */
    function path(){
        if ($this->getVar('parent')==0) return $this->getVar('shortname','n').'/';
        $parent = new ShopCategory($this->getVar('parent','n'));
        return $parent->path() . $this->getVar('shortname').'/';
    }
    /**
     * Obtiene el enlace a la categor?a
     */
    public function permalink(){
        $mc = RMUtilities::module_config('shop');
        $link = ShopFunctions::get_url();
        $link .= ($mc['urlmode']==0 ? '?cat='.$this->id() : 'category/'.$this->path());
        return $link;
    }

    /**
     * Guardamos los valores en la base de datos
     */
    function save(){
        if ($this->isNew()){
            return $this->saveToTable();
        } else {
            return $this->updateTable();
        }
    }
    /**
     * Elimina de la base de datos la categor?a actual
     */
    function delete(){
        $this->db->queryF("UPDATE ".$this->db->prefix("shop_categories")." SET parent='".$this->getVar('parent','n')."' WHERE parent='".$this->id()."'");
        $this->db->queryF("DELETE FROM ".$this->db->prefix("shop_catprods")." WHERE cat='".$this->id()."'");
        return $this->deleteFromTable();
    }
}

