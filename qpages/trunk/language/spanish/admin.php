<?php
// $Id$
// --------------------------------------------------------
// Quick Pages
// Módulo para la publicación de páginas individuales
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

/**
 * Cadenas generales de la sección administrativa
 */
define('_AS_QP_HOME','Estado del Módulo');
define('_AS_QP_CATEGOS','Categorías');
define('_AS_QP_PAGES','Páginas');
define('_AS_QP_GOTOMODULE','Ver Módulo');
define('_AS_QP_DBOK','Base de datos actualizada satisfactoriamente');
define('_AS_QP_DBERROR','Ocurrieron errores al realizar esta operación');

define('_AS_QP_ID','ID');
define('_AS_QP_NAME','Nombre');

# Errores Generales
define('_AS_QP_ERRHTACCESS','El archivo <strong>"%s"</strong> no puede ser modificado por el servidor y es necesario hacerlo para cambiar el método de manejo de los enlaces.
		Por favor modifica los permisos de escritura del archivo o modificalo manualmente.');
define('_AS_QP_ERRTOKEN','Identificador de Sesión no Válido');

switch (QP_LOCATION){
	case 'index':
		
		define('_AS_QP_DETAILS','Ver Detalles');
		define('_AS_QP_CATEGOSNUM','%s Categorías');
		define('_AS_QP_PAGESNUM','%s Páginas');
		define('_AS_QP_PUBLICNUM','%s Publicadas');
		define('_AS_QP_PUBLICNUM_ALT','%s Páginas Publicadas');
		define('_AS_QP_PRIVATENUM','%s Privadas');
		define('_AS_QP_PRIVATENUM_ALT','%s Páginas Privadas');
		define('_AS_QP_VISIT','Visitar Sitio');
		
		define('_AS_QP_VERSIONINFO','Información de Quick Pages');
		define('_AS_QP_MOREVPAGES','Páginas mas Leidas');
		
		break;
	
	case 'categos':
		
		define('_AS_QP_LIST','Categorías Existentes');
		define('_AS_QP_CATLIST','Categorías');
		define('_AS_QP_NEWCAT','Nueva Categoría');
		define('_AS_QP_DELCAT','Eliminar Categoría');
		define('_AS_QP_CATPAGES','Páginas');
		define('_AS_QP_NEWTITLE','Nueva Categoría');
		define('_AS_QP_EDITTITLE','Editando Categoría');
		define('_AS_QP_DESC','Descripción');
		define('_AS_QP_CATPARENT','Categoría Raíz');
		
		define('_AS_QP_CONFIRMDEL','<strong>¿Realmente deseas eliminar esta categoría?</strong>');
		define('_AS_QP_DELETEDESC','Esta acción tambien eliminará todas las páginas contenidas en la categoría seleccionada');
		# ERRORES
		
		define('_AS_QP_ERRID','Especifica una categoría válida');
		define('_AS_QP_ERRNAME','Proporciona un nombre para esta categoría');
		define('_AS_QP_ERREXISTS','Ya existe una categoría con el mismo nombre');
		
		break;
	
	case 'pages':
	
		define('_AS_QP_PUBLICPAGES','Publicadas');
		define('_AS_QP_PRIVATEPAGES','Privadas');
		define('_AS_QP_NEWPAGE','Nueva Página');
		define('_AS_QP_NEWLINKED','Nueva Redirección');
		define('_AS_QP_PAGELIST','Páginas Existentes');
		define('_AS_QP_PRIVATELIST','Páginas Privadas');
		define('_AS_QP_PUBLICLIST','Páginas Publicadas');
		define('_AS_QP_TITLE','Título');
		define('_AS_QP_DATEPAGE','Creación');
		define('_AS_QP_MODPAGE','Modificada');
		define('_AS_QP_MENUPAGE','En Menú');
		define('_AS_QP_CATEGO','Categoría');
		define('_AS_QP_READS','Accesos');
		define('_AS_QP_ACCESS','Estado');
		define('_AS_QP_SEARCH','Buscar:');
		define('_AS_QP_SHOWALL','Mostrar Todas');
		define('_AS_QP_RESULTS','Resultados por Página:');
		define('_AS_QP_NEXTPAGE','Siguiente');
		define('_AS_QP_PREVPAGE','Anterior');
		define('_AS_QP_PUBLICATE','Páginas Pulicadas');
		define('_AS_QP_PRIVATIZE','Páginas Privadas');
		define('_AS_QP_PUBLISHED','Publicada');
		define('_AS_QP_PRIVATED','Privada');
		define('_AS_QP_DELPAGE','Eliminar Página');
		define('_AS_QP_ORDER','Orden');
		define('_AS_QP_SAVECHGS','Guardar Cambios');
		define('_AS_QP_LINKED','Enlazadas');
		
		# Formulario
		define('_AS_QP_NEWTITLE','Crear Nueva Página');
		define('_AS_QP_EDITTITLE','Editar Página');
		define('_AS_QP_NEWLINKTITLE','Crear Nueva Página Enlazada');
		define('_AS_QP_EDITLINKTITLE','Editar Página Enlazada');
		define('_AS_QP_URL','URL');
		define('_AS_QP_FRIENDTITLE','Título Corto');
		define('_AS_QP_FRIENDDESC','Este título será utilizado para crear URLs amigables. No debe contener espacios ni carácteres especiales. Si no se especifica será creado automáticamente.');
		define('_AS_QP_SHORTDESC','Descripción Corta');
		define('_AS_QP_PAGETEXT','Contenido de la Página');
		define('_AS_QP_XCODE','Habilitar XOOPS Code');
		define('_AS_QP_SAVEANDRETURN','Guardar y Seguir Editando');
		define('_AS_QP_PAGESTATUS','Estado de la Página');
		define('_AS_QP_GROUPS','Grupos Permitidos');
		define('_AS_QP_GROUPS_DESC','Solo los grupos seleccionados podrán tener acceso a este documento.');
		define('_AS_QP_INMENU','Mostrar en el Menú');
		
		define('_AS_QP_CONFIRMDEL','¿Realmente deseas eliminar esta página?');
		
		# ERRORES
		define('_AS_QP_NOID','Especifica una página válida');
		define('_AS_QP_SAVE','Guardar');
		define('_AS_QP_PUBLISH','Publicar');
		define('_AS_QP_ERRTITLE','Proporciona un titulo para esta página');
		define('_AS_QP_ERRTEXT','No has escrito nada para el contenido de la Página');
		define('_AS_QP_ERRCAT','Selecciona una categoría para esta Página');
		define('_AS_QP_ERREXISTS','Ya existe una página con el mismo titulo corto. Por favor modifica el titulo corto o especifica un nuevo título.');
		define('_AS_QP_SELECTONE','Selecciona al menos una página para modificar');
		
		break;
}

?>