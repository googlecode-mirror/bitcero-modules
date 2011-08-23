<?php
// $Id: header.php 55 2008-01-28 23:50:07Z BitC3R0 $
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

include '../../header.php';

define('BB_URL', XOOPS_URL.'/modules/exmbb');

// Actualizamos los usuarios online
include_once XOOPS_ROOT_PATH.'/kernel/online.php';
$online = new XoopsOnlineHandler($db);
$online->write($xoopsUser ? $xoopsUser->uid() : 0, $xoopsUser ? $xoopsUser->uname() : '', time(), $xoopsModule->mid(), $_SERVER['REMOTE_ADDR']);

$mc =& $xoopsModuleConfig;

?>
