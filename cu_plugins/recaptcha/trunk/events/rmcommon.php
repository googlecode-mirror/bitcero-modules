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
    
    public function eventRmcommonCommentsForm($form, $module, $params, $type){
        global $xoopsUser;
        
        if ($xoopsUser && $xoopsUser->isAdmin() && !$config['show']) return $form;
        
        $form['fields'] = self::get_html();
        return $form;
        
    }
    
    /**
    * This method allows to other modules or plugins to get a recaptcha field
    */
    public function eventRmcommonRecaptchaField(){        
        $field = self::get_html();
        return $field;
    }
    
    private function get_html(){
        
        $config = RMFunctions::get()->plugin_settings('recaptcha', true);
        
        include_once(RMCPATH.'/plugins/recaptcha/recaptchalib.php');
        $publickey = $config['public']; // you got this from the signup page
        
        RMTemplate::get()->add_head('
            <script type="text/javascript">
                var RecaptchaOptions = {
                    theme: \''.$config['theme'].'\'
                };
            </script>
        ');
        
        return recaptcha_get_html($publickey);
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
    
    public function eventRmcommonCaptchaCheck(){
        $config = RMFunctions::get()->plugin_settings('recaptcha', true);
        include_once(RMCPATH.'/plugins/recaptcha/recaptchalib.php');
        $privatekey = $config['private'];
        $resp = recaptcha_check_answer ($privatekey,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);

        return $resp;
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
    
    public function eventRmcommonGetSystemTools($tools){
        
        load_plugin_locale('recaptcha', '', 'rmcommon');
        
        $rtn = array(
            'link'  => RMCURL.'/plugins.php?action=configure&plugin=recaptcha',
            'icon'  => RMCURL.'/plugins/recaptcha/recaptcha.png',
            'caption' => __('Recaptcha options', 'recaptcha')
        );
        
        $tools[] = $rtn;
        
        return $tools;
           
    }
    
}
