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
    
    function __construct(){
        $this->info = $this->theme_info();
    }
	
	protected function set_config(){
		$this->info = $this->theme_info();
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
    public function addError($error){
        $this->errors[] = $error;
    }
	
	public function get_info($index = ''){
        
        $info = $this->info;
        
        if($index=='') return $this->info;
        
        if(!isset($this->info[$index])) return;
        
        return $this->info[$index];
        
    }
    
    abstract protected function theme_info();
	
}
