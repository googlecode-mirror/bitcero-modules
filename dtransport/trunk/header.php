<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage files for download in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include '../../header.php';

$mc = $xoopsModuleConfig;

// Constantes del Módulo
define('DT_PATH',XOOPS_ROOT_PATH.'/modules/dtransport');
define('DT_URL',XOOPS_URL.'/modules/dtransport');
$xoopsTpl->assign('dtrans_url', DT_URL);

// Xoops Module Header
$dtfunc = new DTFunctions();

$dtfunc->checkAlert();

$tpl = RMTemplate::get();

