<?php
// $Id$
// --------------------------------------------------------------
// MetaSEO plugin for Common Utilities
// Optimize searchs by adding meta description and keywords to you rtemplate
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class MetaseoPluginQpagesPreload
{
	/**
	* This method is designed specially for MyWords
	*/
	public function eventQpagesViewPage(QPPage $page){
		
		$config = RMFunctions::get()->plugin_settings('metaseo', true);
		
		if (!$config['meta']){
		
			$metas = '<meta name="description" content="'.TextCleaner::truncate($page->getText(), $config['len']).'" />';
			/*$tags = array();
			foreach($post->tags() as $tag){
				$tags[] = $tag['tag'];
			}
			$tags = implode(',',$tags);
			$metas .= '<meta name="keywords" content="'.$tags.'" />';*/
			
		} else {
			
			$metas = '<meta name="description" content="'.$page->get_meta($config['meta_name'], false).'" />';
			$metas = '<meta name="description" content="'.$page->get_meta($config['meta_keys'], false).'" />';
			
		}
		
		RMTemplate::get()->add_head($metas);
		
		return $post_data;
		
	}
}
