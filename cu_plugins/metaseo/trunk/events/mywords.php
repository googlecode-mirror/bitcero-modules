<?php

class MetaseoPluginMywordsPreload
{
	/**
	* This method is designed specially for MyWords
	*/
	public function eventMywordsViewPost($post_data, MWPost $post){
		
		$config = RMFunctions::get()->plugin_settings('metaseo', true);
		
		if (!$config['meta']){
		
			$metas = '<meta name="description" content="'.TextCleaner::truncate($post->content(true), $config['len']).'" />';
			$tags = array();
			foreach($post->tags() as $tag){
				$tags[] = $tag['tag'];
			}
			$tags = implode(',',$tags);
			$metas .= '<meta name="keywords" content="'.$tags.'" />';
			
		} else {
			
			$metas = '<meta name="description" content="'.$post->get_meta($config['meta_name'], false).'" />';
			$metas = '<meta name="description" content="'.$post->get_meta($config['meta_keys'], false).'" />';
			
		}
		
		RMTemplate::get()->add_head($metas);
		
		return $post_data;
		
	}
}
