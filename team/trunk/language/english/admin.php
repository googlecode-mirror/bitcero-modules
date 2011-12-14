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
define('_AS_TC_DBOK','Database updated successfully');

// ERRORES
define('_AS_TC_DBFAIL','Unable to successfully perform this operation');
define('_AS_TC_ERRSESSID','The Session ID is no longer valid');

switch(TC_LOCATION){
	
	case 'categories':
		
		define('_AS_TC_CATSLOC','Manage Categories');
		define('_AS_TC_NEW','Create Category');
		define('_AS_TC_CATSM','Categories');
		define('_AS_TC_CATSNEW','Move category');
		
		// Tabla
		define('_AS_TC_EXISTINGCATS','Existing categories');
		define('_AS_TC_NAME','Name');
		define('_AS_TC_TEAMS','Equipment');
		
		// Formulario
		define('_AS_TC_NEWTITLE','Create Category');
		define('_AS_TC_EDITTITLE','Change Category');
		define('_AS_TC_DESC','Description');
		define('_AS_TC_SHORTNAME','Short Name');
		
		define('_AS_TC_CONFIRMDEL','Do you really want to delete this category?');
		
		// ERRORES
		define('_AS_TC_ERRID','The category ID is invalid');
		define('_AS_TC_NOEXISTS','The category specified does not exist');
		define('_AS_TC_ERREXISTS','There is already a category with the same name');
		
		break;
	
	case 'coachs':
		
		define('_AS_TC_COACHSM','Coach');
		define('_AS_TC_NEW','New Manager');
		define('_AS_TC_EDIT','Editar Coach');
		define('_AS_TC_COACHSLOC','Edit Coach');
		define('_AS_TC_EXISTING','Existing Coaches');
		define('_AS_TC_NAME','Name');
		define('_AS_TC_TEAMS','Team');
		define('_AS_TC_IMG','Image');
		
		// Formulario
		define('_AS_TC_FNAME','Name');
		define('_AS_TC_FROLE','Position');
		define('_AS_TC_FPIC','Photo');
		define('_AS_TC_FBIO','Information');
		define('_AS_TC_FSHORTNAME','Short name');
		define('_AS_TC_FCURRENTPIC','Current Photo');
		define('_AS_TC_TEAMSTITLE','Current Team');
		define('_AS_TC_TEAMSSEL','Select Team ');
		
		define('_AS_TC_CONFDEL','Do you really want to delete this coach?');
		
		// ERRORES
		define('_AS_TC_ERRID','The selected coach is not valid');
		define('_AS_TC_ERRNOEXISTS','No coach specified');
		define('_AS_TC_ERREXISTS','There is already a coach with the same name');
		define('_AS_TC_NOSEL','You must specify at least one coach to remove');
		
		break;
	
	case 'teams':
		
		define('_AS_TC_TEAMSLOC','Team Management');
		define('_AS_TC_TEAMSM','Teams');
		define('_AS_TC_NEW','Create Team');
		define('_AS_TC_EXISTING','Existing Teams');
		define('_AS_TC_IMAGE','Logo');
		define('_AS_TC_NAME','Name');
		define('_AS_TC_CATEGO','Category');
		define('_AS_TC_COACHS','Coaches');
		define('_AS_TC_DATE','Level');
		define('_AS_TC_PLAYERS','Members');
		
		// Formulario
		define('_AS_TC_FNEW','Create Team');
		define('_AS_TC_FEDIT','Edit Team');
		define('_AS_TC_FCAT','Category');
		define('_AS_TC_FNAME','Name');
		define('_AS_TC_FSHORTNAME','Short Name');
		define('_AS_TC_FIMAGE','Team Photo');
		define('_AS_TC_FCURIMAGE','Current Photo');
		define('_AS_TC_FINFO','Team Information');
		define('_AS_TC_FCOACHS','Assign Coaches');
		define('_AS_TC_FCOACHSSEL','Select Coaches');
		
		define('_AS_TC_CONFM','Do you really want to delete the selected teams?');
		define('_AS_TC_CONFDEL','Do you really want to delete this team?');
		
		// ERRORES
		define('_AS_TC_ERRID','The team selected is not valid');
		define('_AS_TC_ERRNOEXISTS','The selected team does not exist');
		define('_AS_TC_ERREXISTS','There is a team with the same name');
		define('_AS_TC_NOSEL','You must specify at least one team to delete');
		
		break;
	
	case 'players':
		
		define('_AS_TC_MPLAYS','Players');
		define('_AS_TC_NEW','Create a player');
		define('_AS_TC_PLAYLOC','Player Management');
		define('_AS_TC_EXISTING','Existing players in %s');
		define('_AS_TC_EXISTINGSEL','Select a Team');
		define('_AS_TC_TEAM','Team:');
		define('_AS_TC_SELECT','Select...');
		define('_AS_TC_COACHS','Coaches:');
		define('_AS_TC_PIC','Photo');
		define('_AS_TC_NAME','Name');
		define('_AS_TC_NUMBER','Number');
		define('_AS_TC_AGE','Age');
		define('_AS_TC_DATE','Height');
		
		define('_AS_TC_CONFDEL','Do you really want to delete the selected player?');
		define('_AS_TC_CONFDELS','Do you really want to delete the selected players?');
		
		// Formulario
		define('_AS_TC_FNEWTITLE','Add Player');
		define('_AS_TC_FEDITTITLE','Edit Player');
		define('_AS_TC_FTEAM','Team');
		define('_AS_TC_FNAME','Name');
		define('_AS_TC_FSHORTNAME','Short Name');
		define('_AS_TC_FBIRTH','Birthday');
		define('_AS_TC_FNUMBER','Player Number');
		define('_AS_TC_FPIC','Photo');
		define('_AS_TC_FCURPIC','Current Photo');
		define('_AS_TC_FBIO','Information');
		
		// ERRORES
		define('_AS_TC_ERRIDTEAM','First, select a team.');
		define('_AS_TC_ERRID','The player is invalid');
		define('_AS_TC_ERRNOEXISTS','The specified player does not exist');
		define('_AS_TC_ERRTEAMNOEXISTS','The specified team does not exist');
		define('_AS_TC_ERREXISTS','There is already a player with the same name');
		
		break;
	
}
?>
