<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RmcommonCorePreload extends XoopsPreloadItem
{
	public function eventCoreIncludeCommonStart(){

        if(substr($_SERVER['REQUEST_URI'], -16)=='system/admin.php' && empty($_POST)){
            header('location: '.XOOPS_URL.'/modules/rmcommon/');
            die();
        }
        
		require_once XOOPS_ROOT_PATH.'/modules/rmcommon/loader.php';
		
	}
    
    /**
    * To prevent errors when upload images with closed site 
    */
    public function eventCoreIncludeCommonLanguage(){
        global $xoopsConfig;

        if (RMFunctions::current_url()==RMCURL.'/include/upload.php' && $xoopsConfig['closesite']){
            $security = rmc_server_var($_POST, 'rmsecurity', 0);
            $data = TextCleaner::getInstance()->decrypt($security, true);
            $data = explode("|", $data); // [0] = referer, [1] = session_id(), [2] = user, [3] = token
            $xoopsUser = new XoopsUser($data[0]);
            if ($xoopsUser->isAdmin()) $xoopsConfig['closesite'] = 0;
        }
        
    }
	
	public function eventCoreFooterEnd(){
		
		ob_end_flush();

	}
}
