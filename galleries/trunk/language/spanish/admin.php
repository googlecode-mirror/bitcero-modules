<?php
// $Id$
// --------------------------------------------------------
// Gallery System
// Manejo y creación de galerías de imágenes
// CopyRight © 2008. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.exmsystem.org
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
// @copyright: 2008 Red México

define('_AS_GS_ID','ID');
define('_AS_GS_DBOK','Base de Datos Actualizada Correctamente');
define('_AS_GS_DBERROR','Error al realizar esta operación');
define('_AS_GS_DBERRORS','Errores al realizar esta operación: <br />');
define('_AS_GS_SESSINVALID','Lo sentimos, tu identificador de sesión ya no es válido');
define('_AS_GS_SHOWING', 'Mostrando elementos <strong>%u</strong> a <strong>%u</strong> de <strong>%u</strong>.');

switch (GS_LOCATION){
	case 'index':
		define('_AS_GS_HOME','Inicio');
		define('_AS_GS_CLICK','Click Aquí');
		define('_AS_GS_SETS','Albumes');
		define('_AS_GS_SETSNUM','%s Albumes');
		define('_AS_GS_TAGS','Etiquetas');
		define('_AS_GS_TAGSNUM','%s Etiquetas');
		define('_AS_GS_USERS','Usuarios');
		define('_AS_GS_USERSNUM','%s Usuarios');
		define('_AS_GS_IMGS','Imágenes');
		define('_AS_GS_IMGSNUM','%s Imágenes');
		define('_AS_GS_POSTCARDS','Postales');
		define('_AS_GS_SIZE','Utilizado');
		define('_AS_GS_SIZENUM','%s ');
		define('_AS_GS_POSTCARDSNUM','%s Postales');
		define('_AS_GS_HELP','Ayuda');
		

		define('_AS_GS_ERRACCESS','El módulo esta configurado para utilizar las capacidades de <strong>mod_rewrite</strong> para el manejo de
        			las urls, sin embargo ha sido imposible escrbir el archivo <strong>.htaccess</strong> incluido.<br />
        			Por favor otorgue permisos de escritura al archivo (chmod 777 en Linux, Solo lectura en Windows) o bien
        			escriba usted mismo la infromación copiando el texto que se proporciona mas abajo.');
     		define('_AS_GS_ERRACCWRITE','El archivo <strong>.htaccess</strong> tiene permisos de escritura lo que puede convertirse
        			en un problema de seguridad. Para evitar esto por favor cambie los permisos del archivo. En Linux
        			chmod 444 y en Windows establezca como Solo Lectura');
		define('_AS_GS_SHOWCODE','Mostrar Código');
		

	break;	
	case 'sets':
		
		define('_AS_GS_SETS','Albumes');
		define('_AS_GS_NEW','Nuevo Album');
		define('_AS_GS_SETSLOC','Administración de Albumes');
		define('_AS_GS_LOCDELETE','Eliminar Album');
		define('_AS_GS_NEWSET','Crear Album');
		define('_AS_GS_EDITSET','Editar Album');
		define('_AS_GS_DELETECONF','¿Realmente deseas eliminar el album <strong>%s</strong>?');
		define('_AS_GS_DELETECONFS','¿Realmente deseas eliminar los albums seleccionados?');
		define('_AS_GS_ALLPERM','Advertencia: Todos los datos se eliminarán permanentemente');
		define('_AS_GS_PUBLICS','Pública');
		define('_AS_GS_NOPUBLICS','No publica');
		define('_AS_GS_SEARCH','Buscar');
		

		define('_AS_GS_EXISTING','Albumes Existentes');
		define('_AS_GS_TITLE','Título');
		define('_AS_GS_OWNER','Propietario');
		define('_AS_GS_PUBLIC','Público');
		define('_AS_GS_PRIVACY','Privacidad');
		define('_AS_GS_CREATED','Creado');
		define('_AS_GS_PICS','Imágenes');
		define('_AS_GS_PRIVATE','Privado');		
		define('_AS_GS_PRIVFRIEND','Privado(Tú y Amigos)');

		//Errores
		define('_AS_GS_ERRSETEXIST','Album no existente');
		define('_AS_GS_ERRSETVALID','Album no válido');
		define('_AS_GS_ERRSET','Debes proporcionar al menos un album para eliminar');
		define('_AS_GS_ERRVALID','Album %s no válido <br />');
		define('_AS_GS_ERREXIST','Album %s no existente <br />');
		define('_AS_GS_ERRDELETE','Album %s no eliminado <br />');
		define('_AS_GS_ERRTITLE','Título de Album Existente');
		define('_AS_GS_ERRSETPUBLIC','Debes proporcionar al menos un album para publicar/no publicar');		
		
		break;
		
	case 'tags':
	
		define('_AS_GS_TAGSLOC','Control de Etiquetas');
		define('_AS_GS_LOCDELETE','Eliminar Etiqueta');
		define('_AS_GS_TAGS','Etiquetas');
		define('_AS_GS_NEW','Crear Etiquetas');
		define('_AS_GS_DELETECONF','¿Realmente deseas eliminar la etiqueta <strong>%s</strong>?');
		define('_AS_GS_DELETECONFS','¿Realmente deseas eliminar las etiquetas seleccionadas?');
		define('_AS_GS_ALLPERM','Advertencia: Todos los datos se eliminarán permanentemente');

		//Tabla
		define('_AS_GS_TAG','Etiqueta');
		define('_AS_GS_EXIST','Etiquetas Existentes');
		define('_AS_GS_PICS','Imágenes');
		define('_AS_GS_SEARCH','Buscar');
		

		//Formulario
		define('_AS_GS_NAME','Nombre');
		define('_AS_GS_EDITTAG','Editar Etiqueta');
		define('_AS_GS_NEWTAG','Crear Etiquetas');

		//Errores
		define('_AS_GS_ERRSAVE','Etiqueta %s no ha sido almacenada<br />');
		define('_AS_GS_ERRNAME','Nombre de Etiqueta %s ya existente<br />');
		define('_AS_GS_ERRTAGVALID','Etiqueta no válida');
		define('_AS_GS_ERRTAGEXIST','Etiqueta no existente');
		define('_AS_GS_ERRTAG','Debes proporcionar al menos una etiqueta para eliminar');
		define('_AS_GS_ERRTAGEDIT','Debes proporcionar al menos una etiqueta para editar');
		define('_AS_GS_ERRNOTVALID','Etiqueta %s no válida <br />');
		define('_AS_GS_ERRNOTEXIST','Etiqueta %s no existente <br />');
		define('_AS_GS_ERRDELETE','Etiqueta %s no eliminada <br />');
		define('_AS_GS_ERRSIZETAG','Etiqueta %s no se encuentra en el rango de caracteres permitidos');
		
		break;
		
	case 'users':
	
		define('_AS_GS_USERS','Usuarios');	
		define('_AS_GS_NEW','Nuevo Usuario');
		define('_AS_GS_USERSLOC','Administración de Usuarios');
		define('_AS_GS_DELETECONF','¿Realmente deseas eliminar el usuario <strong>%s</strong>?');
		define('_AS_GS_DELETECONFS','¿Realmente deseas eliminar los usuarios seleccionados?');
		define('_AS_GS_ALLPERM','Advertencia: Todos los datos se eliminarán permanentemente');
		define('_AS_GS_LOCDELETE','Eliminar Usuario');

		//Tabla
		define('_AS_GS_EXIST','Usuarios Existentes');
		define('_AS_GS_NAME','Nombre');
		define('_AS_GS_QUOTA','Cuota');
		define('_AS_GS_PICS','Imágenes');
		define('_AS_GS_SETS','Albumes');
		define('_AS_GS_DATE','Fecha');
		define('_AS_GS_SEARCH','Buscar');	
		define('_AS_GS_USED','Cuota Usada');
		define('_AS_GS_BLOCKED','Bloqueado');
		define('_AS_GS_BLOCK','Bloquear/Desbloquear');		

		//Formulario
		define('_AS_GS_USER','Usuario');
		define('_AS_GS_EDITUSER','Editar Usuario');
		define('_AS_GS_NEWUSER','Nuevo Usuario');
		define('_AS_GS_DESCQUOTA','Especifique la cuota en MegaBytes');

		//Errores
		define('_AS_GS_ERRUSER','Usuario ya registrado');
		define('_AS_GS_ERRUSERVALID','Usuario no válido');
		define('_AS_GS_ERRUSEREXIST','Usuario no existente');
		define('_AS_GS_ERRUSERDEL','Debes proporcionar al menos un usuario para eliminar');	
		define('_AS_GS_ERRNOTVALID','Usuario %s no válido <br />');
		define('_AS_GS_ERRNOTEXIST','Usuario %s no existente <br />');
		define('_AS_GS_ERRDELETE','Usuario %s no eliminado <br />');
		define('_AS_GS_ERRUSERBLOCK','Debes proporcionar al menos un usuario para bloquear/desbloquear');	
		define('_AS_GS_ERRSAVE','Usuario %s no actualizado <br />');

		break;
		
	case 'images':
	
		define('_AS_GS_IMAGES','Imágenes');
		define('_AS_GS_NEW','Crear Imagen');
		define('_AS_GS_NEWIMGS','Crear Imagenes');
		define('_AS_GS_IMGSLOC','Administración de Imágenes');
		define('_AS_GS_DELETECONF','¿Realmente deseas eliminar la imagen <strong>%s</strong>?');
		define('_AS_GS_DELETECONFS','¿Realmente deseas eliminar las imágenes seleccionadas?');
		define('_AS_GS_ALLPERM','Advertencia: Todos los datos se eliminarán permanentemente');
		define('_AS_GS_LOCDELETE','Eliminar Imagen');
		define('_AS_GS_PUBLICIMG','Pública');
		define('_AS_GS_NOPUBLICIMG','Privada');
		define('_AS_GS_PRIVATEF','Privada(Tú y Amigos)');

		//Tabla
		define('_AS_GS_EXIST','Imágenes Existentes');
		define('_AS_GS_TITLE','Título');
		define('_AS_GS_DESC','Descripción');
		define('_AS_GS_IMG','Imagen');
		define('_AS_GS_DATE','Fecha');
		define('_AS_GS_OWNER','Usuario');
		define('_AS_GS_SEARCH','Buscar:');
		define('_AS_GS_RESULTS','Resultados:');
		define('_AS_GS_INDATE','Entre:');
		define('_AS_GS_PUBLIC','Privacidad');
		define('_AS_GS_USERS','Usuario');
		define('_AS_GS_AND','y');


		//Formulario
		define('_AS_GS_NEWIMG','Crear imagen');
		define('_AS_GS_EDITIMG','Editar Image');
		define('_AS_GS_IMAGE','Imagen');
		define('_AS_GS_USER','Usuario');
		define('_AS_GS_IMGACT','Imagen Actual');
		define('_AS_GS_ALBUMS','Albumes');
		define('_AS_GS_TAGS','Etiquetas');
		define('_AS_GS_DESCTAGS','Separe cada etiqueta con un espacio.');
		define('_AS_GS_PRIVATE','Privada');		
		define('_AS_GS_PRIVFRIEND','Privada(Usted y amigos)');



		define('_AS_GS_SAVE','Guardar cambios');
		define('_AS_GS_USERASSIGN','Propietario de Imágenes');
		define('_AS_GS_MESSAGE','"Etiquetas" es un campo obligatorio'); 

		//Errores
		define('_AS_GS_ERRIMGVALID','Imagen no válida');
		define('_AS_GS_ERRIMGEXIST','Imagen no existente');
		define('_AS_GS_ERRUSER','Error los datos sel usuario no fueron almacenados');
		define('_AS_GS_ERRSAVEIMG','Imagen %s no almacenada <br />');
		define('_AS_GS_ERRIMG','Error en imagen %s: %s');
		define('_AS_GS_ERRIMGDELETE','Debes proporcionar al menos una imagen para eliminar');	
		define('_AS_GS_ERRNOTVALID','Imagen %s no válida <br />');
		define('_AS_GS_ERRNOTEXIST','Imagen %s no existente <br />');
		define('_AS_GS_ERRDELETE','Imagen %s no eliminada <br />');
		define('_AS_GS_ERRIMGPUB','Debes proporcionar al menos una imagen para publicar/no publicar');	
		define('_AS_GS_ERRSAVE','Imagen %s no ha sido actualizada <br />');
	

	break;
	case 'post':
		define('_AS_GS_POSTCARDS','Postales');
		define('_AS_GS_DELETECONF','¿Realmente deseas eliminar la postal <strong>%s</strong>?');
		define('_AS_GS_DELETECONFS','¿Realmente deseas eliminar las postales seleccionadas?');
		define('_AS_GS_ALLPERM','Advertencia: Todos los datos se eliminarán permanentemente');
		define('_AS_GS_LOCDELETE','Eliminar Postal');


	
		//Tabla
		define('_AS_GS_EXIST','Postales Existentes');
		define('_AS_GS_TITLE','Título');
		define('_AS_GS_DATE','Fecha');
		define('_AS_GS_REMIT','Remitente');
		define('_AS_GS_VIEW','Visto');
		

		//Errores
		define('_AS_GS_ERRNOTVALID','Postal %s no válida <br />');
		define('_AS_GS_ERRNOTEXIST','Postal %s no existente <br />');
		define('_AS_GS_ERRDELETE','Postal %s no eliminada <br />');
		define('_AS_GS_ERRPOSTDEL','Debes porporcionar al menos una postal para eliminar');
		
	break;
		
}


?>
