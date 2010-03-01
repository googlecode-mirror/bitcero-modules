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

define('_MI_RMMF_MODDESC','Módulo para el manejo de portafolios profesionales');

define('_MI_RMMF_AM1', 'Trabajos Existentes');
define('_MI_RMMF_AM2', 'Nuevo Trabajo');
define('_MI_RMMF_AM3', 'Categorías');
define('_MI_RMMF_AM4', 'Nueva Categoría');

// OPCIONES DE CONFIGURACION
define("_MI_RMMF_EDITOR",'Tipo de Editor:');
define("_MI_RMMF_FORM_COMPACT","Compacto");
define("_MI_RMMF_FORM_DHTML","DHTML");
define("_MI_RMMF_FORM_SPAW","Editor Spaw");
define("_MI_RMMF_FORM_HTMLAREA","Editor HtmlArea");
define("_MI_RMMF_FORM_FCK","Editor FCK");
define("_MI_RMMF_FORM_KOIVI","Editor Koivi");
define('_MI_RMMF_FORMATDATE','Formato de Fecha:');
define('_MI_RMMF_IMGW','Ancho de Imágenes:');
define('_MI_RMMF_IMGH','Alto de Imágenes:');
define('_MI_RMMF_THW','Ancho de Miniaturas:');
define('_MI_RMMF_THH','Alto de Miniaturas:');
define('_MI_RMMF_IMGSNUM','Número de Imágenes a Cargar:');
define('_MI_RMMF_STORE','Directorio para almacenar las imágenes:');
define('_MI_RMMF_STORE_DESC','El directorio debe tener permisos de escritura.');
define('_MI_RMMF_TITLE','Título del Módulo');
define('_MI_RMMF_RECENTSNUM','Número de Trabajos Recientes:');
define('_MI_RMMF_RECENTSNUM_DESC','<span style="font-size: 10px;">Número de trabajos recientes que se mostrarán en la página principal del módulo.</span>');
define('_MI_RMMF_FEATUREDNUM','Número de Trabajos Destacados:');
define('_MI_RMMF_WORKSNUM','N&uacute;mero de Trabajos por P&aacute;gina:');

// Bloques
define('_MI_RMMF_BKRECENT','Trabajos Recientes');
define('_MI_RMMF_BKCOMMENTS','Comentarios de Clientes');
define('_MI_RMMF_BKFATURED','Trabajos Destacados');
define('_MI_RMMF_BKRANDOM','Trabajos Aleatorios');
?>