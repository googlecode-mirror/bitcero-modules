<?php
// $Id: admin.php 13 2009-08-31 00:45:24Z i.bitcero $
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

# Cadenas Generales
define('_AS_MW_DBOK','Base de datos actualizada correctamente');
define('_AS_MW_DBERROR','Ocurrió un error al realizar esta operación');
define('_AS_MW_ID','ID');
define('_AS_MW_ERRTOKEN','Identificador de sesión inválido');
define('_AS_MW_NEXTPAGE','Página Siguiente');
define('_AS_MW_PREVPAGE','Página Anterior');

# Barra de Navegación
define('_AS_MW_HOME','Estado del Módulo');
define('_AS_MW_CATEGOS','Categorías');
define('_AS_MW_POSTS','Artículos');
define('_AS_MW_EDITORS','Editores');
define('_AS_MW_CONFIGS','Configuraciones');
define('_AS_MW_IMPORT','Importar');

define('_AS_MW_ERRHTACCESS','El archivo <strong>"%s"</strong> no puede ser modificado por el servidor y es necesario hacerlo para cambiar el método de manejo de los enlaces.
		Por favor modifica los permisos de escritura del archivo o modificalo manualmente.');

switch (MW_LOCATION){
	case 'index':
		
		define('_AS_MW_STATUS','Estado Actual del Módulo');
		define('_AS_MW_ABOUT','Acerca de Natural Press');
		define('_AS_MW_MOREINFO','Información de Natural Press');
		define('_AS_MW_CATEGOSNUM','%s Categorías');
		define('_AS_MW_POSTSNUM','%s Artículos');
		define('_AS_MW_APPROVENUM','%s Aprobados');
		define('_AS_MW_APPROVENUM_ALT','%s Artículos Aprobados');
		define('_AS_MW_WAITNUM','%s Esperando');
		define('_AS_MW_WAITNUM_DESC','%s Artículos Esperando Aprobación');
		define('_AS_MW_COMSNUM','%s Comentarios');
		define('_AS_MW_TRACKSNUM','%s Trackbacks');
		define('_AS_MW_EDITORSNUM','%s Editores');
		define('_AS_MW_REPLACESNUM','%s Reemplazos');
		define('_AS_MW_DETAILS','Ver Detalles');
		define('_AS_MW_VISIT','Visitanos');
		define('_AS_MW_HELP','Ayuda');
		define('_AS_MW_SOCIAL','%u Sitios');
		
		define('_AS_MW_RECENTS','Últimos %s Artículos Enviados');
		define('_AS_MW_AUTHOR','Autor: ');
		define('_AS_MW_VERINFO','Información de MyWords');
		define('_AS_MW_SENTDATE','Enviado:');
		define('_AS_MW_APPROVED','Aprobado:');
		
		break;
		
	case 'categos':
		
		define('_AS_MW_CATLOCATION','Categorías');
		define('_AS_MW_NEWCAT','Crear Categoría');
		define('_AS_MW_LIST','Categorías');
		define('_AS_MW_NAME','Nombre');
		define('_AS_MW_DESC','Descripción');
		define('_AS_MW_CATPOSTS','Envíos');
		define('_AS_MW_NEWTITLE','Crear Nueva Categoría');
		define('_AS_MW_EDITTITLE','Editar Categoría');
		define('_AS_MW_CATPARENT','Categoría Raíz');
		define('_AS_MW_INMENU','Mostrar como Menú');
		define('_AS_MW_ISMENU','Menú');
		define('_AS_MW_READGROUPS','Grupos con Permiso de Lectura');
		define('_AS_MW_WRITEGROUPS','Grupos con Permiso de Escritura');
		define('_AS_MW_DELETECATEGO','Eliminar Categoría');
		
		// MENSAJES
		define('_AS_MW_CONFIRMDEL','<strong>¿Realmente deseas eliminar esta categoría?</strong>');
		define('_AS_MW_DELETEDESC','Esta acción no eliminará los artículos existentes, solo las relaciones entre ellos y la categoría por lo que deberás asignar dichos artículos a una nueva categoría.<br />Las subcategorías serán asignadas a la categoría superior.');
		
		// ERRORES
		define('_AS_MW_ERRNAME','Debes especificar un nombre para esta categoría');
		define('_AS_MW_ERREXISTS','Ya existe la categoría especcificada');
		define('_AS_MW_ERRID','Debes especificar una categoría válida');
		
		break;
	case 'posts':
		define('_AS_MW_POSTSPAGE','Artículos');
		define('_AS_MW_POSTSAPPROVEDTITLE','Artículos Aprobados');
		define('_AS_MW_POSTSWAITTITLE','Artículos Esperando Aprobación');
		define('_AS_MW_NEWPOST','Escribir Artículo');
		define('_AS_MW_TITLE','Título del Artículo');
		define('_AS_MW_DATEPOST','Fecha de Envío');
		define('_AS_MW_POSTCATS','Categorías');
		define('_AS_MW_ISAPPROVED','Aprobado');
		define('_AS_MW_TRACKBACKS','Trackbacks');
		define('_AS_MW_COMMENTS','Comentarios');
		define('_AS_MW_AUTHOR','Autor');
		define('_AS_MW_NEWPOSTTITLE','Crear Artículo');
		define('_AS_MW_EDITPOSTTITLE','Editar Artículo');
		define('_AS_MW_POSTTEXT','Contenido del Artículo');
		define('_AS_MW_CONTENTDESC','Para dividir el texto y mostrar solo una porción en la página principal escriba
							<strong>[home]</strong> en la parte que desea dividir. El texto que este despues de esta
							etiqueta será mostrado solo cuando el usuario ingrese al artículo.');
		define('_AS_MW_STATUS','Estado del Artículo');
		define('_AS_MW_PUBLIC','Público');
		define('_AS_MW_PRIVATE','Privado');
		define('_AS_MW_SAVEANDRETURN','Guardar y continuar editando');
		define('_AS_MW_SAVE','Guardar');
		define('_AS_MW_PUBLISH','Publicar');
		define('_AS_MW_SENDTRACKS','Enviar Trackbacks a:');
		define('_AS_MW_TRACKSDESC','Separe múltiples URIs con un espacio.<br /><a href="http://es.wikipedia.org/wiki/TrackBack" target="_blank">Trackbacks</a>');
		define('_AS_MW_OPTIONALDATA','Datos Opcionales');
		define('_AS_MW_TRACKSPINGED','Trackbacks enviados');
		define('_AS_MW_POSTFRIEND','Titulo para URL amigable');
		define('_AS_MW_ALLOWPINGS','Recibir Pings');
		define('_AS_MW_ONLYADVANCE','Mostrar solo avance en la página principal');
		define('_AS_MW_ONLYADVANCE_DESC','Para mostrar solo una parte de texto en la página principal inserte la palabra "<strong>[home]</strong>" en el lugar donde deseas dividir el texto.');
		define('_AS_MW_XCODE','Activar XoopsCode');
		define('_AS_MW_EXCERPT','Comentario para los trackbacks');
		define('_AS_MW_EXCERPTDESC','Este texto es enviado como comentario al hacer un ping al artículo enlazado. Si se deja en blanco el módulo enviara las primeras 50 palabras del cuerpo del artículo');
		define('_AS_MW_PUSER','Propietario del Artículo');
		define('_AS_MW_POSTSAPPROVED','Aprobados');
		define('_AS_MW_POSTSWAIT','Pendientes');
		
		define('_AS_MW_APPROVEPOSTS','aprobar Artículos');
		define('_AS_MW_UNAPPROVEPOSTS','No aprobar Artículos');
		
		define('_AS_MW_CONFIRMDEL','¿Realmente deseas eliminar este artículo?. Todos los comentarios hechos en el artículo serán también eliminados.');
		
		define('_AS_MW_COMMWAIT','Esperando Aprobación');
		define('_AS_MW_APROVADOS','Aprobados');
		define('_AS_MW_ALL','Todos');
		define('_AS_MW_USER','Usuario');
		define('_AS_MW_EMAIL','Email');
		define('_AS_MW_DATECOM','Enviado');
		define('_AS_MW_APPROVED','Aprobado');
		define('_AS_MW_APROVAR','aprobar');
		define('_AS_MW_DELSEL', 'Eliminar Elementos Seleccionados');
		define('_AS_MW_APROVESEL', 'aprobar Elementos Seleccionados');
		define('_AS_MW_CONFIRMDELCOMS','¿Realmente deseas eliminar los comentarios seleccionados?');
		define('_AS_MW_CONFIRMDELTRACK', '¿Realmente deseas eliminar este trackback?');
		define('_AS_MW_CONFIRMDELTRACKS','¿Realmente deseas eliminar los trackbacks seleccionados?');
		
		define('_AS_MW_TRACKSFOR','Trackbacks registrados para "%s"');
		define('_AS_MW_TRACKTITLE','Título');
		define('_AS_MW_BLOG','Blog');
		define('_AS_MW_URL','Url');
		define('_AS_MW_SEARCH','Buscar');
		define('_AS_MW_RESULTS','Resultados por Página');
		define('_AS_MW_SHOWALL','Mostrar Todos');
		
		define('_AS_MW_BLOCKIMG','Imágen para Mostrar en Bloque');
		
		define('_AS_MW_METATITLE','Campos Personalizados');
		
		# ERRORES
		define('_AS_MW_ERRTITLE','Por favor proporciona un título para este artículo');
		define('_AS_MW_ERRFRIENDTITLE','Por favor proporciona un titulo para la url amigable');
		define('_AS_MW_ERRTEXT','No haz escrito el contenido para este artículo');
		define('_AS_MW_ERRCATS', 'Debes seleccionar al menos una categoría para este artículo');
		define('_AS_MW_NOID','Especifica un artículo válido');
		define('_AS_MW_NOTRACKID','Debes especificar un trackback válido');
		define('_AS_MW_TRACKNOSEL','No has seleccionado Trackbacks');
		define('_AS_MW_SELECTONE','Selecciona al menos un elemento para modificar');
		
		break;
	
	case 'import':
		
		define('_AS_MW_NOINTALLED','El módulo <strong>"%s"</strong> no ha sido instalado aún');
		define('_AS_MW_CONFIRMIMPORT','¿Realmente deseas importar la información del módulo <strong>"%s"</strong>?');
		break;
	
	case 'editors':
		
		define('_AS_MW_EDITORSLNG','Lista de Editores Autorizados');
		define('_AS_MW_EDITORSTIP','Los <strong>editores</strong> son personas autorizadas para publicar artículos sin necesidad de moderación. Todos los artículos que estas personas envien serán publicados inmediatamente');
		define('_AS_MW_UNAME','Usuario');
		define('_AS_MW_JOINED','Desde');
		define('_AS_MW_POSTSSEND','Envíos');
		define('_AS_MW_SEEPOSTS','Ver Envíos');
		define('_AS_MW_SELECTUSER','Seleccionar Usuarios');
		define('_AS_MW_USERDESC','Puedes seleccionar varios usuarios al mismo tiempo para agregarlos como editores');
		define('_AS_MW_ADDEDITORS','Agregar Editores');
		define('_AS_MW_SEARCHUSR','Buscar Usuarios:');
		define('_AS_MW_SEARCHUSERS','Buscar');
		define('_AS_MW_CONFIRMDEL','¿Realmente deseas eliminar este editor?');
		define('_AS_MW_EDCATEGOS','Puede Publicar en las Categorías Siguientes');
		define('_AS_MW_ALL','Todas las Categorías');
		
		#ERRORES
		define('_AS_MW_ERRKEYWORD','Debes especificar una palabra para buscar');
		define('_AS_MW_ERRSELECT','Debes seleccionar al menos un usuario para agregar como editor');
		define('_AS_MW_ERRUID','Especifica un editor válido');
		
		break;
		
	case 'configs':
	
		define('_AS_MW_REPLACEMENTS','Reemplazos');
		define('_AS_MW_TOSEARCH','Condición de Búsqueda');
		define('_AS_MW_TOREPLACE','Condición de Reemplazo');
		define('_AS_MW_NEWREPLACE','Nueva Condición de Reemplazo');
		define('_AS_MW_EDITREPLACE','Editando Condición de Reemplazo');
		define('_AS_MW_CONFDEL','¿Realmente deseas eliminar esta condición de reemplazo?');
		
		# ERRORES
		
		define('_AS_MW_NODATA','Por favor completa todos los campos requeridos');
		define('_AS_MW_SEXISTS','Ya existe uan condición con el mismo texto de búsqueda');
		define('_AS_MW_NOID','Especifica una condición válida');
		
		break;
    
    case 'bookmarks':
        
        define('_AS_MW_BTITLE','Sitios para Marcadores Existentes');
        define('_AS_MW_BNAME','Nombre');
        define('_AS_MW_BURL','URL');
        define('_AS_MW_BACTIVE','Activo');
        
        define('_AS_MW_LOC','Sitios');
        define('_AS_MW_ADDBM','Agregar Sitio');
        define('_AS_MW_EDITBM','Editar Sitio');
        define('_AS_MW_ACTIVATE','Activar/Desactivar');
        
        // Formulario
        define('_AS_MW_NTITLE','Agregar Sitio para Marcadores');
        define('_AS_MW_ETITLE','Editar Sitio para Marcadores');
        define('_AS_MW_NAME','Nombre del Sitio');
        define('_AS_MW_URL','URL');
        define('_AS_MW_URLD','Agrega la url a donde se agregará el artículo. Utiliza {URL},{TITLE},{DESC} en la url para reemplazar por los datos del artículo');
        define('_AS_MW_ICON','Icono');
        define('_AS_MW_CURRENTICON','Icono Actual');
        define('_AS_MW_ACTIVE','Activo');
        define('_AS_MW_ALT','Texto Alternativo');
        
        // Mensajes
        define('_AS_MW_CONFDEL','¿Realmente deseas eliminar el elemento seleccionado?');
        define('_AS_MW_CONFDELS','¿Realmente deseas eliminar los sitios seleccionados?');
        
        // Errores
        define('_AS_MW_ERRID','Especifica un elemento válido');
        define('_AS_MW_ERRSEL','Selecciona al menos un elemento para modificar');
        
        break;
        
}
?>