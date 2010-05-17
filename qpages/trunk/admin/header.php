<?php
// $Id$
// --------------------------------------------------------------
// Quick Pages
// Create simple pages easily and quickly
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

require '../../../include/cp_header.php';

$mc =& $xoopsModuleConfig;
$myts =& MyTextSanitizer::getInstance();

define('QP_PATH',XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname());
define('QP_URL',XOOPS_URL.($mc['links'] ? $mc['basepath'] : '/modules/'.$xoopsModule->dirname()));

include_once QP_PATH.'/include/general.func.php';

load_mod_locale('qpages');

qpages_toolbar();
