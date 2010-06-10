<?php
// $Id$
// --------------------------------------------------------------
// Rapid Docs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include '../../../include/cp_header.php';
include XOOPS_ROOT_PATH.'/modules/ahelp/include/admin.func.php';

/**
* Verificamos el directorio para los archivos de imagen
*/
if (!file_exists(XOOPS_UPLOAD_PATH.'/ahelp')){
	mkdir(XOOPS_UPLOAD_PATH.'/ahelp',0777);
}

//$access = accessInfo();

$mc =& $xoopsModuleConfig;
$db = Database::getInstance();