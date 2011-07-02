<?php
// $Id$
// --------------------------------------------------------------
// MiniShop 3
// Module for catalogs
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

require '../../../include/cp_header.php';

define('SHOP_PATH',XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname());
define('SHOP_URL', XOOPS_URL.'/modules/'.$xoopsModule->dirname());

define('SHOP_UPPATH', XOOPS_UPLOAD_PATH.'/minishop');
define('SHOP_UPURL', XOOPS_UPLOAD_URL.'/minishop');

if(!is_dir(SHOP_UPPATH)){
    mkdir(SHOP_UPPATH, 511);
    mkdir(SHOP_UPPATH.'/ths', 511);
}