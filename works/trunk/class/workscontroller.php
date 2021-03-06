<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class WorksController implements iCommentsController
{
	public function increment_comments_number($comment){
        
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $params = urldecode($comment->getVar('params'));
        parse_str($params);
        
        if(!isset($work) || $work<=0) return;
        
        $sql = "UPDATE ".$db->prefix("pw_works")." SET comms=comms+1 WHERE id_work=$work";
        $db->queryF($sql);
        
    }
    
    public function reduce_comments_number($comment){
		
		$db = XoopsDatabaseFactory::getDatabaseConnection();
        $params = urldecode($comment->getVar('params'));
        parse_str($params);
        
        if(!isset($work) || $work<=0) return;
        
        $sql = "UPDATE ".$db->prefix("pw_works")." SET comms=comms-1 WHERE id_work=$work AND comms>0";
        $db->queryF($sql);
		
    }
    
    public function get_item($params, $com){
        static $works;
        
        $params = urldecode($params);
        parse_str($params);
        if(!isset($work) || $work<=0) return __('Not found','works');;
        
        if(isset($works[$work])){
        	$ret = $works[$work]->title();
			return $ret;
        }
        
        include_once (XOOPS_ROOT_PATH.'/modules/works/class/pwwork.class.php');
        $item = new PWWork($work);
        if($item->isNew()){
			return __('Unknow','works');
        }
        
        $ret = $item->title();
        $works[$work] = $item;
        return $ret;
        
    }
	
	public function get_item_url($params, $com){
		static $works;
        
        $params = urldecode($params);
        parse_str($params);
        if(!isset($work) || $work<=0) return '';
        
        if(isset($works[$work])){
        	$ret = $works[$work]->link().'#comment-'.$com->id();
			return $ret;
        }
        
        include_once (XOOPS_ROOT_PATH.'/modules/works/class/pwwork.class.php');
        $item = new PWWork($work);
        if($item->isNew()){
			return '';
        }
        
        $ret = $item->link().'#comment-'.$com->id();
        $works[$work] = $item;
        return $ret;
	}
    
    public function get_main_link(){
		
		$mc = RMUtilities::module_config('works');
		
		if ($mc['urlmode']){
			return XOOPS_URL.$mc['htbase'];
		} else {
			return XOOPS_URL.'/modules/works';
		}
		
    }
    
}
