<?php
// $Id: admin.php 37 2008-03-03 18:46:45Z BitC3R0 $
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


define('_AS_DT_SESSINVALID','Lo sentimos, tu identificador de sesión ya no es válido');
define('_AS_DT_DBERROR','Error al realizar esta operación');
define('_AS_DT_DBOK','Base de datos actualizada correctamente');
define('_AS_DT_ID','ID');
define('_AS_DT_ERRORS','Se detectaron los siguientes errores al realizar operación: <br />');
define('_AS_DT_ALLPERM','<strong>Advertencia:</strong> Los datos se eliminarán permanentemente.');
define('_AS_DT_SW','Descargas');
define('_AS_DT_DELSECURE','<br />Elimine manualmente los archivos <br /> %s Del directorio %s');
define('_AS_DT_SHOWING', 'Mostrando elementos <strong>%u</strong> a <strong>%u</strong> de <strong>%u</strong>.');
define('_AS_DT_NOTDIRINSECURE','El directorio de descargas no seguras no existe. <br />Por favor cree este directorio <br />%s');
define('_AS_DT_NOTDIRSECURE','El directorio de descargas seguras no existe. <br />Por favor cree este directorio <br />%s');
define('_AS_DT_LSOFT','Seleccionar Elemento');

switch (DT_LOCATION){

	case 'items':
	
		define('_AS_DT_ITEMS','Descargas');
		define('_AS_DT_ITEMSWAIT','Descargas pendientes');
		define('_AS_DT_ITEMSEDIT','Descargas Editadas');
		define('_AS_DT_NEWITEM','Crear Descarga');
		define('_AS_DT_EDITITEM','Editar Descarga');
		define('_AS_DT_EXISTS','Descargas Existentes');
		define('_AS_DT_EXISTSWAIT','Descargas Pendientes Existentes');
		define('_AS_DT_EXISTSEDIT','Descargas Editadas Existentes');
		define('_AS_DT_APP','Aprobar');
		define('_AS_DT_NOAPP','No aprobar');
		define('_AS_DT_SCREENS','Pantallas');
		define('_AS_DT_FEATURES','Características');
		define('_AS_DT_FILES','Archivos');
		define('_AS_DT_LOGS','Logs');
		define('_AS_DT_RESULT','Resultados');
		define('_AS_DT_LEGEND','Leyenda:');
		define('_AS_DT_PROTECT','Protegida');
		define('_AS_DT_DATE','Fecha:');
		define('_AS_DT_BY','Por:');
		define('_AS_DT_OUTS','Destacado');
		define('_AS_DT_DAILY','Diario');
		define('_AS_DT_DOWNMARK','Descarga Destacada');
		define('_AS_DT_DOWNDAILY','Descarga Diaria');
		define('_AS_DT_SEARCH','Buscar');
		define('_AS_DT_CATEGOEXIST','Descargas Existentes de %s');
		define('_AS_DT_ACCEPT','Aceptar');
		define('_AS_DT_DELETE','Eliminar');
		define('_AS_DT_SUBJECT','Edición de Descarga %s aprobada');
		define('_AS_DT_SUBJECTDEL','Edición de Descarga %s no aprobada');
	

		//Formulario
		define('_AS_DT_CREAITEM','Crear Descarga');
		define('_AS_DT_NAME','Nombre');
		define('_AS_DT_NAMEID','Nombre Corto');
		define('_AS_DT_CATEGO','Categoría');
		define('_AS_DT_SHORTDESC','Descripción Corta');
		define('_AS_DT_DESC','Descripción');
		define('_AS_DT_IMAGE','Imagen');
		define('_AS_DT_IMAGEACT','Imagen Actual');
		define('_AS_DT_LIMITS','Limite de descargas por Usuario');
		define('_AS_DT_DESCLIMITS','= significa ilimitado.');
		define('_AS_DT_USER','Usuario');
		define('_AS_DT_SECURE','Descarga Segura');
		define('_AS_DT_GROUPS','Grupos que pueden realizar descargas');
		define('_AS_DT_APPROVED','Aprobado');
		define('_AS_DT_MARK','Descarga destacada');
		define('_AS_DT_RATING','Rating');
		define('_AS_DT_TAGS','Etiquetas');
		define('_AS_DT_DESCTAGS','Separar con un espacio " " las etiquetas');
		define('_AS_DT_NEWFILES','Crear Archivos');
		define('_AS_DT_NEWLOG','Crear Log');
		define('_AS_DT_ALERT','Alerta');
		define('_AS_DT_ACTALERT','Activar alerta');
		define('_AS_DT_MP','Mensaje Privado');
		define('_AS_DT_EMAIL','Correo Electrónico');
		define('_AS_DT_LIMIT','Límite de días');
		define('_AS_DT_DESCLIMIT','Límite en días de inactividad del <br />elemento antes de enviar la alerta');
		define('_AS_DT_MODE','Modo de envío de alerta');
		define('_AS_DT_LICENCES','Licencias');	
		define('_AS_DT_PLATFORMS','Plataformas');
		define('_AS_DT_LICOTHER','Otra...');
		define('_AS_DT_VERSION','Versión');
		define('_AS_DT_OTHER','Otros Datos');
		define('_AS_DT_AUTHOR','Nombre del Autor');
		define('_AS_DT_AUTHORURL','URL del Autor');
		define('_AS_DT_LANGS','Idiomas Disponibles');
		
		// Mensajes
		define('_AS_DT_DELETECONF','¿Realmente deseas eliminar la descarga <strong>%s</strong> ?');		

		//Errores
		define('_AS_DT_ERR_ITEMVALID','Descarga no válida');
		define('_AS_DT_ERR_ITEMEXIST','No existe la descarga especificada');
		define('_AS_DT_ERRNAME','Ya existe una descarga con el mismo nombre');
		define('_AS_DT_ERRNAMEID','Ya existe una descarga con el mismo nombre corto');
		define('_AS_DT_NOTID','Debes seleccionar al menos un elemento');
		define('_AS_DT_ERRNOTVALID','La Descarga %s no es válida <br />');
		define('_AS_DT_ERRNOTEXIST','La descarga %s no existe <br />');
		define('_AS_DT_ERRNOTSAVE','No se ha actualizado la descarga %s<br />');

		break;
		
	case 'screens':
	
		define('_AS_DT_SCREENS','Pantallas');
		define('_AS_DT_NEWSCREEN','Crear Pantalla');
		define('_AS_DT_EXISTITEM','Pantallas Existentes de %s ');
		define('_AS_DT_DELETECONF','¿Realmente deseas eliminar la pantalla <strong>%s</strong>?');
		define('_AS_DT_DELCONF','¿Realmente deseas eliminar las pantallas seleccionadas?');
		define('_AS_DT_SELECTITEM','Seleccionar Software para visualizar su lista de pantallas existentes');
		define('_AS_DT_DELETESCREEN','Eliminar Pantalla');
		
		//Formulario
		define('_AS_DT_EDITSCREEN','Editar Pantalla');
		define('_AS_DT_TITLE','Título');
		define('_AS_DT_DESC','Descripción');
		define('_AS_DT_IMAGE','Imagen');
		define('_AS_DT_ITEM','Software');
		define('_AS_DT_IMAGEACT','Imagen Actual');
		define('_AS_DT_NEWSCREENS','Crear pantalla de %s');
		define('_AS_DT_EDITSCREENS','Editar pantalla de %s');


		//Errores
		define('_AS_DT_ERRNAME','Error nombre de pantalla existente');
		define('_AS_DT_ERR_SCVALID','Error Pantalla no válida');
		define('_AS_DT_ERR_SCEXIST','Error Pantalla no existente');
		define('_AS_DT_ERR_ITEMVALID','Error Software no válido');
		define('_AS_DT_ERR_ITEMEXIST','Error Software no existente');
		define('_AS_DT_ERRSCVAL','Pantalla %s no válida <br />');
		define('_AS_DT_ERRSCEX','Pantalla %s no existente <br />');
		define('_AS_DT_ERRSCDEL','Pantalla %s no eliminada <br />');
		define('_AS_DT_NOTSCREEN','Debes proporcionar al menos una pantalla para eliminar');
		define('_AS_DT_ERRCOUNT','Ya se han almacenado el número máximo de pantallas permitidas');
		

		break;
		
	case 'features':
	
		define('_AS_DT_FEATURES','Características');
		define('_AS_DT_NEWFEATURE','Crear Característica');
		define('_AS_DT_EXISTS','Características Existentes de %s');
		define('_AS_DT_DELETECONF','¿Realmente deseas eliminar la característica <strong>%s</strong>?');
		define('_AS_DT_DELCONF','¿Realmente deseas eliminar las características?');
		define('_AS_DT_SELECTITEM','Selecciona un elemento para visualizar su lista de características');
		define('_AS_DT_DELETEFEATURE','Eliminar Característica');	
		define('_AS_DT_CREATED','Creada');
		define('_AS_DT_FEATNEW','Característica Nueva');

		//Formulario
		define('_AS_DT_EDITFEATURE','Editar Característica');
		define('_AS_DT_SOFTWARE','Software');
		define('_AS_DT_TITLE','Título');
		define('_AS_DT_MODIFIED','Modificación');
		define('_AS_DT_CONTENT','Contenido');
		define('_AS_DT_NEW','Mostrar como nueva');
		define('_AS_DT_NEWFEATURES','Crear característica de %s');
		define('_AS_DT_EDITFEATURES','Editar característica de %s');
		define('_AS_DT_SHORTNAME','Nombre Corto');

		//Errores
		define('_AS_DT_ERRFEATVALID','Característica no válida');
		define('_AS_DT_ERRFEATEXIST','Característica no existente');
		define('_AS_DT_ERRNAME','Error nombre de característica existente');
		define('_AS_DT_ERR_ITEMVALID','Software no válido');
		define('_AS_DT_ERR_ITEMEXIST','La descarga especificada no existe');
		define('_AS_DT_ERRFEATVAL','Característica %s no válida <br />');
		define('_AS_DT_ERRFEATEX','Característica %s no existente <br />');
		define('_AS_DT_ERRFEATSAVE','Característica %s no actualizada <br />');
		define('_AS_DT_ERRFEAT','Debes proporcionar al menos una característica');
		define('_AS_DT_ERRFEATDEL','No se pudo eliminar la característica %s <br />');
	
		break;
		
	case 'listsoft':
	
		define('_AS_DT_LISTSOFT','Descargas Existentes');
		define('_AS_DT_SEARCH','Buscar: ');
		
		break;
		
	case 'files':
	
		define('_AS_DT_FILES','Archivos');
		define('_AS_DT_NEWFILE','Crear Archivo');
		define('_AS_DT_DELETECONF','¿Realmente deseas eliminar el archivo <strong>%s</strong>?');
		define('_AS_DT_DELCONF','¿Realmente deseas eliminar los archivos ?');
		define('_AS_DT_DELETECONFG','¿Realmente deseas eliminar el grupo <strong>%s</strong> ?');
		define('_AS_DT_DELETEGROUP','Eliminar Grupo');
		define('_AS_DT_EXIST','Archivos Existentes de %s');
		define('_AS_DT_SELECTITEM','Seleccionar software para visualizar su lista de archivos existentes');
		define('_AS_DT_SAVE','Guardar Cambios');
		define('_AS_DT_DELETEFILE','Eliminar Archivo');
		define('_AS_DT_SOFTWARE','Descargas');

		//Formulario
		define('_AS_DT_EDITFILE','Editar Archivo');
		define('_AS_DT_FILE','Archivo');
		define('_AS_DT_DESCTYPEFILE','Tipos de extensiones permitidas %s <br /> El tamaño permitido del archivo es de %s');
		define('_AS_DT_NAMEFILE','Nombre de archivo');
		define('_AS_DT_FILEURL','URL de archivo');
		define('_AS_DT_REMOTE','Archivo remoto');
		define('_AS_DT_GROUP','Grupo');
		define('_AS_DT_DEFAULT','Archivo a descargar por defecto');
		define('_AS_DT_DOWNS','Descargas');
		define('_AS_DT_SIZE','Tamaño de archivo');
		define('_AS_DT_DESCSIZE','Indique el tamaño de archivo si solo ha proporcionado la URL.<br /> Especifique el tamaño en kilobytes.');
		define('_AS_DT_DEFAULTFILE','Predeterminado');
		define('_AS_DT_NEWFILES','Crear archivo de %s');
		define('_AS_DT_EDITFILES','Editar archivo de %s');
		define('_AS_DT_FILETITLE','Título Descriptivo');
		define('_AS_DT_FILETITLE_DESC','Si no se especifica un título descriptivo el nombre de archivo o url serán usados en su lugar.');

		define('_AS_DT_NEWGROUP','Crear Grupo');
		define('_AS_DT_NAME','Nombre');

		//Errores
		define('_AS_DT_ERRFILEVALID','Archivo no válido');
		define('_AS_DT_ERRFILEEXIST','Archivo no existente');
		define('_AS_DT_ERR_ITEMVALID','Software no válido');
		define('_AS_DT_ERR_ITEMEXIST','Software no existente');
		define('_AS_DT_ERRNAMEGROUP','Grupo Existente');
		define('_AS_DT_ERRVALID','Archivo %s no válido <br />');
		define('_AS_DT_ERREXIST','Archivo %s no existente <br />');
		define('_AS_DT_ERRUPDATE','No se modificó el archivo %s <br />');
		define('_AS_DT_ERRNOTEXT','Este tipo de archivo no esta permitido: %s');
		define('_AS_DT_ERRNOEXISTFILE','El archivo no existe en el directorio de descargas seguras.<br /> Por favor antes cargue el archivo en el directorio: <br /> %s ');

		define('_AS_DT_ERRGROUPVALID','Grupo no válido');
		define('_AS_DT_ERRGROUPEXIST','Grupo no existente');
		define('_AS_DT_ERRFILEVAL','Archivo %s no válido <br />');
		define('_AS_DT_ERRFILEEX','Archivo %s no existente <br />');
		define('_AS_DT_ERRFILEDEL','Archivho %s no ha sido eliminado <br />');
		define('_AS_DT_NOTFILE','Debes proporcionar al menos un archivo');
		define('_AS_DT_ERRSIZE','Tamaño de archivo no especificado. Es necesario proporcionarlo.');
		
		
		break;
		
	case 'logs':
	
		define('_AS_DT_LOGS','Logs');
		define('_AS_DT_NEWLOG','Crear Log');
		define('_AS_DT_EDITLOG','Editar Log');
		define('_AS_DT_TITLE','Título');
		define('_AS_DT_DATE','Fecha');
		define('_AS_DT_EXIST','Logs Existentes de %s');
		define('_AS_DT_SELECTITEM','Seleccionar software para visualizar su lista de logs');
		define('_AS_DT_DELETELOG','Eliminar Log');
		define('_AS_DT_DELETECONF','¿Realmente deseas eliminar el log <strong>%s</strong>?');
		define('_AS_DT_DELCONF','¿Realmente deseas eliminar los logs?');

		//Formulario
		define('_AS_DT_NEWLOGS','Crear log de %s');
		define('_AS_DT_EDITLOGS','Editar log de %s');
		define('_AS_DT_SOFTWARE','Software');
		define('_AS_DT_LOG','Log');

		//Errores
		define('_AS_DT_ERR_ITEMVALID','Software no válido');
		define('_AS_DT_ERR_ITEMEXIST','Software no existente');
		define('_AS_DT_ERRLOGVALID','Log no válido');
		define('_AS_DT_ERRLOGEXIST','Log no existente');
		define('_AS_DT_NOTLOG','Debes proporcionar al menos un log');
		define('_AS_DT_ERRLOGVAL','Log %s no válido <br />');
		define('_AS_DT_ERRLOGEX','Log %s no existente <br />');
		define('_AS_DT_ERRLOGDEL','Log %s no eliminado <br />');
		
		break;
		
	case 'licences':
		
		define('_AS_DT_LICENCES','Licencias');
		define('_AS_DT_NEWLICENSE','Crear Licencia');
		define('_AS_DT_EXIST','Licencias Existentes');
		define('_AS_DT_DELETELIC','Eliminar Licencia');
		define('_AS_DT_DELETECONF','¿Realmente deseas eliminar la licencia <strong>%s</strong>?');
		define('_AS_DT_DELCONF','¿Realmente deseas eliminar las licencias?');

		//Formulario
		define('_AS_DT_EDITLIC','Editar Licencia');
		define('_AS_DT_NAME','Nombre Licencia');
		define('_AS_DT_URL','URL');
		define('_AS_DT_TYPE','Tipo');
		define('_AS_DT_FREE','Licencia Libre');
		define('_AS_DT_RESTRICT','Licencia Restringida');
		

		//Errores
		define('_AS_DT_ERRURL','Proporciona una URL correcta');
		define('_AS_DT_ERRLICVALID','Licencia no válida');
		define('_AS_DT_ERRLICEXIST','Licencia no existente');
		define('_AS_DT_ERRNAME','Nombre de licencia existente');
		define('_AS_DT_ERRLICVAL','Licencia %s no válida <br />');
		define('_AS_DT_ERRLICEX','Licencia %s no existente <br />');
		define('_AS_DT_ERRLICDEL','Licencia %s no ha sido eliminada <br />');
		define('_AS_D_NOTLIC','Debes proporcionar al menos una licencia ');
		
		break;
		
	case 'plats':
		
		define('_AS_DT_PLATFORMS','Plataformas');
		define('_AS_DT_NEWPLATFORM','Crear Plataforma');
		define('_AS_DT_EXIST','Plataformas Existentes');		
		define('_AS_DT_DELETECONF','¿Realmente deseas eliminar la plataforma <strong>%s</strong>?');
		define('_AS_DT_DELCONF','¿Realmente deseas eliminar las plataformas?');

		//Formulario
		define('_AS_DT_EDITPLAT','Editar Plataforma');
		define('_AS_DT_NAME','Nombre');
		
		//Errores
		define('_AS_DT_ERRPLATVALID','Plataforma no válida');
		define('_AS_DT_ERRPLATEXIST','Plataforma no existente');
		define('_AS_DT_ERRNAME','Nombre de plataforma existente');
		define('_AS_DT_NOTPLAT','Debes proporcionar al menos una plataforma');
		define('_AS_DT_ERRPLATVAL','Plataforma %s no válida <br />');
		define('_AS_DT_ERRPLATEX','Plataforma %s no existente <br />');
		define('_AS_DT_ERRPLATDEL','Plataforma %s no ha sido eliminada <br />');
		
		break;
		
	case 'categories':
		define('_AS_DT_CATEGO','Categorías');
		define('_AS_DT_NEWCATEGO','Crear categoría');
		define('_AS_DT_DELCAT','Eliminar Categoría');
		define('_AS_DT_DELETECONF','¿Realmente deseas eliminar la categoría <strong>%s</strong>?');
		define('_AS_DT_DELCONF','¿Realmente deseas eliminar las categorías?');
		define('_AS_DT_EXIST','Categorías Existentes');
	

		//Tabla
		define('_AS_DT_NAME','Nombre');
		define('_AS_DT_PARENT','Categoría Padre');
		define('_AS_DT_ACTIVE','Activa');
		define('_AS_DT_ACTIV','Activar');
		define('_AS_DT_DESACTIV','Desactivar');
		

		//Formulario
		define('_AS_DT_EDITCATEGO','Editar Categoría');
		define('_AS_DT_DESC','Descripción');
		define('_AS_DT_NAMEID','Nombre Corto');


		//Errores
		define('_AS_DT_ERRCATVALID','Categoría no válida');
		define('_AS_DT_ERRCATEXIST','Categoría no existente');
		define('_AS_DT_ERRCATVAL','Categoría %s no válida <br />');
		define('_AS_DT_ERRCATEX','Categoría %s no existente <br />');
		define('_AS_DT_ERRCATDEL','categoría %s no ha sido eliminada <br />');
		define('_AS_DT_ERRCATSAVE','categoría %s no ha sido actualizada <br />');
		define('_AS_DT_ERRCAT','Debes proporcionar al menos una categoría');
		define('_AS_DT_ERRNAME','Nombre de categoría existente');
		define('_AS_DT_ERRNAMEID','Nombre corto de categoría existente');

		break;

	case 'index':
		
		define('_AS_DT_CATS','Categorías');
		define('_AS_DT_CATSNUM','%u Categorías');
		define('_AS_DT_SOFT','Descargas');
		define('_AS_DT_SOFTNUM','%u Descargas');
		define('_AS_DT_SOFTW','En Espera');
		define('_AS_DT_SOFTWNUM','%u En Espera');
		define('_AS_DT_SOFTE','Editadas');
		define('_AS_DT_SOFTENUM','%u Editadas');
		define('_AS_DT_SCREENS','Pantallas');
		define('_AS_DT_SCREENSNUM','%u Pantallas');
		define('_AS_DT_FEATURES','Características');
		define('_AS_DT_FEATURESNUM','%u Características');
		define('_AS_DT_FILES','Archivos');
		define('_AS_DT_FILESNUM','%u Archivos');
		define('_AS_DT_LOGS','Logs');
		define('_AS_DT_LOGSNUM','%u Logs');
		define('_AS_DT_LICS','Licencias');
		define('_AS_DT_LICSNUM','%u Licencias');
		define('_AS_DT_OS','Plataformas');
		define('_AS_DT_OSNUM','%u Plataformas');
		define('_AS_DT_HOME','Inicio');
		define('_AS_DT_HELP','Ayuda');
		define('_AS_DT_CLICK','Haz Click');
		
		define('_AS_DT_ERRACCESS','El módulo esta configurado para utilizar las capacidades de <strong>mod_rewrite</strong> para el manejo de
        			las urls, sin embargo ha sido imposible escrbir el archivo <strong>.htaccess</strong> incluido.<br />
        			Por favor otorgue permisos de escritura al archivo (chmod 777 en Linux, Solo lectura en Windows) o bien
        			escriba usted mismo la infromación copiando el texto que se proporciona mas abajo.');
        define('_AS_DT_ERRACCWRITE','El archivo <strong>.htaccess</strong> tiene permisos de escritura lo que puede convertirse
        			en un problema de seguridad. Para evitar esto por favor cambie los permisos del archivo. En Linux
        			chmod 444 y en Windows establezca como Solo Lectura');
        define('_AS_DT_SHOWCODE','Mostrar Código');
		
		break;
		
}
?>
