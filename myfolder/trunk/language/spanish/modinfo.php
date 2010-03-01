<?php
/*******************************************************************
* $Id$          *
* -------------------------------------------------------          *
* RMSOFT MyFolder 1.0                                              *
* M�dulo para el manejo de un portafolio profesional               *
* CopyRight � 2006. Red M�xico Soft                                *
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
* Archivo de lenguage para la configuraci�n del m�dulo             *
* -------------------------------------------------------          *
* @copyright: � 2006. BitC3R0.                                     *
* @autor: BitC3R0                                                  *
* @paquete: RMSOFT GS 2.0                                          *
* @version: 1.0.5                                                  *
* @modificado: 24/05/2006 12:49:30 a.m.                            *
*******************************************************************/

define('_MI_RMMF_MODDESC','M�dulo para el manejo de portafolios profesionales');

define('_MI_RMMF_AM1', 'Trabajos Existentes');
define('_MI_RMMF_AM2', 'Nuevo Trabajo');
define('_MI_RMMF_AM3', 'Categor�as');
define('_MI_RMMF_AM4', 'Nueva Categor�a');

// OPCIONES DE CONFIGURACION
define("_MI_RMMF_EDITOR",'Tipo de Editor:');
define("_MI_RMMF_FORM_COMPACT","Compacto");
define("_MI_RMMF_FORM_DHTML","DHTML");
define("_MI_RMMF_FORM_SPAW","Editor Spaw");
define("_MI_RMMF_FORM_HTMLAREA","Editor HtmlArea");
define("_MI_RMMF_FORM_FCK","Editor FCK");
define("_MI_RMMF_FORM_KOIVI","Editor Koivi");
define('_MI_RMMF_FORMATDATE','Formato de Fecha:');
define('_MI_RMMF_IMGW','Ancho de Im�genes:');
define('_MI_RMMF_IMGH','Alto de Im�genes:');
define('_MI_RMMF_THW','Ancho de Miniaturas:');
define('_MI_RMMF_THH','Alto de Miniaturas:');
define('_MI_RMMF_IMGSNUM','N�mero de Im�genes a Cargar:');
define('_MI_RMMF_STORE','Directorio para almacenar las im�genes:');
define('_MI_RMMF_STORE_DESC','El directorio debe tener permisos de escritura.');
define('_MI_RMMF_TITLE','T�tulo del M�dulo');
define('_MI_RMMF_RECENTSNUM','N�mero de Trabajos Recientes:');
define('_MI_RMMF_RECENTSNUM_DESC','<span style="font-size: 10px;">N�mero de trabajos recientes que se mostrar�n en la p�gina principal del m�dulo.</span>');
define('_MI_RMMF_FEATUREDNUM','N�mero de Trabajos Destacados:');
define('_MI_RMMF_WORKSNUM','N&uacute;mero de Trabajos por P&aacute;gina:');

// Bloques
define('_MI_RMMF_BKRECENT','Trabajos Recientes');
define('_MI_RMMF_BKCOMMENTS','Comentarios de Clientes');
define('_MI_RMMF_BKFATURED','Trabajos Destacados');
define('_MI_RMMF_BKRANDOM','Trabajos Aleatorios');
?>