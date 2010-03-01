<?php
// $Id$
// --------------------------------------------------------
// Professional Works
// Manejo de Portafolio de Trabajos
// CopyRight © 2008. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.exmsystem.org
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
// --------------------------------------------------------
// @copyright: 2008 Red México

require '../../../include/cp_header.php';

define('PW_PATH',XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname());
define('PW_URL', XOOPS_URL.'/modules/works');

# Definimos el motor de plantillas si no existe
$mc =& $xoopsModuleConfig;
$myts =& MyTextSanitizer::getInstance();

# Asignamos las variables básicas a SMARTY
$tpl->assign('pw_url',PW_URL);
$tpl->assign('pw_path',PW_PATH);

// Directorios
if (!file_exists(XOOPS_UPLOAD_PATH.'/works')) mkdir(XOOPS_UPLOAD_PATH.'/works');
if (!file_exists(XOOPS_UPLOAD_PATH.'/works/ths')) mkdir(XOOPS_UPLOAD_PATH.'/works/ths');

?>
