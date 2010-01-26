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

ob_start('cu_render_output');

/**
* This file contains the autoloader function files from RMCommon Utilities
*/
function rmc_autoloader($class){
	global $xoopsModule;
	
	if(class_exists($class)) return;
	
	$class = strtolower($class);
	
	if($class=='xoopskernel') return;
	
	if (substr($class, 0, 2)=='rm') $class = substr($class, 2);

	if (substr($class, strlen($class) - strlen('handler'))=='handler'){
		$class = substr($class, 0, strlen($class) - 7);
	}
      
    $paths = array(
    	'/api',
        '/kernel',
        '/class',
        '/fields'
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

/**
* Modify the page output to include some new features
* 
* @param mixed $output
* @return string
*/
function cu_render_output($output){
	global $xoTheme, $xoopsTpl;
    
    if (function_exists('xoops_cp_header')) return $output;
    
	$page = $output;
    if($xoopsTpl){
	    if(defined('COMMENTS_INCLUDED') && COMMENTS_INCLUDED){
		    RMTemplate::get()->add_style('comments.css', 'rmcommon');
	    }
    }
	
	$pos = strpos($page, "</head>");
	if($pos===FALSE) return $output;
	include_once RMTemplate::get()->tpl_path('rmc_header.php', 'rmcommon');
	    
	$rtn = substr($page, 0, $pos);
	$rtn .= $scripts;
	$rtn .= $styles;
	$rtn .= $heads;
	$rtn .= substr($page, $pos);
	return $rtn;
}

include_once XOOPS_ROOT_PATH.'/class/logger/xoopslogger.php';
include_once XOOPS_ROOT_PATH.'/class/database/databasefactory.php';

$db = XoopsDatabaseFactory::getDatabaseConnection();
$rmc_config = RMFunctions::get()->configs();

require_once 'api/l10n.php';

load_mod_locale('rmcommon','');

if (!$rmc_config){
    _e('Sorry, Red Mexico Common Utilities has not been installed yet!');
    die();
}

include_once RMCPATH.'/include/tpl_functions.php';

define('RMCLANG','en_US');
