<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include '../../../include/cp_header.php';

define('GS_URL',XOOPS_URL.'/modules/galleries');
define('GS_PATH',XOOPS_ROOT_PATH.'/modules/galleries');

$mc =& $xoopsModuleConfig;
$mc['storedir'] = substr($mc['storedir'],strlen($mc['storedir'])-1)=="/" ? substr($mc['storedir'],0,strlen($mc['storedir'])-1) : $mc['storedir'];

//Creamos el directorio de imagenes
if (!file_exists($mc['storedir'])){
	mkdir($mc['storedir']);
	mkdir($mc['storedir'].'/originals');
}

$tpl = RMTemplate::get();

$tpl->assign('gs_url', GS_URL);
$tpl->assign('gs_path', GS_PATH);
