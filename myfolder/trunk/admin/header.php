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
load_mod_locale('myfolder', 'admin_');

$mc =& $xoopsModuleConfig;
$db =& $xoopsDB;
$myts =& MyTextSanitizer::getInstance();

include 'admin.func.php';
