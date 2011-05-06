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
        RMLightbox::get();
	}
	
    /**
    * Replaces all ocrrencies for lightbox with the apropiate code
    * @param string $output XOOPS output
    * @return string $text Output converted 	
    */
    public function eventRmcommonEndFlush($output){
        
        $pattern = "/\[lightbox=(['\"]?)([^\"'<>]*)\\1](.*)\[\/lightbox\]/sU";
        $text = preg_replace_callback($pattern, 'found_lightbox', $output);
        
        $pattern = "/\[lightbox](.*)\[\/lightbox\]/sU";
        $text = preg_replace_callback($pattern, 'found_lightbox', $output);
        
        if(RMLightbox::get()->elements){
            $text = str_replace("<!--LightBoxPlugin-->", RMLightbox::get()->render(), $text);
        }
        
        return $text;
        
    }
}

