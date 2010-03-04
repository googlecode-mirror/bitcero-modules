<?php
// $Id$
// --------------------------------------------------------
// Gallery System
// Manejo y creación de galerías de imágenes
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

$tpl->assign('gs_url', GS_URL);
$tpl->assign('gs_path', GS_PATH);

?>