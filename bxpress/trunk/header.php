<?php
// $Id$
// --------------------------------------------------------------
// bXpress Forums
// An simple forums module for XOOPS and Common Utilities
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include '../../header.php';

define('BX_URL', XOOPS_URL.'/modules/bxpress');

// Actualizamos los usuarios online
$db = Database::getInstance();
$tpl = $xoopsTpl;
include_once XOOPS_ROOT_PATH.'/kernel/online.php';
$online = new XoopsOnlineHandler($db);
$online->write($xoopsUser ? $xoopsUser->uid() : 0, $xoopsUser ? $xoopsUser->uname() : '', time(), $xoopsModule->mid(), $_SERVER['REMOTE_ADDR']);

$mc =& $xoopsModuleConfig;
