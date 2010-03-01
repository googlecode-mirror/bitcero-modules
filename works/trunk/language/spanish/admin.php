<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('_AS_PW_ID','ID');
define('_AS_PW_ERRSESSID','El ID de sesión ha caducado. Por favor vuelve a intentarlo.');
define('_AS_PW_DBOK','!Base de Datos Actualizada Correctamente¡');
define('_AS_PW_DBERROR','Error al realizar esta operación');
define('_AS_PW_DBERRORS','Errores al realizar ésta operación:<br/>');
define('_AS_PW_SHOWING', 'Mostrando elementos <strong>%u</strong> a <strong>%u</strong> de <strong>%u</strong>.');

switch(PW_LOCATION){
	
	case 'index':	
		define('_AS_PW_HOME','Inicio');
		define('_AS_PW_CLICK','Click Aquí');
		define('_AS_PW_CATEGOS','Categorías');
		define('_AS_PW_CATSNUM','%s Categorías');
		define('_AS_PW_TYPES','Tipos de Cliente');
		define('_AS_PW_TYPESNUM','%s Tipos de Cliente');
		define('_AS_PW_CLIENTS','Clientes');
		define('_AS_PW_CLIENTSNUM','%s Clientes');
		define('_AS_PW_WORKS','Trabajos');
		define('_AS_PW_WORKSNUM','%s Trabajos');
		define('_AS_PW_HELP','Ayuda');
	
		define('_AS_PW_ERRACCESS','El módulo esta configurado para utilizar las capacidades de <strong>mod_rewrite</strong> para el manejo de
        			las urls, sin embargo ha sido imposible escrbir el archivo <strong>.htaccess</strong> incluido.<br />
        			Por favor otorgue permisos de escritura al archivo (chmod 777 en Linux, Solo lectura en Windows) o bien
        			escriba usted mismo la infromación copiando el texto que se proporciona mas abajo.');
     		define('_AS_PW_ERRACCWRITE','El archivo <strong>.htaccess</strong> tiene permisos de escritura lo que puede convertirse
        			en un problema de seguridad. Para evitar esto por favor cambie los permisos del archivo. En Linux
        			chmod 444 y en Windows establezca como Solo Lectura');
		define('_AS_PW_SHOWCODE','Mostrar Código');

		break;
		
	case 'categories':
		
		define('_AS_PW_CATEGOS','Categorias');
		define('_AS_PW_NEWCATEGO','Crear Categoría');
		define('_AS_PW_CATLOC','Administración de Categorías');
		define('_AS_PW_DELETE','Eliminar Categoría');
		define('_AS_PW_DELETECONF','¿Realmente deseas eliminar la categoría <strong>%s</strong>?');
		define('_AS_PW_DELETECONFS','¿Realmente deseas eliminar las categorías seleccionadas?');
		define('_AS_PW_ALLPERM','<strong>Advertencia:</strong> Todos los datos se eliminarán permanentemente.');
		
		define('_AS_PW_EXISTING','Categorías Existentes');
		define('_AS_PW_NAME','Nombre');
		define('_AS_PW_DESC','Descripción');
		define('_AS_PW_WORKS','Trabajos');
		define('_AS_PW_ACTIVE','Activa');
		define('_AS_PW_ORDER','Orden');
		define('_AS_PW_SAVE','Guardar Cambios');
		define('_AS_PW_ACTIV','Activar');
		define('_AS_PW_DESACTIVE','Desactivar');
		
		// Formulario
		define('_AS_PW_FNEW','Crear Categoría');
		define('_AS_PW_FEDIT','Editar Categoría');
		define('_AS_PW_FNAME','Nombre');
		define('_AS_PW_FSHORTNAME','Nombre Corto');
		define('_AS_PW_FACTIVE','Categoría Activa');
		define('_AS_PW_FORDER','Orden');
		define('_AS_PW_FDESC','Descripción');
		define('_AS_PW_FPARENT','Categoría Padre');
		
		// ERRORES
		define('_AS_PW_ERRCATVALID','La categoría proporcionada no es válida');
		define('_AS_PW_ERRCATEXIST','La categoría proporcionada no existe');
		define('_AS_PW_ERRCATNAMEEXIST','Nombre de categoría existente');
		define('_AS_PW_ERRCATNAMEID','Nombre Corto existente');
		define('_AS_PW_NOTVALID','Categoría %s no válida <br />');
		define('_AS_PW_NOTEXIST','Categoría %s no existente <br />');
		define('_AS_PW_NOTSAVE','Categoría %s no ha sido actualizada <br />');
		define('_AS_PW_ERRNOTCATEGORY','Debes proporcionar al menos una categoría para activar/desactivar');
		define('_AS_PW_NOTDELETE','Categoría %s no ha sido eliminada <br />');
		define('_AS_PW_ERRNOTCATDEL','Debes proporcionar al menos una categoría para eliminar');		

		break;
		
	case 'types':
		define('_AS_PW_TYPELOC','Tipos de Cliente');
		define('_AS_PW_TYPES','Tipos');
		define('_AS_PW_NEWTYPE','Crear Tipos de Cliente');
		define('_AS_PW_DELETE','Eliminar Tipos de Cliente');
		define('_AS_PW_DELETECONF','¿Realmente deseas eliminar el tipo <strong>%s</strong>?');
		define('_AS_PW_DELETECONFS','¿Realmente deseas eliminar los tipos seleccionados?');
		define('_AS_PW_ALLPERM','<strong>Advertencia:</strong> Todos los datos se eliminarán permanentemente.');

		//Tabla
		define('_AS_PW_EXIST','Tipos de Cliente Existentes');
		define('_AS_PW_TYPE','Tipo');

		//Formulario
		define('_AS_PW_EDITTYPE','Editar Tipos de Cliente');
		define('_AS_PW_FNAME','Nombre');
		define('_AS_PW_SAVE','Guardar Cambios');

		//Errores
		define('_AS_PW_ERRTYPENAME','Tipo %s ya existente<br />');
		define('_AS_PW_ERRTYPESAVE','Tipo %s no ha sido almacenado <br />');
		define('_AS_PW_ERRTYPE','Debes proporcionar al menos un tipo para editar');
		define('_AS_PW_ERRNOTTYPEDEL','Debes proporcionar al menos un tipo para eliminar');
		define('_AS_PW_NOTVALID','Tipo %s no válido <br />');
		define('_AS_PW_NOTEXIST','Tipo %s no existente <br />');
		define('_AS_PW_NOTDELETE','Tipo %s no ha sido eliminado <br />');

		break;
		
	case 'clients':
		define('_AS_PW_CLIENTLOC','Administración de Clientes');
		define('_AS_PW_CLIENTS','Clientes');
		define('_AS_PW_NEWCLIENT','Nuevo Cliente');
		define('_AS_PW_DELETE','Eliminar Clientes');
		define('_AS_PW_DELETECONF','¿Realmente deseas eliminar el cliente <strong>%s</strong>?');
		define('_AS_PW_DELETECONFS','¿Realmente deseas eliminar los clientes seleccionados?');
		define('_AS_PW_ALLPERM','<strong>Advertencia:</strong> Todos los datos se eliminarán permanentemente.');

		//Tabla
		define('_AS_PW_EXIST','Clientes Existentes');
		define('_AS_PW_NAME','Nombre');
		define('_AS_PW_BUSINESS','Empresa');
		define('_AS_PW_TYPE','Tipo');
		
		//Formulario
		define('_AS_PW_EDITCLIENT','Editar Cliente');
		define('_AS_PW_FNAME','Nombre');
		define('_AS_PW_FBUSINESS','Empresa');
		define('_AS_PW_FMAIL','Correo Electrónico');
		define('_AS_PW_FDESC','Descripción');
		define('_AS_PW_FTYPE','Tipo de Cliente');

		//Errores
		define('_AS_PW_ERRCLIENTVALID','El cliente especificado no es válido');
		define('_AS_PW_ERRCLIENTEXIST','El cliente especificado no existe');
		define('_AS_PW_ERRNOTCLIENTDEL','Debes proporcionar al menos un cliente para eliminar');
		define('_AS_PW_NOTVALID','Cliente %s no es válido <br />');
		define('_AS_PW_NOTEXIST','Cliente %s no existente <br />');
		define('_AS_PW_NOTDELETE','Cliente %s no ha sido eliminado <br />');
		
		break;
		
	case 'works':
	
		define('_AS_PW_WORKS','Trabajos');
		define('_AS_PW_NEWWORK','Crear Trabajo');
		define('_AS_PW_WORKLOC','Administración de Trabajos');
		define('_AS_PW_DELETE','Eliminar Trabajo');

		define('_AS_PW_DELETECONF','¿Realmente deseas eliminar el trabajo <strong>%s</strong>?');
		define('_AS_PW_DELETECONFS','¿Realmente deseas eliminar los trabajos seleccionados?');
		define('_AS_PW_ALLPERM','<strong>Advertencia:</strong> Todos los datos se eliminarán permanentemente.');

		define('_AS_PW_EXIST','Trabajos Existentes');
		define('_AS_PW_TITLE','Título');
		define('_AS_PW_CATEGO','Categoría');
		define('_AS_PW_CLIENT','Cliente');
		define('_AS_PW_START','Fecha de Inicio');
		define('_AS_PW_MARK','Destacado');
		define('_AS_PW_PUBLIC','Público');
		define('_AS_PW_PUBLISH','Publicar');
		define('_AS_PW_NOPUBLIC','No publicar');
		define('_AS_PW_MRK','Destacar');
		define('_AS_PW_NOMARK','No Destacar');
		define('_AS_PW_IMAGE','Imágenes');
		define('_AS_PW_NAMESITE','Nombre del Sitio');
		define('_AS_PW_RATING','Rating');

		//Formulario
		define('_AS_PW_WORKEDIT','Editar Trabajo');
		define('_AS_PW_FTITLE','Título');
		define('_AS_PW_FSHORT','Descripción Corta');
		define('_AS_PW_FDESC','Descripción');
		define('_AS_PW_FCATEGO','Categoría');
		define('_AS_PW_FCLIENT','Cliente');
		define('_AS_PW_FCOMMENT','Comentario');
		define('_AS_PW_FURL','URL');
		define('_AS_PW_FSTART','Fecha de Inicio');
		define('_AS_PW_FPERIOD','Período');
		define('_AS_PW_FCOST','Costo');
		define('_AS_PW_FMARK','Destacado');
		define('_AS_PW_FPUBLIC','Público');
		define('_AS_PW_FIMAGE','Imagen');
		define('_AS_PW_FIMGACT','Imagen Actual');
		define('_AS_PW_OPTIONS','Opciones');

		//Errores
		define('_AS_PW_ERRWORKVALID','Trabajo no válido');
		define('_AS_PW_ERRORWORKEXIST','Trabajo no existente');	
		define('_AS_PW_ERRNOTWORKDEL','Debes proporcionar al menos un trabajo para eliminar');
		define('_AS_PW_ERRNOTWORKUP','Debes proporcionar al menos un trabajo para actualizar');
	        define('_AS_PW_NOTVALID','Trabajo %s no válido <br />');
		define('_AS_PW_NOTEXIST','Trabajo %s no existente <br />');
		define('_AS_PW_NOTDELETE','Trabajo %s no ha sido eliminado <br />');
		define('_AS_PW_NOTUPDATE','Trabajo %s no ha sido actualizada <br />');
		define('_AS_PW_EXISTS','¡Ya existe otro proyecto con el mismo nombre!');
		
		break;	
		
	case 'images':
	
		define('_AS_PW_IMGLOC','Control de Imágenes');
		define('_AS_PW_IMAGES','Imágenes');
		define('_AS_PW_NEWIMG','Crear Imagen');
		define('_AS_PW_DELETE','Eliminar Imagen');
		define('_AS_PW_DELETECONF','¿Realmente deseas eliminarla imagen <strong>%s</strong>?');
		define('_AS_PW_DELETECONFS','¿Realmente deseas eliminar las imágenes seleccionadas?');
		define('_AS_PW_ALLPERM','<strong>Advertencia:</strong> Todos los datos se eliminarán permanentemente.');

		//Tabla
		define('_AS_PW_TITLE','Título');
		define('_AS_PW_IMAGE','Imagen');
		define('_AS_PW_EXIST','Imágenes Existentes de %s');

		//Formulario
		define('_AS_PW_EDITIMG','Editar Imagen');
		define('_AS_PW_FTITLE','Título');
		define('_AS_PW_FDESC','Descripción');
		define('_AS_PW_FIMAGE','Imagen');
		define('_AS_PW_FIMGACT','Imagen Actual');

		//Errores
		define('_AS_PW_ERRWORKVALID','Trabajo no válido');
		define('_AS_PW_ERRWORKEXIST','Trabajo no existente');
		define('_AS_PW_ERRIMGVALID','Imagen no válida');
		define('_AS_PW_ERRIMGEXIST','Imagen no existente');
		define('_AS_PW_ERRNOTIMGDEL','Debes proporcionar al menos una imagen para eliminar');
		define('_AS_PW_NOTVALID','Imagen %s no válida <br />');
		define('_AS_PW_NOTEXIST','Imagen %s no existente <br />');
		define('_AS_PW_NOTDELETE','Imagen %s no ha sido eliminada <br />');
		
		break;
		
}
?>
