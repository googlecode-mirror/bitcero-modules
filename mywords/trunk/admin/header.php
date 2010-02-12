<?php
// $Id: header.php 50 2009-09-17 20:36:31Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$xpath = str_replace("\\", "/", dirname(__FILE__));
$xpath = str_replace("/modules/mywords/admin", "", $xpath);

require $xpath.'/include/cp_header.php';
require $xpath.'/modules/rmcommon/admin_loader.php';

load_mod_locale('mywords', 'admin_');

$db =& $xoopsDB;

define('MW_PATH',XOOPS_ROOT_PATH.'/modules/mywords');
define('MW_URL', MWFunctions::get_url());

# Asignamos las variables básicas a SMARTY
$tpl->assign('MW_URL',MW_URL);
$tpl->assign('MW_PATH',MW_PATH);

// Locale
load_mod_locale('mywords','admin');

$mc =& $xoopsModuleConfig;

// Activate scheduled posts
MWFunctions::go_scheduled();
