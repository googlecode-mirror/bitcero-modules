<?php
// $Id$
// --------------------------------------------------------------
// Equipo Club Gallos
// Un modulo sencillo para el manejo de equipos
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include '../../header.php';

$mc =& $xoopsModuleConfig;
$util =& RMUtilities::get();
$myts =& MyTextSanitizer::getInstance();
$db = XoopsDatabaseFactory::getDatabaseConnection();
$tpl = $xoopsTpl;

// Constantes
define('TC_PATH',XOOPS_ROOT_PATH.'/modules/team');
define('TC_URL',XOOPS_URL.'/modules/team');

$tpl->assign('tc_url', TC_URL);

$xmh = "\n<link href='".TC_URL."/styles/main.css' rel='stylesheet' type='text/css' media='all' />\n";
