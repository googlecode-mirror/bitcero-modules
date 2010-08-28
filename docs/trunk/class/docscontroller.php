<?php
// $Id$
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* This file contains the object MywordsController that
* will be uses by Common Utilities to do some actions
* like update comments
*/

class DocsController
{
    public function increment_comments_number($comment){
        
        $db = Database::getInstance();
        $params = urldecode($comment->getVar('params'));
        parse_str($params);
        
        // Invalid parameters
        if(!isset($res) || $res<=0) return;
        
        if(isset($id) && $id>0)
            $sql = "UPDATE ".$db->prefix("rd_sections")." SET comments=comments+1 WHERE id_sec=$id";
        else
            $sql = "UPDATE ".$db->prefix("rd_resources")." SET comments=comments+1 WHERE id_res=$res";
        
        $db->queryF($sql);

    }
    
    public function reduce_comments_number($comment){
		
		$db = Database::getInstance();
        $params = urldecode($comment->getVar('params'));
        parse_str($params);
        
         // Invalid parameters
        if(!isset($res) || $res<=0) return;
        
        if(isset($id) && $id>0)
            $sql = "UPDATE ".$db->prefix("rd_sections")." SET comments=comments-1 WHERE id_sec=$id";
        else
            $sql = "UPDATE ".$db->prefix("rd_resources")." SET comments=comments-1 WHERE id_res=$res";
        
        $db->queryF($sql);
		
    }
    
    public function get_item($params, $com, $url = false){
        static $cresources;
        static $csections;
        
        $params = urldecode($params);
        parse_str($params);
        if(!isset($res) || $res<=0) return __('Unknow element','docs');;
        
        include_once XOOPS_ROOT_PATH.'/modules/docs/class/rdresource.class.php';
        include_once XOOPS_ROOT_PATH.'/modules/docs/class/rdsection.class.php';
        
        if(isset($id) && $id>0){

            if (isset($csections[$id])){
                $ret = '<a href="'.$csections[$id]->permalink().'#comment-'.$com->id().'">'.$csections[$id]->getVar('title').'</a>';
                return $ret;
            }
            
            $sec = new RDSection($id);
            if($sec->isNew())
                return __('Unknow element','docs');
            
            $ret = '<a href="'.$sec->permalink().'#comment-'.$com->id().'">'.$sec->getVar('title').'</a>';
            $csections[$id] = $sec;
            
            return $ret;
            
        } else {
            
            if (isset($cresources[$res])){
                $ret = '<a href="'.$cresources[$res]->permalink().'#comment-'.$com->id().'">'.$cresources[$res]->getVar('title').'</a>';
                return $ret;
            }
            
            $resoruce = new RDResource($res);
            if($resoruce->isNew())
                return __('Unknow element','docs');
            
            $ret = '<a href="'.$resoruce->permalink().'#comment-'.$com->id().'">'.$resoruce->getVar('title').'</a>';
            $cresources[$res] = $resoruce;
            
            return $ret;
            
        }
        
        
    }
    
    public function get_main_link(){
		
		$mc = RMUtilities::module_config('mywords');
		
		if ($mc['permalinks']>1){
			return XOOPS_URL.$mc['basepath'];
		} else {
			return XOOPS_URL.'/modules/mywords';
		}
		
    }
    
}
