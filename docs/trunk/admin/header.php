<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

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
?>