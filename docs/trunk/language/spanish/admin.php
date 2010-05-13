<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

define('_AS_AH_ID','ID');
define('_AS_AH_REFERENCES','Insertar Referencias');
define('_AS_AH_FIGURES','Insertar Figuras');
define('_AS_AH_RECOMMEND','Destacar');
define('_AS_AH_NORECOMMEND','Normal');

//Errores globales
define('_AS_AH_SESSINVALID','Lo sentimos, tu identificador de sesión ya no es válido');
define('_AS_AH_DBOK','¡Base de datos actualizada satisfactoriamente!');
define('_AS_AH_DBERROR','Error al realizar esta operación');
define('_AS_AH_ERRORS','Errores al realizar esta operación: <br />');
define('_AS_AH_ALLPERM','Esta acción eliminará permanentemente los datos.');
define('_AS_AH_SHOWING', 'Mostrando elementos <strong>%u</strong> a <strong>%u</strong> de <strong>%u</strong>.');

switch (AH_LOCATION){

    case 'index':
        
        define('_AS_AH_GOMOD','Ver Módulo');
        define('_AS_AH_CLICK','Haz Click');
        define('_AS_AH_RES','Publicaciones');
        define('_AS_AH_RESNUM','%u en Total');
        define('_AS_AH_RESAPPROVE','%u Aprobados');
        define('_AS_AH_RESNOAPPROVE','%u No Aprobados');
        define('_AS_AH_SECS','Secciones');
        define('_AS_AH_SECSNUM','%u Secciones');
        define('_AS_AH_EDITS','Modificaciones');
        define('_AS_AH_EDITSNUM','%u Esperando');
        define('_AS_AH_REFS','Referencias');
        define('_AS_AH_REFSNUM','%u Referencias');
        define('_AS_AH_FIGS','Figuras');
        define('_AS_AH_FIGSNUM','%u Figuras');
        define('_AS_AH_HELP','Ayuda');
        
        define('_AS_AH_ACCESS','El módulo esta configurado para utilizar las capacidades de <strong>mod_rewrite</strong> para el manejo de
        			las urls, sin embargo debe modificar su archivo .htaccess para activar la redirección desde otro directorio. Por favor incluya el código
        			mostrado mas abajo en el archivo .htaccess de la raiz de su sitio. Recuerde reemplazar las rutas por las de su sitio (ej. wiki por docs).');
        define('_AS_AH_SHOWCODE','Mostrar Código');
        
        break;

	case 'resources':
		
		define('_AS_AH_RESOURCES','Publicaciones');
		define('_AS_AH_NEWRESOURCE','Crear Publicación');
		
		define('_AS_AH_RESEXIST','Publicaciones Existentes');
		define('_AS_AH_TITLE','Título');
		define('_AS_AH_DATE','Fecha de Creación');
		define('_AS_AH_APPROVE','Aprobado');
		define('_AS_AH_PUBLIC','Público');
		define('_AS_AH_QUICK','Índice Rápido');
		define('_AS_AH_QUICKNO','No aplica si es un artículo');
		define('_AS_AH_PUB','Publicar');
		define('_AS_AH_NOPUB','No publicar');
		define('_AS_AH_NOQUICK','Sin índice rápido');
		define('_AS_AH_RESULT','Resultados');
		define('_AS_AH_SECTIONS','Secciones');
		define('_AS_AH_APPROVRES','Aprobar');
		define('_AS_AH_NOAPPROV','No aprobar');
		define('_AS_AH_SUBJECT','Publicación %s aprobada');
		
		//Formulario
		define('_AS_AH_EDITRESOURCE','Editar publicación');
		define('_AS_AH_NAMEID','Nombre Corto');
		define('_AS_AH_DESC','Descripción');
		define('_AS_AH_IMAGE','Imagen');
		define('_AS_AH_IMAGEACT','Imagen Actual');
		define('_AS_AH_EDITORS','Editores');
		define('_AS_AH_APPROV','Aprobar automáticamente contenido de editores');
		define('_AS_AH_GROUPS','Grupos con permiso de acceso');
		define('_AS_AH_SHOWINDEX','Mostrar índice a usuarios restringidos');
		define('_AS_AH_OWNER','Propietario de publicación');
		define('_AS_AH_APPROVEDRES','Aprobar publicación');
        define('_AS_AH_FEATURED','Publicación destacada');

		define('_AS_AH_DELETECONF','¿Realmente deseas eliminar la publicación <strong>%s</strong>');
		
		define('_AS_AH_ADV','Nota: Se eliminarán las secciones, contenidos, referencias y figuras que pertenezcan a ésta publicación. <br />');

		//Errores
		define('_AS_AH_IDNOTVALID','Publicación no válida');
		define('_AS_AH_NOTEXIST','Publicación no existente');
		define('_AS_AH_ERRTITLE','Titulo de publicación existente');
		define('_AS_AH_ERRIMAGE','Error al guardar imagen');
		define('_AS_AH_NOTRESOURCE','Debes seleccionar al menos una publicación');		
		define('_AS_AH_IDNOT','Publicación %s no válida <br />');
		define('_AS_AH_NOEXIST','Publicación %s no existente <br />');
		define('_AS_AH_NOSAVE','Error al guardar %s <br />');

        break;

	case 'sections':
		define('_AS_AH_SECTIONS','Secciones');
		define('_AS_AH_NEWSECTIONS','Crear Sección');
		define('_AS_AH_EDITSECTION','Editar Sección');

		define('_AS_AH_TITLE','Título');
		define('_AS_AH_ORDER','Orden');
		define('_AS_AH_RESOURCES','Publicaciones');
		define('_AS_AH_RESOURCE','Publicación');
		define('_AS_AH_EXIST','Secciones Existentes');
		define('_AS_AH_NOTRES','Selecciona una publicación para poder ver sus secciones');
		define('_AS_AH_SAVE','Guardar Cambios');

		//Formulario
		define('_AS_AH_EDITSECTIONS','Editar Sección');
		define('_AS_AH_SECTION','Sección Raíz');
		define('_AS_AH_CONTENT','Contenido');
		define('_AS_AH_FUSER','Autor');
		define('_AS_AH_DELETECONF','¿Realmente deseas eliminar la sección <strong>%s</strong>');
		define('_AS_AH_ADV','Nota: Se eliminarán los contenidos que pertenezcan a ésta sección. <br />');
		define('_AS_AH_REFFIG','Las referencias y figuras solo estarán disponibles después de que haya guardado la sección.');
		define('_AS_AH_SAVENOW','Guardar');
		define('_AS_AH_SAVERET','Guardar y Continuar Editando');
		define('_AS_AH_SHORTNAME','Nombre Corto');

		//Errores
		define('_AS_AH_ERRTITLE','Ya existe otra sección con el mismo título');
		define('_AS_AH_NOTRESOURCE','No proporcionaste una publicación');
		define('_AS_AH_NOTEXIST','Publicación no existente');
		define('_AS_AH_NOTSECTION','Sección no válida');
		define('_AS_AH_NOTEXISTSEC','Sección no existente');
		define('_AS_AH_NOTVALID','Sección %s no válida <br />');
		define('_AS_AH_NOTEXISTSECT','Sección %s no existente <br />');
		define('_AS_AH_NOTSAVEORDER','Error al actualizar %s <br />');
		
   		break;

	case 'references':
	
		define('_AS_AH_TITLE','Título');
		define('_AS_AH_INSERT','Insertar');
		define('_AS_AH_SECTION','Sección');
		define('_AS_AH_EXIST','Referencias Existentes');
		define('_AS_AH_CONFIRM','¿Realmente deseas eliminar las referencias seleccionadas? \n Esta acción eliminará los datos permanentemente.');
		define('_AS_AH_RESULT','Resultados');
		define('_AS_AH_SEARCH','Buscar');
		define('_AS_AH_REFEREN','Referencias');

		//Formulario
		define('_AS_AH_NEW','Crear Referencia');
		define('_AS_AH_REFERENCE','Referencia');
		define('_AS_AH_EDIT','Editar Referencia');
		
		
		//Errores
		define('_AS_AH_RESOURCE','Publicación no válida, No se puede insertar la referencia');
		define('_AS_AH_RESNOEXIST','Publicación no existente, No se puede insertar la referencia');
		define('_AS_AH_SECNOVALID','La Sección no es válida. No se puede insertar la referencia');
		define('_AS_AH_SECNOEXIST','No existe la sección especificada. No se puede insertar la referencia');
		define('_AS_AH_TITLEEXIST','Título existente, No se puede insertar la referencia');
		define('_AS_AH_REFNOTVALID','Referencia %s no válida <br />' );
		define('_AS_AH_REFNOTEXIST','Referencia %s no existente <br />');
		define('_AS_AH_REF','Debes seleccionar al menos una referencia');
		define('_AS_AH_NOTREF','Referencia no válida');
		define('_AS_AH_NOTREFEXIST','Referencia no existente');
		
		break;
		
	case 'figures':
		define('_AS_AH_DESC','Descripción');
		define('_AS_AH_EXIST','Figuras Existentes');
		define('_AS_AH_INSERT','Insertar');
		define('_AS_AH_RESOURCE','Publicación no válida, no se puede insertar figura');
		define('_AS_AH_RESNOEXIST','Publicación no existente, No se puede insertar figura');
		define('_AS_AH_CONFIRM','¿Realmente deseas eliminar las figuras seleccionadas? \n Esta acción eliminará los datos permanentemente.');
		define('_AS_AH_SEARCH','Buscar');

		//Formulario
		define('_AS_AH_NEWF','Crear Figura');
		define('_AS_AH_EDITF','Editar Figura');
		define('_AS_AH_FIG','Figura');	
		define('_AS_AH_ALIGN','Alineación');	
		define('_AS_AH_CLASS','Clase');
		define('_AS_AH_WIDTH','Ancho');
		define('_AS_AH_BORDER','Borde');
		define('_AS_AH_BGCOLOR','Color de Fondo');
		define('_AS_AH_FONT','Fuente');
		define('_AS_AH_FONTSIZE','Tamaño de Fuente');
		define('_AS_AH_MARGIN','Margen');
		define('_AS_AH_STYLE','Estilo');
		define('_AS_AH_NONE','Ninguna');
		define('_AS_AH_LEFT','Izquierda');
		define('_AS_AH_RIGHT','Derecha');
		define('_AS_AH_LEFTV','Left');
		define('_AS_AH_RIGHTV','Right');

		//Errores
		define('_AS_AH_NOTFIG','Figura no válida');
		define('_AS_AH_NOTFIGEXIST','Figura no existente');
		define('_AS_AH_FIGS','Debes seleccionas al menos una figura');
		define('_AS_AH_NOTVALID','Figura %s no válida <br />');
		define('_AS_AH_NOTEXIST','Figura %s no existente <br />');

	    break;

	case 'refs':
		define('_AS_AH_REFS','Referencias');
		define('_AS_AH_TITLE','Título');
		define('_AS_AH_TEXT','Referencia');
		define('_AS_AH_RESOURCE','Publicación');
		define('_AS_AH_EXIST','Referencias Existentes');
		define('_AS_AH_SEARCH','Buscar');
		define('_AS_AH_DELETECONF','¿Realmente deseas eliminar las referencias seleccionadas?');
		define('_AS_AH_RESULT','Resultados');
		
		//Formulario
		define('_AS_AH_EDIT','Editar Referencia');

		//Errores
		define('_AS_AH_TITLEEXIST','Título existente, No se puede insertar la referencia');
		define('_AS_AH_NOTREF','Debes proporcionar al menos una referencia para eliminar');
		define('_AS_AH_NOTVALID','La referencia %s no es válida <br />');
		define('_AS_AH_NOTEXIST','La referencia %s no existe<br />');
		define('_AS_AH_NOTDELETE','La referencia %s no fue eliminada <br />');

	    break;

	case 'figs':
		define('_AS_AH_FIGS','Figuras');
		define('_AS_AH_EXIST','Figuras Existentes');
		define('_AS_AH_DESC','Descripción');
		define('_AS_AH_RESOURCE','Publicación');
		define('_AS_AH_SEARCH','Buscar');
		define('_AS_AH_DELETECONF','¿Realmente deseas eliminar las figuras seleccionadas?');
		define('_AS_AH_RESULT','Resultados');

		//Formulario
		define('_AS_AH_EDIT','Editar Figura');
		define('_AS_AH_FIG','Figura');	
		define('_AS_AH_ALIGN','Alineación');	
		define('_AS_AH_CLASS','Clase');
		define('_AS_AH_WIDTH','Ancho');
		define('_AS_AH_BORDER','Borde');
		define('_AS_AH_BGCOLOR','Color de Fondo');
		define('_AS_AH_FONT','Fuente');
		define('_AS_AH_FONTSIZE','Tamaño de Fuente');
		define('_AS_AH_MARGIN','Margen');
		define('_AS_AH_STYLE','Estilo');
		define('_AS_AH_NONE','Ninguna');
		define('_AS_AH_LEFT','Izquierda');
		define('_AS_AH_RIGHT','Derecha');
		define('_AS_AH_LEFTV','Left');
		define('_AS_AH_RIGHTV','Right');

		//Errores
		define('_AS_AH_REFNOTVALID','La referencia especificada no es válida');
		define('_AS_AH_REFNOTEXIST','No existe la referencia especificada');
		define('_AS_AH_NOTFIG','Debes proporcionar al menos una figura para eliminar');
		define('_AS_AH_NOTVALID','La figura %s no es válida <br />');
		define('_AS_AH_NOTEXIST','La figura %s no existe<br />');
		define('_AS_AH_NOTDELETE','La figura %s no fue eliminada <br />');
		define('_AS_AH_FIGNOTVALID','La figura especificada no es válida');
		define('_AS_AH_FIGNOTEXIST','La figura especificada no existe');

		break;

	case 'editions':
		
		define('_AS_AH_EDITLOC','Elementos Modificados');
		define('_AS_AH_EDITREVLOC','Revisando %s');
		define('_AS_AH_EDITEDTLOC','Editando %s');
		define('_AS_AH_EDITS','Modificaciones');
		define('_AS_AH_EDITSTITLE','Modificaciones Existentes');
		define('_AS_AH_CONFIRMMSG','¿Realmente deseas eliminar el contenido editado "%s"?');
		define('_AS_AH_DELCONF','¿Realmente deseas eliminar los elementos seleccionados?');
		
		// Formulario
		define('_AS_AH_RESOURCE','Publicación');
		define('_AS_AH_CONTENT','Contenido');
		define('_AS_AH_SECTION','Sección Raíz');
		define('_AS_AH_ORDER','Orden');
		define('_AS_AH_FUSER','Editor');
		define('_AS_AH_EDITSECTIONS','Edición de Contenido');
		define('_AS_AH_SAVENOW','Guardar Cambios');

		// Tabla
		define('_AS_AH_EDITEDTITLE','Título Editado');
		define('_AS_AH_ORINGINALTITLE','Título Original');
		define('_AS_AH_MODIFIED','Modificado');
		define('_AS_AH_BY','Por');
		define('_AS_AH_REVIEW','Revisar');
		define('_AS_AH_APPROVE','Aprobar');
		define('_AS_AH_VIEW','Ver Contenido');
		
		// Revisión
		define('_AS_AH_ORIGINAL','Contenido Original');
		define('_AS_AH_EDITED','Contenido Editado');
		define('_AS_AH_TITLE','Título');
		
		// ERRORES
		define('_AS_AH_NOID','Debes especificar un elemento para realizar esta acción');
		define('_AS_AH_NOTEXISTS','No existe el elemento especificado');
		define('_AS_AH_NOTEXISTSSEC','La sección original no existe.');
		define('_AS_AH_ERRORSONAPPROVE','Ocurrieon algunos errores al realizar esta operación');
		define('_AS_AH_ERRTITLE','Ya existe otra sección con el mismo título');
		
		break;

}
