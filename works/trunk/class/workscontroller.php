<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class WorksController
{
	public function increment_comments_number($comment){
        
        $db = Database::getInstance();
        $params = urldecode($comment->getVar('params'));
        parse_str($params);
        
        if(!isset($work) || $work<=0) return;
        
        $sql = "UPDATE ".$db->prefix("pw_works")." SET comms=comms+1 WHERE id_work=$work";
        $db->queryF($sql);
        
    }
    
    public function reduce_comments_number($comment){
		
		$db = Database::getInstance();
        $params = urldecode($comment->getVar('params'));
        parse_str($params);
        
        if(!isset($work) || $work<=0) return;
        
        $sql = "UPDATE ".$db->prefix("pw_works")." SET comms=comms-1 WHERE id_work=$work AND comms>0";
        $db->queryF($sql);
		
    }
    
    public function get_item($params, $com, $url = false){
        static $works;
        
        $params = urldecode($params);
        parse_str($params);
        if(!isset($work) || $work<=0) return __('Not found','admin_works');;
        
        if(isset($works[$work])){
        	$ret = '<a href="'.$works[$work]->link().'#comment-'.$com->id().'" target="_blank">'.$works[$work]->title().'</a>';
			return $ret;
        }
        
    }
    
}
