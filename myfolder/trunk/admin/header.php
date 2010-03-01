<?php
// $Id$
// --------------------------------------------------------------
// MyFolder
// Advanced Portfolio System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include '../../../include/cp_header.php';

/**
 * Nos aseguramos que exista el lenguage buscaado
 */
if (file_exists(XOOPS_ROOT_PATH . '/modules/rmmf/language/' . $xoopsConfig['language'] . '/admin.php')) {
	include_once XOOPS_ROOT_PATH. '/modules/rmmf/language/' . $xoopsConfig['language'] . '/admin.php';
} else {
	include_once XOOPS_ROOT_PATH . '/modules/rmmf/language/spanish/admin.php';
}

$mc =& $xoopsModuleConfig;
$db =& $xoopsDB;
$myts =& MyTextSanitizer::getInstance();

include 'admin.func.php';
