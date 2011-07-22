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
            $cat = new ShopCategory();
            $cat->assignVars($row);
            $row['link'] = $cat->permalink();
            $categories[] = $row;
            if ($include_subs) ShopFunctions::categos_list($categories, $row['id_cat'], $indent+1, $include_subs, $exclude);
        }
        
    }
    
    /**
    * Show admin menu and include the javascript files
    */
    public function include_required_files(){
        RMTemplate::get()->add_style('admin.css','shop');
        RMTemplate::get()->add_local_script('admin.js', 'shop');
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
            
            $ret = XOOPS_URL.'/modules/shop/';
            
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
    
    /**
    * Get parameters from url
    */
    public function url_params(){
        $mc = RMUtilities::module_config('shop');
        
        $category = '';
        $page = 1;
        $id = '';
        $contact = '';
        
        if($mc['urlmode']){
            
            $params = str_replace(XOOPS_URL, '', RMFunctions::current_url());
            $params = str_replace($mc['htbase'], '', $params);
            $params = trim($params, '/');
            $params = explode("/", $params);
            
            $fc = false; // Switch to category path
            
            foreach($params as $i => $k){
                switch($k){
                    case 'category':
                        $category = $params[$i+1];
                        $fc = true;
                        break;
                    case 'page':
                        $page = $params[$i+1];
                        $fc = false;
                        break;
                    case 'contact':
                        $contact = $params[$i+1];
                        $fc = false;
                        break;
                    default:
                        if($category!='' && $category!=$k && $fc)
                            $category .= '/'.$k;
                        $id = $category==''&&$page<=1&&$contact==''?$params[0]:'';
                        break;
                }
            }
        
        } else {
            
            $category = rmc_server_var($_GET, 'cat', 0);
            $id = rmc_server_var($_GET, 'id', 0);
            $page = rmc_server_var($_GET, 'page', 0);
            $contact = rmc_server_var($_GET, 'contact', 0);
            
        }
        
        $ret['category'] = $category;
        $ret['page'] = $page;
        $ret['id'] = $id;
        $ret['contact'] = $contact;
        
        return $ret;
        
    }
    
    /**
    * Error 404
    */
    public function error404(){
        global $xoopsLogger;
        $xoopsLogger->renderingEnabled = false;
        $xoopsLogger->activated = false;
        header("HTTP/1.0 404 Not Found");
        if (substr(php_sapi_name(), 0, 3) == 'cgi')
              header('Status: 404 Not Found', TRUE);
              else
              header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');

        echo __("ERROR 404. Document not Found",'shop');
        die();
    }
    
}

function shop_dashed(&$item, $key){
    
    $item['dash'] = str_repeat("&#8212;",$item['indent']);
        
}
