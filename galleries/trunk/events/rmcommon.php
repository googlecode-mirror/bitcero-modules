<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class GalleriesRmcommonPreload
{
    function eventRmcommonIncludeCommonLanguage(){
        global $xoopsConfig;
        
        if (RMFunctions::current_url()==XOOPS_URL.'/modules/galleries/admin/images.php' && $xoopsConfig['closesite']){
            $security = rmc_server_var($_POST, 'rmsecurity', 0);
            $data = TextCleaner::getInstance()->decrypt($security, true);
            $data = explode("|", $data); // [0] = referer, [1] = session_id(), [2] = user, [3] = token
            $xoopsUser = new XoopsUser($data[0]);
            if ($xoopsUser->isAdmin()) $xoopsConfig['closesite'] = 0;
        }
        
    }
    
    public function eventRmcommonGetFeedsList($feeds){
        
        load_mod_locale('galleries');
        include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsfunctions.class.php';
        $module = RMFunctions::load_module('galleries');
        $config = RMUtilities::module_config('galleries');

        $data = array(
                'title'    => $module->name(),
                'url'    => GSFunctions::get_url(),
                'module' => 'galleries'
        );
        
        $options[] = array(
            'title'    => __('All Recent Pictures', 'galleries'),
            'params' => 'show=pictures',
            'description' => __('Show all recent pictures','galleries')
        );
        
        $options[] = array(
            'title'    => __('All Recent Albums', 'galleries'),
            'params' => 'show=albums',
            'description' => __('Show all recent albums','galleries')
        );
        
        $feed = array('data'=>$data,'options'=>$options);
        $feeds[] = $feed;
        return $feeds;
        
    }
    
}
