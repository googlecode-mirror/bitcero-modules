<?php
// $Id$
// --------------------------------------------------------------
// LightBox plugin for Common Utilities
// Integrate jQuery LightBox with Common Utilities
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class LightboxPluginRmcommonPreload{
	
	public function eventRmcommonBaseLoaded(){
		include_once RMCPATH.'/plugins/lightbox/lightbox.php';
	}
	
	/**
	* Replace [lighbox=#element a] by <div class="lightbox_container"> to include lightbox effects in texts
	* @params array with patterns and replacements
	* @param object TextCleaner object
	* @return array witj patterns and $replacements must be filled also
	*/
	public function eventRmcommonCodeDecode($text){
		
		$pattern = "/\[lightbox=(['\"]?)([^\"'<>]*)\\1](.*)\[\/lightbox\]/sU";
		$text = preg_replace_callback($pattern, 'found_lightbox', $text);
		
		$pattern = "/\[lightbox](.*)\[\/lightbox\]/sU";
		$text = preg_replace_callback($pattern, 'found_lightbox', $text);
	
		return $text;
		
	}
	
}

