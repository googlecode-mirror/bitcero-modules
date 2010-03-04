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

define('_MS_GS_INIT','Inicio');
define('_MS_GS_SEARCH','Buscar');

// Encabezado
define('_MS_GS_HOME','Inicio');
define('_MS_GS_TAG','Etiquetas');
define('_MS_GS_SETS','Albumes');
define('_MS_GS_PICS','Fotografías');
define('_MS_GS_HPHOTOS','Fotografías');
define('_MS_GS_SENDPICS','Subir Imágenes');
define('_MS_GS_MYPHOTOS','Mis Fotografías');
switch(GS_LOCATION){
	
	case 'index':
	
		define('_MS_GS_LASTPHOTOS','Fotografías Recientes');
		define('_MS_GS_BY','Por: %s');
		define('_MS_GS_LASTSETS','Albumes Recientes');
		define('_MS_GS_SETCREATED','Creado:');
		define('_MS_GS_SETBY','Por:');
		define('_MS_GS_SETPICS','Fotografías:');
		define('_MS_GS_OTHERSETS','Otros Álbumes');
		define('_MS_GS_VIEW','Ver');
		define('_MS_GS_MORE','Mas Álbumes');
		
		define('_MS_GS_BROWSESET','Explorar Albumes');
		define('_MS_GS_BROWSEIMGS','Explorar Imágenes');
		define('_MS_GS_BROWSETAGS','Etiquetas Populares');
		
		break;
		
	case 'explore':

		define('_MS_GS_SHOWING','Imágenes <strong>%u</strong> a <strong>%u</strong> de <strong>%u</strong>');
		define('_MS_GS_SHOWINGSET','Albumes <strong>%u</strong> a <strong>%u</strong> de <strong>%u</strong>');

		//Albumes		
		define('_MS_GS_SETSEXIST','Albumes Existentes');
		define('_MS_GS_DATE','Creado:');
		define('_MS_GS_BY','Por:');
		define('_MS_GS_IMGS','Fotografías:');
		define('_MS_GS_VIEW','Ver');
		define('_MS_GS_BOOKMARK','Favoritos');
		define('_MS_GS_SETOF','Albumes de %s');
		define('_MS_GS_TAGS','Etiquetas');
		define('_MS_GS_PIC','Fotografías');

		//Imágenes
		define('_MS_GS_EXISTIMGS','Fotografías Existentes');
		define('_MS_GS_CREATED','Creada el <span>%s</span>');
		define('_MS_GS_COMMENTS','<span>%u</span> Comentarios');
		define('_MS_GS_QUICK','Vista Rápida');

		//Etiquetas
		define('_MS_GS_HITS','Hits:');
		define('_MS_GS_PICSTAG','Fotografías marcadas con la etiqueta "%s"');
		define('_MS_GS_PICSTAGUSR','Fotografías de %s en la etiqueta "%s"');
		define('_MS_GS_TAGSEX','Etiquetas Existentes');
		define('_MS_GS_TAGSOF','Etiquetas registradas de %s');

		//Errores
		define('_MS_GS_ERRUSER','Usuario no existente');
		define('_MS_GS_ERRTAG','Etiqueta no existente');

		break;
	
	case 'user':
	
		define('_MS_GS_PICSOF','Fotos de %s');
		define('_MS_GS_PICSOFIN','Fotos en "%s"');
		define('_MS_GS_BMARK','Favoritos');
		
		define('_MS_GS_SHOWING','Imágenes <strong>%u</strong> a <strong>%u</strong> de <strong>%u</strong>');
		define('_MS_GS_CREATED','Creada el <span>%s</span>');
		define('_MS_GS_COMMENTS','<span>%u</span> Comentarios');
		define('_MS_GS_QUICK','Vista Rápida');
		define('_MS_GS_PNTITLE','Fotos de %s');
		define('_MS_GS_PHOTOS','Fotos');
		define('_MS_GS_BROWSE','Explorar');
		define('_MS_GS_BELONG','Esta Foto también pertenece a:');
		define('_MS_GS_TAGS','Etiquetas');
		define('_MS_GS_LASTPIC','Estas en la última fotografía');
		define('_MS_GS_FIRSTPIC','Estas en la primer fotografía');
		
		define('_MS_GS_POSTCARDS','Postal');
		define('_MS_GS_BOOKMARK','Favoritos');
		define('_MS_GS_TOSET','Agregar al Album');
		define('_MS_GS_INSET','Fotografías en el Album');
		define('_MS_GS_NUMPICS','%u Fotos');
		define('_MS_GS_NUMVIEWS','%u Vistas');
		define('_MS_GS_UPDATED','Actualizado el %s');
		define('_MS_GS_GALFROM','Galería de %s');
		define('_MS_GS_USAYS','dice:');
		define('_MS_GS_POSTED','Publicado el %s.');
		define('_MS_GS_TCOMMENTS','Comentarios');
		define('_MS_GS_SEND','Enviar Comentario');
		define('_MS_GS_CONFDEL','¿Realmente deseas eliminar %s?');
		
		// ERRORES
		define('_MS_GS_ERRUSR','El usuario especificado no es válido.');
		define('_MS_GS_ERRIMG','El imágen especificada no es válida.');
		define('_MS_GS_ERRSET','El álbum especificado no es válido.');
		define('_MS_GS_ERRPRIVACY','Lo sentimos, la imagen especificada es privada.');
		define('_MS_GS_ERRNOTFRIEND','Lo sentimos, usted no es un amigo no puede visualizar la imagen.');
		define('_MS_GS_ERRSETPRIVACY','Lo sentimos, el album especificado es privado.');
		define('_MS_GS_ERRNOTFRIENDSET','Lo sentimos, usted no es un amigo no puede visualizar el album.');
			
		break;
		
	case 'search':
	
		define('_MS_GS_SHOWING','Resultados <strong>%u</strong> a <strong>%u</strong> de <strong>%u</strong>');
		define('_MS_GS_CREATED','Creada el <span>%s</span>');
		define('_MS_GS_BY','Por: <a href="%s">%s</a>');
		define('_MS_GS_IMGS','Fotografías:');
		define('_MS_GS_QUICK','Vista Rápida');
		define('_MS_GS_COMMENTS','<span>%u</span> Comentarios');
		define('_MS_GS_VIEMORE','Ver <a href="%s">más fotos</a> o visita su <a href="%s">perfil</a>.');
		define('_MS_GS_FOUND','Encontramos <strong>%u resultados</strong> para fotos que coinciden con "<span>%s</span>"');

		break;
		
	case 'panel':
	
		define('_MS_GS_SHOWING', 'Mostrando elementos <strong>%u</strong> a <strong>%u</strong> de <strong>%u</strong>.');
		define('_MS_GS_DBOK','Base de Datos Actualizada Correctamente');
		define('_MS_GS_EXISTS','Imágenes Existentes de %s');
		define('_MS_GS_EXISTSSET','Imágenes Existentes de %s del album "%s"');
		define('_MS_GS_ID','ID');
		define('_MS_GS_TITLE','Título');
		define('_MS_GS_IMAGE','Imagen');
		define('_MS_GS_USER','Usuario');
		define('_MS_GS_PUBLIC','Pública');
		define('_MS_GS_CREATED','Creada');
		define('_MS_GS_ADDSET','Agregar a Album');
		define('_MS_GS_CONFIRM','¿Realmente deseas eliminar la imagen? \n Los datos se eliminarán permanentemente');
		define('_MS_GS_CONFIRMS','¿Realmente deseas eliminar las imágenes? \n Los datos se eliminarán permanentemente');
		define('_MS_GS_SAVE','Guardar Cambios');
		define('_MS_GS_CHANGENAME','Cambiar Título');
		define('_MS_GS_ADDDESC','Haz click para agregar una descripción');
		define('_MS_GS_CHANGEDESC','Haz click para cambiar la descripción');
		

		define('_MS_GS_FRIENDS','Mis Amigos');
		define('_MS_GS_FAVOURITES','Favoritos');		
		define('_MS_GS_MSETS','Mis Albumes');
		define('_MS_GS_MPICS','Mis Fotos');

		//Formulario
		define('_MS_GS_NEW','Crear Imagen');
		define('_MS_GS_EDIT','Editar Imagen');
		define('_MS_GS_IMGACT','Imagen Actual');
		define('_MS_GS_DESC','Descripción');
		define('_MS_GS_FSETS','Albumes');
		define('_MS_GS_TAGS','Etiquetas');
		define('_MS_GS_DESCTAGS','Separe las etiquetas con un espacio.');
		define('_MS_GS_PRIVATE','Privada');
		define('_MS_GS_PRIVFRIEND','Privada(Usted y amigos)');

		//Formulario de albumes
		define('_MS_GS_ADDALBUM','Agregar Imagen a Album');

		//Albumes
		define('_MS_GS_CONFIRMSET','¿Realmente deseas eliminar el album? \nLos datos se eliminarán permanentemente.');
		define('_MS_GS_CONFIRMSETS','¿Realmente deseas eliminar los albumes seleccionados? \nLos datos se eliminarán permanentemente.');
	

		//Tabla de albumes
		define('_MS_GS_SETEXISTS','Albumes Existentes');
		define('_MS_GS_NAME','Título');
		define('_MS_GS_DATE','Fecha');
		define('_MS_GS_SETPUBLIC','Público');
		define('_MS_GS_SETPRIVACY','Privacidad');
		define('_MS_GS_PRIVATEME','Privada (Solo tu puedes verlas)');
		define('_MS_GS_PRIVATEF','Privada (Tu y tus amigos)');
		define('_MS_GS_PUBLICSET','Pública (Todos)');
		
		//Formulario de albumes
		define('_MS_GS_EDITSET','Editar Album');
		define('_MS_GS_NEWSET','Crear Album');

		//Amigos
		define('_MS_GS_UNAME','Nombre');
		define('_MS_GS_NEWFRIEND','Adicionar Amigo');
		define('_MS_GS_CONFIRMFRIEND','¿Realmente deseas eliminar el amigo especificado\nLos datos se eliminarán permanentemente.');
		define('_MS_GS_CONFIRMFRIENDS','¿Realmente deseas eliminar los amigos especificados\nLos datos se eliminarán permanentemente.');
		define('_MS_GS_EXISTFRIEND','Amigos Registrados');
		
		//Favoritos
		define('_MS_GS_CONFIRMBK','¿Realmente deseas eliminar la imagen?\nLos datos se eliminarán permanentemente.');		
		define('_MS_GS_CONFIRMBKS','¿Realmente deseas eliminar las imágenes seleccionadas?\nLos datos se eliminarán permanentemente.');		
		define('_MS_GS_EXISTBK','Imágenes de Favoritos Existentes');
		
		//Errores
		define('_MS_GS_ERRUSER','Usuario no registrado');
		define('_MS_GS_ERRIMAGE','Imagen no válida');
		define('_MS_GS_ERRIMAGEEXIST','Imagen Existente');
		define('_MS_GS_DBERROR','Error al realizar la operación');
		define('_MS_GS_DBERRORS','Errores al realizar operación<br />');
		define('_MS_GS_ERRIMGDELETE','Debes proporcionar al menos una imagen para eliminar');
		define('_MS_GS_ERRNOTVALID','Imagen %s no válida <br />');
		define('_MS_GS_ERRNOTEXIST','Imagen %s no existente <br />');
		define('_MS_GS_ERRDELETE','Imagen %s no eliminada <br />');
		define('_MS_GS_ERRSAVE','Imagen %s no actualizada <br />');
		define('_MS_GS_ERRIMGASSIGN','Debes proporcionar al menos una imagen para asignar');
		define('_MS_GS_ERRNOTASSIGN','Imagen %s no assignada');
		define('_MS_GS_ERROWNER','Lo sentimos, usted no es el dueño de la imagen');
		define('_MS_GS_ERRNOTOWNER','Imagen %s no asignada, no eres el dueño de la imagen');
		define('_MS_GS_ERRSETVALID','Album no válido');
		define('_MS_GS_ERRSETEXIST','Album no existente');

		//Errores de albumes
		define('_MS_GS_ERRNOTVALIDSET','Album %s no válido <br />');
		define('_MS_GS_ERRNOTEXISTSET','Album %s no existente <br />');
		define('_MS_GS_ERRDELETESET','Album %s no eliminado <br />');
		define('_MS_GS_ERRSETDELETE','Debes proporcionar al menos un album para eliminar');

		//Errores en amigos
		define('_MS_GS_ERRUSEREXIST','Usuario no existente');
		define('_MS_GS_ERRFRIENDDEL','Debes de proporcionar al menos un amigo para eliminar');
		define('_MS_GS_ERRNOTVALIDFRIEND','Amigo %s no válido<br />');
		define('_MS_GS_ERRNOTEXISTFRIEND','Amigo %s no existente<br />');
		define('_MS_GS_ERRDELFREIEND','Amigo %s no eliminado<br />');
		define('_MS_GS_FRIENDEXIST','Amigo ya registrado');

		//Errores en favoritos
		define('_MS_GS_ERRIMGVALID','Imagen no válida');
		define('_MS_GS_ERRIMGEXIST','Imagen no existente');
		define('_MS_GS_ERRPRIVACY','Imagen privada no puede ser agregada a favoritos');
		define('_MS_GS_ERRNOTFRIEND','Lo sentimos, imagen solo para amigos');
		define('_MS_GS_ERRIMGADD','Imagen ya registrada en favoritos');
		

		break;
	case 'submit':
	
		define('_MS_GS_UPLOADY','Carga tus fotos');
		define('_MS_GS_USED','Has usado <strong>%s</strong> de tus <strong>%s</strong> disponibles. Te restan <strong>%s</strong>.');
		define('_MS_GS_STEP1','Paso 1:');
		define('_MS_GS_STEP2','Paso 2:');
		define('_MS_GS_STEP3','Paso 3:');
		define('_MS_GS_STEP4','Paso 4:');
		define('_MS_GS_CHOOSE','Seleccionar los Archivos');
		define('_MS_GS_PRIVACY','Establecer Privacidad');
		define('_MS_GS_PRIVATEME','Privada <span class="gray">(Solo tu puedes verlas)</span>');
		define('_MS_GS_PRIVATEF','Privada <span class="gray">(Tu y tus amigos pueden verlas)</span>');
		define('_MS_GS_PUBLIC','Publica <span class="gray">(Todos pueden verlas)</span>');
		define('_MS_GS_TAGSESP','Especificar Etiquetas');
		define('_MS_GS_UPLOAD','Cargar los Archivos');
		define('_MS_GS_CLICKTOU','Click para Enviar');
		define('_MS_GS_MAXSIZE','El tamaño máximo permitido para cada archivo es de %s.');
		
		define('_MS_GS_DBOK','Base de datos actualizada correctamente');
		define('_MS_GS_ERRUSER','Los archivos del usuario no han sido creados');
				
		// ERRORES
		define('_MS_GS_ERRACCESS','Lo sentimos, no tienes permisos para enviar imágenes');
		define('_MS_GS_DBERRORS','Errores al realizar esta operación: <br />'); 
		define('_MS_GS_ERRSAVEIMG','Imagen %s no ha sido almacenda <br />');
		define('_MS_GS_QUOTAEX','Lo sentimos, has excedido tu cuota límite.');
		
		break;
	
	case 'postcards':
		
		define('_MS_GS_NEWPOST','Enviar Postal: %s');
		define('_MS_GS_NEWTITLE','Crear Postal Electrónica');
		define('_MS_GS_YEMAIL','Tu Email:');
		define('_MS_GS_YNAME','Tu Nombre:');
		define('_MS_GS_TEMAIL','Email del Destinatario:');
		define('_MS_GS_TNAME','Nombre del Destinatario:');
		define('_MS_GS_TITLE','Título de la Postal:');
		define('_MS_GS_MESSAGE','Mensaje para la Postal:');
		define('_MS_GS_CODE','Código de Seguridad:');
		define('_MS_GS_CODE_DESC','Escribe las letras y/o números tal y como aparecen en la imágen');
		define('_MS_GS_PREVIEW','Previsualizar');
		define('_MS_GS_SAYS','<strong>%s</strong> dice:');
		define('_MS_GS_PTITLE','Postal: %s');
		define('_MS_GS_SEE','Ver Imagen');
		define('_MS_GS_SEEUSER','Ver Imagenes del Usuario');
		define('_MS_GS_SUBJECT','%s, ¡Tienes una postal esperando!');
		define('_MS_GS_SENDOK','¡La postal ha sido enviada correctamente!');
		
		// ERRORES
		define('_MS_GS_ERRIMG','La fotografía especificada no es válida.');
		define('_MS_GS_ERRCODE','El código de seguridad no es válido.');
		define('_MS_GS_ERRSEND','No se ha podido enviar esta postal. Por favor vuelve a intentarlo.');
		define('_MS_GS_ERRUSR','Necesitas registrarte para poder enviar postales.');
		define('_MS_GS_SRVDISABLED','El servicio de postales electrónicas esta deshabilitado actualmente.');
		define('_MS_GS_ERRPOSTEXIST','Postal no existente');
	
		break;
	
}

?>
