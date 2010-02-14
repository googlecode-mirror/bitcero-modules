<?php
// $Id$
// --------------------------------------------------------------
// Recaptcha plugin for Common Utilities
// Allows to integrate recaptcha in comments or forms
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class AvatarsPluginRmcommonPreload
{
    
    public function eventRmcommonLoadingComments($comms, $obj, $params, $type, $parent, $user){
        
        $config = RMFunctions::get()->plugin_settings('avatars', true);
        
        foreach($comms as $i => $com){
            $comms[$i]['poster']['avatar'] = "http://www.gravatar.com/avatar/".md5($comms[$i]['poster']['email'])."?s=".$config['size'].'&d='.$config['default'];
        }
        
        return $comms;
        
    }
    
    public function eventRmcommonLoadingAdminComments($comms){
        
        $config = RMFunctions::get()->plugin_settings('avatars', true);
        
        foreach($comms as $i => $com){
            $comms[$i]['poster']['avatar'] = "http://www.gravatar.com/avatar/".md5($comms[$i]['poster']['email'])."?s=".$config['size'].'&d='.$config['default'];
        }
        
        return $comms;
        
    }
    
    public function eventRmcommonCurrentModuleMenu($menu){
        global $xoopsModule;
        
        if($xoopsModule->getVar('dirname')!='rmcommon') return $menu;
        
        $option = array(
            'title'=>__('Gravatar options','gravatar'),
            'link' => 'plugins.php?action=configure&plugin=avatars',
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
        
        load_plugin_locale('avatars', '', 'rmcommon');
        
        $rtn = array(
            'link'  => RMCURL.'/plugins.php?action=configure&plugin=avatars',
            'icon'  => RMCURL.'/plugins/avatars/gravatar.jpg',
            'caption' => __('Avatars options', 'avatars')
        );
        
        $tools[] = $rtn;
        
        return $tools;
           
    }
    
}
