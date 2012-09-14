<?php
// $Id: rmcommon.php 594 2011-02-07 20:58:31Z i.bitcero $
// --------------------------------------------------------------
// Recaptcha plugin for Common Utilities
// Allows to integrate recaptcha in comments or forms
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class AyahPluginRmcommonPreload
{
    
    private function set_config(){
        
        $config = RMFunctions::get()->plugin_settings('ayah', true);
        
        if(!defined('AYAH_PUBLISHER_KEY'))
            define( 'AYAH_PUBLISHER_KEY', $config['publisher']);
        
        if(!defined('AYAH_SCORING_KEY'))
            define( 'AYAH_SCORING_KEY', $config['scoring']);
        
    }
    
    public function eventRmcommonCommentsForm($form, $module, $params, $type){
        global $xoopsUser;
        
        $config = RMFunctions::get()->plugin_settings('ayah', true);
        
        if ($xoopsUser && $xoopsUser->isAdmin() && !$config['show']) return $form;
        
        $form['fields'] = self::get_html();
        return $form;
        
    }
    
    /**
    * This method allows to other modules or plugins to get a recaptcha field
    */
    public function eventRmcommonRecaptchaField(){
        
        global $xoopsUser;
        
        $config = RMFunctions::get()->plugin_settings('recaptcha', true);
        
        if ($xoopsUser && $xoopsUser->isAdmin() && !$config['show']) return $form;
        
        $field = self::get_html();
        return $field;
    }
    
    private function get_html(){
        
        $config = RMFunctions::get()->plugin_settings('ayah', true);
        self::set_config();
        require_once(RMCPATH.'/plugins/ayah/include/ayah.php');
        $ayah = new AYAH();
        
        $ayah->debug_mode($config['debug']);
        
        return $ayah->getPublisherHTML();
    }
    
    public function eventRmcommonCommentPostdata($ret){
        global $xoopsUser;
        
        $config = RMFunctions::get()->plugin_settings('ayah', true);
        
        if ($xoopsUser && $xoopsUser->isAdmin() && !$config['show']) return;
        
        self::set_config();
        
        include_once(RMCPATH.'/plugins/ayah/include/ayah.php');
        $ayah = new AYAH();
        $ayah->debug_mode($config['debug']);
        $resp = $ayah->scoreResult();

        if (!$resp) {
            redirect_header($ret, 2, __('Please, confirm that you are a human!', 'ayah'));
            die();
        }

    }
    
    public function eventRmcommonCaptchaCheck($value){
        global $xoopsUser;
        
        $config = RMFunctions::get()->plugin_settings('recaptcha', true);
        
        if ($xoopsUser && $xoopsUser->isAdmin() && !$config['show']) return $value;
        
        self::set_config();
        
        include_once(RMCPATH.'/plugins/ayah/include/ayah.php');
        $ayah = new AYAH();
        $ayah->debug_mode($config['debug']);
        $resp = $ayah->scoreResult();
                     
        return $resp;
    }
    
    public function eventRmcommonModulesMenu($menu){
        return $menu;
    }
          
}
