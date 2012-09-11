<?php
// $Id$
// --------------------------------------------------------------
// Recaptcha plugin for Common Utilities
// Allows to integrate recaptcha in comments or forms
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RecaptchaCUPlugin extends RMIPlugin
{
	
	function __construct(){
		
		// Load language
		load_plugin_locale('recaptcha', '', 'rmcommon');
		
		$this->info = array(
			'name'			=> __('Recaptcha Plugin', 'recaptcha'),
			'description'	=> __('Plugin to insert a recaptcha field on comments and other forms','recaptcha'),
			'version'		=> '1.0.0.0',
			'author'		=> 'Eduardo Cortés',
			'email'			=> 'i.bitcero@gmail.com',
			'web'			=> 'http://redmexico.com.mx',
			'dir'			=> 'recaptcha'
		);
		
	}
    
    public function options(){
        
        require 'include/options.php';
        return $options;
        
    }
    
    public function on_install(){
        return true;
    }
    
    public function on_uninstall(){
        return true;
    }
    
    public function on_update(){
        return true;
    }
    
    public function on_activate($q){
        return true;
    }
    
    public function show(){
        $config = RMFunctions::get()->plugin_settings('recaptcha', true);
        
        include_once(RMCPATH.'/plugins/recaptcha/recaptchalib.php');
        $publickey = $config['public']; // you got this from the signup page
        $field = recaptcha_get_html($publickey);
        return $field;
    }
    
    public function check(){
        $config = RMFunctions::get()->plugin_settings('recaptcha', true);
        include_once(RMCPATH.'/plugins/recaptcha/recaptchalib.php');
        $privatekey = $config['private'];
        $resp = recaptcha_check_answer ($privatekey,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);

        return $resp->is_valid;
    }
    
}