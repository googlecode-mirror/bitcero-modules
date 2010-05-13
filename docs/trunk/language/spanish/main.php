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

define('_MS_EXMBB_INDEX','Índice de Foros');
define('_MS_EXMBB_SEARCH','Buscar');
define('_MS_EXMBB_SEARCHQ','Buscar:');
define('_MS_EXMBB_SESSINVALID','Lo sentimos, tu identificador de sesión ya no es válido');
define('_MS_EXMBB_JUMPO','Cambiar Foro:');
define('_MS_EXMBB_GO','Ir!');
define('_MS_EXMBB_PAGES','Páginas:');
define('_MS_EXMBB_ANNOUNCEMENT','Anuncio');
define('_MS_EXMBB_NEWTOPIC','Nuevo Tema');

// Fechas
define('_MS_EXMBB_TODAY', "Hoy %s");
define('_MS_EXMBB_YESTERDAY', "Ayer %s");

// Correo
define('_AS_EXMBB_ADMSUBJECT','Nuevo Tema en %s');
define('_AS_EXMBB_ADMSUBJECTPOST','Mensaje Editado en %s');


switch(BB_LOCATION){
    case 'index':
        
        define('_MS_EXMBB_FORUM','Foro');
        define('_MS_EXMBB_TOPICS','Temas');
        define('_MS_EXMBB_POSTS','Mensajes');
        define('_MS_EXMBB_LASTPOST','Último Mensaje');
        define('_MS_EXMBB_LASTUSER','Último usuario registrado:');
        define('_MS_EXMBB_REGNUM','Usuarios registrados conectados:');
        define('_MS_EXMBB_ANNUM','Usuarios anónimos conectados:');
        define('_MS_EXMBB_TOTALUSERS','Usuarios registrados:');
        define('_MS_EXMBB_TOTALTOPICS','Total de temas:');
        define('_MS_EXMBB_TOTALPOSTS','Total de Mensajes:');
        define('_MS_EXMBB_BY','por %s');
        
        break;  
       
    case 'forum':
        
        define('_MS_EXMBB_TOPIC','Tema');
        define('_MS_EXMBB_REPLIES','Respuestas');
        define('_MS_EXMBB_VIEWS','Vistas');
        define('_MS_EXMBB_LASTPOST','Último Mensaje');
        define('_MS_EXMBB_BY','por %s');
        define('_MS_EXMBB_NONEW','Sin Novedades');
        define('_MS_EXMBB_WITHNEW','Con Novedades');
        define('_MS_EXMBB_HOTNONEW','Popular sin Novedades');
        define('_MS_EXMBB_HOTNEW','Popular con Novedades');
        define('_MS_EXMBB_STICKY','Fijado:');
        define('_MS_EXMBB_NEWPOSTS','Nuevos Mensajes');
        define('_MS_EXMBB_MODERATE','Moderar Foro');
        define('_MS_EXMBB_CLOSED','Cerrado:');
        
        // ERRORES
        define('_MS_EXMBB_NOID','Especifica un foro');
        define('_MS_EXMBB_NOEXISTS','No existe el foro especificado');
        define('_MS_EXMBB_NOALLOWED','Lo sentimos, no tienes autorización para ver este foro');
        
        break;
    
    case 'posts':
    	
    	// Formulario
    	define('_MS_EXMBB_FTOPICTITLE','Crear Nuevo Tema');
    	define('_MS_EXMBB_FEDITTITLE','Editar Mensaje');
    	define('_MS_EXMBB_FREPLYTITLE','Responder');
    	define('_MS_EXMBB_FMSGTIP','Escribe tu mensaje y envíalo');
    	define('_MS_EXMBB_SUBJECT','Asunto del Mensaje:');
    	define('_MS_EXMBB_NAME','Tu Nombre:');
    	define('_MS_EXMBB_EMAIL','Tu E-mail:');
    	define('_MS_EXMBB_MSG','Mensaje');
    	define('_MS_EXMBB_BBCODE','Códigos BB');
    	define('_MS_EXMBB_SMILES','Emoticons');
    	define('_MS_EXMBB_HTML','HTML');
    	define('_MS_EXMBB_BR','Saltos de Línea');
    	define('_MS_EXMBB_IMG','Imágenes');
    	define('_MS_EXMBB_ATTACH','Adjuntar Archivo:');
    	define('_MS_EXMBB_EXTS','Extensiones Permitidas: %s');
    	define('_MS_EXMBB_TOPICREV','Resumen de Mensajes (Nuevos Primero)');
    	define('_MS_EXMBB_EXATTACH','Archivos Adjuntos');
    	define('_MS_EXMBB_EXATTACHTIP','Puedes cargar nuevos archivos a este mensaje. Tienes un límite 
    					de <strong>%s</strong> adjuntos por mensaje.');
    	define('_MS_EXMBB_CURATTACH','Archivos adjuntos actualmente');
    	define('_MS_EXMBB_UPLOAD','Subir Archivo');
    	define('_MS_EXMBB_SAVECHANGES','Guardar Cambios');
    	define('_MS_EXMBB_DELFILE','Eliminar Archivo(s)');
    	define('_MS_EXMBB_STICKYTOPIC','Fijar Tema');
    	
    	// Mensajes
    	define('_MS_EXMBB_POSTOK','¡Tu mensaje se ha enviado correctamente!');
    	define('_MS_EXMBB_POSTOKEDIT','¡Tu mensaje ha sido editado correctamente!');
    	define('_MS_EXMBB_POSTOKERR','Se creo el mensaje pero sucediron errores durant eel proceso:');
    	define('_MS_EXMBB_ATTACHOK','¡Archivo adjuntado correctamente!');
    	define('_MS_EXMBB_DELETEOK','¡Archivos eliminados Correctamente!');
    	
    	// ERRORES
    	define('_MS_EXMB_ERRFORUMNEW','Debes especificar un foro para poder crear un tema');
    	define('_MS_EXMBB_TOPICNOEXISTS','No existe el tema especificado');
    	define('_MS_EXMBB_FORUMNOEXISTS','No existe el foro especificado');
    	define('_MS_EXMBB_NOPERM','Lo sentimos, no tienes permiso para realizar esta acción');
    	define('_MS_EXMBB_ERRPOST','No se pudo crear el mensaje, por favor vuelva a intentarlo.');
    	define('_MS_EXMBB_ERRPOSTEDIT','No se pudo modificar el mensaje, por favor vuelva a intentarlo.');
    	define('_MS_EXMBB_NOMSGID','Por favor especifica el mensaje a editar');
    	define('_MS_EXMBB_POSTNOEXISTS','No existe el mensaje especificado');
    	define('_MS_EXMBB_ERRATLIMIT','Has alacanzado el número máximo de archivos adjuntos para este mensaje');
    	define('_MS_EXMBB_ERRSAVEATTACH','No se pudo almacenar el archivo.');
    	define('_MS_EXMBB_ERRSELECTFILES','¡No has seleccionado archivos para eliminar!');
    	define('_MS_EXMBB_ERRHAPPEN','Ocurrierón algunos errores al realizar esta operación:');
    	
    	break;
    
    case 'topics':
    	
    	define('_MS_EXMBB_REPLY','Responder');
    	define('_MS_EXMBB_REPORT','Reportar');
    	define('_MS_EXMBB_QUOTE','Acotación');
    	define('_MS_EXMBB_REGISTERED','Registrado: %s');
    	define('_MS_EXMBB_UPOSTS','Envíos: %u');
    	define('_MS_EXMBB_UIP','IP: %s');
    	define('_MS_EXMBB_UONLINE','¡En Línea!');
    	define('_MS_EXMBB_UOFFLINE','Desconectado');
    	define('_MS_EXMBB_ATTACHMENTS','Archivos Adjuntos');
    	
    	define('_MS_EXMBB_MOVE','Mover Tema');
    	define('_MS_EXMBB_OPEN','Abrir Tema');
    	define('_MS_EXMBB_CLOSE','Cerrar Tema');
    	define('_MS_EXMBB_STICKY','Fijar Tema');
    	define('_MS_EXMBB_UNSTICKY','No Fijar Tema');
    	define('_MS_EXMBB_TOPICCLOSED','Tema Cerrado');
	define('_MS_EXMBB_APPROVED','Aprobado');
	define('_MS_EXMBB_NOAPPROVED','No Aprobado');
    	define('_MS_EXMBB_TOPICNOAPPROVED','Tema No Aprobado');
	define('_MS_EXMBB_EDIT','Texto Editado');
	define('_MS_EXMBB_APP','Aprobar');
	define('_MS_EXMBB_NOAPP','No Aprobar');


    	// ERRORES
    	define('_MS_EXMBB_ERRID','Por favor especifica un tema válido');
    	define('_MS_EXMBB_ERREXISTS','No existe el tema especificado.');
    	define('_MS_EXMBB_NOALLOWED','Lo sentimos, no tienes autorización para ver este foro');
    	
    	break;
    
    case 'delete':
    	
    	// Mensajes
    	define('_MS_EXMBB_DELTITLE','Eliminar Mensaje');
    	define('_MS_EXMBB_DELCONF','¿Realmente deseas eliminar este mensaje?');
    	define('_MS_EXMBB_DELWARN','<strong>Advertencia:</strong> Este es el primer mensaje del tema. Al eliminarlo todo el tema y sus mensajes serán eliminados también.');
    	define('_MS_EXMBB_DELOK','¡Mensaje eliminado correctamente!');
    	define('_MS_EXMBB_DELOKTOPIC','¡Tema eliminado correctamente!');
    	
    	// ERRORES
    	define('_MS_EXMBB_NOMSGID','Por favor especifica el mensaje a editar');
    	define('_MS_EXMBB_POSTNOEXISTS','No existe el mensaje especificado');
    	define('_MS_EXMBB_NOPERM','Lo sentimos, no tienes permiso para realizar esta acción');
    	define('_MS_EXMBB_NODELTOPIC','No se pudo eliminar el tema.');
    	define('_MS_EXMBB_NODEL','No se pudo eliminar el mensaje.');
    	
    	break;
    
    case 'moderate':
    	
    	define('_MX_EXMBB_MODERATING','Moderando');
    	define('_MS_EXMBB_TOPIC','Tema');
        define('_MS_EXMBB_REPLIES','Respuestas');
        define('_MS_EXMBB_VIEWS','Vistas');
        define('_MS_EXMBB_LASTPOST','Último Mensaje');
        define('_MS_EXMBB_STICKY','Fijado:');
        define('_MS_EXMBB_BY','por %s');
        define('_MS_EXMBB_MOVE','Mover');
        define('_MS_EXMBB_MOVENOW','Mover Temas');
        define('_MS_EXMBB_OPEN','Abrir');
        define('_MS_EXMBB_CLOSE','Cerrar');
        define('_MS_EXMBB_DOSTICKY','Fijar');
	define('_MS_EXMBB_APPROVED','Aprobado');
        define('_MS_EXMBB_DOUNSTICKY','No Fijar');
	define('_MS_EXMBB_APP','Aprobar');
	define('_MS_EXMBB_NOAPP','No Aprobar');
        define('_MS_EXMBB_DELNOW','¡Eliminar Temas!');
        define('_MS_EXMBB_DELTITLE','Eliminar Temas del Foro');
        define('_MS_EXMBB_DELCONF','¿Realmente deseas eliminar los temas seleccionados?');
        
        // Mover Temas
        define('_MS_EXMBB_MOVETITLE','Mover Temas');
        define('_MS_EXMBB_MOVETARGET','Selecciona el foro al que deseas mover los temas seleccionados:');
        define('_MS_EXMBB_MOVEOK','Los temas han sido reubicados.');
        define('_MS_EXMBB_ACTIONOK','Operación completada.');
    	
    	// ERRORES
    	define('_MS_EXMBB_ERRID','Especifica el foro que deseas moderar');
    	define('_MS_EXMBB_ERREXISTS','No existe el foro especificado');
    	define('_MS_EXMBB_NOPERM','Lo sentimos, no tienes permiso para realizar esta acción');
    	define('_MS_EXMBB_SELTOPIC','Selecciona al menos un tema para moderar');
    	define('_MS_EXMBB_NOSELMOVEFOR','Selecciona el foro al que deseas mover los temas');
	define('_MS_EXMBB_NOTID','Mensaje no válido');
	define('_MS_EXMBB_NOTEXIST','Mensaje no existente');
    	
    	break;
    case 'report':
	define('_MS_EXMBB_REPORT','Reportar');
	define('_MS_EXMBB_ADDREPORT','Escribe motivo de reporte');


	define('_MS_EXMBB_POSTNOTVALID','Mensaje no válido');
	define('_MS_EXMBB_POSTNOTEXIST','Mensaje no existente');
	define('_MS_EXMBB_SAVEREPORT','Reporte almacenado correctamente');
	define('_MS_EXMBB_SAVENOTREPORT','Reporte no almacenado');
	define('_MS_EXMBB_REPORTPOST','Reportar envío');

 	
	break;
    case 'search':
	define('_MS_EXMBB_NOTWORD','Debes proporcionar alguna palabra para la búsqueda');

	define('_MS_EXMBB_ALLTOPICS','Todos los temas');
	define('_MS_EXMBB_RECENTTOPICS','Temas recientes');
	define('_MS_EXMBB_ANUNSWERED','Temas sin respuesta');
	define('_MS_EXMBB_ALLWORDS','Todas las palabras');
	define('_MS_EXMBB_ANYWORDS','Cualquier palabra');
	define('_MS_EXMBB_EXACTPHRASE','Frase Exacta');
	define('_MS_EXMBB_TOPIC','Tema');
        define('_MS_EXMBB_REPLIES','Respuestas');
        define('_MS_EXMBB_VIEWS','Vistas');
	define('_MS_EXMBB_LAST','Último Envío');
      	define('_MS_EXMBB_NEWTOPIC','Nuevo Tema');
        define('_MS_EXMBB_BY','por %s');
        define('_MS_EXMBB_STICKY','Fijado:');
        define('_MS_EXMBB_NEWPOSTS','Nuevos Mensajes');
        define('_MS_EXMBB_CLOSED','Cerrado:');
	define('_MS_EXMBB_FORUM','Foro');
	define('_MS_EXMBB_DATE','Fecha de envío');
	
	break;
    case 'files':
	define('_MS_EXMBB_NOTID','Archivo no válido');
	define('_MS_EXMBB_NOEXISTS','Archivo no existente');
	break;

}
?>
