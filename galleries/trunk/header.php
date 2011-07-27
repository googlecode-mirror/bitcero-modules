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

include '../../header.php';

$mc =& $xoopsModuleConfig;
$xmh = '';
$tpl = $xoopsTpl;
$db = Database::getInstance();

$tpl->assign('gs_url', GS_URL);
$tpl->assign('ths_width', $xoopsModuleConfig['image_ths'][0]);
$tpl->assign('quickview', $xoopsModuleConfig['quickview']);
