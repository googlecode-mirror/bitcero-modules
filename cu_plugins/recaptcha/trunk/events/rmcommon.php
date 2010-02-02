<?php
// $Id$
// --------------------------------------------------------------
// Recaptcha plugin for Common Utilities
// Allows to integrate recaptcha in comments or forms
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RecaptchaPluginRmcommonPreload
{
    
    public function eventRmcommonCommentsForm(&$form, $module, $params, $type){
        global $xoopsUser;
        
        $config = RMFunctions::get()->plugin_settings('recaptcha', true);
        
        if ($xoopsUser && $xoopsUser->isAdmin() && !$config['show']) return $form;
        
        include_once(RMCPATH.'/plugins/recaptcha/recaptchalib.php');
        $publickey = $config['public']; // you got this from the signup page
        $form['fields'] = recaptcha_get_html($publickey);
        return $form;
        
    }
    
    public function eventRmcommonCommentPostdata($ret){
        global $xoopsUser;
        
        $config = RMFunctions::get()->plugin_settings('recaptcha', true);
        
        if ($xoopsUser && $xoopsUser->isAdmin() && !$config['show']) return;
        
        include_once(RMCPATH.'/plugins/recaptcha/recaptchalib.php');
        $privatekey = $config['private'];
        $resp = recaptcha_check_answer ($privatekey,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);

        if (!$resp->is_valid) {
            redirect_header($ret, 2, __('Please verify that recaptcha words are correct!', 'recaptcha'));
            die();
        }

    }
    
    public function eventRmcommonModulesMenu($menu){
        return $menu;
    }
    
    public function eventRmcommonCurrentModuleMenu($menu){
        global $xoopsModule;
        
        if($xoopsModule->getVar('dirname')!='rmcommon') return $menu;
        
        $option = array(
            'title'=>__('Recaptcha','recaptcha'),
            'link' => 'plugins.php?action=configure&plugin=recaptcha',
            'selected' => ''
        );
        
        foreach($menu as $i => $item){
            if ($item['location']!='plugins') continue;
            
            $menu[$i]['options'][] = $option;
            break;
            
        }
        
        return $menu;
        
    }
    
}
