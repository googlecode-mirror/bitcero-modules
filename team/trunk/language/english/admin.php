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

define('_AS_TC_ID','ID');
define('_AS_TC_DBOK','Base de Datos actualizada correctamente');

// ERRORES
define('_AS_TC_DBFAIL','No se pudo realizar correctamente esta operación');
define('_AS_TC_ERRSESSID','El Identificador de Sesión ya no es válido');

switch(TC_LOCATION){
	
	case 'categories':
		
		define('_AS_TC_CATSLOC','Administración de Categorías');
		define('_AS_TC_NEW','Crear Categoría');
		define('_AS_TC_CATSM','Categorías');
		define('_AS_TC_CATSNEW','Mueva Categoría');
		
		// Tabla
		define('_AS_TC_EXISTINGCATS','Categorías Existentes');
		define('_AS_TC_NAME','Nombre');
		define('_AS_TC_TEAMS','Equipos');
		
		// Formulario
		define('_AS_TC_NEWTITLE','Crear Categoría');
		define('_AS_TC_EDITTITLE','Modificar Categoría');
		define('_AS_TC_DESC','Descripción');
		define('_AS_TC_SHORTNAME','Nombre Corto');
		
		define('_AS_TC_CONFIRMDEL','¿Realmente deseas eliminar esta categoría?');
		
		// ERRORES
		define('_AS_TC_ERRID','El ID de categoría especificado no es válido');
		define('_AS_TC_NOEXISTS','No existe la categoría especificada');
		define('_AS_TC_ERREXISTS','Ya existe una categoría con el mismo nombre');
		
		break;
	
	case 'coachs':
		
		define('_AS_TC_COACHSM','Entrenadores');
		define('_AS_TC_NEW','Nuevo Entrenador');
		define('_AS_TC_EDIT','Editar Entrenador');
		define('_AS_TC_COACHSLOC','Manejo de Entrenadores');
		define('_AS_TC_EXISTING','Entrenadores Existentes');
		define('_AS_TC_NAME','Nombre');
		define('_AS_TC_TEAMS','Equipos');
		define('_AS_TC_IMG','Imágen');
		
		// Formulario
		define('_AS_TC_FNAME','Nombre');
		define('_AS_TC_FROLE','Puesto');
		define('_AS_TC_FPIC','Fotografía');
		define('_AS_TC_FBIO','Información');
		define('_AS_TC_FSHORTNAME','Nombre Corto');
		define('_AS_TC_FCURRENTPIC','Fotografía Actual');
		define('_AS_TC_TEAMSTITLE','Equipos Existentes');
		define('_AS_TC_TEAMSSEL','Seleccione los Equipos para este Entrenador');
		
		define('_AS_TC_CONFDEL','¿Realmente deseas eliminar este Entrenador?');
		
		// ERRORES
		define('_AS_TC_ERRID','El Entrenador seleccionado no es válido');
		define('_AS_TC_ERRNOEXISTS','No existe el entrenador especificado');
		define('_AS_TC_ERREXISTS','Ya existe un entrenador con el mismo nombre');
		define('_AS_TC_NOSEL','Debes especificar almenos un Entrenador para eliminar');
		
		break;
	
	case 'teams':
		
		define('_AS_TC_TEAMSLOC','Manejo de Equipos');
		define('_AS_TC_TEAMSM','Equipos');
		define('_AS_TC_NEW','Crear Equipo');
		define('_AS_TC_EXISTING','Equipos Existentes');
		define('_AS_TC_IMAGE','Imágen');
		define('_AS_TC_NAME','Nombre');
		define('_AS_TC_CATEGO','Categoría');
		define('_AS_TC_COACHS','Entrenadores');
		define('_AS_TC_DATE','Alta');
		define('_AS_TC_PLAYERS','Integrantes');
		
		// Formulario
		define('_AS_TC_FNEW','Crear Equipo');
		define('_AS_TC_FEDIT','Editar Equipo');
		define('_AS_TC_FCAT','Categoría');
		define('_AS_TC_FNAME','Nombre');
		define('_AS_TC_FSHORTNAME','Nombre Corto');
		define('_AS_TC_FIMAGE','Imágen del Equipo');
		define('_AS_TC_FCURIMAGE','Imágen Actual');
		define('_AS_TC_FINFO','Información del Equipo');
		define('_AS_TC_FCOACHS','Asignar Entrenadores');
		define('_AS_TC_FCOACHSSEL','Seleccione los entrenadores');
		
		define('_AS_TC_CONFM','¿Realmente deseas eliminar los equipos seleccionados?');
		define('_AS_TC_CONFDEL','¿Realmente deseas eliminar este Equipo?');
		
		// ERRORES
		define('_AS_TC_ERRID','El Equipo seleccionado no es válido');
		define('_AS_TC_ERRNOEXISTS','No existe el Equipo especificado');
		define('_AS_TC_ERREXISTS','Ya existe un equipo con el mismo nombre');
		define('_AS_TC_NOSEL','Debes especificar almenos un Equipo para eliminar');
		
		break;
	
	case 'players':
		
		define('_AS_TC_MPLAYS','Jugadores');
		define('_AS_TC_NEW','Crear Jugador');
		define('_AS_TC_PLAYLOC','Administración de Jugadores');
		define('_AS_TC_EXISTING','Jugadores Existentes en %s');
		define('_AS_TC_EXISTINGSEL','Selecciona un Equipo');
		define('_AS_TC_TEAM','Equipo:');
		define('_AS_TC_SELECT','Seleccionar...');
		define('_AS_TC_COACHS','Entrenadores:');
		define('_AS_TC_PIC','Foto');
		define('_AS_TC_NAME','Nombre');
		define('_AS_TC_NUMBER','Número');
		define('_AS_TC_AGE','Edad');
		define('_AS_TC_DATE','Alta');
		
		define('_AS_TC_CONFDEL','¿Realmente deseas eliminar el jugador seleccionado?');
		define('_AS_TC_CONFDELS','¿Realmente deseas eliminar los jugadores seleccionados?');
		
		// Formulario
		define('_AS_TC_FNEWTITLE','Nuevo Jugador');
		define('_AS_TC_FEDITTITLE','Editar Jugador');
		define('_AS_TC_FTEAM','Equipo');
		define('_AS_TC_FNAME','Nombre');
		define('_AS_TC_FSHORTNAME','Nombre Corto');
		define('_AS_TC_FBIRTH','Fecha de Nacimiento');
		define('_AS_TC_FNUMBER','Número del Jugador');
		define('_AS_TC_FPIC','Fotografía');
		define('_AS_TC_FCURPIC','Fotografía Actual');
		define('_AS_TC_FBIO','Información');
		
		// ERRORES
		define('_AS_TC_ERRIDTEAM','Primero seleccione un equipo.');
		define('_AS_TC_ERRID','El jugador especificado no es válido');
		define('_AS_TC_ERRNOEXISTS','El jugador especificado no existe');
		define('_AS_TC_ERRTEAMNOEXISTS','El equipo especificado no existe');
		define('_AS_TC_ERREXISTS','Ya existe un jugador con el mismo nombre');
		
		break;
	
}
?>
