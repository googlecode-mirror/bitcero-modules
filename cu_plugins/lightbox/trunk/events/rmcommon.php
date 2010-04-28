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
	
}

