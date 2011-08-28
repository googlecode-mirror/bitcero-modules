<?php
// $Id$
// --------------------------------------------------------------
// EXMBB Forums
// An simple forums module for XOOPS and Common Utilities
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include '../../../include/cp_header.php';

/**
 * Establecemos el objeto Smarty. Generalmente la variable
 * $xoopsTpl no es creada en la secci?n administrativa de XOOPS en las
 * versiones 2.0.x. En versiones 2.2 y superiores esta variable ya
 * esta presente.
 */

 /**
 * @todo Cambiar la url para trabajar con urls cortas
 */
define('BB_URL',XOOPS_URL.'/modules/'.$xoopsModule->dirname());
define('BB_PATH',XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname());
define('BB_UPLOADS_PATH',XOOPS_ROOT_PATH.'/uploads/exmbb');

$mc =& $xoopsModuleConfig;

/**
 * Comprobamos que existan los directorios requeridos
 * para el funcionamiento del módulo
 */
if (!file_exists($mc['attachdir'])){
    mkdir($mc['attachdir'], 0777);
    chmod($mc['attachdir'], 0777);
}

// Add css
RMTemplate::get()->add_style('admin.css', 'bxpress');
