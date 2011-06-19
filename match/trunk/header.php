<?php
// $Id$
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include '../../mainfile.php';

define('MCH_URL', XOOPS_URL.'/'.($xoopsModuleConfig['urlmode'] ? $xoopsModuleConfig['htbase'] : 'modules/match'));
define('MCH_PATH',XOOPS_ROOT_PATH.'/modules/match');
define('MCH_UP_URL', XOOPS_UPLOAD_URL.'/teams');
define('MCH_UP_PATH', XOOPS_UPLOAD_PATH.'/teams');