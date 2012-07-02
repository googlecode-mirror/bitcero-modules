<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Módulo para la administración de descargas
// CopyRight  2007 - 2008. Red México
// http://www.redmexico.com.mx
// http://www.exmsystem.com
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
// @copyright:  2007 - 2008. Red México

define('_MI_DT_NAME',__('D-Transport','dtransport'));
define('_MI_DT_DESC',__('Módulo para la administración de archivos descargables en EXM','dtransport'));

//Menu
define('_MI_DT_INIT',__('Inicio','dtransport'));
define('_MI_DT_CATEGOS',__('Categorías','dtransport'));
define('_MI_DT_ITEMS',__('Descargas','dtransport'));
define('_MI_DT_ITEMSP',__('Descargas Pendientes','dtransport'));
define('_MI_DT_ITEMSEDIT',__('Descargas Editadas','dtransport'));
define('_MI_DT_SCREENS',__('Pantallas','dtransport'));
define('_MI_DT_FEATURES',__('Características','dtransport'));
define('_MI_DT_FILES',__('Archivos','dtransport'));
define('_MI_DT_LOGS',__('Logs','dtransport'));
define('_MI_DT_LICENCES',__('Licencias','dtransport'));
define('_MI_DT_PLATFORMS',__('Plataformas','dtransport'));

// Menu Frontal
define('_MI_DT_SEND',__('Enviar Descarga','dtransport'));

// Permalinks
define('_MI_DT_PERMALINK',__('Permalinks mode:','dtransport'));
define('_MI_DT_MODEDEF',__('PHP default','dtransport'));
define('_MI_DT_MODESHORT',__('Based on name','dtransport'));
define('_MI_DT_HTBASE',__('Permalinks base path:','dtransport'));

//Titulo
define('_MI_DT_TITLE',__('Título de Encabezado','dtransport'));
define('_MI_DT_DESCTITLE',__('Este título se mostrará como encabezado en las páginas del módulo','dtransport'));

//Permitir envío de descargas
define('_MI_DT_SENDDOWN',__('Permitir envío de descargas','dtransport'));
define('_MI_DT_DESCSENDDOWN',__('Determina si el módulo acepta el envío de descargas por parte de usuarios desde la sección frontal.','dtransport'));

//Grupos que pueden enviar descargas
define('_MI_DT_GROUPS_SEND',__('Grupos que pueden enviar descargas','dtransport'));

//Grupos que serán notificados del envio de descargas
define('_MI_DT_GROUPS_NOTIF',__('Grupos que serán notificados del envío de descargas','dtransport'));

//Aprobar descargas enviadas por usuarios registrados
define('_MI_DT_APPREG',__('Aprobar descargas de usuarios registrados','dtransport'));
define('_MI_DT_DESCAPPREG',__('Determina si se aprueban las descargas enviadas por usuarios registrados.','dtransport'));

//Aprobar descargas enviadas por usuarios anónimos
define('_MI_DT_APPANONIM',__('Aprobar descargas de usuarios anónimos','dtransport'));
define('_MI_DT_DESCAPPANONIM',__('Determina si se aprueban las descargas enviadas por usuarios anónimos cuando estos tienen permisos para enviar descargas.','dtransport'));

//Número máximo de pantallas
define('_MI_DT_LIMITSCREEN',__('Número máximo de pantallas','dtransport'));
define('_MI_DT_DESCLIMITSCREEN',__('Determina el número máximo de pantallas que se pueden crear para un elemento.','dtransport'));

//Directorio de descarga segura
define('_MI_DT_DIRSECURE',__('Directorio para Descargas Seguras','dtransport'));
define('_MI_DT_DESCDIRSECURE',__('Especifica el directorio donde se obtendrán los archivos para las descargas seguras. Se recomienda que este directorio este fuera del su directorio web en su servidor.','dtransport'));

//Directorio de descarga no segura
define('_MI_DT_DIRINSECURE',__('Directorio para Descargas Normales','dtransport'));
define('_MI_DT_DESCDIRINSECURE',__('Especifica el directorio donde se obtendrán los archivos para las descargas de acceso normal. Este directorio debe existir dentro del directorio de instalación de EXM System.','dtransport'));

//Votaciones de usuarios anónimos
define('_MI_DT_VOTEANONIM',__('Permitir Votar a usuarios anónimos','dtransport'));

//Descargas destacadas
define('_MI_DT_DESTDOWN',__('Mostrar Descargas Destacadas en la Página Inicial','dtransport'));
define('_MI_DT_DESCDESTDOWN',__('Determina si se muestran las descargas destacadas en la página principal.','dtransport'));

define('_MI_DT_FEATCAT',__('Mostrar Descargas Destacadas en las Categorías','dtransport'));
define('_MI_DT_FEATCATDESC',__('Esta opción activa el recuadro "Descargas Destacadas" en la primer página de cada categoría.','dtransport'));

//Limite de descargas destacadas
define('_MI_DT_LIMITDEST',__('Límite de descargas destacadas','dtransport'));
define('_MI_DT_DESCLIMITDEST',__('Indica el número máximo de descargas destacadas que se mostrarán en la página principal.','dtransport'));

//Modo para descargas destacadas
define('_MI_DT_MODEDOWN',__('Modo para descargas destacadas','dtransport'));
define('_MI_DT_DESCMODEDOWN',__('Determina la forma en que se muestran las descargas destacadas en la página principal.','dtransport'));
define('_MI_DT_RECENT',__('Recientes','dtransport'));
define('_MI_DT_RANDOM',__('Aleatorias','dtransport'));

//Activar descargas del dia
define('_MI_DT_ACTIVEDOWN',__('Mostrar Descargas del Día en la Página Principal','dtransport'));
define('_MI_DT_DESCACTIVEDOWN',__('Indica si se mostrará el recuadro "Descargas del Día"" en la página principal en páginas de categorías.','dtransport'));

define('_MI_DT_DAYDOWNCAT',__('Mostrar Descargas del Día en Categorías','dtransport'));

//Límite de descargas del dia
define('_MI_DT_LIMITDOWN',__('Límite de descargas del día','dtransport'));
define('_MI_DT_DESCLIMITDOWN',__('Número de descargas del día que se mostrarán.','dtransport'));

//Activar notificaciones
define('_MI_DT_ACTIVENOT',__('Activar notificaciones','dtransport'));

//Imagen
define('_MI_DT_THS',__('Tamaño de Imagen Miniatura','dtransport'));
define('_MI_DT_DESCTHS',__('Especifique el tamaño de la imagen en pixeles','dtransport'));
define('_MI_DT_IMAGE',__('Tamaño de Imagen Grande','dtransport'));
define('_MI_DT_DESCIMAGE',__('Especifique el tamaño en pixeles de las imágenes','dtransport'));
define('_MI_DT_REDIMIMAGE',__('Tipo de Redimensión','dtransport'));
define('_MI_DT_CROPTHS',__('Recortar miniatura','dtransport'));
define('_MI_DT_CROPBIG',__('Recortar imagen grande','dtransport'));
define('_MI_DT_CROPBOTH',__('Recortar ambas','dtransport'));
define('_MI_DT_REDIM',__('Redimensionar','dtransport'));
define('_MI_DT_FILE',__('Tamaño de archivo de imagen','dtransport'));
define('_MI_DT_DESCSIZE',__('Tamaño de imagen en Kilobytes','dtransport'));

//Archivo de descarga
define('_MI_DT_SIZEFILE',__('Tamaño de archivo de descarga','dtransport'));
define('_MI_DT_DESCSIZEFILE',__('Especifique el tamaño del archivo en Megabytes','dtransport'));

//Tipo de archivo
define('_MI_DT_TYPEFILE',__('Extensiones de archivos permitidas','dtransport'));
define('_MI_DT_DESCTYPEFILE',__('Separe cada extensión con una "|" ','dtransport'));

//Aprobar ediciones
define('_MI_DT_APPROVEEDIT',__('Aprobar ediciones','dtransport'));

//Notificar cuando haya ediciones
define('_MI_DT_EDIT_NOTIF',__('Notificar cuando se realicen ediciones y no se hayan aprobado','dtransport'));

// URLS
define('_MI_DT_URLMODE',__('Utilizar URLs amigables','dtransport'));

//Limite de descargas nuevas
define('_MI_DT_LIMITRECENT',__('Límite de descargas nuevas','dtransport'));

//Días de característica nueva
define('_MI_DT_NEWFEAT',__('Días para considerar un elemento como Nuevo','dtransport'));
define('_MI_DT_DESCNEWFEAT',__('Número de días en que un elemento es considerado como nuevo.','dtransport'));
define('_MI_DT_UPDITEM',__('Días para considerar un elemento como Actualizado','dtransport'));
define('_MI_DT_DESCUPDITEM',__('Número de días en que un elemento es considerado como actualizado.','dtransport'));

// Categorías en las páginas
define('_MI_DT_SHOWCATS',__('Mostrar las categorías en las páginas del Módulo','dtransport'));

// Resultados por Página
define('_MI_DT_XPAGE',__('Resultados por Página','dtransport'));

// Tamaño de las screenshots
define('_MI_DT_SCREENTH',__('Tamaño de la Captura de Pantalla Miniatura','dtransport'));
define('_MI_DT_SCREENN',__('Tamaño de la Captura de Pantalla Normal','dtransport'));


//Mostrar etiquetas populares
define('_MI_DT_ACTIVETAGS',__('Mostrar etiquetas populares','dtransport'));

//Limite de etiquetas populares
define('_MI_DT_TOTAL_TAGSPOP',__('Límite de Etiquetas Populares','dtransport'));
define('_MI_DT_DESC_TOTAL_TAGSPOP',__('Determina el total de etiquetas populares  a visualizar','dtransport'));

//Limite de etiquetas
define('_MI_DT_TOTAL_TAGS',__('Límite de Etiquetas por programa','dtransport'));
define('_MI_DT_DESC_TOTAL_TAGS',__('Determina el total de etiquetas que se permitirán por programa','dtransport'));

//Mostrar programas relacionados
define('_MI_DT_ACTRELATSW',__('Mostrar programas relacionados','dtransport'));

//Limite de programas relacionados
define('_MI_DT_LIMITRELATSOFT',__('Limite de programas relacionados','dtransport'));
define('_MI_DT_DESC_LIMITRELATSOFT',__('Determina el total de programas relacionados a visualizar','dtransport'));

//Mostrar otros programas de la categoría
define('_MI_DT_ACTOTHERSW',__('Otros programas de la categoría','dtransport'));
define('_MI_DT_DESCOTHERSW',__('Especifica si se mostrarán otros programas de la categoría seleccionada','dtransport'));

//Limite de otros programas de la categoria
define('_MI_DT_LIMITOTHERSW',__('Limite de otros programas','dtransport'));
define('_MI_DT_DESC_LIMITOTHERSW',__('Determina el total de otros programas a visualizar','dtransport'));

//Tamaño de fuente de etiquetas
define('_MI_DT_SIZEFONTTAGS',__('Tamaño de fuente de etiquetas','dtransport'));
define('_MI_DT_DESCSIZEFONTTAGS',__('Determina el tamaño máximo de fuente de la etiqueta con mayor hit','dtransport'));

//Número de caracteres minimo de una etiqueta
define('_MI_DT_CARACTER_TAGS',__('Número de caracteres mínimo para una etiqueta','dtransport'));

// Bloques
define('_MI_DT_BLOCKITEMS',__('Lista de Descargas','dtransport'));
define('_MI_DT_BLOCKITEMS_DESC',__('Bloque para presentar un listado de descargas','dtransport'));

define('_MI_DT_BLOCKTAGS',__('Búsquedas Populares','dtransport'));
define('_MI_DT_BLOCKTAGS_DESC',__('Presenta una lista de palabras mas búscadas en D-Transport','dtransport'));

// Sindicación
define('_MI_DT_RSSNAME',__('Sindicación de Descargas','dtransport'));
define('_MI_DT_RSSDESC',__('Opciones para la sindicación de descargas','dtransport'));
define('_MI_DT_RSSRECENT',__('Descargas Recientes','dtransport'));
define('_MI_DT_RSSRECENTDESC',__('Muestra las descargas agregadas recientemente.','dtransport'));
define('_MI_DT_RSSPOP',__('Descargas Populares','dtransport'));
define('_MI_DT_RSSPOPDESC',__('Muestra lo mas descargado.','dtransport'));
define('_MI_DT_RSSRATE',__('Descargas Mejor Valoradas','dtransport'));
define('_MI_DT_RSSRATEDESC',__('Descargas mejor valoradas por los usuarios.','dtransport'));

//Páginas del módulo
define('_MI_DT_INDEX',__('Home Page','dtransport'));
define('_MI_DT_CATEGORY',__('Categories','dtransport'));
define('_MI_DT_PFEATURES',__('Features','dtransport'));
define('_MI_DT_PFILES',__('Files','dtransport'));
define('_MI_DT_ITEM',__('Item Details','dtransport'));
define('_MI_DT_PLOGS',__('Logs','dtransport'));
define('_MI_DT_MYDOWNS',__('My Downloads','dtransport'));
define('_MI_DT_PSCREENS',__('Screenshots','dtransport'));
define('_MI_DT_TAGS',__('Tags','dtransport'));
define('_MI_DT_SUBMIT',__('Submit Download','dtransport'));
define('_MI_DT_SEARCH',__('Search','dtransport'));
define('_MI_DT_DOWNLOAD',__('Download File','dtransport'));
define('_MI_DT_COMMENTS',__('Comments','dtransport'));

?>