<?php
// $Id$
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


define('_AS_DT_SESSINVALID','Sorry, your ID sesion is no longer valid');
define('_AS_DT_DBERROR','Failed while performing this operation');
define('_AS_DT_DBOK','Database succesfully updated');
define('_AS_DT_ID','ID');
define('_AS_DT_ERRORS','The following error were detected while performing this operation: <br />');
define('_AS_DT_ALLPERM','<strong>Warning:</strong> The data will be permanently removed.');
define('_AS_DT_SW','Downloads');
define('_AS_DT_DELSECURE','<br />Remove files manually <br /> %s From directory %s');
define('_AS_DT_SHOWING', 'Showing elements <strong>%u</strong> to <strong>%u</strong> of <strong>%u</strong>.');
define('_AS_DT_NOTDIRINSECURE','El directorio de descargas no seguras no existe. <br />Please create this directory <br />%s');
define('_AS_DT_NOTDIRSECURE','El directorio de descargas seguras no existe. <br />Please create this directory <br />%s');
define('_AS_DT_LSOFT','Select Element');

switch (DT_LOCATION){

	case 'items':
	
		define('_AS_DT_ITEMS','Downloads');
		define('_AS_DT_ITEMSWAIT','Pending Downloads');
		define('_AS_DT_ITEMSEDIT','Edited Downloads');
		define('_AS_DT_NEWITEM','Create Downloads');
		define('_AS_DT_EDITITEM','Edit Download');
		define('_AS_DT_EXISTS','Existing Downloads');
		define('_AS_DT_EXISTSWAIT','Pending Downloads');
		define('_AS_DT_EXISTSEDIT','Existing Edited Downloads');
		define('_AS_DT_APP','Approve');
		define('_AS_DT_NOAPP','Not approve');
		define('_AS_DT_SCREENS','Screens');
		define('_AS_DT_FEATURES','Features');
		define('_AS_DT_FILES','Files');
		define('_AS_DT_LOGS','Logs');
		define('_AS_DT_RESULT','Results');
		define('_AS_DT_LEGEND','Legend:');
		define('_AS_DT_PROTECT','Protect');
		define('_AS_DT_DATE','Date:');
		define('_AS_DT_BY','By:');
		define('_AS_DT_OUTS','Featured');
		define('_AS_DT_DAILY','Daily');
		define('_AS_DT_DOWNMARK','Featured Download');
		define('_AS_DT_DOWNDAILY','Daily Download');
		define('_AS_DT_SEARCH','Search');
		define('_AS_DT_CATEGOEXIST','Existing Downloads of %s');
		define('_AS_DT_ACCEPT','Accept');
		define('_AS_DT_DELETE','Delete');
		define('_AS_DT_SUBJECT','Edición de Descarga %s aprobada');
		define('_AS_DT_SUBJECTDEL','Edición de Descarga %s no aprobada');
	

		//Formulario
		define('_AS_DT_CREAITEM','Create Download');
		define('_AS_DT_NAME','Name');
		define('_AS_DT_NAMEID','Nombre Corto');
		define('_AS_DT_CATEGO','Category');
		define('_AS_DT_SHORTDESC','Short Description');
		define('_AS_DT_DESC','Description');
		define('_AS_DT_IMAGE','Image');
		define('_AS_DT_IMAGEACT','Current Image');
		define('_AS_DT_LIMITS','User download limit');
		define('_AS_DT_DESCLIMITS','= significa ilimitado.');
		define('_AS_DT_USER','User');
		define('_AS_DT_SECURE','Secure Download');
		define('_AS_DT_GROUPS','Groups that can perform downloads');
		define('_AS_DT_APPROVED','Approve');
		define('_AS_DT_MARK','Descarga destacada');
		define('_AS_DT_RATING','Rating');
		define('_AS_DT_TAGS','Tags');
		define('_AS_DT_DESCTAGS','Saparate with a space " " the tags');
		define('_AS_DT_NEWFILES','Create Files');
		define('_AS_DT_NEWLOG','Create Log');
		define('_AS_DT_ALERT','Alert');
		define('_AS_DT_ACTALERT','Activate alert');
		define('_AS_DT_MP','Private message');
		define('_AS_DT_EMAIL','Email');
		define('_AS_DT_LIMIT','Limit of days');
		define('_AS_DT_DESCLIMIT','Límite en días de inactividad del <br />elemento antes de enviar la alerta');
		define('_AS_DT_MODE','Modo de envío de alerta');
		define('_AS_DT_LICENCES','Licences');	
		define('_AS_DT_PLATFORMS','Platforms');
		define('_AS_DT_LICOTHER','Other...');
		define('_AS_DT_VERSION','Version');
		define('_AS_DT_OTHER','Other Data');
		define('_AS_DT_AUTHOR','Author Name');
		define('_AS_DT_AUTHORURL','Author URL');
		define('_AS_DT_LANGS','Available Languages');
		
		// Mensajes
		define('_AS_DT_DELETECONF','Do you really wish to delete the download <strong>%s</strong> ?');		

		//Errores
		define('_AS_DT_ERR_ITEMVALID','Invalid download');
		define('_AS_DT_ERR_ITEMEXIST','The specify download does not exist');
		define('_AS_DT_ERRNAME','Already exist a download with the same name');
		define('_AS_DT_ERRNAMEID','Already exist a download with the same short name');
		define('_AS_DT_NOTID','You must select at least one element');
		define('_AS_DT_ERRNOTVALID','The Download %s is not valid <br />');
		define('_AS_DT_ERRNOTEXIST','The Download %s does not exist <br />');
		define('_AS_DT_ERRNOTSAVE','The download has not been updated %s<br />');

		break;
		
	case 'screens':
	
		define('_AS_DT_SCREENS','Screens');
		define('_AS_DT_NEWSCREEN','Create Screen');
		define('_AS_DT_EXISTITEM','Existing Screens of %s ');
		define('_AS_DT_DELETECONF','Do you really wish to delete the screen <strong>%s</strong>?');
		define('_AS_DT_DELCONF','Do you really wish to delete the selected screens?');
		define('_AS_DT_SELECTITEM','Select software to view its existing screens lists');
		define('_AS_DT_DELETESCREEN','Delete Screen');
		
		//Formulario
		define('_AS_DT_EDITSCREEN','Edit Screen');
		define('_AS_DT_TITLE','Title');
		define('_AS_DT_DESC','Description');
		define('_AS_DT_IMAGE','Image');
		define('_AS_DT_ITEM','Software');
		define('_AS_DT_IMAGEACT','Current Image');
		define('_AS_DT_NEWSCREENS','Create screen of %s');
		define('_AS_DT_EDITSCREENS','Edit screen of %s');


		//Errores
		define('_AS_DT_ERRNAME','Error existing screen name');
		define('_AS_DT_ERR_SCVALID','Error screen not valid');
		define('_AS_DT_ERR_SCEXIST','Error not exisitng screen');
		define('_AS_DT_ERR_ITEMVALID','Error Software not valid');
		define('_AS_DT_ERR_ITEMEXIST','Error not existing software');
		define('_AS_DT_ERRSCVAL','Screen %s not valid <br />');
		define('_AS_DT_ERRSCEX','Screen %s not existing <br />');
		define('_AS_DT_ERRSCDEL','Screen %s not deleted <br />');
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
