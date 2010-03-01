<?php
/*******************************************************************
* $Id$             *
* ----------------------------------------------------             *
* RMSOFT MyFolder 1.0                                              *
* Módulo para el manejo de un portafolio profesional               *
* CopyRight © 2006. Red México Soft                                *
* Autor: BitC3R0                                                   *
* http://www.redmexico.com.mx                                      *
* http://www.xoops-mexico.net                                      *
* --------------------------------------------                     *
* This program is free software; you can redistribute it and/or    *
* modify it under the terms of the GNU General Public License as   *
* published by the Free Software Foundation; either version 2 of   *
* the License, or (at your option) any later version.              *
*                                                                  *
* This program is distributed in the hope that it will be useful,  *
* but WITHOUT ANY WARRANTY; without even the implied warranty of   *
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the     *
* GNU General Public License for more details.                     *
*                                                                  *
* You should have received a copy of the GNU General Public        *
* License along with this program; if not, write to the Free       *
* Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,   *
* MA 02111-1307 USA                                                *
*                                                                  *
* ----------------------------------------------------             *
* menu.php:                                                        *
* Menú para la sección administrativa                              *
* ----------------------------------------------------             *
* @copyright: © 2006. BitC3R0.                                     *
* @autor: BitC3R0                                                  *
* @paquete: RMSOFT MyFolder v1.0                                   *
* @version: 1.0.1                                                  *
* @modificado: 24/05/2006 12:38:10 a.m.                            *
*******************************************************************/

$adminmenu[0]['title'] = _MI_RMMF_AM1;
$adminmenu[0]['link'] = "admin/index.php";
$adminmenu[1]['title'] = _MI_RMMF_AM2;
$adminmenu[1]['link'] = "admin/index.php?op=new";
$adminmenu[2]['title'] = _MI_RMMF_AM3;
$adminmenu[2]['link'] = "admin/categos.php";
$adminmenu[3]['title'] = _MI_RMMF_AM4;
$adminmenu[3]['link'] = "admin/categos.php?op=new";
?>