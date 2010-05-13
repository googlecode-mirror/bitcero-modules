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

define('_AS_BB_SELECT','Seleccionar...');
define('_AS_BB_ID','ID');
define('_AS_BB_DBOK','¡Base de datos actualizada satisfactoriamente!');

// ERRORES GLOBALES
define('_AS_BB_ERRACTION','Se detectaron errores al realizar esta operación:<br /><br />');
define('_AS_BB_ERRTOKEN','El identificador de sesión ya no es válido');
define('_AS_BB_HTNOWRITE','No es posible modificar el archivo .htaccess para configurar el método de manejo de URLS');

// Fechas
define('_MS_EXMBB_TODAY', "Hoy %s");
define('_MS_EXMBB_YESTERDAY', "Ayer %s");

switch (BB_LOCATION){

	case 'index':
		
		define('_AS_BB_INDEX','Inicio');
		define('_AS_BB_CATEGOS','Categorías');
		define('_AS_BB_CATEGOSNUM','%s Categorías');
		define('_AS_BB_FORUMS','Foros');
		define('_AS_BB_FORUMSNUM','%s Foros');
		define('_AS_BB_ANOUN','Anuncios');
		define('_AS_BB_ANOUNNUM','%s Anuncios');
		define('_AS_BB_REPORTS','Reportes');
		define('_AS_BB_REPORTSNUM','%s Reportes');
		define('_AS_BB_PURGE','Purgar');
		define('_AS_BB_CLICK','Click aquí');
		define('_AS_BB_TOPICS','Temas');
		define('_AS_BB_TOPICSNUM','%s Temas');
		define('_AS_BB_ANUNSWERED','Sin respuesta');
		define('_AS_BB_ANUNSWEREDNUM','%s Sin respuesta');
		define('_AS_BB_RECENT','Recientes');
		define('_AS_BB_RECENTNUM','%s Recientes');
		define('_AS_BB_REDMEX','Red México');
		define('_AS_BB_SITE','Visitar Sitio');
		define('_AS_BB_HELP','Ayuda');
		
		define('_AS_BB_BY','Por %s');
		define('_AS_BB_LASTTOPICS','Mensajes Recientes');
		define('_AS_BB_VERSIONS','Información de %s'); 

		break;
		
	case 'categories':
	    
        define('_AS_BB_CATEGOS','Categorías');
        define('_AS_BB_EXISTING','Categorías Existentes');
	define('_AS_BB_NEWCATEGO','Crear Categoría');
	define('_AS_BB_DELCATEGO','Eliminar Categoría');
        define('_AS_BB_EDITCATEGO','Editar Categoría');
	define('_AS_BB_TITLE','Título');
	define('_AS_BB_DESC','Descripción');
        define('_AS_BB_ORDER','Orden');
        define('_AS_BB_ACTIVE','Activa');
	define('_AS_BB_ACTIV','Activar');
        
        define('_AS_BB_DISABLE','Desactivar');
        define('_AS_BB_SAVE','Guardar Cambios');

	define('_AS_BB_DELETECONF','¿Realmente deseas eliminar las categorías seleccionadas?');
	define('_AS_BB_ALLPERM','ADVERTENCIA: Esta acción eliminará todos los foros pertenecientes a éstas categorías. <br />Eliminará los datos permanentemente.');
        
        // Formulario
        define('_AS_BB_ACTIVATE','Activar');
        define('_AS_BB_SHOWDESC','Mostrar Descripción');
        define('_AS_BB_GROUPS','Grupos con Acceso');
        define('_AS_BB_FRIENDNAME','Nombre para URL');
				
		// ERRORES
        define('_AS_BB_ERRID','La categoría especificada no es válida');
        define('_AS_BB_ERRNOEXISTS','No existe la categoría especificada.');
        define('_AS_BB_ERREXISTS','Ya existe una categoría con el mismo nombre');
        define('_AS_BB_ERREXISTSF','Ya existe una categoría con el mismo nombre para la URL');
	define('_AS_BB_ERRUPLOADIMG','No se pudo cargar la imágen proporcionada.');
	define('_AS_BB_ERRNOTCAT','Debes especificar al menos una categoría para realizar esta acción');
	define('_AS_BB_ERRCATNOVALID','Categoría %s no válida  <br />');
	define('_AS_BB_ERRCATNOEXIST','Categoría %s no existente <br />');
	define('_AS_BB_ERRCATNODEL','No se pudo eliminar la categoría %s <br />');
	define('_AS_BB_ERRCATNOSAVE','No se pudo modificar la categoría %s <br />');
        
        break;
    
    case 'forums':
        
        // Menu
        define('_AS_BB_FORUMS','Foros');
        define('_AS_BB_NEWFORUM','Crear Foro');
        
        // Lista de Foros
        define('_AS_BB_LFORUMS','Lista de Foros Existentes');
        define('_AS_BB_LNAME','Nombre');
        define('_AS_BB_LNUMTOPICS','Temas');
        define('_AS_BB_LNUMPOSTS','Envíos');
        define('_AS_BB_LCATEGO','Categoría');
        define('_AS_BB_LACTIVE','Activo');
        define('_AS_BB_LATTACH','Adjuntos');
        define('_AS_BB_LORDER','Posición');
        define('_AS_BB_LDEACTIVATE','Desactivar');
        define('_AS_BB_LACTIVATE','Activar');
        define('_AS_BB_LSAVE','Guardar Cambios');

		//Moderadores
		define('_AS_BB_MODERATORS','Moderadores');
		define('_AS_BB_LIST','Elija de la lista los usuarios moderadores');
		define('_AS_BB_USERS','Usuarios');
		define('_AS_BB_NOTSAVE','Información de Moderadores no almacenada');
		        
        define('_AS_BB_DELETELOC','Eliminar Foro');
        define('_AS_BB_DELETECONF','<strong>¿Realmente deseas eliminar este foro?</strong><br /><br />
        					ADVERTENCIA: ¡Todos los temas y mensajes creados en este foro serán eliminados!');
        
        // Formulario
        define('_AS_BB_FNEW','Crear Foro');
        define('_AS_BB_FEDIT','Editar Foro');
        define('_AS_BB_FCATEGO','Categoría');
        define('_AS_BB_FNAME','Nombre del Foro');
        define('_AS_BB_FDESC','Descripción');
        define('_AS_BB_FPARENT','Foro Raíz');
        define('_AS_BB_FACTIVATE','Activar Foro');
        define('_AS_BB_FALLOWSIG','Permitir firmas en los envios');
        define('_AS_BB_FALLOWPREFIX','Permitir prefijos en los Envios');
        define('_AS_BB_FTHRESHOLD','Respuestas para marcar un tema como popular');
        define('_AS_BB_FORDER','Orden en la Lista');
        define('_AS_BB_FCREATE','Crear Foro');
        define('_AS_BB_FATTACH','Aceptar Archivos Adjuntos');
        define('_AS_BB_FATTACHSIZE','Tamaño máximo permitido para archivos adjuntos');
        define('_AS_BB_FATTACHSIZE_DESC','Especifique este valor en Kilobytes');
        define('_AS_BB_FATTACHEXT','Extensiones permitidas');
        define('_AS_BB_FATTACHEXT_DESC',"Especifique las extensiones permitidas separando cada una con '|'");
        define('_AS_BB_GROUPSVIEW','Pueden Ver el Foro');
        define('_AS_BB_GROUPSTOPIC','Pueden Iniciar Nuevos Temas');
        define('_AS_BB_GROUPSREPLY','Pueden Responder');
        define('_AS_BB_GROUPSEDIT','Pueden Editar su Envío');
        define('_AS_BB_GROUPSDELETE','Pueden Eliminar');
        define('_AS_BB_GROUPSVOTE','Pueden Calificar');
        define('_AS_BB_GROUPSATTACH','Pueden Adjuntar Archivos');
        define('_AS_BB_GROUPSAPPROVE','Pueden Enviar sin Necesidad de Aprobación');
        
        // ERRORES
        define('_AS_BB_NOID','El foro especificado no es válido');
        define('_AS_BB_NOEXISTS','No existe el foro especificado.');
        define('_AS_BB_NOSELECTFORUM','Es necesario seleccionar al menos un foro para realizar esta acción.');
        
        break;
    
    case 'announcements':
    	
    	define('_AS_BB_ANOUNLOC','Anuncios');
    	define('_AS_BB_ANOUNNEW','Crear Anuncio');
    	
    	// Lista
    	define('_AS_BB_EXISTING','Anuncios Existentes');
    	define('_AS_BB_ANNOUNCEMENT','Anuncio');
    	define('_AS_BB_EXPIRE','Expira');
    	define('_AS_BB_WHERE','Donde');
    	define('_AS_BB_BY','Por');
    	define('_AS_BB_DELETE','Eliminar Anuncios');
    	
    	// Formulario
    	define('_AS_BB_NEWLOC','Crear Anuncio');
    	define('_AS_BB_EDITLOC','Editar Anuncio');
    	define('_AS_BB_FANNOUNCEMENT','Texto del Anuncio');
    	define('_AS_EXMBB_BBCODE','Códigos BB');
    	define('_AS_EXMBB_SMILES','Emoticons');
    	define('_AS_EXMBB_HTML','HTML');
    	define('_AS_EXMBB_BR','Saltos de Línea');
    	define('_AS_EXMBB_IMG','Imágenes');
    	define('_AS_EXMBB_FEXPIRE','Fecha de Caducidad');
    	define('_AS_EXMBB_FWHERE','Mostrar En');
    	define('_AS_EXMBB_FWHERE0','Página Principal');
    	define('_AS_EXMBB_FWHERE1','En foro');
    	define('_AS_EXMBB_FWHERE2','Todo el Módulo');
    	define('_AS_EXMBB_FFORUM','Foro');
    	define('_AS_EXMBB_FFORUM_DESC','Por favor selecciona el foro donde se mostrará este anuncio. Esta opción solo es válida cuando "En Foro" ha sido seleccionado.');
    	define('_AS_EXMBB_FCREATE','Crear Anuncio');
    	define('_AS_EXMBB_FEDIT','Editar Anuncio');
    	
    	// Mensajes
    	define('_AS_EXMBB_CONFDELB','¿Realmente deseas aliminar los anuncios seleccionados?');
    	
    	// ERRORES
    	define('_AS_EXMBB_ERRID','El anuncio especificado no es válido');
    	define('_AS_EXMBB_ERREXISTS','No existe el anuncio especificado');
    	define('_AS_EXMBB_ERRCADUC','La fecha de caducidad debe ser mayor a la fecha actual');
    	define('_AS_EXMBB_ERRSEL','Selecciona al menos un anuncio para eliminar.');
    	
    	break;

     case 'reports':
	define('_AS_EXMBB_SESSINVALID','Lo sentimos, tu identificador de sesión ya no es válido');
	//Barra de Reportes
	define('_AS_EXMBB_ALLREPORTS','Todos los Reportes');
	define('_AS_EXMBB_REVREPORTS','Reportes Revisados');
	define('_AS_EXMBB_REVNOTREPORTS','Reportes No Revisados');
	define('_AS_EXMBB_REPORTS','Reportes');
	define('_AS_EXMBB_LISTREPORTS','Lista de Reportes');
	define('_AS_EXMBB_REPORT','Reporte');
	define('_AS_EXMBB_POST','Mensaje');
	define('_AS_EXMBB_DATE','Fecha');
	define('_AS_EXMBB_USER','Usuario');
	define('_AS_EXMBB_ZAPPED','Revisado');
	define('_AS_EXMBB_ZAPPEDNAME','Revisado por');
	define('_AS_EXMBB_ZAPPEDTIME','Fecha revision');
	define('_AS_EXMBB_REVIEW','Revisar');
	define('_AS_EXMBB_NOTREVIEW','No Revisar');
	define('_AS_EXMBB_OPTIONS','Opciones');
	define('_AS_EXMBB_REPREVIEW','Lista de Reportes Revisados');
	define('_AS_EXMBB_REPNOTREVIEW','Lista de Reportes No Revisados');
	define('_AS_EXMBB_DELREPORT','¿Realmente deseas eliminar este reporte?  \nEsta acción eliminará los datos permanentemente.');		
	define('_AS_EXMBB_DELREPORTS','¿Realmente deseas eliminar los reportes seleccionados? \nEsta acción eliminará los datos permanentemente.');	
	//Error 
	define('_AS_EXMBB_ERRREPORTS','Debes seleccionar al menos un reporte');
	define('_AS_EXMBB_ERRORREPORT','Reporte %s no es válido <br />');
	define('_AS_EXMBB_NOTSAVE','No se modificó el reporte %s <br />'); 
	define('_AS_EXMBB_NOTEXIST','Reporte %s no existe <br />');
	define('_AS_EXMBB_NOTDELETE','No se eliminó el reporte %s <br />');
	break;

    case 'purge':
	define('_AS_EXMBB_SESSINVALID','Lo sentimos, tu identificador de sesión ya no es válido');
	//Formulario de Purge	
	define('_AS_EXMBB_PURGE','Purgar Temas');
	define('_AS_EXMBB_FORUMS','En que foro quieres purgar temas');
	define('_AS_EXMBB_ALLFORUMS','Todos los foros');
	define('_AS_EXMBB_DAYSOLD','Días');
	define('_AS_EXMBB_DESCDAYSOLD','Eliminar temas con estos o más días de antigüedad');
	define('_AS_EXMBB_ALLTOPICS','Todos los temas');
	define('_AS_EXMBB_TOPICSUNANSWERED','Temas sin respuesta');
	define('_AS_EXMBB_DELETE','Temas a eliminar');
	define('_AS_EXMBB_TOPICSFIXED','Eliminar temas fijos');
	define('_AS_EXMBB_DELTOPICS','¿Realmente deseas eliminar los temas? \nEsta acción eliminará los datos permanentemente.');
	break;
        
}

?>
