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

define('QP_PATH',XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname());
if (!defined("QP_URL"))
    define('QP_URL',XOOPS_URL.($mc['links'] ? $mc['basepath'] : '/modules/'.$xoopsModule->dirname()));

RMTemplate::get()->add_xoops_style('main.css', 'qpages');

include_once 'include/general.func.php';
