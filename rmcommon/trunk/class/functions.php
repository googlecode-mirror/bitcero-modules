<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RMFunctions
{
	public function get(){
		static $instance;
		
		if (!isset($instance))
			$instance = new RMFunctions();
		
		return $instance;
		
	}
	
	/**
	* Get Common Utilities Configurations
	*/
	public function configs($name=''){
		static $rmc_configs;
		
		if (!isset($rmc_configs)){
			$db = Database::getInstance();
			$sql = "SELECT mid FROM ".$db->prefix("modules")." WHERE dirname='rmcommon'";
			list($id) = $db->fetchRow($db->query($sql));
			
			include_once XOOPS_ROOT_PATH.'/kernel/object.php';
			include_once XOOPS_ROOT_PATH.'/kernel/configitem.php';
			include_once XOOPS_ROOT_PATH.'/class/criteria.php';
			include_once XOOPS_ROOT_PATH.'/class/module.textsanitizer.php';
			$ret = array();
	        $result = $db->query("SELECT * FROM ".$db->prefix("config")." WHERE conf_modid='$id'");
	        
	        while($row = $db->fetchArray($result)){
				$config = new XoopsConfigItem();
				$config->assignVars($row);
				$rmc_configs[$config->getVar('conf_name')] = $config->getConfValueForOutput();
	        }
		}
		
		$name = trim($name);
		if($name!=''){
			if(isset($rmc_configs[$name])) return $rmc_configs[$name];
		}
		
		return $rmc_configs;
	}
	/**
	* Check the number of images category on database
	*/
	public function get_num_records($table, $filters=''){
		
		$db = Database::getInstance();
		
		$sql = "SELECT COUNT(*) FROM ".$db->prefix($table);
		$sql .= $filters!='' ? " WHERE $filters" : '';
		
		list($num) = $db->fetchRow($db->query($sql));
		
		return $num;
		
	}
	
	/**
	* Create the module toolbar. This function must be called only from rmcommon module administration
	*/
	public function create_toolbar(){
		
		RMTemplate::get()->add_tool(__('Dashboard','rmcommon'), 'index.php', 'images/dashboard.png', 'dashboard');
		RMTemplate::get()->add_tool(__('Images','rmcommon'), 'images.php', 'images/images.png', 'imgmanager');
		RMTemplate::get()->add_tool(__('Comments','rmcommon'), 'comments.php', 'images/comments.png', 'comments');
        RMTemplate::get()->add_tool(__('Plugins','rmcommon'), 'plugins.php', 'images/plugin.png', 'plguinsmng');
		
	}
    
    /**
    * Encode array keys to make a valid url string
    * 
    * @param array Array to encode
    * @param string Var name to generate url
    * @param string URL separator
    */
    public function urlencode_array($array, $name, $separator='&'){
        
        $toImplode = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $toImplode[] = self::urlencode_array($value, "{$name}[{$key}]", $separator);
            } else {
                $toImplode[] = "{$name}[{$key}]=".urlencode($value);
            }
        }
        return implode($separator, $toImplode);
        
    }
    
    /**
	* This functions allows to get the groups names for a single category
	* @param array Groups ids
	* @param bool Return as list
	* @return array|list
	*/
	public function get_groups_names($groups, $list = true){
		
		$ret = array();
		if (count($groups)==1 && $groups[0] == 0){
			$ret[] = __('All','rmcommon');
			return $list ? __('All','rmcommon') : $ret;
		}
		
		if(in_array(0, $groups)) $ret[] = __('All','rmcommon');
		
		
		$db = Database::getInstance();
		$sql = "SELECT name FROM ".$db->prefix("groups")." WHERE groupid IN(".implode(',',$groups).")";
		$result = $db->query($sql);
		while($row = $db->fetchArray($result)){
			$ret[] = $row['name'];
		}
		
		if ($list) return implode(', ',$ret);
		return $ret;
	}
	
	/**
	* Load all categories from database
	* @param string SQL Filters
	* @return array
	*/
	public function load_images_categories($filters='ORDER BY id_cat DESC', $object = false){
		$db = Database::getInstance();
		$sql = "SELECT * FROM ".$db->prefix("rmc_img_cats")." $filters";
		$result = $db->query($sql);
		$categories = array();
		while($row = $db->fetchArray($result)){
			$tc = new RMImageCategory();
			$tc->assignVars($row);
            if (!$object){
                $categories[] = array(
                    'id'    => $tc->id(),
                    'name'    => $tc->getVar('name')
                );
            } else {
                $categories[] = $tc;
            }			
		}
		
		return $categories;
	}
    
    /**
    * Get all comments for given parameters
    * @param string Object id (can be a module name)
    * @param string Params for comment item
    * @param string Object type (eg. module, plugin, etc)
    * @param int Comment parent id, will return all comments under a given parent
    * @param int User that has been posted the comments
    * @return array
    */
    public function get_comments($obj,$params,$type='module',$parent=0,$user=null,$assign=true){
        
        $db = Database::getInstance();
        
        $params = urlencode($params);
        $sql = "SELECT * FROM ".$db->prefix("rmc_comments")." WHERE status='approved' AND id_obj='$obj' AND params='$params' AND type='$type' AND parent='$parent'".($user==null?'':" AND user='$user'");
        $result = $db->query($sql);
        
        $ucache = array();
        $ecache = array();

        while($row = $db->fetchArray($result)){
            
            $com = new RMComment();
            $com->assignVars($row);
            
            // Editor data
            if(!isset($ecache[$com->getVar('user')])){
                $ecache[$com->getVar('user')] = new RMCommentUser($com->getVar('user'));
            }
            
            $editor = $ecache[$com->getVar('user')];
            
            if($editor->getVar('xuid')>0){
            
                if(!isset($ucache[$editor->getVar('xuid')])){
                    $ucache[$editor->getVar('xuid')] = new XoopsUser($editor->getVar('xuid'));
                }
                
                $user = $ucache[$editor->getVar('xuid')];
                
                $poster = array(
                    'id' => $user->getVar('uid'),
                    'name'  => $user->getVar('uname'),
                    'email' => $user->getVar('email'),
                    'posts' => $user->getVar('posts'),
                    'avatar'=> $user->getVar('user_avatar'),
                    'rank'  => $user->rank(),
                    'url'   => $user->getVar('url')
                );
            
            } else {
                
                $poster = array(
                    'id'    => 0,
                    'name'  => $editor->getVar('name'),
                    'email' => $editor->getVar('email'),
                    'posts' => 0,
                    'avatar'=> '',
                    'rank'  => '',
                    'url'  => $editor->getVar('url')
                );
                
            }
            
            $comms[] = array(
                'id'        => $row['id_com'],
                'text'      => TextCleaner::getInstance()->clean_disabled_tags(TextCleaner::getInstance()->popuplinks(TextCleaner::getInstance()->nofollow($com->getVar('content')))),
                'poster'    => $poster,
                'posted'    => sprintf(__('Posted on %s'), formatTimestamp($com->getVar('posted'), 'l')),
                'ip'        => $com->getVar('ip')
            );            
        }
        
        $comms = RMEvents::get()->run_event('rmcommon.loading_comments', $comms, $obj, $params, $type, $parent, $user);
        
        if ($assign){
            global $xoopsTpl;
            $xoopsTpl->assign('comments', $comms);
            return true;
        } else {
            return $comms;
        }
        
    }
    
    /**
    * Create the comments form
    * You need to include the template 'rmc_comments_form.html' where
    * you wish to show this form
    * @param string Object name (eg. mywords, qpages, etc.)
    * @param string Params to be included in form
    * @param string Object type (eg. module, plugin, etc.)
    * @param string File path to get the methods to update comments
    */
    public function comments_form($obj, $params, $type='module', $file=array()){
        global $xoopsTpl, $xoopsRequestUri, $xoopsUser;
        
        $form = array(
            'show_name'     => !($xoopsUser),
            'lang_name'     => __('Name','rmcommon'),
            'show_email'    => !($xoopsUser),
            'lang_email'    => __('Email address','rmcommon'),
            'show_url'      => !($xoopsUser),
            'lang_url'      => __('Web site', 'rmcommon'),
            'lang_text'     => __('Your comment', 'rmcommon'),
            'lang_submit'   => __('Submit Comment', 'rmcommon'),
            'lang_title'    => __('Submit a comment', 'rmcommon'),
            'uri'			=> urlencode(RMFunctions::current_url()),
            'actionurl'		=> RMCURL.'/post_comment.php',
            'params'		=> urlencode($params),
            'update'        => urlencode(str_replace(XOOPS_ROOT_PATH, '', $file)),
            'type'			=> $type,
            'object'		=> $obj,
            'action'		=> 'save'
        );
        
        // You can include new content into Comments form
        // eg. Captcha checker, etc
        $form = RMEvents::get()->run_event('rmcommon.comments_form', $form, $obj, $params, $type);
        RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.validate.min.js');
        RMTemplate::get()->add_head('<script type="text/javascript">
        $(document).ready(function(){
        	$("#rmc-comment-form").validate({
        		messages: {
        			comment_name: "'.__('Please specify your name','rmcommon').'",
        			comment_email: "'.__('Please specify a valid email','rmcommon').'",
        			comment_text: "'.__('Please write a message','rmcommon').'",
        			comment_url: "'.__('Please enter a valid URL','rmcommon').'"
        		}
        	});
        });</script>');
        
        $xoopsTpl->assign('cf', $form);
        
    }
    
    public function current_url() {
		$pageURL = 'http';
		if (isset($_SERVER["HTTPS"]) && strtolower($_SERVER["HTTPS"]) == "on") {$pageURL .= "s";}
		$pageURL .= "://";
	  	$pageURL .= $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
	 	return $pageURL;
	}
	
}
