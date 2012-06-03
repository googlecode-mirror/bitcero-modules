<?php
// $Id$
// --------------------------------------------------------------
// Equipo Club Gallos
// Un modulo sencillo para el manejo de equipos
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

require '../../../include/cp_header.php';

define('TC_PATH',XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname());
define('TC_URL', XOOPS_URL.'/modules/'.$xoopsModule->dirname());

# Definimos el motor de plantillas si no existe
$mc =& $xoopsModuleConfig;
$myts =& MyTextSanitizer::getInstance();

$db = XoopsDatabaseFactory::getDatabaseConnection();
# Asignamos las variables básicas a SMARTY
$tpl = RMTemplate::get();
$tpl->assign('tc_url',TC_URL);
$tpl->assign('tc_path',TC_PATH);

// Directorios
if (!file_exists(XOOPS_UPLOAD_PATH.'/teams')) mkdir(XOOPS_UPLOAD_PATH.'/teams',777);
if (!file_exists(XOOPS_UPLOAD_PATH.'/teams/ths')) mkdir(XOOPS_UPLOAD_PATH.'/teams/ths',777);
if (!file_exists(XOOPS_UPLOAD_PATH.'/teams/coachs')){
	mkdir(XOOPS_UPLOAD_PATH.'/teams/coachs',777);
	mkdir(XOOPS_UPLOAD_PATH.'/teams/coachs/ths',777);
}
if (!file_exists(XOOPS_UPLOAD_PATH.'/teams/players')){
	mkdir(XOOPS_UPLOAD_PATH.'/teams/players',777);
	mkdir(XOOPS_UPLOAD_PATH.'/teams/players/ths',777);
}
