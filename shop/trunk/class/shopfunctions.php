<?php
// $Id$
// --------------------------------------------------------------
// MiniShop 3
// Module for catalogs
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class ShopFunctions
{
    /**
    * Get all categories from database arranged by parents
    * 
    * @param mixed $categories
    * @param mixed $parent
    * @param mixed $indent
    * @param mixed $include_subs
    * @param mixed $exclude
    * @param mixed $order
    */
    public function categos_list(&$categories, $parent = 0, $indent = 0, $include_subs = true, $exclude=0, $order="id_cat DESC"){
        
        $db = Database::getInstance();
        
        $sql = "SELECT * FROM ".$db->prefix("shop_categories")." WHERE parent='$parent' ORDER BY $order";
        $result = $db->query($sql);
        while ($row = $db->fetchArray($result)){
            if ($row['id_cat']==$exclude) continue;
            $row['indent'] = $indent;
            $categories[] = $row;
            if ($include_subs) ShopFunctions::categos_list($categories, $row['id_cat'], $indent+1, $include_subs, $exclude);
        }
        
    }
    
    /**
    * Show admin menu and include the javascript files
    */
    public function include_required_files(){
        RMTemplate::get()->add_style('admin.css','shop');
        include '../include/toolbar.php';
    }
    
    /**
    * Get correct base url for links
    */
    function get_url(){
        global $xoopsModule, $xoopsModuleConfig;
        if(!$xoopsModule || $xoopsModule->dirname()!='shop')
            $mc = RMUtilities::get()->module_config('shop');
        else
            $mc = $xoopsModuleConfig;
        
        if ($mc['urlmode']){
            
            $ret = XOOPS_URL.'/'.trim($mc['htbase'], "/").'/';
            
        } else {
            
            $ret = XOOPS_URL.'/modules/mywords/';
            
        }
        
        return $ret;
    }
    
    /**
    * @desc Devuelve la categoría "uncategorized"
    * @return array
    */
    public function default_category_id(){
        
        $db = Database::getInstance();
        $result = $db->query("SELECT id_cat FROM ".$db->prefix("shop_categories")." WHERE id_cat='1'");
        if ($db->getRowsNum($result)<=0) return false;
        
        list($id) = $db->fetchRow($result);
        return $id;
        
    }
    
}
