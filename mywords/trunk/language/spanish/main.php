<?php
// $Id: main.php 13 2009-08-31 00:45:24Z i.bitcero $
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

define('_MS_MW_PERMALINK','Link permanente a %s');
define('_MS_MW_PUBLISH','Publicado el %s a las %s por %s');
define('_MS_MW_COMMENTON','Comenta sobre $s');
define('_MS_MW_NUMCOMS','<strong>%u</strong> Comentarios &raquo;');
define('_MS_MW_NUMCOMSL','%u Comentarios');
define('_MS_MW_COMS','Comentarios');
define('_MS_MW_NUMTRACKSL','%u Trackbacks');
define('_MS_MW_CONTINUE','Continúa leyendo %s &raquo;');
define('_MS_MW_CATEGOS','Publicado en:');
define('_MS_MW_SENDCOMMENT','Enviar un comentario');
define('_MS_MW_NAME','Nombre');
define('_MS_MW_MAIL','Mail');
define('_MS_MW_NOPUBLISH','No será publicado');
define('_MS_MW_YOUCANUSE','Puedes usar las siguientes etiquetas:<br />%s');
define('_MS_MW_USERSAY','<strong>%s</strong> dice:');
define('_MS_MW_WRITECOM','Escribir Comentario &raquo;');
define('_MS_MW_COMMOK','Tu comentario ha sido agregado. Gracias');
define('_MS_MW_NEXTPAGE','Página Siguiente');
define('_MS_MW_PREVPAGE','Página Anterior');
define('_MS_MW_NOAUTHCOM','Lo sentimos, no tienes autorización para enviar comentarios');
define('_MS_MW_COMPENDING','Tu comentario ha sido enviado y esta en espera de ser aprovado por el administrador. Gracias');
define('_MS_MW_BLOGNAME','Blog:');
define('_MS_MW_URL','URL:');
define('_MS_MW_SECCODE','Código de Seguridad');
define('_MS_MW_ANONYMOUS','Anónimo');
define('_MS_MW_READS','<strong>%s</strong> Lecturas');
define('_MS_MW_ALLOWEDTAGS','Etiquetas HTML permitidas: <code>%s</code>');

// Categorías (24/02/2009)
define('_MS_MW_POSTSINCAT','Artículos en la Categoría &#8216;%s&#8217;');

// Autores
define('_MS_MW_POSTSFROMAUTHOR','Artículos de &#8216;%s&#8217;');

# FORMULARIO DE ENVIO DE ARTÍCULOS
define('_MS_MW_FORMTITLE','Enviar Artículo');
define('_MS_MW_EDITINGTITLE','Editando "%s"');
define('_MS_MW_POSTTITLE','Título del Artículo');
define('_MS_MW_CONTENT','Contenido del Artículo');
define('_MS_MW_POSTCATS','Categorías');
define('_MS_MW_SENDTRACKS','Enviar Trackbacks a:');
define('_MS_MW_TRACKSDESC','Separe múltiples URIs con un espacio.<br /><a href="http://es.wikipedia.org/wiki/TrackBack" target="_blank">Trackbacks</a>');
define('_MS_MW_EXCERPT','Comentario para los trackbacks');
define('_MS_MW_EXCERPTDESC','Este texto es enviado como comentario al hacer un ping al artículo enlazado. Si se deja en blanco el módulo enviara las primeras 50 palabras del cuerpo del artículo');
define('_MS_MW_SAVEANDRETURN','Guardar y continuar editando');
define('_MS_MW_SAVE','Guardar');
define('_MS_MW_BUTPUBLISH','Publicar');
define('_MS_MW_DBOK','Base de datos actualizada correctamente');
define('_MS_MW_SECURITYCODE','Código de Verificación');
define('_MS_MW_OPTIONALDATA','Datos Opcionales');
define('_MS_MW_TRACKSPINGED','Trackbacks enviados');
define('_MS_MW_POSTFRIEND','Titulo para URL amigable');
define('_MS_MW_ONLYADVANCE','Mostrar solo avance en la página principal');
define('_MS_MW_ONLYADVANCE_DESC','Muestra solo un determinado número de palabras cuando el artículo es listado en la página principal o dentro de las categorías.');
define('_MS_MW_STATUS','Estado del Artículo');
define('_MS_MW_PUBLIC','Público');
define('_MS_MW_PRIVATE','Privado');

# ERRORES
define('_MS_MW_ERRNOPOST','No es válido el artículo especificado');
define('_MS_MW_ERRNAME','Por favor escribe tu nombre');
define('_MS_MW_ERRMAIL','Por favor escribe tu email');
define('_MS_MW_ERRTEXT','No has escrito nada en el comentario');
define('_MS_MW_ERRMAILINV','Al parecer el mail que proporcionaste no es válido');
define('_MS_MW_ERRCOMM','Ocurrió un error al enviar tu comentario. Por favor vuelve a intentarlo');
define('_MS_MW_ERRCATFOUND','No hemos podido encontrar la categoría especificada');
define('_MS_MW_ERRCODE','El código de seguridad introducido no es válido');

define('_MS_MW_URLMISSING','Es necesario que especifiques la URL desde la que nos enlazas');
define('_MS_MW_NOTITLE','Por favor, porporciona el título para tu comentario');
define('_MS_MW_NOEXCERPT','Debes proporcionar el texto para el comentario');
define('_MS_MW_NOBLOGNAME','No has especificado el nombre de tu blog');
define('_MS_MW_NOPINGS','Trackbacks desactivados');
define('_MS_MW_EXISTSTRACK','Ya existe una referencia a un trackback con el mismo título desde tu blog');
define('_MS_MW_ERRDB','Ocurrió un error, por favor inténtalo mas tarde');

define('_MS_MW_ERRTOKEN','Identificador de sesión inválido');
define('_MS_MW_UNABLESEND','El envío de artículos esta desactivado.');
define('_MS_MW_YOUNOTSEND','No tienes autorización para enviar artículos');
define('_MS_MW_ERRTITLE','Por favor proporciona un título para este artículo');
define('_MS_MW_ERRFRIENDTITLE','Por favor proporciona un titulo para la url amigable');
define('_MS_MW_ERRSUBMITTEXT','No haz escrito el contenido para este artículo');
define('_MS_MW_ERRCATS', 'Debes seleccionar al menos una categoría para este artículo');
define('_MS_MW_ERREXISTS','Ya existe un artículo con el mismo nombre para el día de hoy');
define('_MS_MW_DBERROR','Ocurrió un error al realizar esta operación. Por favor vuelve a intentarlo.');
define('_MS_MW_ERRFRIENDNAME','Error en el titulo corto');
define('_MS_MW_ERRNOACCESS','Lo sentimos, no tienes suficientes privilegios para leer los artículos dentro de esta categoría');

?>