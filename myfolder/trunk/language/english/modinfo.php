<?php
/*******************************************************************
* $Id$          *
* -------------------------------------------------------          *
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
* -------------------------------------------------------          *
* modinfo.php:                                                     *
* Archivo de lenguage para la configuración del módulo             *
* -------------------------------------------------------          *
* @copyright: © 2006. BitC3R0.                                     *
* @autor: BitC3R0                                                  *
* @paquete: RMSOFT GS 2.0                                          *
* @version: 1.0.5                                                  *
* @modificado: 24/05/2006 12:49:30 a.m.                            *
*******************************************************************/

define('_MI_RMMF_MODDESC','Module to publish professional portfolios');

define('_MI_RMMF_AM1', 'Existing Works');
define('_MI_RMMF_AM2', 'New Work');
define('_MI_RMMF_AM3', 'Categories');
define('_MI_RMMF_AM4', 'New Category');

// OPCIONES DE CONFIGURACION
define("_MI_RMMF_EDITOR",'Editor Type:');
define("_MI_RMMF_FORM_COMPACT","Compact");
define("_MI_RMMF_FORM_DHTML","DHTML");
define("_MI_RMMF_FORM_SPAW","Spaw Editor");
define("_MI_RMMF_FORM_HTMLAREA","HtmlArea Editor");
define("_MI_RMMF_FORM_FCK","FCK Editor");
define("_MI_RMMF_FORM_KOIVI","Koivi Editor");
define('_MI_RMMF_FORMATDATE','Date Format:');
define('_MI_RMMF_IMGW','Image Width:');
define('_MI_RMMF_IMGH','Image Height:');
define('_MI_RMMF_THW','Thumbnail Width:');
define('_MI_RMMF_THH','Thumbnail Height:');
define('_MI_RMMF_IMGSNUM','Works Image number:');
define('_MI_RMMF_STORE','Image store directory:');
define('_MI_RMMF_STORE_DESC','This directory must have write permissions.');
define('_MI_RMMF_TITLE','Module Title:');
define('_MI_RMMF_RECENTSNUM','Recent Works Number:');
define('_MI_RMMF_RECENTSNUM_DESC','<span style="font-size: 10px;">This works will be showing in the index page of module.</span>');
define('_MI_RMMF_FEATUREDNUM','Featured Works Number:');
define('_MI_RMMF_WORKSNUM','Works per page:');

// Bloques
define('_MI_RMMF_BKRECENT','Recent Works');
define('_MI_RMMF_BKCOMMENTS','Customer Comments');
define('_MI_RMMF_BKFATURED','Featured Works');
define('_MI_RMMF_BKRANDOM','Random Works');
?>