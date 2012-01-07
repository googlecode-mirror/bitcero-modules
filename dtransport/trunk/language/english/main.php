<?php
// $Id: main.php 37 2008-03-03 18:46:45Z BitC3R0 $
// --------------------------------------------------------------
// D-Transport
// Módulo para la administración de descargas
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
// @copyright: 2007 - 2008 Red México

// Cadenas para el encabezado
define('_MS_DT_MINE','Mis Descargas');
define('_MS_DT_SUBMIT','Enviar Descarga');
define('_MS_DT_SEARCH','Buscar');
define('_MS_DT_RECENTS','Novedades');
define('_MS_DT_POPULAR','Populares');
define('_MS_DT_BESTRATE','Mejor Valorado');
define('_MS_DT_DOWNLOAD','Descargar');
define('_MS_DT_READMORE','Leer Mas &raquo;');
define('_MS_DT_INFO','Información');
define('_MS_DT_DOWNS','Descargas');
define('_MS_DT_RATE','Valoración');
define('_MS_DT_LIC','Licencia:');
define('_MS_DT_OS','Plataforma:');
define('_MS_DT_CREATED','Creado:');
define('_MS_DT_MODIFIED','Modificado:');
define('_MS_DT_TOTAL','Total');
define('_MS_DT_USERS','Usuarios');
define('_MS_DT_SCREENS','Capturas');
define('_MS_DT_NAME','Nombre');
define('_MS_DT_CORDER','Creado');
define('_MS_DT_CMODIFIED','Modified');
define('_MS_DT_ORDERBY','Ordenar Por');
define('_MS_DT_ORDERU','Usuarios');
define('_MS_DT_YOUREHERE','Estas Aquí:');
define('_MS_DT_NEW','Nuevo!');
define('_MS_DT_UPDATEDA','Actualizado!');
define('_MS_DT_DBOK','Base de Datos Actualizada Correctamente');
define('_MS_DT_DBERROR','Error al realizar esta operación');	
define('_MS_DT_MYDOWNS','Mis Descargas');
define('_MS_DT_ID','ID');
define('_MS_DT_ERRUSER','Esta descarga no ha sido registrada por usted');

switch(DT_LOCATION){
	
	case 'category':
		
		define('_MS_DT_MARKED','Descargas Destacadas');
		define('_MS_DT_DAYDOWN','Descargas del Día en %s');
		define('_MS_DT_DOWNIN', 'Descargas en %s');
		define('_MS_DT_SHOWING', 'Resultados %u a %u de %u');
		define('_MS_DT_CATS', 'Categorías');
		define('_MS_DT_SUBCATS', 'Subcategorías');
		
		// ERRORES
		define('_MS_DT_ERRID','Debes especificar una categoría válida');
	
		break;
	
	case 'item':
		
		define('_MS_DT_GENERALD','Datos Generales');
		define('_MS_DT_SCREENSNUM','%u Imágenes');
		define('_MS_DT_SCREENSS','%u Imágen');
		define('_MS_DT_COMSNUM','%u Comentarios');
		define('_MS_DT_COMSS','%u Comentario');
		define('_MS_DT_REGISTER','Registrado:');
		define('_MS_DT_UPDATED','Actualizado:');
		define('_MS_DT_AUTHOR','Autor:');
		define('_MS_DT_LANG','Idioma:');
		define('_MS_DT_SIZE','Tamaño:');
		define('_MS_DT_DOWNSNUM','Descargas:');
		define('_MS_DT_VERSION','Versión:');
		define('_MS_DT_DWONNOW','Descargar');
		define('_MS_DT_BY','Por <em>%s</em>');
		define('_MS_DT_FEATURES','Características');
		define('_MS_DT_FEATURESOP','%u Características');
		define('_MS_DT_FEATURESOPS','%u Característica');
		define('_MS_DT_LOGSOP','%u Log de Cambios');
		define('_MS_DT_LOGS','Registro de Cambios');
		define('_MS_DT_IMAGES','Imágenes de <span>%s</span>');
		define('_MS_DT_IMAGESLOC','Imágenes');
		define('_MS_DT_FEATSLOC','Características');
		define('_MS_DT_LOGSLOC','Registros');
		define('_MS_DT_LOGSTITLE','Registros de %s');
		define('_MS_DT_FILES','Archivos Adicionales');
		define('_MS_DT_FILE','Archivo');
		define('_MS_DT_FSIZE','Tamaño');
		define('_MS_DT_FDOWNS','Descargas');
		define('_MS_DT_FDATE','Fecha');
		define('_MS_DT_ITEMSREL','Programas Relacionados');
		define('_MS_DT_ITEMSOTHER','Otros Programas en %s');
		define('_MS_DT_TAGS','Etiquetas:');
		define('_MS_DT_RATEUSER','Calificación:');
		define('_MS_DT_VOTE','Calificar:');
		define('_MS_DT_VOTEBUTTON','Votar');
		
		// Imágenes
		define('_MS_DT_OPINE','Opinar');
		
		// ERRORES
		define('_MS_DT_ERRID','Debes especificar un elemento válido');
		define('_MS_DT_NOIMGS','Esta descarga no tiene imágenes');
		define('_MS_DT_NOFEATS','No han sido especificadas características para esta descarga');
		define('_MS_DT_NOLOGS','No existen registros para esta descarga');
		define('_MS_DT_NOFILE','El archivo especificado no es válido');
		define('_MS_DT_NODOWN','No tienes autorización para descargar este archivo');
		
		break;
	
	case 'download':
		
		// ERRORES
		define('_MS_DT_NOFILE','No existe el archivo especificado');
		define('_MS_DT_NOITEM','No hemos podido localizar la descarga especificada');
		define('_MS_DT_NODOWN','No tienes autorización para descargar este archivo');
		define('_MS_DT_DOWNLIMIT','Has alcanzado el número máximo de descargas para este archivo.');
		define('_MS_DT_NOEXISTSFILE','Existe un problema con el archivo seleccionado. Por favor inente mas tarde o seleccione otro archivo para descargar.<br />Por favor reporte este error al administrador.');
		
		break;
	
	case 'search':
		
		define('_MS_DT_SHOWING', 'Resultados %u a %u de %u');
		define('_MS_DT_SEARCHLOC', 'Buscar Descargas');
		define('_MS_DT_DOWNSRECENT','Novedades');
		define('_MS_DT_DOWNSPOPULAR','Descargas Populares');
		define('_MS_DT_DOWNSRATED','Descargas Mejor Valoradas');

		//Errores
		define('_MS_DT_ERRSEARCH','Debes proporcionar al menos una palabra para la búsqueda');
		
		break;
	case 'rate':

		define('_MS_DT_VOTEOK','Tu voto ha sido registrado.<br />¡Gracias por Votar!');
		
		//Errores
		define('_MS_DT_ERRIDVALID','Debes especificar un elemento válido');
		define('_MS_DT_ERRIDEXIST','Elemento no existente');
		define('_MS_DT_NORATE','La calificación proporcionada no es válida');
		define('_MS_DT_NODAY','Solo puedes votar una vez por día por cada recurso.<br />Por favor vuelve a intentarlo mañana.');
		define('_MS_DT_VOTEFAIL','Ocurrió un error al intentar registrar tu voto. Por favor intenalo mas tarde');


		break;
	case 'tags':

		define('_MS_DT_MARKED','Descargas Destacadas');
		define('_MS_DT_DAYDOWN','Descargas del Día en %s');
		define('_MS_DT_DOWNIN', 'Descargas en %s');
		define('_MS_DT_SHOWING', 'Resultados %u a %u de %u');
		define('_MS_DT_TAGS', 'Etiquetas');
		
		// ERRORES
		define('_MS_DT_ERRID','Debes especificar una etiqueta válida');

		break;
	case 'submit':

		define('_MS_DT_SEND','Enviar descarga');
		define('_MS_DT_SUBJECT','Descarga Enviada %s');
		define('_MS_DT_SUBJECTEDIT','Descarga Editada %s');
		

		//Formulario
		define('_MS_DT_EDITSW','Editar Descarga');
		define('_MS_DT_CREASW','Crear Descarga');
		define('_MS_DT_CATEGO','Categoría');
		define('_MS_DT_SHORTDESC','Descripción Corta');
		define('_MS_DT_DESC','Descripción');
		define('_MS_DT_IMAGE','Imagen');
		define('_MS_DT_IMAGEACT','Imagen Actual');
		define('_MS_DT_TAGS','Etiquetas');
		define('_MS_DT_DESCTAGS','Separar con un espacio " " las etiquetas');
		define('_MS_DT_NEWFILES','Crear Archivos');
		define('_MS_DT_NEWLOG','Crear Log');
		define('_MS_DT_ALERT','Alerta');
		define('_MS_DT_ACTALERT','Activar alerta');
		define('_MS_DT_MP','Mensaje Privado');
		define('_MS_DT_EMAIL','Correo Electrónico');
		define('_MS_DT_LIMIT','Límite de días');
		define('_MS_DT_DESCLIMIT','Límite en días de inactividad del <br />elemento antes de enviar la alerta');
		define('_MS_DT_MODE','Modo de envío de alerta');
		define('_MS_DT_LICENCES','Licencias');	
		define('_MS_DT_PLATFORMS','Plataformas');
		define('_MS_DT_LICOTHER','Otra...');
		define('_MS_DT_VERSION','Versión');
		define('_MS_DT_OTHER','Otros Datos');
		define('_MS_DT_AUTHOR','Nombre del Autor');
		define('_MS_DT_AUTHORURL','URL del Autor');
		define('_MS_DT_LANGS','Idiomas Disponibles');

		


		//Errores
		define('_MS_DT_ERRSAVE','Error al realizar la operación. No se almacenaron los datos');
		define('_MS_DT_ERRID','Debes especificar un elemento válido');
		define('_MS_DT_ERREXIST','Elemento no existente');
		define('_MS_DT_ERRNOTDOWNS','Lo sentimos,no está activado el envío de descargas');
		define('_MS_DT_ERRUSERDOWNS','Lo sentimos, no perteneces a los grupos con permisos de envío de descargas');
		
		
		break;
	case 'mydowns':
		define('_MS_DT_SHOWING', 'Mostrando elementos <strong>%u</strong> a <strong>%u</strong> de <strong>%u</strong>.');

		//Tabla
		define('_MS_DT_DATE','Fecha');
		define('_MS_DT_NUMDOWNS','Descargas');
		define('_MS_DT_LEGEND','Leyenda:');
		define('_MS_DT_SCREEN','Pantallas');
		define('_MS_DT_FEATURES','Características');
		define('_MS_DT_FILES','Archivos');
		define('_MS_DT_LOGS','Logs');
		define('_MS_DT_APPROVED','Aprobado');
		
		//Errores
		define('_MS_DT_NOTDOWNS','No existe ninguna descarga registrada por usted');


		break;
	case 'screens':
		define('_MS_DT_NEWSCREEN','Crear Pantalla');
		define('_MS_DT_EXISTITEM','Pantallas Existentes de %s ');
		define('_MS_DT_DELETESCREEN','¿Realmente deseas eliminar la pantalla ?\nLos datos se eliminarán permanentemente.'); 
		define('_MS_DT_DELETESCREENS','¿Realmente deseas eliminar las pantallas ?\nLos datos se eliminarán permanentemente.'); 
		define('_MS_DT_SCREEN','Pantallas');
		
		//Tabla
		define('_MS_DT_EDITSCREEN','Editar Pantalla');
		define('_MS_DT_TITLE','Título');
		define('_MS_DT_DESC','Descripción');
		define('_MS_DT_IMAGE','Imagen');
		define('_MS_DT_ITEM','Software');
		define('_MS_DT_IMAGEACT','Imagen Actual');
		define('_MS_DT_NEWSCREENS','Crear pantalla de %s');
		define('_MS_DT_EDITSCREENS','Editar pantalla de %s');
	
		//Errores
		define('_MS_DT_ERRIDITEM','Elemento no válido');
		define('_MS_DT_ERRITEMEXIST','Elemento no existente');
		define('_MS_DT_ERRID','Pantalla no válida');
		define('_MS_DT_ERREXIST','Pantalla no existente');
		define('_MS_DT_ERRNAME','Nombre de pantalla existente');
		define('_MS_DT_NOTSCREEN','Debes proporcionar al menos una pantalla para eliminar');
		define('_MS_DT_ERRSCVAL','Pantalla %s no válida <br />');
		define('_MS_DT_ERRSCEX','Pantalla %s no existente <br />');
		define('_MS_DT_ERRSCDEL','Pantalla %s no eliminada <br />');

	
		break;
	case 'files':

		define('_MS_DT_SAVE','Guardar Cambios');
		define('_MS_DT_EXIST','Archivos Existentes de %s');
		define('_MS_DT_DELETEGR','¿Realmente deseas eliminar el grupo %s ?\n Los datos se eliminarán permanentemente.');
		define('_MS_DT_DELETEFILE','¿Realmente deseas eliminar el archivo?\n Los datos se eliminarán permanentemente.');
		define('_MS_DT_DELETEFILES','¿Realmente deseas eliminar los archivos ?\n Los datos se eliminarán permanentement.');
		define('_MS_DT_FILES','Archivos');

		//Tabla
		define('_MS_DT_EDITFILE','Editar Archivo');
		define('_MS_DT_FILE','Archivo');
		define('_MS_DT_DESCTYPEFILE','Tipos de extensiones permitidas %s <br /> El tamaño permitido del archivo es de %s');
		define('_MS_DT_NAMEFILE','Nombre de archivo');
		define('_MS_DT_FILEURL','URL de archivo');
		define('_MS_DT_REMOTE','Archivo remoto');
		define('_MS_DT_GROUP','Grupo');
		define('_MS_DT_DEFAULT','Archivo a descargar por defecto');
		define('_MS_DT_SIZE','Tamaño de archivo');
		define('_MS_DT_DESCSIZE','Indique el tamaño de archivo si solo ha proporcionado la URL.<br /> Especifique el tamaño en kilobytes.');
		define('_MS_DT_DEFAULTFILE','Predeterminado');
		define('_MS_DT_NEWFILES','Crear archivo de %s');
		define('_MS_DT_EDITFILES','Editar archivo de %s');
		define('_MS_DT_CREATEGROUP','Crear Grupo');
		define('_MS_DT_CREATEFILE','Crear Archivo');
	
		define('_MS_DT_NEWGROUP','Crear Grupo');
		define('_MS_DT_EDITGROUP','Editar Grupo');

		//Errores
		define('_MS_DT_ERRFILEVALID','Archivo no válido');
		define('_MS_DT_ERRFILEEXIST','Archivo no existente');
		define('_MS_DT_ERR_ITEMVALID','Software no válido');
		define('_MS_DT_ERR_ITEMEXIST','Software no existente');
		define('_MS_DT_ERRNAMEGROUP','Grupo Existente');
		define('_MS_DT_ERRVALID','Archivo %s no válido <br />');
		define('_MS_DT_ERREXIST','Archivo %s no existente <br />');
		define('_MS_DT_ERRUPDATE','No se modificó el archivo %s <br />');
		define('_MS_DT_ERRNOTEXT','Este tipo de archivo no esta permitido: %s');
		define('_MS_DT_ERRNOEXISTFILE','El archivo no existe en el directorio de descargas seguras.<br /> Por favor antes cargue el archivo en el directorio: <br /> %s ');

		define('_MS_DT_ERRGROUPVALID','Grupo no válido');
		define('_MS_DT_ERRGROUPEXIST','Grupo no existente');
		define('_MS_DT_ERRFILEVAL','Archivo %s no válido <br />');
		define('_MS_DT_ERRFILEEX','Archivo %s no existente <br />');
		define('_MS_DT_ERRFILEDEL','Archivho %s no ha sido eliminado <br />');
		define('_MS_DT_NOTFILE','Debes proporcionar al menos un archivo para eliminar');
		define('_MS_DT_ERRSIZE','Tamaño de archivo no especificado. Es necesario proporcionarlo.');
		
		break;
	case 'logs':
		define('_MS_DT_LOGS','Logs');
		define('_MS_DT_NEWLOG','Crear Log');
		define('_MS_DT_EDITLOG','Editar Log');
		define('_MS_DT_TITLE','Título');
		define('_MS_DT_DATE','Fecha');
		define('_MS_DT_EXIST','Logs Existentes de %s');
		define('_MS_DT_DELETELOG','¿Realmente deseas eliminar el log?');
		define('_MS_DT_DELETELOGS','¿Realmente deseas eliminar los logs?');
				

		//Formulario
		define('_MS_DT_NEWLOGS','Crear log de %s');
		define('_MS_DT_EDITLOGS','Editar log de %s');
		define('_MS_DT_SOFTWARE','Software');
		define('_MS_DT_LOG','Log');

		//Errores
		define('_MS_DT_ERR_ITEMVALID','Software no válido');
		define('_MS_DT_ERR_ITEMEXIST','Software no existente');
		define('_MS_DT_ERRLOGVALID','Log no válido');
		define('_MS_DT_ERRLOGEXIST','Log no existente');
		define('_MS_DT_NOTLOG','Debes proporcionar al menos un log para eliminar');
		define('_MS_DT_ERRLOGVAL','Log %s no válido <br />');
		define('_MS_DT_ERRLOGEX','Log %s no existente <br />');
		define('_MS_DT_ERRLOGDEL','Log %s no eliminado <br />');
		break;
	case 'features':
		define('_MS_DT_FEATURES','Características');
		define('_MS_DT_NEWFEATURE','Crear Característica');
		define('_MS_DT_EXISTS','Características Existentes de %s');
		define('_MS_DT_DELETECONF','¿Realmente deseas eliminar la característica <strong>%s</strong>?');
		define('_MS_DT_DELCONF','¿Realmente deseas eliminar las características?');
		define('_MS_DT_SELECTITEM','Selecciona un elemento para visualizar su lista de características');
		define('_MS_DT_DELETEFEATURE','Eliminar Característica');	
		define('_MS_DT_CREATEDF','Creada');
		define('_MS_DT_DELETEFEAT','¿Realmente deseas eliminar la característica?\n Los datos se eliminarán permanentemente.');
		define('_MS_DT_DELETEFEATS','¿Realmente deseas eliminar las características?\n Los datos se eliminarán permanentemente.');

		//Formulario
		define('_MS_DT_EDITFEATURE','Editar Característica');
		define('_MS_DT_SOFTWARE','Software');
		define('_MS_DT_TITLE','Título');
		define('_MS_DT_MODIFIEDF','Modificación');
		define('_MS_DT_CONTENT','Contenido');
		define('_MS_DT_NEWFEATURES','Crear característica de %s');
		define('_MS_DT_EDITFEATURES','Editar característica de %s');


		//Errores
		define('_MS_DT_ERRFEATVALID','Característica no válida');
		define('_MS_DT_ERRFEATEXIST','Característica no existente');
		define('_MS_DT_ERRNAME','Error nombre de característica existente');
		define('_MS_DT_ERR_ITEMVALID','Software no válido');
		define('_MS_DT_ERR_ITEMEXIST','La descarga especificada no existe');
		define('_MS_DT_ERRFEATVAL','Característica %s no válida <br />');
		define('_MS_DT_ERRFEATEX','Característica %s no existente <br />');
		define('_MS_DT_ERRFEATSAVE','Característica %s no actualizada <br />');
		define('_MS_DT_ERRFEAT','Debes proporcionar al menos una característica');
		define('_MS_DT_ERRFEATDEL','No se pudo eliminar la característica %s <br />');
	

		break;
	case 'index':
	default:
		
		define('_MS_DT_DAYDOWN','Descargas del Día');
		define('_MS_DT_MARKED','Descargas Destacadas');
		
		// Tabla Recientes
		define('_MS_DT_RECENTSLIST','Novedades');
		
		define('_MS_DT_CATEGOS','Categorías');
		define('_MS_DT_TAGSPOPULAR','Búsquedas Populares');
		define('_MS_DT_HITS','Hits: ');
		
		break;
		
}

?>
