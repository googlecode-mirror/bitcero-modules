<?php
// $Id$
// --------------------------------------------------------------
// MiniShop 3
// Module for catalogs
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class ShopProduct extends RMObject
{
    private $metas = array();
    private $categos = array();
    private $lcats = array();
    
    function __construct($id=null){
        $this->db =& Database::getInstance();
        $this->myts =& MyTextSanitizer::getInstance();
        $this->_dbtable = $this->db->prefix("shop_products");
        $this->setNew();
        $this->initVarsFromTable();
        
        if ($id==null) return;
    
        if ($this->loadValues($id)){
            $this->unsetNew();
            return true;
        } else {
            $this->primary = "nameid";
            if ($this->loadValues($id))    $this->unsetNew();
            
            $this->primary = "id_product";
            return;
        }        
        
    }
    
    public function id(){
        return $this->getVar('id_product');
    }
    
    public function permalink(){
        $mc = RMUtilities::module_config('shop');
        $rtn = ShopFunctions::get_url();
        $rtn .= $mc['urlmode']==0 ? 'product.php?id='.$this->id() : $this->getVar('nameid').'/';
        return $rtn;
    }
    
    /**
    * Meta data
    */
    private function load_meta(){
        if (!empty($this->metas)) return;

        $result = $this->db->query("SELECT name,value FROM ".$this->db->prefix("shop_meta")." WHERE product='".$this->id()."'");
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
     * Obtiene las catgorías a las que pertenece el artículo
     * @param string Indicates the returned data (ids, data, objects)
     * @return string, array
     */
    public function get_categos($w='ids'){
        global $mc;
        
        $tbl1 = $this->db->prefix("shop_categories");
        $tbl2 = $this->db->prefix("shop_catprods");
        
        $objs = array();
        if (empty($this->categos)){    
            $result = $this->db->query("SELECT a.* FROM $tbl1 a,$tbl2 b WHERE b.product='".$this->id()."' AND a.id_cat=b.cat GROUP BY b.cat");
            $rtn = array();
            while ($row = $this->db->fetchArray($result)){
                $cat = new ShopCategory();
                $this->lcats[] = $row;
                $this->categos[] = $row['id_cat'];
                $cat->assignVars($row);
                $objs[] = $cat;
                $this->lcats[count($this->lcats)-1]['permalink'] = $cat->permalink();
            }
        }
        
        if ($w=='ids')
            return $this->categos;
        elseif ($w=='data')
            return $this->lcats;
        
        // Return objects
        $rtn = array();
        if (empty($objs)){
            foreach($this->lcats as $row){
                $cat = new ShopCategory();
                $cat->assignVars($row);
                $rtn[] = $cat;
            }
        }
        
        return $rtn;
        
    }
    /**
     * Assign this post to a new category.
     * If Replace parameter is true, delete previos categories assignments and replace
     * with new given cats
     * @param int|array Category ID or array with categories ID
     * @param bool Replace or add
     */
    public function add_categories($cat, $replace = false){
        if (empty($this->categos) && !$replace) $this->get_categos();
        
        if (!is_array($cat)) $cat = array($cat);
        
        if($replace) $this->categos = array();
        
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
     * @param bool Get names with links. Only works when $asList equal true
     * @param string Section for link. It can be front or admin. Only works when $asList equal true
     * @return string or array
     */
    public function get_categories_names($asList=true, $delimiter=',', $links = true, $section='front'){
        
        if (empty($this->lcats)) $this->get_categos('data');
        
        $rtn = $asList ? '' : array();
        $url = ShopFunctions::get_url();
        
        foreach ($this->lcats as $cat){
            if ($asList){
                if ($links){
                    $category = new ShopCategory();
                    $category->assignVars($cat);
                    $rtn .= $rtn == '' ? '' : "$delimiter";
                    $rtn .= '<a href="'.($section=='front' ? $category->permalink() : 'products.php?cat='.$cat['id_cat']).'">'.$cat['name'].'</a>';
                } else {
                    $rtn .= $rtn == '' ? $cat['name'] : "$delimiter $cat[name]";
                }
            } else {
                $rtn[] = $row['nombre'];
            }
        }
        
        return $rtn;
    }
    
    /**
    * Save existing meta
    */
    private function save_metas(){
        
        $this->db->queryF("DELETE FROM ".$this->db->prefix("shop_meta")." WHERE product='".$this->id()."'");
        if (empty($this->metas)) return true;
        $sql = "INSERT INTO ".$this->db->prefix("shop_meta")." (`name`,`value`,`product`) VALUES ";
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
        if (empty($this->categos)){
            $this->add_categories(ShopFunctions::default_category_id());
        }
        $this->db->queryF("DELETE FROM ".$this->db->prefix("shop_catprods")." WHERE product='".$this->id()."'");
        $sql = "INSERT INTO ".$this->db->prefix("shop_catprods")." (`product`,`cat`) VALUES ";
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
     * Guardamos los datos en la base de datos
     */
    public function save(){    
        
        if ($this->isNew()){
            if(!$this->saveToTable()) return false;
            $this->setVar('id_post', $this->db->getInsertId());
        } else {
            if(!$this->updateTable()) return false;
        }
        $this->save_categories();
        $this->save_metas();
        
        RMEvents::get()->run_event('shop.save.product', $this, $this->isNew());
               
        return true;
    }
    
}
