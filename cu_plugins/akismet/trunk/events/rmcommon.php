<?php
// $Id$
// --------------------------------------------------------------
// Akismet plugin for Common Utilities
// Integrate Akismet API in Common Utilities
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// see: http://akismet.com
// --------------------------------------------------------------

include_once RMCPATH.'/plugins/akismet/include/Akismet.class.php';

class AkismetPluginRmcommonPreload{
	
	public function eventRmcommonSavingComment(RMComment $com){
		global $name, $url, $xuid, $xoopsUser, $email, $text, $xoopsConfig;
		
		 $config = RMFunctions::get()->plugin_settings('akismet', true);
         
         if ($config['key']=='') return;
		 
		 $akismet = new Akismet(XOOPS_URL, $config['key']);
		 $akismet->setCommentAuthor($name);
		 $akismet->setCommentAuthorEmail($email);
		 $akismet->setCommentAuthorURL($url);
		 $akismet->setCommentContent($text);
		 $akismet->setUserIP($_SERVER['REMOTE_ADDR']);
		 $cpath = XOOPS_ROOT_PATH.'/modules/'.$com->getVar('id_obj').'/class/'.$com->getVar('id_obj').'controller.php';
		 if(is_file($cpath)){
			if(!class_exists(ucfirst($com->getVar('id_obj')).'Controller'))
				include_once $cpath;
			
			$class = ucfirst($com->getVar('id_obj')).'Controller';
			$controller = new $class();
			$permalink = $controller->get_item($com->getVar('params'), $com, true);
			$akismet->setPermalink($permalink);
        }
        
        if ($akismet->isCommentSpam()){
			$com->setVar('status','spam');
            return false;
        }
        
        return true;
		
	}
	
	/**
	* This event check spam in comments, posts and other contents for modules
	* 
	* @param array All params to check (blogurl, name, email, url, text, permalink)
	* @return bool
	*/
	public function eventRmcommonCheckPostSpam($params){
		 $config = RMFunctions::get()->plugin_settings('akismet', true);
		 
         if ($config['key']=='') return;
         
		 extract($params);
		 
		 $akismet = new Akismet($blogurl, $config['key']);
		 $akismet->setCommentAuthor($name);
		 $akismet->setCommentAuthorEmail($email);
		 $akismet->setCommentAuthorURL($url);
		 $akismet->setCommentContent($text);
		 $akismet->setPermalink($permalink);
		 $akismet->setUserIP($_SERVER['REMOTE_ADDR']);
        
        if ($akismet->isCommentSpam()){
			return false;
        }
        
        return true;
	}
	
}
