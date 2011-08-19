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
    private $plugin_settings = array();
    
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
		RMTemplate::get()->add_tool(__('Modules','rmcommon'), 'modules.php', 'images/modules.png', 'modules');
        RMTemplate::get()->add_tool(__('Blocks','rmcommon'), 'blocks.php', 'images/blocks.png', 'blocks');
		RMTemplate::get()->add_tool(__('Images','rmcommon'), 'images.php', 'images/images.png', 'imgmanager');
		RMTemplate::get()->add_tool(__('Comments','rmcommon'), 'comments.php', 'images/comments.png', 'comments');
        RMTemplate::get()->add_tool(__('Plugins','rmcommon'), 'plugins.php', 'images/plugin.png', 'plugins');
        
        RMEvents::get()->run_event('rmcommon.create.toolbar');
		
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
        global $xoopsUser;
        
        define('COMMENTS_INCLUDED', 1);
        $db = Database::getInstance();
        
        $rmc_config = RMFunctions::configs();
        
        $params = urlencode($params);
        $sql = "SELECT * FROM ".$db->prefix("rmc_comments")." WHERE status='approved' AND id_obj='$obj' AND params='$params' AND type='$type' AND parent='$parent'".($user==null?'':" AND user='$user'")." ORDER BY posted";
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
                    'avatar'=> XOOPS_UPLOAD_URL.'/'.$user->getVar('user_avatar'),
                    'rank'  => $user->rank(),
                    'url'   => $user->getVar('url')!='http://'?$user->getVar('url'):''
                );
            
            } else {
                
                $poster = array(
                    'id'    => 0,
                    'name'  => $editor->getVar('name'),
                    'email' => $editor->getVar('email'),
                    'posts' => 0,
                    'avatar'=> '',
                    'rank'  => '',
                    'url'  => $editor->getVar('url')!='http://'?$editor->getVar('url'):''
                );
                
            }
            
            if ($xoopsUser && $xoopsUser->isAdmin()){
				$editlink = RMCURL.'/comments.php?action=edit&amp;id='.$com->id().'&amp;ret='.urlencode(self::current_url());				
            }elseif($rmc_config['allow_edit']){
				$time_limit = time() - $com->getVar('posted');
	            if($xoopsUser && $xoopsUser->getVar('uid')==$editor->getVar('xuid') && $time_limit<($rmc_config['edit_limit']*3600)){
					$editlink = RMCURL.'/post_comment.php?action=edit&amp;id='.$com->id().'&amp;ret='.urlencode(self::current_url());				
	            } else {
					$editlink = '';
	            }
			}
            
            $comms[] = array(
                'id'        => $row['id_com'],
                'text'      => TextCleaner::getInstance()->clean_disabled_tags(TextCleaner::getInstance()->popuplinks(TextCleaner::getInstance()->nofollow($com->getVar('content')))),
                'poster'    => $poster,
                'posted'    => sprintf(__('Posted on %s'), formatTimestamp($com->getVar('posted'), 'l')),
                'ip'        => $com->getVar('ip'),
                'edit'		=> $editlink
            );  
            
            unset($editor);
        }
        
        $comms = RMEvents::get()->run_event('rmcommon.loading.comments', $comms, $obj, $params, $type, $parent, $user);
        global $xoopsTpl;
        $xoopsTpl->assign('lang_edit', __('Edit','rmcommon'));
        
        if ($assign){
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
        
        $config = self::configs();
        
        if (!$config['enable_comments']){
			 return;
        }
        
        if (!$xoopsUser && !$config['anonymous_comments']){
			return;
        }
        
        if (!defined('COMMENTS_INCLUDED')){
			define('COMMENTS_INCLUDED', 1);
        }
        
        $xoopsTpl->assign('enable_comments_form', 1);

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
        
        $form = RMEvents::get()->run_event('rmcommon.comments.form', $form, $obj, $params, $type);
        RMTemplate::get()->add_jquery();
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
    
    /**
    * Delete comments assigned to a object
    * @param string Module name
    * @param string Params
    */
    public function delete_comments($module, $params){
		
		if ($module=='' || $params == '') return;
		
		$db = Database::getInstance();
		$sql = "DELETE FROM ".$db->prefix("rmc_comments")." WHERE id_obj='$module' AND params='$params'";
        
        // Event
        RMEvents::get()->run_event('rmcommon.deleting.comments', $module, $params);
        
		return $db->queryF($sql);
		
    }
    
    public function current_url() {
		$pageURL = 'http';
		if (isset($_SERVER["HTTPS"]) && strtolower($_SERVER["HTTPS"]) == "on") {$pageURL .= "s";}
		$pageURL .= "://";
	  	$pageURL .= $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
	 	return $pageURL;
	}
    
    /**
    * Get plugins settings
    * @param string Plugin dirname
    * @param bool Type of values returned
    */
    public function plugin_settings($dir, $values = false){
        
        if ($dir=='') return;
        
        if (!isset($this->plugin_settings[$dir])){
        
            $db = Database::getInstance();
            $sql = "SELECT * FROM ".$db->prefix("rmc_settings")." WHERE element='$dir'";
            $result = $db->query($sql);
            if($db->getRowsNum($result)<=0) return;
            $configs = array();
            while ($row = $db->fetchArray($result)){
                $configs[$row['name']] = $row;
            }
            
            $configs = $this->getConfValueForOutput($configs);
            $this->plugin_settings[$dir] = $configs;
        
        }
        
        if (!$values) return $this->plugin_settings[$dir];
        
        $ret = array();
        foreach($this->plugin_settings[$dir] as $name => $conf){
            $ret[$name] = $conf['value'];
        }
        
        return $ret;
        
    }
    
    private function getConfValueForOutput($confs){
        foreach ($confs as $name => $data){
            
            switch ($data['valuetype']) {
                case 'int':
                    $confs[$name]['value'] = intval($data['value']);
                    break;
                case 'array':
                    $confs[$name]['value'] = unserialize($data['value']);
                    break;
                case 'float':
                    $confs[$name]['value'] = floatval($data['value']);
                    break;
                case 'textarea':
                    $confs[$name]['value'] = stripSlashes($data['value']);
                    break;
            }
        }
        
        return $confs;
    }
    
    /**
    * Check if a plugin is installed and active in Common Utilities
    */
    public function plugin_installed($dir){
		
		if (isset($GLOBALS['installed_plugins'][$dir]))
			return true;
		else
			return false;
		
    }
    
    /**
    * Get a existing plugin
    */
    public function load_plugin($name){
		
		$name = strtolower($name);
		if (!file_exists(RMCPATH.'/plugins/'.$name.'/'.$name.'-plugin.php'))
			return false;
		
		include_once RMCPATH.'/plugins/'.$name.'/'.$name.'-plugin.php';
		$class = ucfirst($name).'CUPlugin';
		
		if (!class_exists($class))
			return false;
		
		$plugin = new $class();
		return $plugin;
		
    }
    
    /**
    * Load a module as XoopsModule object
    * @param int|string Module id or module name
    * @return object XoopsModule
    */
    public function load_module($mod){
		$mh = xoops_gethandler('module');
		if (is_numeric($mod)){
			$m = $mh->get($mod);
		} else {
			$m = $mh->getByDirname($mod);
		}
		
		return $m;
    }
    
    /**
    * Get modules list
    */
    public function get_modules_list($active=-1){
        
        $db = Database::getInstance();
        
        $sql = "SELECT mid, name, dirname FROM " . $db->prefix("modules");
        if($active>-1 && $active<2)
            $sql .= " WHERE isactive=$active";
        
        $sql .= " ORDER BY name";
        $result = $db->query($sql);
        $modules = array();
        while ($row = $db->fetchArray($result)) {
            $modules[] = $row;
        }
        
        return $modules;
    }
    
    
	
}
