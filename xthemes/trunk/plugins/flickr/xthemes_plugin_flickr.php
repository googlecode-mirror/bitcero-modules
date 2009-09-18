<?php
// $Id: xthemes_plugin_flickr.php 10 2009-08-30 23:32:21Z i.bitcero $
// --------------------------------------------------------------
// Flickr I.Themes Plugin
// Simple plugin to get flickr photos
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// License: GPL v2
// --------------------------------------------------------------

class XThemesFlickr extends XThemesPlugin
{
	
	public function __construct(&$smarty = null, $params = array()){
		$this->smarty = $smarty;
		$this->params = $params;
	}
	/**
	* Gets the information about plugin
	* @return array
	*/
	public function get_info(){
		return array(
	        'name'          => 'Flickr Gallery Widget',
	        'version'       => '1.0',
	        'author'        => 'Eduardo Cortés',
	        'url'           => 'http://redmexico.com.mx',
	        'description'   => 'This plugin allows you to add a simple flickr widget to your page',
	        'help'          => ''
	    );
	}
	
	/**
	* Run several actions when activating the plugin
	* @return bool
	*/
	public function install(){
		// Insert configuration on db
		$config = array(
			'tags' 			=> '',
			'type'			=> 'public',
			'num_items'		=> '4',
			'set'			=> '',
			'group'			=> '',
			'id'			=> ''
		);
		
		it_insert_configs($config, 'flickr');
	}
	
	/**
	* Show the configuration form for plugin
	*/
	public function get_config(){
		
		$tags = '';
		$type = 'public';
		$num_items = 4;
		$group = '';
		$id = '';
		$set = '';
		
		extract(it_get_current_config('flickr'));
		
		include_once 'include/config-page.php';
		
	}
	
	/**
	* This methos do all actions to show plugin information
	*/
	public function execute(){
		
		// Load plugin configuration
		$settings = it_get_current_config('flickr');
		// Load data
		$rss = $this->get_rss($settings);
		// Limit data
		$items = array_slice($rss->items, 0, $settings['num_items']);
		
		// Parse data
		foreach ( $items as $item ) {
			if(!preg_match('<img src="([^"]*)" [^/]*/>', $item['description'], $imgUrlMatches)) {
				continue;
			}
			$baseurl = str_replace("_m.jpg", "", $imgUrlMatches[1]);
			$image = array();
			$image['thumbnails'] = array(
				'small' => $baseurl . "_m.jpg",
				'square' => $baseurl . "_s.jpg",
				'thumbnail' => $baseurl . "_t.jpg",
				'medium' => $baseurl . ".jpg",
				'large' => $baseurl . "_b.jpg"
			);
			#check if there is an image title (for html validation purposes)
			if($item['title'] !== "") 
				$image['title'] = htmlspecialchars(stripslashes($item['title']));
			else 
				$image['title'] = $settings['default_title'];
			
			$image['url'] = $item['link'];
			
			if ($this->params['do'] == 'print'){
				
			} else {
				$this->smarty->append('flickr_images', $image);
			}
			
		}
		
	}
	
	/**
	* Get photos from flickr rss
	*/
	private function get_rss($settings) {
		
		include_once ITPATH.'/class/rss.php';
		// get the feeds
		if ($settings['type'] == "user") { $rss_url = 'http://api.flickr.com/services/feeds/photos_public.gne?id=' . $settings['id'] . '&tags=' . $settings['tags'] . '&format=rss_200'; }
		elseif ($settings['type'] == "favorite") { $rss_url = 'http://api.flickr.com/services/feeds/photos_faves.gne?id=' . $settings['id'] . '&format=rss_200'; }
		elseif ($settings['type'] == "set") { $rss_url = 'http://api.flickr.com/services/feeds/photoset.gne?set=' . $settings['set'] . '&nsid=' . $settings['id'] . '&format=rss_200'; }
		elseif ($settings['type'] == "group") { $rss_url = 'http://api.flickr.com/services/feeds/groups_pool.gne?id=' . $settings['group'] . '&format=rss_200'; }
		elseif ($settings['type'] == "public" || $settings['type'] == "community") { $rss_url = 'http://api.flickr.com/services/feeds/photos_public.gne?tags=' . $settings['tags'] . '&format=rss_200'; }
		else { 
			return;
		}
		# get rss file
		return @fetch_rss($rss_url);
	}
	
}

