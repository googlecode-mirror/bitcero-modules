<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

include ('../../header.php');

$mc =& $xoopsModuleConfig;

//include_once 'include/functions.php';
define('AHURL',$mc['access'] ? XOOPS_URL.$mc['htpath'] : XOOPS_URL.'/modules/ahelp/index.php?page=');
$xoopsTpl->assign('ah_url',AHURL);
