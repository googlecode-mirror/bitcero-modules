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
$xoopsTpl->assign('dt_url', DT_URL);
$xoopsTpl->assign('dt_img_url', XOOPS_URL.'/modules/dtransport');

$dtfunc->checkAlert();

$tpl = RMTemplate::get();

$tpl->add_local_script('downloads.js', 'dtransport');

