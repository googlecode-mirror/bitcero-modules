<?php
// $Id$
// --------------------------------------------------------------
// Quick Pages
// Create simple pages easily and quickly
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

require_once XOOPS_ROOT_PATH.'/header.php';

$mc =& $xoopsModuleConfig;
$db =& Database::getInstance();
$tpl =& $xoopsTpl;
$myts =& MyTextSanitizer::getInstance();
$util = new RMUtils();

define('QP_PATH',XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname());
define('QP_URL',XOOPS_URL.($mc['links'] ? $mc['basepath'] : '/modules/'.$xoopsModule->dirname()));

if (!file_exists(QP_PATH.'/language/'.$xoopsConfig['language'].'/main.php')){
	include_once QP_PATH.'/language/spanish/main.php';
}

$xmh = '<link rel="stylesheet" type="text/css" media="screen" href="'.QP_URL.'/styles/main.css" />';

include_once 'include/general.func.php';
