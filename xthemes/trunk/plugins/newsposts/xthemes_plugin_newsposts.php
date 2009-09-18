<?php
// $Id: xthemes_plugin_newsposts.php 10 2009-08-30 23:32:21Z i.bitcero $
// --------------------------------------------------------------
// News Recent Posts I.Themes Plugin
// Plugin to load and show the recent news posts from "News" module
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// License: GPL v2
// --------------------------------------------------------------

class XThemesNewsposts extends XThemesPlugin
{
	function __construct(&$smarty = null, $params = array()){
		$this->smarty = $smarty;
		$this->params = $params;
	}
	
	/**
	* PLugin Info
	*/
	public function get_info(){
		return array(
	        'name'          => 'News Recent Posts Plugin',
	        'version'       => '1.0',
	        'author'        => 'Eduardo Cortés',
	        'url'           => 'http://redmexico.com.mx',
	        'description'   => 'This plugin allows to your theme loads the recent posts ins News module',
	        'help'          => ''
	    );
	}
	
	public function install(){
		$config = array(
			'date_format'	=> 'm/d/Y',
			'cache'			=> 1
		);
		
		it_insert_configs($config, 'newsposts');
		
	}
	
	/**
	* Shows the configuration form
	*/
	public function get_config(){
		
		$cache_time = '30';
		$cache_multi = '60';
		extract(it_get_current_config('newsposts'));
		
		include_once 'config_page.php';
		
	}
	
	/**
	* Requirements
	*/
	public function check_requirements(){
		// Check for "News module"
		if (!it_module_installed('news')){
			$this->errors[] = "Module News has not been installed yet! This plugin requires that this module are instlled previously";
			return false;
		}
		return true;
	}
	
	/**
	* Execute all actions from this plugin
	*/
	public function execute(){
		
		include_once XOOPS_ROOT_PATH.'/modules/news/class/class.newsstory.php';
		include_once XOOPS_ROOT_PATH.'/modules/news/class/class.sfiles.php';
		include_once XOOPS_ROOT_PATH.'/modules/news/class/class.newstopic.php';
		include_once XOOPS_ROOT_PATH.'/modules/news/include/functions.php';
		include_once XOOPS_ROOT_PATH.'/class/tree.php';
		
		$config = it_get_current_config('newsposts');
		
		$limit = 5;
		$topic = 0;
		$len = 0;
		extract($this->params);
		
		$cache_file = XOOPS_CACHE_PATH.'/'.md5('newsposts'.$limit.$topic.$len).'.php';
		
		$cache_limit = $config['cache_time'] * $config['cache_multi'];
		
		if (file_exists($cache_file) && (time()-filemtime($cache_file)) <= $cache_limit){
			$items = json_decode(file_get_contents($cache_file), true);
		} else {
		
			$sarray = NewsStory::getAllPublished($limit, 0, false, $topic, 0, true, 'published', false);
			
			$items = array();
			
			foreach ($sarray as $item){
				$items[] = array(
					'title' 	=> $item->title(),
					'text'		=> $len==0 ? $item->hometext() : strip_tags(substr($item->hometext(), 0, $len), '<strong><em>'),
					'date'		=> date($config['date_format'], $item->published()),
					'comments'	=> $item->comments(),
					'link'		=> XOOPS_URL.'/modules/news/article.php?storyid='.$item->storyid()
				);
			}
			
			file_put_contents($cache_file, json_encode($items));
		
		}
		
		$this->smarty->assign("newsposts_items", $items);
		
	}
	
}