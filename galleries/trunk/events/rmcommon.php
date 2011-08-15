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
    
}