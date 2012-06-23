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



define('_MI_DT_NAME','D-Transport');
define('_MI_DT_DESC','Módulo para la administración de archivos descargables en EXM');

//Menu
define('_MI_DT_INIT','Inicio');
define('_MI_DT_CATEGOS','Categorías');
define('_MI_DT_ITEMS','Descargas');
define('_MI_DT_ITEMSP','Descargas Pendientes');
define('_MI_DT_ITEMSEDIT','Descargas Editadas');
define('_MI_DT_SCREENS','Pantallas');
define('_MI_DT_FEATURES','Características');
define('_MI_DT_FILES','Archivos');
define('_MI_DT_LOGS','Logs');
define('_MI_DT_LICENCES','Licencias');
define('_MI_DT_PLATFORMS','Plataformas');

// Menu Frontal
define('_MI_DT_SEND','Enviar Descarga');

//Titulo
define('_MI_DT_TITLE','Título de Encabezado');
define('_MI_DT_DESCTITLE','Este título se mostrará como encabezado en las páginas del módulo');

//Permitir envío de descargas
define('_MI_DT_SENDDOWN','Permitir envío de descargas');
define('_MI_DT_DESCSENDDOWN','Determina si el módulo acepta el envío de descargas por parte de usuarios desde la sección frontal.');

//Grupos que pueden enviar descargas
define('_MI_DT_GROUPS_SEND','Grupos que pueden enviar descargas');

//Grupos que serán notificados del envio de descargas
define('_MI_DT_GROUPS_NOTIF','Grupos que serán notificados del envío de descargas');

//Aprobar descargas enviadas por usuarios registrados
define('_MI_DT_APPREG','Aprobar descargas de usuarios registrados');
define('_MI_DT_DESCAPPREG','Determina si se aprueban las descargas enviadas por usuarios registrados.');

//Aprobar descargas enviadas por usuarios anónimos
define('_MI_DT_APPANONIM','Aprobar descargas de usuarios anónimos');
define('_MI_DT_DESCAPPANONIM','Determina si se aprueban las descargas enviadas por usuarios anónimos cuando estos tienen permisos para enviar descargas.');

//Número máximo de pantallas
define('_MI_DT_LIMITSCREEN','Número máximo de pantallas');
define('_MI_DT_DESCLIMITSCREEN','Determina el número máximo de pantallas que se pueden crear para un elemento.');

//Directorio de descarga segura
define('_MI_DT_DIRSECURE','Directorio para Descargas Seguras');
define('_MI_DT_DESCDIRSECURE','Especifica el directorio donde se obtendrán los archivos para las descargas seguras. Se recomienda que este directorio este fuera del su directorio web en su servidor.');

//Directorio de descarga no segura
define('_MI_DT_DIRINSECURE','Directorio para Descargas Normales');
define('_MI_DT_DESCDIRINSECURE','Especifica el directorio donde se obtendrán los archivos para las descargas de acceso normal. Este directorio debe existir dentro del directorio de instalación de EXM System.');

//Votaciones de usuarios anónimos
define('_MI_DT_VOTEANONIM','Permitir Votar a usuarios anónimos');

//Descargas destacadas
define('_MI_DT_DESTDOWN','Mostrar Descargas Destacadas en la Página Inicial');
define('_MI_DT_DESCDESTDOWN','Determina si se muestran las descargas destacadas en la página principal.');

define('_MI_DT_FEATCAT','Mostrar Descargas Destacadas en las Categorías');
define('_MI_DT_FEATCATDESC','Esta opción activa el recuadro "Descargas Destacadas" en la primer página de cada categoría.');

//Limite de descargas destacadas
define('_MI_DT_LIMITDEST','Límite de descargas destacadas');
define('_MI_DT_DESCLIMITDEST','Indica el número máximo de descargas destacadas que se mostrarán en la página principal.');

//Modo para descargas destacadas
define('_MI_DT_MODEDOWN','Modo para descargas destacadas');
define('_MI_DT_DESCMODEDOWN','Determina la forma en que se muestran las descargas destacadas en la página principal.');
define('_MI_DT_RECENT','Recientes');
define('_MI_DT_RANDOM','Aleatorias');

//Activar descargas del dia
define('_MI_DT_ACTIVEDOWN','Mostrar Descargas del Día en la Página Principal');
define('_MI_DT_DESCACTIVEDOWN','Indica si se mostrará el recuadro "Descargas del Día"" en la página principal en páginas de categorías.');

define('_MI_DT_DAYDOWNCAT','Mostrar Descargas del Día en Categorías');

//Límite de descargas del dia
define('_MI_DT_LIMITDOWN','Límite de descargas del día');
define('_MI_DT_DESCLIMITDOWN','Número de descargas del día que se mostrarán.');

//Activar notificaciones
define('_MI_DT_ACTIVENOT','Activar notificaciones');

//Imagen
define('_MI_DT_THS','Tamaño de Imagen Miniatura');
define('_MI_DT_DESCTHS','Especifique el tamaño de la imagen en pixeles');
define('_MI_DT_IMAGE','Tamaño de Imagen Grande');
define('_MI_DT_DESCIMAGE','Especifique el tamaño en pixeles de las imágenes');
define('_MI_DT_REDIMIMAGE','Tipo de Redimensión');
define('_MI_DT_CROPTHS','Recortar miniatura');
define('_MI_DT_CROPBIG','Recortar imagen grande');
define('_MI_DT_CROPBOTH','Recortar ambas');
define('_MI_DT_REDIM','Redimensionar');
define('_MI_DT_FILE','Tamaño de archivo de imagen');
define('_MI_DT_DESCSIZE','Tamaño de imagen en Kilobytes');

//Archivo de descarga
define('_MI_DT_SIZEFILE','Tamaño de archivo de descarga');
define('_MI_DT_DESCSIZEFILE','Especifique el tamaño del archivo en Kilobytes');

//Tipo de archivo
define('_MI_DT_TYPEFILE','Tipo de archivo de descarga');
define('_MI_DT_DESCTYPEFILE','Separe cada extensión con una "|" ');

//Aprobar ediciones
define('_MI_DT_APPROVEEDIT','Aprobar ediciones');

//Notificar cuando haya ediciones
define('_MI_DT_EDIT_NOTIF','Notificar cuando se realicen ediciones y no se hayan aprobado');

// URLS
define('_MI_DT_URLMODE','Utilizar URLs amigables');

//Limite de descargas nuevas
define('_MI_DT_LIMITRECENT','Límite de descargas nuevas');

//Días de característica nueva
define('_MI_DT_NEWFEAT','Días para considerar un elemento como Nuevo');
define('_MI_DT_DESCNEWFEAT','Número de días en que un elemento es considerado como nuevo.');
define('_MI_DT_UPDITEM','Días para considerar un elemento como Actualizado');
define('_MI_DT_DESCUPDITEM','Número de días en que un elemento es considerado como actualizado.');

// Categorías en las páginas
define('_MI_DT_SHOWCATS','Mostrar las categorías en las páginas del Módulo');

// Resultados por Página
define('_MI_DT_XPAGE','Resultados por Página');

// Tamaño de las screenshots
define('_MI_DT_SCREENTH','Tamaño de la Captura de Pantalla Miniatura');
define('_MI_DT_SCREENN','Tamaño de la Captura de Pantalla Normal');


//Mostrar etiquetas populares
define('_MI_DT_ACTIVETAGS','Mostrar etiquetas populares');

//Limite de etiquetas populares
define('_MI_DT_TOTAL_TAGSPOP','Límite de Etiquetas Populares');
define('_MI_DT_DESC_TOTAL_TAGSPOP','Determina el total de etiquetas populares  a visualizar');

//Limite de etiquetas
define('_MI_DT_TOTAL_TAGS','Límite de Etiquetas por programa');
define('_MI_DT_DESC_TOTAL_TAGS','Determina el total de etiquetas que se permitirán por programa');

//Mostrar programas relacionados
define('_MI_DT_ACTRELATSW','Mostrar programas relacionados');

//Limite de programas relacionados
define('_MI_DT_LIMITRELATSOFT','Limite de programas relacionados');
define('_MI_DT_DESC_LIMITRELATSOFT','Determina el total de programas relacionados a visualizar');

//Mostrar otros programas de la categoría
define('_MI_DT_ACTOTHERSW','Otros programas de la categoría');
define('_MI_DT_DESCOTHERSW','Especifica si se mostrarán otros programas de la categoría seleccionada');

//Limite de otros programas de la categoria
define('_MI_DT_LIMITOTHERSW','Limite de otros programas');
define('_MI_DT_DESC_LIMITOTHERSW','Determina el total de otros programas a visualizar');

//Tamaño de fuente de etiquetas
define('_MI_DT_SIZEFONTTAGS','Tamaño de fuente de etiquetas');
define('_MI_DT_DESCSIZEFONTTAGS','Determina el tamaño máximo de fuente de la etiqueta con mayor hit');

//Número de caracteres minimo de una etiqueta
define('_MI_DT_CARACTER_TAGS','Número de caracteres mínimo para una etiqueta');

//Horas de comprobación de alertas
define('_MI_DT_HRSALERTS','Número de horas de verificación para alertas');
define('_MI_DT_DESCHRSALERTS','Especifica el número de horas que deben transcurrir para comprobar el envío de alertas');

// Bloques
define('_MI_DT_BLOCKITEMS','Lista de Descargas');
define('_MI_DT_BLOCKITEMS_DESC','Bloque para presentar un listado de descargas');

define('_MI_DT_BLOCKTAGS','Búsquedas Populares');
define('_MI_DT_BLOCKTAGS_DESC','Presenta una lista de palabras mas búscadas en D-Transport');

// Sindicación
define('_MI_DT_RSSNAME','Sindicación de Descargas');
define('_MI_DT_RSSDESC','Opciones para la sindicación de descargas');
define('_MI_DT_RSSRECENT','Descargas Recientes');
define('_MI_DT_RSSRECENTDESC','Muestra las descargas agregadas recientemente.');
define('_MI_DT_RSSPOP','Descargas Populares');
define('_MI_DT_RSSPOPDESC','Muestra lo mas descargado.');
define('_MI_DT_RSSRATE','Descargas Mejor Valoradas');
define('_MI_DT_RSSRATEDESC','Descargas mejor valoradas por los usuarios.');

//Páginas del módulo
define('_MI_DT_INDEX','Página Inicial');
define('_MI_DT_CATEGORY','Categorías');
define('_MI_DT_PFEATURES','Características');
define('_MI_DT_PFILES','Archivos');
define('_MI_DT_ITEM','Detalles de la Descarga');
define('_MI_DT_PLOGS','Logs');
define('_MI_DT_MYDOWNS','Mis descargas');
define('_MI_DT_PSCREENS','Pantallas');
define('_MI_DT_TAGS','Etiquetas');
define('_MI_DT_SUBMIT','Enviar Descarga');
define('_MI_DT_SEARCH','Búsqueda');
define('_MI_DT_DOWNLOAD','Descargar Archivo');
define('_MI_DT_COMMENTS','Comentarios');
?>
