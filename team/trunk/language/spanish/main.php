<?php
// $Id$
// --------------------------------------------------------
// The Coach
// Manejo de Integrantes de Equipos Deportivos
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

define('_MS_TC_COMMENT','Enviar Comentario');

switch(TC_LOCATION){
	case 'index':
		
		define('_MS_TC_TITLE','Nuestros Equipos');
		define('_MS_TC_CATTITLE','Categorías');
		define('_MS_TC_TEAMSTITLE','Equipos');
		
		break;
	
	case 'teams':
		
		define('_MS_TC_PLAYERS','Integrantes');
		define('_MS_TC_INFO','Información del Equipo');
		define('_MS_TC_COACHS','Entrenadores');
		define('_MS_TC_PTITLE','Equipo "%s"');
		
		// ERRORES
		define('_MS_TC_ERRID','Especifica un equipo para ver su información.');
		define('_MS_TC_ERRNOEXISTIS','No existe el equipo especificado.');
		
		break;
	
	case 'players':
		
		define('_MS_TC_PTITLE','%s: Jugador #%s');
		define('_MS_TC_DATA','Ficha del Jugador');
		define('_MS_TC_NAME','Nombre:');
		define('_MS_TC_NUMBER','Número:');
		define('_MS_TC_TEAM','Equipo:');
		define('_MS_TC_AGE','Edad:');
		define('_MS_TC_DATE','Alta:');
		define('_MS_TC_BIO','Biografía:');
		define('_MS_TC_LINK','Enlace:');
		define('_MS_TC_PLAYERS','Otros Jugadores de %s');
		
		// ERRORES
		define('_MS_TC_ERRID','Especifica un integrante para ver su información.');
		define('_MS_TC_ERRNOEXISTIS','No existe el integrante especificado.');
		
		break;
	
	case 'coachs':
		
		define('_MS_TC_PTITLE','Entrenador "%s"');
		define('_MS_TC_DATA','Ficha del Entrenador');
		define('_MS_TC_NAME','Nombre:');
		define('_MS_TC_TEAM','Equipos:');
		define('_MS_TC_DATE','Alta:');
		define('_MS_TC_BIO','Biografía:');
		
		break;
	
	case 'categories':
		
		define('_MS_TC_TEAMIN','Equipos Registrados en esta Categoría');
		define('_MS_TC_PTITLE','Categoría "%s"');
		
		// ERRORES
		define('_MS_TC_ERRID','Especifica una categoría.');
		define('_MS_TC_ERRNOEXISTIS','No existe la categoría especificada.');
		
		break;
	
	case 'comments':
		
		define('_MS_TC_PTITLE','Envío de Comentarios');
		
		define('_MS_TC_FTITLE','Envíar Comentarios');
		define('_MS_TC_FNAME','Tu Nombre:');
		define('_MS_TC_FEMAIL','Tu Email:');
		define('_MS_TC_FCOMMENT','Comentario:');
		define('_MS_TC_FCODE','Escriba el Código de Verificación:');
		define('_MS_TC_COMFROM','Comentarios desde %s');
		define('_MS_TC_COMTHX','Gracias por tus comentarios.');
		
		// ERRORES
		define('_MS_TC_ERRID','El ID de sessión ha caducado. Por favor vuelve a intentarlo.');
		define('_MS_TC_ERRFIELDS','Por favor completa todos los campos.');
		
		break;
		
}

?>