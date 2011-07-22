<?php
// $Id$
// --------------------------------------------------------------
// MiniShop 3
// Module for catalogs
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

require_once XOOPS_ROOT_PATH.'/header.php';

define('SHOP_PATH',XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname());
define('SHOP_URL', XOOPS_URL.'/modules/'.$xoopsModule->dirname());

define('SHOP_UPPATH', XOOPS_UPLOAD_PATH.'/minishop');
define('SHOP_UPURL', XOOPS_UPLOAD_URL.'/minishop');

$xoopsTpl->assign('shopConfig', $xoopsModuleConfig);
$xoopsTpl->assign('shop_url', ShopFunctions::get_url());

