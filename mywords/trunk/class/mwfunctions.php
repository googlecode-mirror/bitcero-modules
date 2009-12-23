<?php
// $Id: mwfunctions.php 53 2009-09-18 06:02:06Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* This file contains general functions used in MyWords
* @author BitC3R0 <i.bitcero@gmail.com>
* @since 2.0
*/
class MWFunctions
{
	private $max_popularity = 0;
    
	public function get(){
		static $instance;
		
		if (isset($instance))
			return $instance;
		
		$instance = new MWFunctions();
		return $instance;
	}
	
	/**
	* Retrieve metas from database
	* @return array
	*/
	function get_metas(){
		$db = Database::getInstance();
		$result = $db->query("SELECT name FROM ".$db->prefix("mw_meta")." GROUP BY name");
		$ret = array();
		while($row = $db->fetchArray($result)){
			$ret[] = $row['name'];
		}
		return $ret;
	}
    
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
        
        $sql = "SELECT * FROM ".$db->prefix("mw_categories")." WHERE parent='$parent' ORDER BY $order";
        $result = $db->query($sql);
        while ($row = $db->fetchArray($result)){
            if ($row['id_cat']==$exclude) continue;
            $row['indent'] = $indent;
            $categories[] = $row;
            if ($include_subs) MWFunctions::categos_list($categories, $row['id_cat'], $indent+1, $include_subs, $exclude);
        }
        
    }
    
    /**
    * Show admin menu and include the javascript files
    */
    public function include_required_files(){
		RMTemplate::get()->add_style('admin.css','mywords');
		include 'menu.php';
    }
    
    /**
    * Check if a category exists already
    * @param object MWCategory object
    * @return bool
    */
    public function category_exists(MWCategory $cat){
		
		$db = Database::getInstance();
		$sql = "SELECT COUNT(*) FROM ".$db->prefix("mw_categories")." WHERE name='".$cat->getVar('name','n')."' OR
				shortname='".$cat->getVar('shortname','n')."'";
		
		if (!$cat->isNew()){
			$sql .= " AND id_cat<>".$cat->id();
		}
		
		list($num) = $db->fetchRow($db->query($sql));
		
		if ($num>0) return true;
		
		return false;
		
    }
    
    /**
    * Check if given post already exists
    * @param object MWPost object
    * @return bool
    */
    public function post_exists(MWPost $post){
        
        if ($post->getVar('title','n')=='') return false;
        
        // the pubdate
        if ($post->getVar('pubdate')<=0){
        	
			$day = date('j', $post->getVar('schedule'));
			$month = date('n', $post->getVar('schedule'));
			$year = $day = date('Y', $post->getVar('schedule'));
			
			$bdate = mktime(0, 0, 0, $month, $day, $year);
			$tdate = mktime(23, 59, 59, $month, $day, $year);
			
        } else {
			
			$day = date('j', $post->getVar('pubdate'));
			$month = date('n', $post->getVar('pubdate'));
			$year = date('Y', $post->getVar('pubdate'));
			
			$bdate = mktime(0, 0, 0, $month, $day, $year);
			$tdate = $bdate + 86400;
			
        }
        
        $db = Database::getInstance();
        $sql = "SELECT COUNT(*) FROM ".$db->prefix("mw_posts")." WHERE (pubdate>=$bdate AND pubdate<=$tdate) AND 
        		(title='".$post->getVar('title','n')."' OR shortname='".$post->getVar('shortname','n')."')";
        
        if (!$post->isNew()){
			$sql .= " AND id_post<>".$post->id();
        }

        list($num) = $db->fetchRow($db->query($sql));
        
        if ($num>0){
			return true;
        }
        
        return false;
        
    }
    
    /**
    * Get the tags list based on given parameters
    * @param string SQL Select
    * @param string SQL Where
    * @param string SQL Order
    * @param string SQL Limit
    * @return array
    */
    public function get_tags($select = '*', $where='',$order='',$limit=''){
        $db = Database::getInstance();
        $sql = "SELECT $select FROM ".$db->prefix("mw_tags").($where!='' ? " WHERE $where" : '').($order!='' ? " ORDER BY $order" : '' ).($limit!='' ? " LIMIT $limit" : '');
        $result = $db->query($sql);
        $tags = array();
        while ($row = $db->fetchArray($result)){
            $tags[] = $row;
        }
        asort($tags);
        return $tags;
    }
    
    /**
    * Get the font size for tags names based on their popularity
    * @param int Number of posts for this tag
    * @param int Max font size for tag name. This value is expressend in 'ems' (2em)
    * @return float Size of tag expressed as em value
    */
    public function tag_font_size($posts, $max_size = 3){
        
        $db = Database::getInstance();
        if ($this->max_popularity<=0){
            $sql = "SELECT MAX(posts) FROM ".$db->prefix("mw_tags");
            list($this->max_popularity) = $db->fetchRow($db->query($sql));
        }
        
        if ($this->max_popularity<=0) return 0.85;
        
        $base_size = $max_size / $this->max_popularity;
        
        $ret = $posts * $base_size;
        
        if ($ret<0.85) return 0.85;
        
        return number_format($ret, 2);
        
    }
    
    /**
    * @desc Devuelve la categoría "uncategorized"
    * @return array
    */
    public function default_category_id(){
        
        $db = Database::getInstance();
        $result = $db->query("SELECT id_cat FROM ".$db->prefix("mw_categories")." WHERE id_cat='1'");
        if ($db->getRowsNum($result)<=0) return false;
        
        list($id) = $db->fetchRow($result);
        return $id;
        
    }
    
    /**
    * Get author name
    * @param int Author (XoopsUser) ID
    * @return string
    */
    public function author_name($uid){
        
        $db = Database::getInstance();
        $result = $db->query("SELECT uname FROM ".$db->prefix("users")." WHERE uid='$uid'");
        if ($db->getRowsNum($result)<=0) return false;
        
        list($uname) = $db->fetchRow($result);
        return $uname;
        
    }
    
    /**
    * Add tags to database
    * @param string|array Tags names
    * @return array Tags saved ID
    */
    public function add_tags($tags){
        
        if (!is_array($tags))
            $tags = array($tags);
        
        if(empty($tags)) return;
        
        $db = Database::getInstance();
        
        $sql = "SELECT id_tag, shortname FROM ".$db->prefix('mw_tags')." WHERE ";
        $sa = '';
        foreach($tags as $tag){
            $sa .= $sa=='' ? "shortname='".TextCleaner::sweetstring($tag)."'" : " OR shortname='".TextCleaner::sweetstring($tag)."'";
        }

        $result = $db->query($sql.$sa);
        $existing = array();
        $ids = array();
        
        while($row = $db->fetchArray($result)){
            $existing[$row['shortname']] = $row['id_tag'];
            $ids[] = $row['id_tag'];
        }

        $sa = '';
        
        foreach ($tags as $tag){
            
            $short = TextCleaner::sweetstring($tag);
            
            if (isset($existing[$short])) continue;
            $sql = "INSERT INTO ".$db->prefix("mw_tags")." (`tag`,`shortname`,`posts`) VALUES ('$tag','$short','0')";
            if ($db->queryF($sql)){
                $ids[] = $db->getInsertId();
            }
            
        }

        return $ids;
        
    }
    
    /**
    * Get correct base url for links
    */
    function get_url(){
        global $xoopsModule;
        if(!$xoopsModule || $xoopsModule->dirname()!='mywords')
            $xoopsModuleConfig = RMUtilities::get()->module_config('mywords');
        
        if ($xoopsModuleConfig['permalinks']>1){
            
            return XOOPS_URL.rtrim($xoopsModuleConfig['basepath'], "/").'/';
            
        } else {
            return XOOPS_URL.'/modules/mywords/';
        }
        
    }
	
}
