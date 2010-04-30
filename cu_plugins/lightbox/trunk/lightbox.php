<?php
// $Id$
// --------------------------------------------------------------
// LightBox plugin for Common Utilities
// Integrate jQuery LightBox with Common Utilities
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RMLightbox
{
	/**
	* Contains elements to be hanlded by lightbox
	* 
	* @var mixed
	*/
	private $elements = array();
	
	public function get(){
		static $instance;
		
		if (isset($instance))
			return $instance;
		
		$instance = new RMLightbox();
		return $instance;
	}
	
	public function __construct(){
		RMTemplate::get()->add_script(RMCURL.'/plugins/lightbox/js/jquery.lightbox.min.js');
		RMTemplate::get()->add_style('lightbox.css', 'rmcommon', 'plugins/lightbox');
	}
	
	/**
	* Elements that can be hadled by lighbox plugin.
	* eg. #container a (Will handle all "a" elements inside "#container" element)
	* eg. a.lights (will handle all "a" elements with class "lights")
	* 
	* You can provide a single element as string, or an array with all elements that you whish to hanlde
	* 
	* @param string|array $elements
	*/
	public function add_element($elements){		
		if (is_array($elements)){
			foreach ($elements as $element){
				if (in_array($element, $this->elements))
					continue;
				$this->elements[] = $element;
			}
		} else {
			if (in_array($elements, $this->elements))
				return;
			$this->elements[] = $elements;
		}
	}
	
	public function __destruct(){
		$script = "<script type='text/javascript'>\n";
		$script .= "var lburl = '".RMCURL."/plugins/lightbox';\n";
		$script .= "\$(function(){\n";
		
		if (is_array($this->elements)){
			foreach ($this->elements as $element){
				$script .= "\$(\"$element\").lightBox();\n";
			}
		} else {
			$script .= "\$(\"$this->elements\").lightBox();\n";
		}
		
		$script .= "});\n</script>\n";
		RMTemplate::get()->add_head($script);
	}
}

/**
* Function to handle matches from preg_replace_callback
*/
function found_lightbox($matches){
	RMLightbox::get()->add_element('.lightbox_container '.(isset($matches[2])!='' ? $matches[2] : 'a'));
	
	return '<div class="lightbox_container">'.(isset($matches[3]) ? $matches[3] : $matches[1]).'</div>';
	
}