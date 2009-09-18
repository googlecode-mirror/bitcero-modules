<?php
// $Id: ithemes_plugin_twitter.php 8 2009-08-26 17:00:35Z i.bitcero $
// --------------------------------------------------------------
// Twitter I.Themes Plugin
// Simple plugin to get latest twitter posts
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// License: GPL v2
// --------------------------------------------------------------

class IthemesTwitter
{
	public function get_info(){
		return array(
	        'name'          => 'Twitter Widget',
	        'version'       => '1.0',
	        'author'        => 'Eduardo Cortés',
	        'url'           => 'http://redmexico.com.mx',
	        'description'   => 'This plugin allows you to add a widget with latest twitter posts',
	        'help'          => ''
	    );
	}
}
