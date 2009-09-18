<?php
// $Id: theme.php 10 2009-08-30 23:32:21Z i.bitcero $
// --------------------------------------------------------------
// I.Themes
// Module for manage themes by Red Mexico
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// License: GPL v2
// --------------------------------------------------------------

/**
* This is the plugins parent class
*/

abstract class XThemesTheme
{
	protected $info = array();
	protected $errors = array();
	
	protected function set_config(){
		$info = $this->get_info();
	}
	
	public function name(){
		return $this->info['name'];
	}
	public function version(){
		return $this->info['version'];
	}
	public function author(){
		return $this->info['author'];
	}
	public function url(){
		return $this->info['url'];
	}
	public function description(){
		return $this->info['description'];
	}
	public function help(){
		return $this->info['help'];
	}
	public function email(){
		return $this->info['email'];
	}
	public function errors(){
		return $this->errors;
	}
	
	abstract public function get_info();
	
}
