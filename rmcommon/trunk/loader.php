<?php
// $Id: loader.php 22 2009-09-13 07:42:57Z i.bitcero $
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define("RMCPATH",XOOPS_ROOT_PATH.'/modules/rmcommon');
define("RMCURL",XOOPS_URL.'/modules/rmcommon');
define('ABSURL', XOOPS_URL);
define('ABSPATH', XOOPS_ROOT_PATH);
define('RMCVERSION','2.0');

/**
* This file contains the autoloader function files from RMCommon Utilities
*/
function rmc_autoloader($class){
	global $xoopsModule;
	
	$class = strtolower($class);
	
	if (substr($class, 0, 2)=='rm') $class = substr($class, 2);

	if (substr($class, strlen($class) - strlen('handler'))=='handler'){
		$class = substr($class, 0, strlen($class) - 7);
	}
      
    $paths = array(
    	'/api',
        '/class',
        '/kernel',
        '/fields'
        //'/rmcommon' /* @Todo: Eliminar este directorio */
    );

    if (is_a($xoopsModule, 'XoopsModule') && $xoopsModule->dirname()!='system'){
    	$paths[] = '/modules/'.$xoopsModule->dirname().'/class';
    }
	
    foreach ($paths as $path){
    	
    	if (file_exists(RMCPATH.$path.'/'.$class.'.php')){
        	include_once RMCPATH.$path.'/'.$class.'.php';
        } elseif(file_exists(RMCPATH.$path.'/'.$class.'.class.php')){
        	include_once RMCPATH.$path.'/'.$class.'.class.php';
        } elseif (file_exists(XOOPS_ROOT_PATH.$path.'/'.$class.'.php')){
        	include_once XOOPS_ROOT_PATH.$path.'/'.$class.'.php';
        } elseif(file_exists(XOOPS_ROOT_PATH.$path.'/'.$class.'.class.php')){
            include_once XOOPS_ROOT_PATH.$path.'/'.$class.'.class.php';
        }
    }
	
}

spl_autoload_register('rmc_autoloader');

$rmc_config = RMUtilities::get()->module_config('rmcommon');

require_once 'api/l10n.php';

load_mod_locale('rmcommon','');

if (!$rmc_config){
    _e('Sorry, Red Mexico Common Utilities has not been installed yet!');
    die();
}

include_once RMCPATH.'/include/tpl_functions.php';

define('RMCLANG','en_US');
