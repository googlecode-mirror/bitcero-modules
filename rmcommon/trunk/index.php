<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include_once '../../include/cp_header.php';
require_once XOOPS_ROOT_PATH.'/modules/rmcommon/admin_loader.php';
define('RMCLOCATION','dashboard');

RMFunctions::create_toolbar();
xoops_cp_header();

xoops_cp_footer();
