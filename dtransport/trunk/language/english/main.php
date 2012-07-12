<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage files for download in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

// Cadenas para el encabezado
define('_MS_DT_MINE',__('Mis Descargas','dtransport'));
define('_MS_DT_SUBMIT',__('Enviar Descarga','dtransport'));
define('_MS_DT_SEARCH',__('Buscar','dtransport'));
define('_MS_DT_RECENTS',__('Novedades','dtransport'));
define('_MS_DT_POPULAR',__('Populares','dtransport'));
define('_MS_DT_BESTRATE',__('Mejor Valorado','dtransport'));
define('_MS_DT_DOWNLOAD',__('Descargar','dtransport'));
define('_MS_DT_READMORE',__('Leer Mas &raquo;','dtransport'));
define('_MS_DT_INFO',__('Información','dtransport'));
define('_MS_DT_DOWNS',__('Descargas','dtransport'));
define('_MS_DT_RATE',__('Valoración','dtransport'));
define('_MS_DT_LIC',__('Licencia:','dtransport'));
define('_MS_DT_OS',__('Plataforma:','dtransport'));
define('_MS_DT_CREATED',__('Creado:','dtransport'));
define('_MS_DT_MODIFIED',__('Modificado:','dtransport'));
define('_MS_DT_TOTAL',__('Total','dtransport'));
define('_MS_DT_USERS',__('Usuarios','dtransport'));
define('_MS_DT_SCREENS',__('Capturas','dtransport'));
define('_MS_DT_NAME',__('Nombre','dtransport'));
define('_MS_DT_CORDER',__('Creado','dtransport'));
define('_MS_DT_CMODIFIED',__('Modified','dtransport'));
define('_MS_DT_ORDERBY',__('Ordenar Por','dtransport'));
define('_MS_DT_ORDERU',__('Usuarios','dtransport'));
define('_MS_DT_YOUREHERE',__('Estas Aquí:','dtransport'));
define('_MS_DT_NEW',__('Nuevo!','dtransport'));
define('_MS_DT_UPDATEDA',__('Actualizado!','dtransport'));
define('_MS_DT_DBOK',__('Base de Datos Actualizada Correctamente','dtransport'));
define('_MS_DT_DBERROR',__('Error al realizar esta operación','dtransport'));	
define('_MS_DT_MYDOWNS',__('Mis Descargas','dtransport'));
define('_MS_DT_ID',__('ID','dtransport'));
define('_MS_DT_ERRUSER',__('Esta descarga no ha sido registrada por usted','dtransport'));

switch(DT_LOCATION){
	
	case 'category':
		
		define('_MS_DT_MARKED',__('Descargas Destacadas','dtransport'));
		define('_MS_DT_DAYDOWN',__('Descargas del Día en %s','dtransport'));
		define('_MS_DT_DOWNIN',__('Descargas en %s','dtransport'));
		define('_MS_DT_SHOWING',__('Resultados %u a %u de %u','dtransport'));
		define('_MS_DT_CATS',__('Categorías','dtransport'));
		define('_MS_DT_SUBCATS',__('Subcategorías','dtransport'));
		
		// ERRORES
		define('_MS_DT_ERRID',__('Debes especificar una categoría válida','dtransport'));
	
		break;
	
	case 'item':
		
		define('_MS_DT_GENERALD',__('Datos Generales','dtransport'));
		define('_MS_DT_SCREENSNUM',__('%u Imágenes','dtransport'));
		define('_MS_DT_SCREENSS',__('%u Imágen','dtransport'));
		define('_MS_DT_COMSNUM',__('%u Comentarios','dtransport'));
		define('_MS_DT_COMSS',__('%u Comentario','dtransport'));
		define('_MS_DT_REGISTER',__('Registrado:','dtransport'));
		define('_MS_DT_UPDATED',__('Actualizado:','dtransport'));
		define('_MS_DT_AUTHOR',__('Autor:','dtransport'));
		define('_MS_DT_LANG',__('Idioma:','dtransport'));
		define('_MS_DT_SIZE',__('Tamaño:','dtransport'));
		define('_MS_DT_DOWNSNUM',__('Descargas:','dtransport'));
		define('_MS_DT_VERSION',__('Versión:','dtransport'));
		define('_MS_DT_DWONNOW',__('Descargar','dtransport'));
		define('_MS_DT_BY',__('Por <em>%s</em>','dtransport'));
		define('_MS_DT_FEATURES',__('Características','dtransport'));
		define('_MS_DT_FEATURESOP',__('%u Características','dtransport'));
		define('_MS_DT_FEATURESOPS',__('%u Característica','dtransport'));
		define('_MS_DT_LOGSOP',__('%u Log de Cambios','dtransport'));
		define('_MS_DT_LOGS',__('Registro de Cambios','dtransport'));
		define('_MS_DT_IMAGES',__('Imágenes de <span>%s</span>','dtransport'));
		define('_MS_DT_IMAGESLOC',__('Imágenes','dtransport'));
		define('_MS_DT_FEATSLOC',__('Características','dtransport'));
		define('_MS_DT_LOGSLOC',__('Registros','dtransport'));
		define('_MS_DT_LOGSTITLE',__('Registros de %s','dtransport'));
		define('_MS_DT_FILES',__('Archivos Adicionales','dtransport'));
		define('_MS_DT_FILE',__('Archivo','dtransport'));
		define('_MS_DT_FSIZE',__('Tamaño','dtransport'));
		define('_MS_DT_FDOWNS',__('Descargas','dtransport'));
		define('_MS_DT_FDATE',__('Fecha','dtransport'));
		define('_MS_DT_ITEMSREL',__('Programas Relacionados','dtransport'));
		define('_MS_DT_ITEMSOTHER',__('Otros Programas en %s','dtransport'));
		define('_MS_DT_TAGS',__('Etiquetas:','dtransport'));
		define('_MS_DT_RATEUSER',__('Calificación:','dtransport'));
		define('_MS_DT_VOTE',__('Calificar:','dtransport'));
		define('_MS_DT_VOTEBUTTON',__('Votar','dtransport'));
		
		// Imágenes
		define('_MS_DT_OPINE',__('Opinar','dtransport'));
		
		// ERRORES
		define('_MS_DT_ERRID',__('Debes especificar un elemento válido','dtransport'));
		define('_MS_DT_NOIMGS',__('Esta descarga no tiene imágenes','dtransport'));
		define('_MS_DT_NOFEATS',__('No han sido especificadas características para esta descarga','dtransport'));
		define('_MS_DT_NOLOGS',__('No existen registros para esta descarga','dtransport'));
		define('_MS_DT_NOFILE',__('El archivo especificado no es válido','dtransport'));
		define('_MS_DT_NODOWN',__('No tienes autorización para descargar este archivo','dtransport'));
		
		break;
	
	case 'download':
		
		// ERRORES
		define('_MS_DT_NOFILE',__('No existe el archivo especificado','dtransport'));
		define('_MS_DT_NOITEM',__('No hemos podido localizar la descarga especificada','dtransport'));
		define('_MS_DT_NODOWN',__('No tienes autorización para descargar este archivo','dtransport'));
		define('_MS_DT_DOWNLIMIT',__('Has alcanzado el número máximo de descargas para este archivo.','dtransport'));
		define('_MS_DT_NOEXISTSFILE',__('Existe un problema con el archivo seleccionado. Por favor inente mas tarde o seleccione otro archivo para descargar.<br />Por favor reporte este error al administrador.','dtransport'));
		
		break;
	
	case 'search':
		
		define('_MS_DT_SHOWING',__('Resultados %u a %u de %u','dtransport'));
		define('_MS_DT_SEARCHLOC',__('Buscar Descargas','dtransport'));
		define('_MS_DT_DOWNSRECENT',__('Novedades','dtransport'));
		define('_MS_DT_DOWNSPOPULAR',__('Descargas Populares','dtransport'));
		define('_MS_DT_DOWNSRATED',__('Descargas Mejor Valoradas','dtransport'));

		//Errores
		define('_MS_DT_ERRSEARCH',__('Debes proporcionar al menos una palabra para la búsqueda','dtransport'));
		
		break;
	case 'rate':

		define('_MS_DT_VOTEOK',__('Tu voto ha sido registrado.<br />¡Gracias por Votar!','dtransport'));
		
		//Errores
		define('_MS_DT_ERRIDVALID',__('Debes especificar un elemento válido','dtransport'));
		define('_MS_DT_ERRIDEXIST',__('Elemento no existente','dtransport'));
		define('_MS_DT_NORATE',__('La calificación proporcionada no es válida','dtransport'));
		define('_MS_DT_NODAY',__('Solo puedes votar una vez por día por cada recurso.<br />Por favor vuelve a intentarlo mañana.','dtransport'));
		define('_MS_DT_VOTEFAIL',__('Ocurrió un error al intentar registrar tu voto. Por favor intenalo mas tarde','dtransport'));


		break;
	case 'tags':

		define('_MS_DT_MARKED',__('Descargas Destacadas','dtransport'));
		define('_MS_DT_DAYDOWN',__('Descargas del Día en %s','dtransport'));
		define('_MS_DT_DOWNIN',__('Descargas en %s','dtransport'));
		define('_MS_DT_SHOWING',__('Resultados %u a %u de %u','dtransport'));
		define('_MS_DT_TAGS',__('Etiquetas','dtransport'));
		
		// ERRORES
		define('_MS_DT_ERRID',__('Debes especificar una etiqueta válida','dtransport'));

		break;
	case 'submit':

		define('_MS_DT_SEND',__('Enviar descarga','dtransport'));
		define('_MS_DT_SUBJECT',__('Descarga Enviada %s','dtransport'));
		define('_MS_DT_SUBJECTEDIT',__('Descarga Editada %s','dtransport'));
		

		//Formulario
		define('_MS_DT_EDITSW',__('Editar Descarga','dtransport'));
		define('_MS_DT_CREASW',__('Crear Descarga','dtransport'));
		define('_MS_DT_CATEGO',__('Categoría','dtransport'));
		define('_MS_DT_SHORTDESC',__('Descripción Corta','dtransport'));
		define('_MS_DT_DESC',__('Descripción','dtransport'));
		define('_MS_DT_IMAGE',__('Imagen','dtransport'));
		define('_MS_DT_IMAGEACT',__('Imagen Actual','dtransport'));
		define('_MS_DT_TAGS',__('Etiquetas','dtransport'));
		define('_MS_DT_DESCTAGS',__('Separar con un espacio " " las etiquetas','dtransport'));
		define('_MS_DT_NEWFILES',__('Crear Archivos','dtransport'));
		define('_MS_DT_NEWLOG',__('Crear Log','dtransport'));
		define('_MS_DT_ALERT',__('Alerta','dtransport'));
		define('_MS_DT_ACTALERT',__('Activar alerta','dtransport'));
		define('_MS_DT_MP',__('Mensaje Privado','dtransport'));
		define('_MS_DT_EMAIL',__('Correo Electrónico','dtransport'));
		define('_MS_DT_LIMIT',__('Límite de días','dtransport'));
		define('_MS_DT_DESCLIMIT',__('Límite en días de inactividad del <br />elemento antes de enviar la alerta','dtransport'));
		define('_MS_DT_MODE',__('Modo de envío de alerta','dtransport'));
		define('_MS_DT_LICENCES',__('Licencias','dtransport'));	
		define('_MS_DT_PLATFORMS',__('Plataformas','dtransport'));
		define('_MS_DT_LICOTHER',__('Otra...','dtransport'));
		define('_MS_DT_VERSION',__('Versión','dtransport'));
		define('_MS_DT_OTHER',__('Otros Datos','dtransport'));
		define('_MS_DT_AUTHOR',__('Nombre del Autor','dtransport'));
		define('_MS_DT_AUTHORURL',__('URL del Autor','dtransport'));
		define('_MS_DT_LANGS',__('Idiomas Disponibles','dtransport'));

		


		//Errores
		define('_MS_DT_ERRSAVE',__('Error al realizar la operación. No se almacenaron los datos','dtransport'));
		define('_MS_DT_ERRID',__('Debes especificar un elemento válido','dtransport'));
		define('_MS_DT_ERREXIST',__('Elemento no existente','dtransport'));
		define('_MS_DT_ERRNOTDOWNS',__('Lo sentimos,no está activado el envío de descargas','dtransport'));
		define('_MS_DT_ERRUSERDOWNS',__('Lo sentimos, no perteneces a los grupos con permisos de envío de descargas','dtransport'));
		
		
		break;
	case 'mydowns':
		define('_MS_DT_SHOWING',__('Mostrando elementos <strong>%u</strong> a <strong>%u</strong> de <strong>%u</strong>.','dtransport'));

		//Tabla
		define('_MS_DT_DATE',__('Fecha','dtransport'));
		define('_MS_DT_NUMDOWNS',__('Descargas','dtransport'));
		define('_MS_DT_LEGEND',__('Leyenda:','dtransport'));
		define('_MS_DT_SCREEN',__('Pantallas','dtransport'));
		define('_MS_DT_FEATURES',__('Características','dtransport'));
		define('_MS_DT_FILES',__('Archivos','dtransport'));
		define('_MS_DT_LOGS',__('Logs','dtransport'));
		define('_MS_DT_APPROVED',__('Aprobado','dtransport'));
		
		//Errores
		define('_MS_DT_NOTDOWNS',__('No existe ninguna descarga registrada por usted','dtransport'));


		break;
	case 'screens':
		define('_MS_DT_NEWSCREEN',__('Crear Pantalla','dtransport'));
		define('_MS_DT_EXISTITEM',__('Pantallas Existentes de %s ','dtransport'));
		define('_MS_DT_DELETESCREEN',__('¿Realmente deseas eliminar la pantalla ?\nLos datos se eliminarán permanentemente.','dtransport')); 
		define('_MS_DT_DELETESCREENS',__('¿Realmente deseas eliminar las pantallas ?\nLos datos se eliminarán permanentemente.','dtransport')); 
		define('_MS_DT_SCREEN',__('Pantallas','dtransport'));
		
		//Tabla
		define('_MS_DT_EDITSCREEN',__('Editar Pantalla','dtransport'));
		define('_MS_DT_TITLE',__('Título','dtransport'));
		define('_MS_DT_DESC',__('Descripción','dtransport'));
		define('_MS_DT_IMAGE',__('Imagen','dtransport'));
		define('_MS_DT_ITEM',__('Software','dtransport'));
		define('_MS_DT_IMAGEACT',__('Imagen Actual','dtransport'));
		define('_MS_DT_NEWSCREENS',__('Crear pantalla de %s','dtransport'));
		define('_MS_DT_EDITSCREENS',__('Editar pantalla de %s','dtransport'));
	
		//Errores
		define('_MS_DT_ERRIDITEM',__('Elemento no válido','dtransport'));
		define('_MS_DT_ERRITEMEXIST',__('Elemento no existente','dtransport'));
		define('_MS_DT_ERRID',__('Pantalla no válida','dtransport'));
		define('_MS_DT_ERREXIST',__('Pantalla no existente','dtransport'));
		define('_MS_DT_ERRNAME',__('Nombre de pantalla existente','dtransport'));
		define('_MS_DT_NOTSCREEN',__('Debes proporcionar al menos una pantalla para eliminar','dtransport'));
		define('_MS_DT_ERRSCVAL',__('Pantalla %s no válida <br />','dtransport'));
		define('_MS_DT_ERRSCEX',__('Pantalla %s no existente <br />','dtransport'));
		define('_MS_DT_ERRSCDEL',__('Pantalla %s no eliminada <br />','dtransport'));

	
		break;
	case 'files':

		define('_MS_DT_SAVE',__('Guardar Cambios','dtransport'));
		define('_MS_DT_EXIST',__('Archivos Existentes de %s','dtransport'));
		define('_MS_DT_DELETEGR',__('¿Realmente deseas eliminar el grupo %s ?\n Los datos se eliminarán permanentemente.','dtransport'));
		define('_MS_DT_DELETEFILE',__('¿Realmente deseas eliminar el archivo?\n Los datos se eliminarán permanentemente.','dtransport'));
		define('_MS_DT_DELETEFILES',__('¿Realmente deseas eliminar los archivos ?\n Los datos se eliminarán permanentement.','dtransport'));
		define('_MS_DT_FILES',__('Archivos','dtransport'));

		//Tabla
		define('_MS_DT_EDITFILE',__('Editar Archivo','dtransport'));
		define('_MS_DT_FILE',__('Archivo','dtransport'));
		define('_MS_DT_DESCTYPEFILE',__('Tipos de extensiones permitidas %s <br /> El tamaño permitido del archivo es de %s','dtransport'));
		define('_MS_DT_NAMEFILE',__('Nombre de archivo','dtransport'));
		define('_MS_DT_FILEURL',__('URL de archivo','dtransport'));
		define('_MS_DT_REMOTE',__('Archivo remoto','dtransport'));
		define('_MS_DT_GROUP',__('Grupo','dtransport'));
		define('_MS_DT_DEFAULT',__('Archivo a descargar por defecto','dtransport'));
		define('_MS_DT_SIZE',__('Tamaño de archivo','dtransport'));
		define('_MS_DT_DESCSIZE',__('Indique el tamaño de archivo si solo ha proporcionado la URL.<br /> Especifique el tamaño en kilobytes.','dtransport'));
		define('_MS_DT_DEFAULTFILE',__('Predeterminado','dtransport'));
		define('_MS_DT_NEWFILES',__('Crear archivo de %s','dtransport'));
		define('_MS_DT_EDITFILES',__('Editar archivo de %s','dtransport'));
		define('_MS_DT_CREATEGROUP',__('Crear Grupo','dtransport'));
		define('_MS_DT_CREATEFILE',__('Crear Archivo','dtransport'));
	
		define('_MS_DT_NEWGROUP',__('Crear Grupo','dtransport'));
		define('_MS_DT_EDITGROUP',__('Editar Grupo','dtransport'));

		//Errores
		define('_MS_DT_ERRFILEVALID',__('Archivo no válido','dtransport'));
		define('_MS_DT_ERRFILEEXIST',__('Archivo no existente','dtransport'));
		define('_MS_DT_ERR_ITEMVALID',__('Software no válido','dtransport'));
		define('_MS_DT_ERR_ITEMEXIST',__('Software no existente','dtransport'));
		define('_MS_DT_ERRNAMEGROUP',__('Grupo Existente','dtransport'));
		define('_MS_DT_ERRVALID',__('Archivo %s no válido <br />','dtransport'));
		define('_MS_DT_ERREXIST',__('Archivo %s no existente <br />','dtransport'));
		define('_MS_DT_ERRUPDATE',__('No se modificó el archivo %s <br />','dtransport'));
		define('_MS_DT_ERRNOTEXT',__('Este tipo de archivo no esta permitido: %s','dtransport'));
		define('_MS_DT_ERRNOEXISTFILE',__('El archivo no existe en el directorio de descargas seguras.<br /> Por favor antes cargue el archivo en el directorio: <br /> %s ','dtransport'));

		define('_MS_DT_ERRGROUPVALID',__('Grupo no válido','dtransport'));
		define('_MS_DT_ERRGROUPEXIST',__('Grupo no existente','dtransport'));
		define('_MS_DT_ERRFILEVAL',__('Archivo %s no válido <br />','dtransport'));
		define('_MS_DT_ERRFILEEX',__('Archivo %s no existente <br />','dtransport'));
		define('_MS_DT_ERRFILEDEL',__('Archivho %s no ha sido eliminado <br />','dtransport'));
		define('_MS_DT_NOTFILE',__('Debes proporcionar al menos un archivo para eliminar','dtransport'));
		define('_MS_DT_ERRSIZE',__('Tamaño de archivo no especificado. Es necesario proporcionarlo.','dtransport'));
		
		break;
	case 'logs':
		define('_MS_DT_LOGS',__('Logs','dtransport'));
		define('_MS_DT_NEWLOG',__('Crear Log','dtransport'));
		define('_MS_DT_EDITLOG',__('Editar Log','dtransport'));
		define('_MS_DT_TITLE',__('Título','dtransport'));
		define('_MS_DT_DATE',__('Fecha','dtransport'));
		define('_MS_DT_EXIST',__('Logs Existentes de %s','dtransport'));
		define('_MS_DT_DELETELOG',__('¿Realmente deseas eliminar el log?','dtransport'));
		define('_MS_DT_DELETELOGS',__('¿Realmente deseas eliminar los logs?','dtransport'));
				

		//Formulario
		define('_MS_DT_NEWLOGS',__('Crear log de %s','dtransport'));
		define('_MS_DT_EDITLOGS',__('Editar log de %s','dtransport'));
		define('_MS_DT_SOFTWARE',__('Software','dtransport'));
		define('_MS_DT_LOG',__('Log','dtransport'));

		//Errores
		define('_MS_DT_ERR_ITEMVALID',__('Software no válido','dtransport'));
		define('_MS_DT_ERR_ITEMEXIST',__('Software no existente','dtransport'));
		define('_MS_DT_ERRLOGVALID',__('Log no válido','dtransport'));
		define('_MS_DT_ERRLOGEXIST',__('Log no existente','dtransport'));
		define('_MS_DT_NOTLOG',__('Debes proporcionar al menos un log para eliminar','dtransport'));
		define('_MS_DT_ERRLOGVAL',__('Log %s no válido <br />','dtransport'));
		define('_MS_DT_ERRLOGEX',__('Log %s no existente <br />','dtransport'));
		define('_MS_DT_ERRLOGDEL',__('Log %s no eliminado <br />','dtransport'));
		break;
	case 'features':
		define('_MS_DT_FEATURES',__('Características','dtransport'));
		define('_MS_DT_NEWFEATURE',__('Crear Característica','dtransport'));
		define('_MS_DT_EXISTS',__('Características Existentes de %s','dtransport'));
		define('_MS_DT_DELETECONF',__('¿Realmente deseas eliminar la característica <strong>%s</strong>?','dtransport'));
		define('_MS_DT_DELCONF',__('¿Realmente deseas eliminar las características?','dtransport'));
		define('_MS_DT_SELECTITEM',__('Selecciona un elemento para visualizar su lista de características','dtransport'));
		define('_MS_DT_DELETEFEATURE',__('Eliminar Característica','dtransport'));	
		define('_MS_DT_CREATEDF',__('Creada','dtransport'));
		define('_MS_DT_DELETEFEAT',__('¿Realmente deseas eliminar la característica?\n Los datos se eliminarán permanentemente.','dtransport'));
		define('_MS_DT_DELETEFEATS',__('¿Realmente deseas eliminar las características?\n Los datos se eliminarán permanentemente.','dtransport'));

		//Formulario
		define('_MS_DT_EDITFEATURE',__('Editar Característica','dtransport'));
		define('_MS_DT_SOFTWARE',__('Software','dtransport'));
		define('_MS_DT_TITLE',__('Título','dtransport'));
		define('_MS_DT_MODIFIEDF',__('Modificación','dtransport'));
		define('_MS_DT_CONTENT',__('Contenido','dtransport'));
		define('_MS_DT_NEWFEATURES',__('Crear característica de %s','dtransport'));
		define('_MS_DT_EDITFEATURES',__('Editar característica de %s','dtransport'));


		//Errores
		define('_MS_DT_ERRFEATVALID',__('Característica no válida','dtransport'));
		define('_MS_DT_ERRFEATEXIST',__('Característica no existente','dtransport'));
		define('_MS_DT_ERRNAME',__('Error nombre de característica existente','dtransport'));
		define('_MS_DT_ERR_ITEMVALID',__('Software no válido','dtransport'));
		define('_MS_DT_ERR_ITEMEXIST',__('La descarga especificada no existe','dtransport'));
		define('_MS_DT_ERRFEATVAL',__('Característica %s no válida <br />','dtransport'));
		define('_MS_DT_ERRFEATEX',__('Característica %s no existente <br />','dtransport'));
		define('_MS_DT_ERRFEATSAVE',__('Característica %s no actualizada <br />','dtransport'));
		define('_MS_DT_ERRFEAT',__('Debes proporcionar al menos una característica','dtransport'));
		define('_MS_DT_ERRFEATDEL',__('No se pudo eliminar la característica %s <br />','dtransport'));
	

		break;
	case 'index':
	default:
		
		define('_MS_DT_DAYDOWN',__('Descargas del Día','dtransport'));

		// Tabla Recientes
		define('_MS_DT_RECENTSLIST',__('Novedades','dtransport'));
		
		define('_MS_DT_CATEGOS',__('Categorías','dtransport'));
		define('_MS_DT_TAGSPOPULAR',__('Búsquedas Populares','dtransport'));
		define('_MS_DT_HITS',__('Hits: ','dtransport'));
		
		break;
		
}
