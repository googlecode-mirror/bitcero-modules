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
$tpl = $xoopsTpl;
$db = Database::getInstance();

load_mod_locale('galleries');

$tpl->assign('gs_url', GS_URL);
$tpl->assign('ths_width', $xoopsModuleConfig['image_ths'][0]);
$tpl->assign('ths_usr_width', $mc['user_format_mode'] ? $mc['user_format_values'][1] : $xoopsModuleConfig['image_ths'][0]);
$tpl->assign('quickview', $xoopsModuleConfig['quickview']);

$tpl->assign('lang_qview', __('Quick View','galleries'));
