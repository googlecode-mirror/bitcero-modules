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

// Permalinks
define('_MI_DT_PERMALINK',__('Permalinks mode:','dtransport'));
define('_MI_DT_MODEDEF',__('PHP default','dtransport'));
define('_MI_DT_MODESHORT',__('Based on name','dtransport'));
define('_MI_DT_HTBASE',__('Permalinks base path:','dtransport'));

//Permitir envío de descargas
define('_MI_DT_SENDDOWN',__('Allow downloads submission','dtransport'));
define('_MI_DT_DESCSENDDOWN',__('Allows to users submit new download items from public section of module.','dtransport'));

//Grupos que pueden enviar descargas
define('_MI_DT_GROUPS_SEND',__('Groups than can submit new downloads','dtransport'));

//Grupos que serán notificados del envio de descargas
define('_MI_DT_GROUPS_NOTIF',__('Groups to be notified for new submitted downloads','dtransport'));

//Aprobar descargas enviadas por usuarios registrados
define('_MI_DT_APPREG',__('Approve submited downloads for registered users','dtransport'));

//Aprobar descargas enviadas por usuarios anónimos
define('_MI_DT_APPANONIM',__('Approve submited downloads for anonymous users','dtransport'));

//Número máximo de pantallas
define('_MI_DT_LIMITSCREEN',__('Maximum number of screenshots by download item','dtransport'));

//Directorio de descarga segura
define('_MI_DT_DIRSECURE',__('Secure directory','dtransport'));
define('_MI_DT_DESCDIRSECURE',__('Specify the directory where secure download files will be stored. It is recomended that this directory will located outside from public directory.','dtransport'));

//Directorio de descarga no segura
define('_MI_DT_DIRINSECURE',__('General directory','dtransport'));
define('_MI_DT_DESCDIRINSECURE',__('Specify the directory where general files will be stored. This directory will exists within your public html directory.','dtransport'));

//Votaciones de usuarios anónimos
define('_MI_DT_VOTEANONIM',__('Allow votes from anonymous users','dtransport'));

//Descargas destacadas
define('_MI_DT_DESTDOWN',__('Show featured downloads list in home page','dtransport'));
define('_MI_DT_INDESTDOWN',__('Show featured downloads list at inner pages','dtransport'));

//Limite de descargas destacadas
define('_MI_DT_LIMITDEST',__('Limit number of featured downloads in list','dtransport'));

//Activar descargas del dia
define('_MI_DT_ACTIVEDOWN',__('Show daily downloads list in home page','dtransport'));
define('_MI_DT_INACTIVEDOWN',__('Show daily downloads list at inner pages','dtransport'));

//Límite de descargas del dia
define('_MI_DT_LIMITDOWN',__('Limit number od daily downloads in list','dtransport'));

//Activar notificaciones
define('_MI_DT_ACTIVENOT',__('Enable notification for new downloads','dtransport'));

//Imagen
define('_MI_DT_THS',__('Thumbnail image format','dtransport'));
define('_MI_DT_DESCIMGSIZE',__('Specify this value with format "width:height:crop|resize"','dtransport'));
define('_MI_DT_IMAGE',__('Big image format','dtransport'));

//Archivo de descarga
define('_MI_DT_FILE',__('Size of image file in Kilobytes','dtransport'));
define('_MI_DT_SIZEFILE',__('Size of files for download in MB','dtransport'));

//Tipo de archivo
define('_MI_DT_TYPEFILE',__('Allowed file extensions','dtransport'));
define('_MI_DT_DESCTYPEFILE',__('Separate each extensión with "|".','dtransport'));

//Aprobar ediciones
define('_MI_DT_APPROVEEDIT',__('Approve editions to existing downloads','dtransport'));

//Notificar cuando haya ediciones
define('_MI_DT_EDIT_NOTIF',__('Notify for editions','dtransport'));

//Limite de descargas nuevas
define('_MI_DT_LIMITRECENT',__('Limit number of downloads for home lists','dtransport'));

//Días de característica nueva
define('_MI_DT_NEWFEAT',__('Number of days during downloads will consider as new','dtransport'));
define('_MI_DT_UPDITEM',__('Number of days during downloads will consider as updated after its edition','dtransport'));

// Categorías en las páginas
define('_MI_DT_SHOWCATS',__('Show categories in home page and categories page','dtransport'));

// Resultados por Página
define('_MI_DT_XPAGE',__('Results per page','dtransport'));

//Mostrar programas relacionados
define('_MI_DT_ACTRELATSW',__('Show related items','dtransport'));

//Limite de programas relacionados
define('_MI_DT_LIMITRELATSOFT',__('Number of items in related items list','dtransport'));

//Número de caracteres minimo de una etiqueta
define('_MI_DT_CARACTER_TAGS',__('Minimum length of tags, in characters','dtransport'));

// Bloques
define('_MI_DT_BLOCKITEMS',__('Lista de Descargas','dtransport'));
define('_MI_DT_BLOCKITEMS_DESC',__('Bloque para presentar un listado de descargas','dtransport'));

define('_MI_DT_BLOCKTAGS',__('Búsquedas Populares','dtransport'));
define('_MI_DT_BLOCKTAGS_DESC',__('Presenta una lista de palabras mas búscadas en D-Transport','dtransport'));

// Alertas
define('_MI_DT_ENABLEALERTS',__('Enable alerts for inactivity','dtransport'));
define('_MI_DT_ALERTDAYS',__('Days without inactivity before to send an alert','dtransport'));
define('_MI_DT_ALERTMODE',__('Message type for alerts','dtransport'));

define('_MI_DT_SECURE',__('Allow creation of protected downloads for users','dtransport'));
define('_MI_DT_PASSWORD',__('Allow creation of downloads with password for all publishers','dtransport'));

//
define ('_MI_DT_PAUSE', __('Time before to start downloads','dtransport'));
define ('_MI_DT_PAUSED', __('This value must be specified in seconds. If you wish to start download immediately then set this value to "0".','dtransport'));
define('_MI_DT_HRSALERTS', __('Interval in hours to send alerts for inactivity','dtransport'));

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