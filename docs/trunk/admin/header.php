<?php
// $Id$
// --------------------------------------------------------------
// Foros EXMBB
// Módulo para el manejo de Foros en EXM
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.xoopsmexico.net
// --------------------------------------------
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License as
// published by the Free Software Foundation; either version 2 of
// the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public
// License along with this program; if not, write to the Free
// Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
// MA 02111-1307 USA
// --------------------------------------------------------------
// @author: BitC3R0
// @copyright: 2007 - 2008 Red México

include '../../../include/cp_header.php';

/**
 * Establecemos el objeto Smarty. Generalmente la variable
 * $xoopsTpl no es creada en la secci?n administrativa de XOOPS en las
 * versiones 2.0.x. En versiones 2.2 y superiores esta variable ya
 * esta presente.
 */

define('BB_URL',XOOPS_URL.'/modules/'.$xoopsModule->dirname());
define('BB_PATH',XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname());
define('BB_UPLOADS_PATH',XOOPS_ROOT_PATH.'/uploads/exmbb');
$tpl->assign('bb_url',XOOPS_URL.'/modules/rmforum');

$mc =& $xoopsModuleConfig;

/**
 * Comprobamos que existan los directorios requeridos
 * para el funcionamiento del módulo
 */
if (!file_exists($mc['attachdir'])){
    mkdir($mc['attachdir'], 0777);
    chmod($mc['attachdir'], 0777);
}

?>