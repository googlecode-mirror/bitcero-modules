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

define('_MI_GS_MODDESC','Módulo para el manejo de galerías de imágenes en EXM System');

// Menu de Administración
define('_MI_GS_MHOME','Inicio');
define('_MI_GS_MALBUMS','Albumes');
define('_MI_GS_MTAGS','Etiquetas');
define('_MI_GS_MUSERS','Usuarios');
define('_MI_GS_MIMAGES','Imágenes');
define('_MI_GS_MPOSTCARD','Postales');

define('_MI_GS_GENERALCNF','Configuración General');
define('_MI_GS_FORMATCNF','Formato del Contenido');
// Titulo de la Sección
define('_MI_GS_SECTIONTITLE','Título de la Sección');

//Minimo número de caracteres de etiqueta
define('_MI_GS_MINTAG','Mínimo de caracteres en etiquetas');

//Maximo número de caracteres de etiqueta
define('_MI_GS_MAXTAG','Máximo de caracteres en etiquetas');

//Permitir a todos los usuarios subir imágenes
define('_MI_GS_ALLOWEDALLS','Permitir a todos los usuarios subir imágenes');

//Grupos que pueden subir imágenes
define('_MI_GS_GROUPSPICS','Grupos que pueden subir imágenes');

//Url amigables
define('_MI_GS_URLMODE','Utilizar URLs amigables');

//directorio de imágenes
define('_MI_GS_STOREDIR','Directorio para almacenar las imágenes');
define('_MI_GS_STOREMODE','Modo para mostrar imágenes');

//Cuota
define('_MI_GS_QUOTA','Cuota de disco por defecto para los usuarios');
define('_MI_GS_DESCQUOTA','Especifique la cuota en MegaBytes');
define('_MI_GS_SECURE','Seguro');
define('_MI_GS_NORMAL','Normal');

//Tamaño en pixeles de imagen
define('_MI_GS_IMAGE','Tamaño en pixeles de imagen normal');
define('_MI_GS_IMAGE_DESC','Especifique el tamaño de la imágen normal. Los valores deben ser especificados en el formato ancho|alto.');
define('_MI_GS_THS','Tamaño en pixeles de imagen miniatura');
define('_MI_GS_THS_DESC','Especifique el tamaño de la imágen miniatura. Los valores deben ser especificados en el formato ancho|alto.');

//Tipo de redimension
define('_MI_GS_REDIMIMAGE','Tipo de Redimensión');
define('_MI_GS_CROPTHS','Recortar miniatura');
define('_MI_GS_CROPBIG','Recortar imagen grande');
define('_MI_GS_CROPBOTH','Recortar ambas');
define('_MI_GS_REDIM','Redimensionar');

//Tamaño de archivo de imagen
define('_MI_GS_SIZE','Tamaño de archivo de imagen');
define('_MI_GS_DESCSIZE','Espacifique el tamaño en Kilobytes');

// Almacenar tamñso orginales
define('_MI_GS_SAVEORIGINAL','Almacenar Imágenes Originales');
define('_MI_GS_DELORIGINAL','Conservar Imágenes Originales despuésa de eliminar de la base de datos');

// Numero de imágenes recientes
define('_MI_GS_LASTNUM','Número de imágenes recientes en la página inicial');
define('_MI_GS_LASTCOLS','Columnas para las imágenes recientes');
define('_MI_GS_SETSNUM','Número de álbumes recientes en la página inicial');

//Número de albumes
define('_MI_GS_LIMITSETS','Número de albumes en lista de albumes');
//Número de columnas en albumes
define('_MI_GS_COLSETS','Número de columnas en albumes');

// Formato de imágenes de usuarios
define('_MI_GS_USRIMGFORMATMODE','Modificar el formato para la lista de imágenes de usuarios');
define('_MI_GS_USRIMGFORMAT','Especificaciones del Formato para las imágenes de usuario');
define('_MI_GS_USRIMGFORMAT_DESC','Los valores deben especificarse con el siguiente formato: Recortar|Ancho|Alto|Resultados|Columnas|Descripción|Nombre del Formato');

//Número de Imágenes
define('_MI_GS_LIMITPICS','Número de imágenes en lista de imágenes');
define('_MI_GS_COLSPICS','Número de columnas en imágenes');

//Número de etiquetas
define('_MI_GS_NUMTAGS','Número de etiquetas');
define('_MI_GS_HITSTAGS','Número mínimo de hits para visualizar etiquetas');
define('_MI_GS_FONTTAGS','Tamaño de fuente para etiqueta mas popular');
define('_MI_GS_DESCFONTTAGS','Especifique el tamaño en pixeles');

//Formato de imágenes de etiquetas
define('_MI_GS_NUMIMGSTAGS','Número de imágenes en etiquetas');
define('_MI_GS_COLSIMGSTAGS','Número de columnas de imágenes en etiquetas');

// Formato de imágenes de albumes
define('_MI_GS_SETFORMATMODE','Modificar el formato para la lista de imágenes de albumes');
define('_MI_GS_SETFORMAT','Especificaciones del Formato para las imágenes de albumes');
define('_MI_GS_SETBIGFORMAT','Especificaciones del Formato para imagen grande de albumes');

// POstalaes
define('_MI_GS_POSTCARDS','Activar el envío de Postales Electrónicas');

define('_MI_GS_ALLOWUSR','Permitir a los usuarios crear imágenes');
define('_MI_GS_ALLOWGRPS','Grupos que pueden enviar imágenes');

// Formato de imágenes de albumes
define('_MI_GS_NUMSEARCH','Número de imágenes en búsqueda');
define('_MI_GS_COLSSEARCH','Número de columnas en búsqueda');
define('_MI_GS_SEARCHFORMATMODE','Modificar el formato para la lista de imágenes de búsqueda');
define('_MI_GS_SEARCHFORMAT','Especificaciones del Formato para las imágenes de búsqueda');

//Duración en días de las postales
define('_MI_GS_TIMEPOSTCARD','Duración en días de las postales');

// Servicio RSS
define('_MI_GS_RSSNAME','Sindicación de Fotografías');
define('_MI_GS_RSSDESC','Suscribete a las actualizaciones en nuestra fotografías a través de RSS.');
define('_MI_GS_RSSIMGS','Fotografías Recientes');
define('_MI_GS_RSSIMGS_DESC','Ultimas fotografías agregadas a nuestras galerías.');
define('_MI_GS_RSSSETS','Albumes Recientes');
define('_MI_GS_RSSSETS_DESC','Los álbumes mas nuevos creados por los usuarios de nuestras galerías');
define('_MI_GS_RSSUSRS','Usuarios Recientes');
define('_MI_GS_RSSUSRS_DESC','Muestra la lista de los últimos usuarios inscritos en las galerías.');

define('_MI_GS_RSSIMGDESC','Descripción: %s<br />Creada: %s | Usuario: %s | %u Accesos');
$gu = XOOPS_URL.'/modules/galleries/images';
define('_MI_GS_RSSSETDESC','Por: %s<br />Creado: %s<br />Fotografías: %u');

// Bloques
define('_MI_GS_BKPICS','Fotografías');
define('_MI_GS_BKSETS','Albumes');

//Páginas del módulo
define('_MI_GS_INDEX','Página Inicial');
define('_MI_GS_USERPICS','Imágenes de Usuario');
define('_MI_GS_PICSDETAILS','Detalles de Imagen');
define('_MI_GS_USERSET','Album de Usuario');
define('_MI_GS_CPANEL','Panel de Control');
define('_MI_GS_EXPLORESETS','Explorar Albumes');
define('_MI_GS_EXPLOREPICS','Explorar Fotografías');
define('_MI_GS_TAGS','Etiquetas Populares');
define('_MI_GS_EXPLORETAGS','Explorar Etiqueta');
define('_MI_GS_SUBMIT','Subir Imágenes');
define('_MI_GS_SEARCH','Búsqueda');

?>
