<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2


//Encabezado
define('_MS_AH_RESOURCE','Seleccionar Recurso');
define('_MS_AH_FIND','Buscar');
define('_MS_AH_FINDLABEL','Búsqueda');
define('_MS_AH_NEWRES','Crear');
define('_MS_AH_VOTED','Mejor Valoradas');
define('_MS_AH_POPULAR','Populares');
define('_MS_AH_RECENT','Recientes');

define('_MS_AH_LOADING','Cargando...');
define('_MS_AH_CLOSE','Cerrar');

define('_MS_AH_HOME','Inicio');
define('_MS_AH_INDEXPUBLIC','Indice de publicación');
define('_MS_AH_NOTPERM','No tienes permisos para acceder a la publicación');
define('_MS_AH_SESSINVALID','Id de Session no válido');
define('_MS_AH_DBOK','Base de datos actualizada correctamente');
define('_MS_AH_DBERROR','Error al realizar esta operación');
define('_MS_AH_ERRORS','Errores al realizar esta operación: <br />');
define('_AS_AH_REFERENCES','Insertar Referencia');
define('_AS_AH_FIGURES','Insertar Figura');
define('_MS_AH_SHOWING', 'Mostrando elementos <strong>%u</strong> a <strong>%u</strong> de <strong>%u</strong>.');
define('_MS_AH_NOTAPPROVED','Imposible acceder, publicación en espera de aprobación');

switch (AH_LOCATION){
	case 'index':
		
		define('_MS_AH_INDEXPUB','Publicaciones');
		define('_MS_AH_READINGS','Destacados');
		define('_MS_AH_MODIFIEDS','Modificaciones Recientes');
		define('_MS_AH_VOTES','Mejor Votadas');
		define('_MS_AH_ALL','Ver todas');
		define('_MS_AH_VOTE','%s Votos');
		define('_MS_AH_READS','%s Lecturas');
		define('_MS_AH_BY','Por %s');
		define('_MS_AH_IN','En %s');
		define('_MS_AH_RATING','Rating:');
   		
   		break;

	case 'resources':
	
		define('_MS_AH_INDEX','Índice');
		define('_MS_AH_QINDEX','Índice Rápido');
		define('_MS_AH_SPECIALS','Páginas Especiales');
		define('_MS_AH_FIGS','Figuras');
		define('_MS_AH_REFS','References');
	
		//Errores
		define('_MS_AH_NOTRESOURCE','El recurso especificado no es válido');
		define('_MS_AH_NOTEXIST','No existe el recurso especificado');

		break;
		
	case 'page':

		define('_MS_AH_BACK','Anterior');
		define('_MS_AH_INDEX','Índice');
		define('_MS_AH_NEXT','Siguiente');

		//Errores
		define('_MS_AH_NOTTEXT','Contenido no válido');
		define('_MS_AH_NOTEXIST','Contenido no existente');
		
		break;
	
	case 'content':
		
		define('_MS_AH_LASTEDITED','ultima edición en %s por %s.');
		define('_MS_AH_CONTENTS','Contenido');
		define('_MS_AH_CONTENTSSEC','Contenido de esta Sección');
		define('_MS_AH_CONTENTSFULL','Contenido Completo');
		define('_MS_AH_PRINT','Imprimir');
		define('_MS_AH_EDIT','Editar');
		define('_MS_AH_RATING', 'Calificación:');
		define('_MS_AH_RATE', 'Calificar:');
		define('_MS_AH_READS', '<strong>%u</strong> Lecturas');
		define('_MS_AH_VOTES', '<strong>%u</strong> Votos');
		define('_MS_AH_INDEX', 'Indice');
		define('_MS_AH_INDEXMORE','%u mas...');
		define('_MS_AH_MORE','Mas');
		define('_MS_AH_LESS','Menos');
		define('_MS_AH_REFS','Referencias');
		define('_MS_AD_PRINTINFO','Documento impreso desde <strong>%s</strong>.<br />(%s)');
		define('_MS_AH_TOP','Arriba');
		define('_MS_AH_FIGSIN','Figuras en %s');
		define('_MS_AH_REFSIN','Referencias en %s');
		
		// ERRORES
		define('_MS_AH_NOID','No has especificado un elemento válido');
		define('_MS_AH_NOCONTENT','No existe contenido para este recurso.');
		define('_MS_AH_NOALLOWED','No estas autorizado a ver el contenido de este recurso.');
		define('_MS_AH_NOPRINT','Operación temporalmente deshabilitada');
        define('_MS_AH_NOCONTENT_LEGEND','Aun no se ha creado contenido para esta sección, estamos trabajando en ello.');
		
		break;

	case 'references':
		
		// ERRORES
		define('_MS_AH_NOID','Referencia no válida');
		define('_MS_AH_NOEXISTS','No existe la referencia especificada');
			
		break;
	
	case 'rate':
		
		define('_MS_AH_VOTEOK','Tu voto ha sido registrado.<br />¡Gracias por Votar!');

		// ERRORES
		define('_MS_AH_NOID','El recurso especificado no es válido');
		define('_MS_AH_NORATE','La calificación proporcionada no es válida');
		define('_MS_AH_NODAY','Solo puedes votar una vez por día por cada recurso.<br />Por favor vuelve a intentarlo mañana.');
		define('_MS_AH_VOTEFAIL','Ocurrió un error al intentar registrar tu voto. Por favor intenalo mas tarde');
		
		break;
		
	case 'publish':
	
		define('_MS_AH_NEWRESOURCE','Crear publicación');
		define('_MS_AH_TITLE','Título');
		define('_MS_AH_DESC','Descripción');
		define('_MS_AH_IMAGE','Imagen');
		define('_MS_AH_IMAGEACT','Imagen Actual');
		define('_MS_AH_EDITORS','Editores');
		define('_MS_AH_GROUPS','Grupos con permiso de acceso');
		define('_MS_AH_PUBLIC','Público');
		define('_MS_AH_QUICK','Índice Rápido');
		define('_MS_AH_SHOWINDEX','Mostrar índice a usuarios restringidos');
		define('_MS_AH_APPROVED','Aprobar publicación');
		define('_MS_AH_AUTOAPPR','Esta sección será aprobada automáticamente');
		define('_MS_AH_NOAPPR','La publicación podrá ser aprobada posteriormente según determine el administrador');
		define('_MS_AH_SUBJECT','Publicación nueva %s no aprobada yet');

		//Errores
		define('_MS_AH_ERRTITLE','Ya existe una publicación con el mismo título');
		define('_MS_AH_ERRIMAGE','Error al guardar imagen');
		define('_MS_AH_ERRPERMGROUP','No cuentas con los permisos para crear una publicación');
		define('_MS_AH_ERRPERM','No se permite la creación de recursos');
		define('_MS_AH_ERRNOTIFY','Error al enviar correo de notificación de nueva publicación no aprobada a administrador');

		break;
		
	case 'edit':
	
		define('_MS_AH_ID','ID');
		define('_AS_AH_NEWPAGE','Crear Página');

		//Formulario de seccion
		define('_MS_AH_NEWSECTION','Crear Sección');
		define('_MS_AH_PUBLISH','Publicación');
		define('_MS_AH_TITLE','Título');
		define('_MS_AH_CONTENT','Contenido');
		define('_MS_AH_PARENT','Sección Raíz');
		define('_MS_AH_ORDER','Orden');
		define('_MS_AH_REFFIG','Las referencias y figuras solo estarán disponibles después de que haya guardado la sección.');
		define('_MS_AH_FUSER','Autor');
		define('_MS_AH_EDIT','Editar Sección');
		define('_MS_AH_SAVE','Guardar');
		define('_MS_AH_SAVERET','Guardar y Continuar editando');
		define('_MS_AH_EXIST','Secciones Existentes');
		define('_MS_AH_LIST','Lista de Secciones');
		define('_MS_AH_GOTOSEC','Volver al Contenido de la Sección');
		define('_MS_AD_APPROVETIP','Las modificaciones son aprobadas automáticamente');
		define('_MS_AD_NOAPPROVETIP','Las modificaciones deberán ser aprovadas por el administrador');

		//Errores
		define('_MS_AH_ERRTITLE','Error titulo de sección existente');
		define('_MS_AH_ERRRESOURCE','Error publicación no válida');
		define('_MS_AH_ERRNOTEXIST','Publicación no existente');
		define('_MS_AH_ERRSECTION','Sección no válida');
		define('_MS_AH_ERRNOTSEC','Sección no existente');
		define('_MS_AH_NOTSECTION','Debes proporionar al menos una sección');
		define('_MS_AH_NOTVALID','Sección %s no válida <br />');
		define('_MS_AH_NOTEXISTSECT','Sección %s no existente <br />');
		define('_MS_AH_NOTSAVEORDER','No se pudo actualizar el orden de la sección %s <br />');
		define('_MS_AH_NOTPERMEDIT','No tienes permisos para editar');
		
		break;
		
	case 'ref':
		define('_MS_AH_ID','ID');
		define('_MS_AH_TITLE','Título');
		define('_MS_AH_INSERT','Insertar Referencia');
		define('_MS_AH_NEW','Crear Referencia');
		define('_MS_AH_EXIST','Referencias Existentes');
		define('_MS_AH_RESULT','Resultados');
		define('_MS_AH_SEARCH','Buscar');
		define('_MS_AH_REFERENCE','Referencia');
		define('_MS_AH_EDIT','Editar Referencia');
		define('_MS_AH_REFEREN','Referencias');
		define('_MS_AH_CONFIRM','¿Realmente deseas eliminar las referencias seleccionadas? \n Esta acción eliminará los datos permanentemente.');


		//errores
		define('_MS_AH_NOTREF','Referencia no válida');
		define('_MS_AH_NOTREFEXIST','Referencia no existente');
		define('_MS_AH_ERRRESOURCE','Error publicación no válida');
		define('_MS_AH_ERRNOTEXIST','Publicación no existente');
		define('_MS_AH_ERRSECTION','Sección no válida');
		define('_MS_AH_ERRNOTSEC','Sección no existente');
		define('_MS_AH_TITLEEXIST','Titulo de referencia existente');
		define('_MS_AH_NOTPERMEDIT','No tienes permisos de edición para esta publicación');

	    break;

	case 'figures':
		define('_MS_AH_ID','ID');
		define('_MS_AH_DESC','Descripción');
		define('_MS_AH_EXIST','Figuras Existentes');
		define('_MS_AH_INSERT','Insertar');
		define('_MS_AH_RES','Publicación no válida, No se pudo insertar la figura');
		define('_MS_AH_RESNOEXIST','Publicación no existente, No se pudo insertar la figura');
		define('_MS_AH_TEXTNOVALID','Contenido no válido, No se pudo insertar la figura');
		define('_MS_AH_TEXTNOEXIST','Contenido no existente, No se pudo insertar la figura');
		define('_MS_AH_CONFIRM','¿Realmente deseas eliminar las figuras seleccionadas? \n Esta acción eliminará los datos permanentemente.');
		define('_MS_AH_SEARCH','Buscar');

		//Formulario
		define('_MS_AH_NEWF','Crear Figura');
		define('_MS_AH_EDITF','Editar Figura');
		define('_MS_AH_FIG','Figura');	
		define('_MS_AH_CLASS','Clase');
		define('_MS_AH_STYLE','Estilo');


		//Errores
		define('_MS_AH_NOTFIG','Figura no válida');
		define('_MS_AH_NOTFIGEXIST','Figura no existente');
		define('_MS_AH_FIGS','Debes seleccionas al menos una figura');
		define('_MS_AH_NOTVALID','Figura no válida %s <br />');
		define('_MS_AH_NOTEXIST','Figura no existente %s <br />');
		define('_MS_AH_NOTPERMEDIT','No tienes permisos de edición para esta publicación');

	    break;

	case 'search':
		define('_MS_AH_RESULT', 'Resultados de la búsqueda <strong>%u</strong> a <strong>%u</strong> de <strong>%u</strong>.');
		define('_MS_AH_RESULTED','Resultados');
		define('_MS_AH_VOTES','Votos');
		define('_MS_AH_READS','Lecturas');
		define('_MS_AH_NOTWORDSEARCH','No se ha proporcionado ninguna palabra para la búsqueda');
		define('_MS_AH_SEARCHS','Búsqueda');
		define('_MS_AH_OWNER','Por');
		define('_MS_AH_CREATED','Publicado');
		define('_MS_AH_RESULTS','Resultados para "%s" ');
		define('_MS_AH_RESULTS1','Publicaciones mejor votadas');
		define('_MS_AH_RESULTS2','Publicaciones mas populares');
		define('_MS_AH_RESULTS3','Publicaciones Recientes');
		define('_MS_AH_SEARCH','Buscar');
		define('_MS_AH_TYPE','Tipo de Búsqueda');
		define('_MS_AH_ALLWORDS','Todas las palabras');
		define('_MS_AH_ANYWORDS','Cualquier palabra');
		define('_MS_AH_PHRASE','Frase Exacta');
		define('_MS_AH_IN','En');
		define('_MS_AH_RESOURCES','Publicaciones');
		define('_MS_AH_SECTIONS','Contenidos');

	    break;

}

/**
* @desc Obtiene la fecha formateada correctamente		
*/
function ahFormatDate($time){
	$date = explode("/", date('j/n/Y', $time));
	
	switch($date[1]){
		case 1:
			$month = "Enero";
			break;
		case 2:
			$month = "Febrero";
			break;
		case 3:
			$month = "Marzo";
			break;
		case 4:
			$month = "Abril";
			break;
		case 5:
			$month = "Mayo";
			break;
		case 6:
			$month = "Junio";
			break;
		case 7:
			$month = "Julio";
			break;
		case 8:
			$month = "Agosto";
			break;
		case 9:
			$month = "Septiembre";
			break;
		case 10:
			$month = "Octubre";
			break;
		case 11:
			$month = "Noviembre";
			break;
		case 12:
			$month = "Diciembre";
			break;
	}
	
	return $month . ' ' . $date[0] . ', ' . $date[2];
	
}

?>