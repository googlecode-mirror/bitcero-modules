<?php
// $Id$
// --------------------------------------------------------------
// Red Mexico GUI for XOOPS
// A new GUI design for XOOPS, specially designed to use with RM Common
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

xoops_load('gui', 'system');

global $xoopsConfig;
include_once XOOPS_ROOT_PATH.'/modules/rmcommon/admin_loader.php';

/**
* XOOPS CPanel "redmexico" GUI class
* 
* @copyright   Red México http://redmexico.com.mx
* @license     http://www.fsf.org/copyleft/gpl.html GNU public license
* @author      BitC3R0       <i.bitcero@gmail.com>
* @version     1.0
*/
class XoopsGuiRedmexico extends  XoopsSystemGui
{
	function __construct(){
		
	}
	
	public function validate(){ return true; }
	
	public function header(){
		global $xoopsConfig, $xoopsUser, $xoopsModule, $xoTheme, $xoopsTpl;
		parent::header();
		
		if ($xoopsModule && !$xoopsModule->getInfo('rmnative'))
			RMTemplate::get()->add_script(XOOPS_URL.'/include/xoops.js');
		
	}
	
	public function footer(){
		global $xoopsConfig, $xoopsOption, $xoopsTpl, $xoTheme;

        $xoopsLogger =& XoopsLogger::getInstance();
        $xoopsLogger->stopTime('Module display');

        if (!headers_sent()) {
            header('Content-Type:text/html; charset='._CHARSET);
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Cache-Control: private, no-cache');
            header("Cache-Control: post-check=0, pre-check=0", false);
            header('Pragma: no-cache');
        }

        //@internal: using global $xoTheme dereferences the variable in old versions, this does not
        //if (!isset($xoTheme)) $xoTheme =& $GLOBALS['xoTheme'];

        //$xoTheme->render();
        $xoopsLogger->stopTime();
        
        // RMCommon Templates
        RMTemplate::get()->footer();
        die();
        
	}
}