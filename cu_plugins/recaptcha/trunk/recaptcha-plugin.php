<?php
// $Id$
// --------------------------------------------------------------
// Recaptcha plugin for Common Utilities
// Allows to integrate recaptcha in comments or forms
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RecaptchaCUPlugin
{
	/**
	* Stores all information about plugin
	*/
	private $info = array();
	
	function __construct(){
		
		// Load language
		load_plugin_locale('recaptcha', '', 'rmcommon');
		
		$this->info = array(
			'name'			=> __('Recaptcha Plugin', 'recaptcha'),
			'description'	=> __('Plugin to insert a receptcha field on comments and other forms','recaptcha'),
			'version'		=> '1.0.0.0',
			'author'		=> 'Eduardo Cortés',
			'email'			=> 'i.bitcero@gmail.com',
			'web'			=> 'http://redmexico.com.mx',
			'dir'			=> 'recaptcha'
		);
		
	}
	
	function get_info($name){
		
		if (!isset($this->info[$name])) return '';
		
		return $this->info[$name];
		
	}
}