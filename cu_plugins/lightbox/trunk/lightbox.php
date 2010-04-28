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
	public function load_lightbox($elements){
		
		RMTemplate::get()->add_script(RMCURL.'/plugins/lightbox/js/jquery.lightbox.min.js');
		RMTemplate::get()->add_style('lightbox.css', 'rmcommon', 'plugins/lightbox');
		
		$script = "<script type='text/javascript'>\n";
		$script .= "var lburl = '".RMCURL."/plugins/lightbox';\n";
		$script .= "\$(function(){\n";
		
		if (is_array($elements)){
			foreach ($elements as $element){
				$script .= "\$(\"$element\").lightBox();\n";
			}
		} else {
			$script .= "\$(\"$elements\").lightBox();\n";
		}
		
		$script .= "});\n</script>\n";
		RMTemplate::get()->add_head($script);
		
	}
}