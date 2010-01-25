<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* This file contains the object MywordsController that
* will be uses by Common Utilities to do some actions
* like update comments
*/

class MywordsController
{
    public function increment_comments_number($comment){
        
        $db = Database::getInstance();
        $params = urldecode($comment->getVar('params'));
        parse_str($params);
        
        if(!isset($post) || $post<=0) return;
        
        $sql = "UPDATE ".$db->prefix("mw_posts")." SET comments=comments+1 WHERE id_post=$post";
        $db->queryF($sql);
        
    }
    
    public function reduce_comments_number($comment){
		
		$db = Database::getInstance();
        $params = urldecode($comment->getVar('params'));
        parse_str($params);
        
        if(!isset($post) || $post<=0) return;
        
        $sql = "UPDATE ".$db->prefix("mw_posts")." SET comments=comments-1 WHERE id_post=$post";
        $db->queryF($sql);
		
    }
    
    public function get_item($params, $com){
        static $posts;
        
        $params = urldecode($params);
        parse_str($params);
        if(!isset($post) || $post<=0) return __('Not found','mywords_admin');;
        
        if(isset($posts[$post])){
        	$ret = '<a href="'.$posts[$post]->permalink().'#comment-'.$com->id().'" target="_blank">'.$posts[$post]->getVar('title').'</a><br /><small>MyWords</small>';
			return $ret;
        }
        
        include_once (XOOPS_ROOT_PATH.'/modules/mywords/class/mwpost.class.php');
        include_once (XOOPS_ROOT_PATH.'/modules/mywords/class/mwfunctions.php');
        $item = new MWPost($post);
        if($item->isNew()){
			return __('Not found','mywords_admin');
        }
        
        $ret = '<a href="'.$item->permalink().'#comment-'.$com->id().'" target="_blank">'.$item->getVar('title').'</a><br /><small>MyWords</small>';
        $posts[$post] = $item;
        return $ret;
        
    }
    
}
