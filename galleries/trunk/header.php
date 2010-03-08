<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('GS_PATH',XOOPS_ROOT_PATH.'/modules/galleries');
define('GS_URL',XOOPS_URL.'/modules/galleries');

if (!file_exists(GS_PATH.'/language/'.$xoopsConfig['language'].'/main.php')){
	include_once GS_PATH.'/language/spanish/main.php';
}

include '../../header.php';

$mc =& $xoopsModuleConfig;
$xmh = '';
$tpl = $xoopsTpl;
$db = Database::getInstance();

$tpl->assign('gs_url', GS_URL);
