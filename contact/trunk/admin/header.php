<?php
// $Id$
// --------------------------------------------------------------
// Contact
// A simple contact module for Xoops
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$xpath = str_replace("\\", "/", dirname(__FILE__));
$xpath = str_replace("/modules/contact/admin", "", $xpath);

require $xpath.'/include/cp_header.php';
require $xpath.'/modules/rmcommon/admin_loader.php';
