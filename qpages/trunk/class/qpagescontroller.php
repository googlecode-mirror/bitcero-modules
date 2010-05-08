<?php
// $Id$
// --------------------------------------------------------------
// Quick Pages
// Create simple pages easily and quickly
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class QpagesController
{
	
	public function get_main_link(){
		
		$mc = RMUtilities::module_config('qpages');
		
		if ($mc['links']){
			return XOOPS_URL.$mc['basepath'];
		} else {
			return XOOPS_URL.'/modules/qpages';
		}
		
    }
	
}
