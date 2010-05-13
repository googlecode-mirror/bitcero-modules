<?php
// $Id$
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

# Información del Módulo
define('_MI_RMF_NAME','Foros de EXM');
define('_MI_RMF_DESC','Módulo para la gestión de foros de discusión en EXM');

# Menu de la secci?n administrativa
define('_MI_RMF_ADM1','Estado Actual');
define('_MI_RMF_ADM2','Categorías');
define('_MI_RMF_ADM3','Foros');
define('_MI_RMF_ADM4','Anuncios');
define('_MI_RMF_ADM5','Reportes');
define('_MI_RMF_ADM6','Purgar');

# Opciones de configuración del módulo
define('_MI_RMF_CNFTITLE','Título del Foro');
define('_MI_RMF_CNFTITLE_DESC','Este título aparecerá en la página principal y en secciones especiales.');

define('_MI_RMF_CNFSTOREFILES','Directorio para almacenar archivos');
define('_MI_RMF_CNFSTOREFILES_DESC','En este directorio se almacenarán los archivos adjuntos de mensajes y las imágenes de categorías');

define('_MI_RMF_CNFMAXFILESIZE','Tamaño Máximo permitido para archivos enviados (en KB)');
define('_MI_RMF_CNFMAXFILESIZE_DESC','Los archivos enviados en los foros serán limitados a este tamaño, archivos de tamaño mayor serán ignorados.');

define('_MI_BB_SHOWCATS','Mostrar Categorías en la Página Principal');
define('_MI_BB_SHOWCATS_DESC','Si activa esta opción los foros se ordenarán por categorías en la página principal.');
define('_MI_BB_URLMODE','Modo para el manejo de URLS');
define('_MI_RMF_URLDEF','Modo por defecto de PHP');
define('_MI_RMF_URLNAME','Basado en Nombres');

define('_MI_BB_TOPICLIMIT','Número de Temas por Página');
define('_MI_BB_POSTLIMIT','Número de Mensajes por Página');

define('_MI_BB_SEARCHANON','Habilitar la búsqueda para usuarios Anónimos');

define('_MI_BB_EDITOR','Tipo de Editor para los Mensajes');
define('_MI_BB_EDITOR1','DHTML');
define('_MI_BB_EDITOR2','TinyMCE');
define('_MI_BB_EDITOR3','FCKEditor');
define('_MI_BB_EDITOR4','TextArea');

define('_MI_BB_HTML','Permitir HTML en los Mensajes');
define('_MI_BB_FILESIZE','Tamaño máximo de archivo para los archivos adjuntos');
define('_MI_BB_FILESIZE_DESC','Especifique este valor en Kilobytes');
define('_MI_BB_APREFIX','Prefijo para Usuarios Anónimos');
define('_MI_BB_TIMENEW','Tiempo para marcar un post como nuevo');
define('_MI_BB_TIMENEW_DESC','Especifique este valor en segundos');
define('_MI_BB_NUMPOST','Limite de Mensajes en Revisión');
define('_MI_BB_NUMPOST_DESC','Número máximo de Mensaje sque se mostrarán en el formulario de mensajes.');
define('_MI_BB_PERPAGE','Número de Mensajes por Página');
define('_MI_BB_PERPAGE_DESC','Este valor puede ser configurado individualmente por cada usuario.');
define('_MI_BB_TPERPAGE','Número de Temas por Página');
define('_MI_BB_DATES','Formato de Fechas');
define('_MI_BB_ATTACHLIMIT','Límite de Archivos Adjuntos por Mensaje');
define('_MI_BB_ATTACHDIR','Directorio para almacenar los archivos adjuntos');
define('_MI_BB_ATTACHDIR_DESC','Este directorio debe existir en el servidor y debe contar con permisos de escritura.');
define('_MI_BB_STICKY','Activar Mensajes Fijos');
define('_MI_BB_STICKY_DESC','Esta opción permitirá crear temas como "fijos". Los temas fijos siempre aparecen en las primeras posiciones.<br />
							 Aún cuando esta opción esta deshabilitada los administradores y moderadores podrán crear mensajes fijos.');
define('_MI_BB_STICKYPOSTS','Número de envios requeridos para que un usuario pueda publicar temas fijos');
define('_MI_BB_ANNOUNCEMENTS', 'Activar anuncios en el módulo');
define('_MI_BB_ANNOUNCEMENTSMAX', 'Número máximo de anuncios a mostrar');
define('_MI_BB_ANNOUNCEMENTSMODE', 'Modo para mostrar anuncios');
define('_MI_BB_ANNOUNCEMENTSMODE1', 'Recientes');
define('_MI_BB_ANNOUNCEMENTSMODE2', 'Aleatorio');

// Notificaciones
// Foros
define('_MI_BB_NOT_FORUMCAT','Foros');
define('_MI_BB_NOT_FORUMCAT_DESC','Notificaciones relacionadas con los foros');
define('_MI_BB_NOTNEWTOPIC','Nuevo Tema Agregado');
define('_MI_BB_NOTNEWTOPICCAPTION','Notificarme cuando se cree un nuevo tema en este foro');
define('_MI_BB_NOTNEWTOPICCAPTION_DESC','Envía una notificación cuando un nuevo tema se crea en un foro determinado');
define('_MI_BB_NOTNEWTOPIC_SUBJECT','Nuevo Tema Agregado');

// Temas
define('_MI_BB_NOT_TOPICCAT','Temas');
define('_MI_BB_NOT_TOPICCAT_DESC','Notificaciones relacionadas a los temas');
define('_MI_BB_NOTNEPOST','Nuevo Mensaje Enviado');
define('_MI_BB_NOTNEPOST_CAPTION','Notificarme cuando se envia un nuevo mensaje en este tema');
define('_MI_BB_NOTNEPOST_DESC','Envia una notificación cuando un nuevo mensaje es enviado en un tema');
define('_MI_BB_NOTNEPOST_SUBJECT','Un nuevo mensaje ha sido enviado');

//Mensaje en cualquier foro
define('_MI_BB_NOT_ANY_FORUM','Todos los foros');
define('_MI_BB_NOT_ANY_FORUM_DESC','Notificaciones relacionadas con cualquier foro');
define('_MI_BB_NOTNEPOSTANYFORUM','Nuevo Mensaje en cualquier foro');
define('_MI_BB_NOTNEPOSTANYFORUM_CAPTION','Notificarme cuando se envía un nuevo mensaje a cualquier foro');
define('_MI_BB_NOTNEPOSTANYFORUM_DESC','Envía una notificación cuando un nuevo mensaje es enviado a cualquier foro');
define('_MI_BB_NOTNEPOSTANYFORUM_SUBJECT','Nuevo mensaje enviado');


//Nuevo mensaje en foro
define('_MI_BB_NOTNEPOSTFORUM','Nuevo Mensaje en foro');
define('_MI_BB_NOTNEPOSTFORUM_CAPTION','Notificarme cuando se envía un nuevo mensaje a este foro');
define('_MI_BB_NOTNEPOSTFORUM_DESC','Envía una notificación cuando un nuevo mensaje es enviado a este foro');
define('_MI_BB_NOTNEPOSTFORUM_SUBJECT','Nuevo mensaje enviado');


//Tiempo de temas recientes
define('_MI_BB_TIMETOPICS','Tiempo de temas recientes');
define('_MI_BB_DESCTIMETOPICS','Tiempo en que se considera un tema como reciente en "horas".'); 

define('_MI_BB_RSSDESC','Descripción de la opción de Sindicación');
// Sindicación
define('_MI_BB_RSSNAME','Sindicación de Foros');
define('_MI_BB_RSSALL','Todos los Mensajes Recientes');
define('_MI_BB_RSSALLDESC','Suscripción a todos los mensajes enviados en todos los foros');
define('_MI_BB_RSSNAMEFORUM','Sindicación de Mensajes en %s');

//Ordenar temas por mensajes recientes
define('_MI_BB_ORDERPOST','Ordenar temas por mensaje reciente');
define('_MI_BB_DESCORDERPOST','Indica si los temas de foro se ordenarán por mensajes recientes');

// Bloques
define('_MI_BB_BKRECENT','Temas con Mensajes Recientes');

//Páginas del módulo
define('_MI_BB_INDEX','Página Inicial');
define('_MI_BB_FORUM','Foro');
define('_MI_BB_TOPIC','Tema');
define('_MI_BB_POST','Mensaje');
define('_MI_BB_EDIT','Editar Mensaje');
define('_MI_BB_MODERATE','Moderar Foro');
define('_MI_BB_REPORT','Reportar');
define('_MI_BB_SEARCH','Búsqueda');

?>
