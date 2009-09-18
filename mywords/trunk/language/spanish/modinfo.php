<?php
// $Id: modinfo.php 13 2009-08-31 00:45:24Z i.bitcero $
// --------------------------------------------------------
// MyWords
// Manejo de Artículos
// CopyRight © 2007 - 2008. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.exmsystem.net
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
// @copyright: 2007 - 2008 Red México
// @author: BitC3R0

include_once XOOPS_ROOT_PATH.'/rmcommon/utilities.class.php';
$mc =& RMUtils::moduleConfig('mywords');

define('_MI_MW_DESC','Módulo para la publicación y manejo de noticias estilo Blog');

define('_MI_MW_AMENU1','Estado del Módulo');
define('_MI_MW_AMENU2','Categorías');
define('_MI_MW_AMENU3','Artículos');
define('_MI_MW_AMENU4','Editores');
define('_MI_MW_AMENU5','Marcadores');

// Menu principal
define('_MI_MW_SEND', 'Enviar Artículo');

//OPCIONES DE CONFIGURACIÓN
define('_MI_MW_FORM_DHTML', 'DHTML');
define('_MI_MW_FORM_COMPACT', 'Compacto');
define('_MI_MW_FORM_TINY', 'TinyMCE');
define('_MI_MW_FORM_HTMLAREA','TextArea');
define('_MI_MW_FORM_FCK', 'FCKEditor');
define('_MI_MW_CNFEDITOR','Tipo de Editor');
# Permalinks
define('_MI_MW_PERMAFORMAT','Formato de los Enlaces');
define('_MI_MW_PERMA_DESC','Determina la forma en que se mostrarán y se procesarán los enlaces en el módulo.');
define('_MI_MW_PERMA_DEF','Por Defecto');
define('_MI_MW_PERMA_DATE','Basado en Fecha y Nombre');
define('_MI_MW_PERMA_NUMS','Numérico');
# Fechas
define('_MI_MW_DATEFORMAT','Formato de la Fecha');
define('_MI_MW_DATE_DESC','Establece el modo en que se mostrará la fecha. Ej. <strong>m/d/Y</strong>.<br />Vea la <a href="http://www.php.net/manual/es/function.date.php" target="_blank">documentación</a>.');
define('_MI_MW_HOURFORMAT','Formato de la Hora');
# Envio de Articulos
define('_MI_MW_ALLOWSUBMIT','Permitir el envío de Artículos');
define('_MI_MW_ALLOWANONYM','Permitir el envío de Artículos a los usuarios anónimos');
# Activar Pings
define('_MI_MW_ALLOWPINGS','Activar recepción de Pings');
define('_MI_MW_ALLOWPINGS_DESC','Activa el almacenamiento de los sitios que realizan trackbacks a los artículos publicados en Natural Press');
# Palabras en la página principal
define('_MI_MW_HOMEWORDS','Número de palabras en la página principal');
define('_MI_MW_HOMEWORDS_DESC','Si se activa la opción "Mostrar solo avance en la página principal" para un artículo entonces el módulo mostrará este número máximo de palabras.');
# Archivos por página
define('_MI_MW_LIMITE','Número de Archivos por Página');
# Hoja de estilos
define('_MI_MW_CSS','Utilizar hoja de estilos css del módulo');
# Comentarios
define('_MI_MW_COMMAN','Permitir a los usuarios anónimos enviar comentarios');
define('_MI_MW_COMMMOD','Los comentarios requieren aprovación');
# Etiquetas permitidas en los comentarios
define('_MI_MW_TAGS','Etiquetas permitidas en los comentarios');
# Activar XOOPS Code
define('_MI_MW_XCODE','Activar XoopsCode');
# Comentarios por página
define('_MI_MW_COMSNUM','Núemero de comentarios por página');
# Autoaprovación
define('_MI_MW_USERAUTO','Aprovar artículos enviados por Usuarios Registrados');
define('_MI_MW_ANOAUTO','Aprovar artículos enviados por Usuarios Anónimos');
# Manejador de Imágenes
define('_MI_MW_IMAGEMANAGER','Permitir a los Usuarios Registrados utilizar el manejador de Imágenes');
define('_MI_MW_IMAGEUPLOAD','Permitir a los Usuarios Registrados subir imágenes');
define('_MI_MW_IMAGEMANAGERAN','Permitir a los Usuario Anónimos utilizar el manejador de Imágenes');
define('_MI_MW_IMAGEUPLOADAN','Permitir a los Usuarios Anónimos subir imágenes');
#Trackbacks
define('_MI_MW_TRACKLEN','Longitud predeterminada para el texto enviado en trackbacks');
# Imágenes para los bloques
define('_MI_MW_BIMGSIZE','Tamaño de la Imágen para los Bloques');
define('_MI_MW_BIMGSIZE_DESC','La imágen especificada en el artículo será redimensionada con estas medidas. Formato "ancho|alto"');
define('_MI_MW_DEFIMG','Imágen por defecto para los artículos en bloques');
define('_MI_MW_DEFIMG_DESC','Cuando se active el modo "gráfico" en el bloque "Articulos Recientes" se utilizará esta imágen siempre que no se haya especificado una para el artículo');

define('_MI_MW_FILESIZE','Tamaño máximo del archivo');

define('_MI_MW_BASEPATH','Directorio Base');
define('_MI_MW_BASEPATHD','Este directorio es utilizado cuando el formato de los enlaces se base en fecha o en numero.');

// BLOQUES
define('_MI_MW_BKCATEGOS','Categorías');
define('_MI_MW_BKRECENT','Artículos Recientes');
define('_MI_MW_BKCOMMENTS','Comentarios Recientes');

// RSS
define('_MI_MW_RSSNAME','Sindicación de Artículos');
define('_MI_MW_RSSNAMECAT','Sindicación de Artículos en %s');
define('_MI_MW_RSSDESC','Descripción para la Sindicación');
define('_MI_MW_RSSALL','Todos los Artículos Recientes');
define('_MI_MW_RSSALLDESC','Muestra todos los artículos agregados recientemente');

// Subpáginas
define('_MI_MW_SPINDEX','Página Inicial');
define('_MI_MW_SPPOST','Artículo');
define('_MI_MW_SPCATEGO','Categoría');
define('_MI_MW_SPAUTHOR','Autor');
define('_MI_MW_SPSUBMIT','Enviar Artículo');

?>