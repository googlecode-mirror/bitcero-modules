<?php
// $Id: item.php 1040 2012-09-09 06:23:02Z i.bitcero $
// --------------------------------------------------------------
// D-Transport
// Manage files for download in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class DtransportController implements iCommentsController
{
    public function increment_comments_number($comment){
        
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $params = urldecode($comment->getVar('params'));
        parse_str($params);
        
        if(!isset($item) || $item<=0) return;
        
        $sql = "UPDATE ".$db->prefix("dtrans_software")." SET comments=comments+1 WHERE id_soft=$item";
        return $db->queryF($sql);
        
    }
    
    public function reduce_comments_number($comment){
        
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $params = urldecode($comment->getVar('params'));
        parse_str($params);
        
        if(!isset($item) || $item<=0) return;
        
        $sql = "UPDATE ".$db->prefix("dtrans_software")." SET comments=comments-1 WHERE id_soft=$item AND comments>0";
        $db->queryF($sql);
        
    }
    
    public function get_item($params, $com){
        static $items;
        
        $params = urldecode($params);
        parse_str($params);
        if(!isset($item) || $item<=0) return __('Not found','dtransport');;
        
        if(isset($items[$item])){
            return $items[$item]->getVar('name').' '.$items[$item]->getVar('version');
        }
        
        include_once (XOOPS_ROOT_PATH.'/modules/dtransport/class/dtsoftware.class.php');
        
        $item = new DTSoftware($item);
        if($item->isNew()){
            return __('Not found','dtransport');
        }
        
        $items[$item->id()] = $item;
        return $item->getVar('name').' '.$item->getVar('version');
        
    }
    
    public function get_item_url($params, $com){
        static $items;
        
        $params = urldecode($params);
        parse_str($params);
        if(!isset($item) || $item<=0) return '';
        
        if(isset($items[$item])){
            $ret = $items[$item]->permalink();
            return $ret;
        }
        
        include_once (XOOPS_ROOT_PATH.'/modules/dtransport/class/dtsoftware.class.php');
        
        $item = new DTSoftware($item);
        if($item->isNew()){
            return '';
        }
        
        $items[$item->id()] = $item;
        
        return $item->permalink();
        
    }
    
    public function get_main_link(){
        
        $mc = RMUtilities::module_config('dtransport');
        
        if ($mc['permalinks']>1){
            return XOOPS_URL.$mc['basepath'];
        } else {
            return XOOPS_URL.'/modules/dtransport';
        }
        
    }
    
}

