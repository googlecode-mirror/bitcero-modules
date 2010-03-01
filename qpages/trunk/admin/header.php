<?php
// $Id$
// --------------------------------------------------------------
// Quick Pages
// Create simple pages easily and quickly
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

require '../../../include/cp_header.php';

$mc =& $xoopsModuleConfig;
$myts =& MyTextSanitizer::getInstance();

define('QP_PATH',XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname());
define('QP_URL',XOOPS_URL.($mc['links'] ? $mc['basepath'] : '/modules/'.$xoopsModule->dirname()));


if (file_exists(QP_PATH.'/language/'.$xoopsConfig['language'].'/admin.php')){
	$tpl->assign('xoops_language', $xoopsConfig['language']);
} else {
	require_once QP_PATH.'/language/spanish/admin.php';
	$tpl->assign('xoops_language', 'spanish');
}

# Asignamos las variables básicas a SMARTY
$tpl->assign('qp_url',QP_URL);
$tpl->assign('qp_path',QP_PATH);

# Funciones específicas para la sección administrativa
require '../include/admin.func.php';

# Comprobamos el archivo htaccess
checkHTAccess();
