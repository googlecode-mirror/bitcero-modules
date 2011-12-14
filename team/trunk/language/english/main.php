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

define('_MS_TC_COMMENT','Submit Comment');

switch(TC_LOCATION){
	case 'index':

        define('_MS_TC_TITLE', 'Our Teams');
        define('_MS_TC_CATTITLE', 'Categories');
        define('_MS_TC_TEAMSTITLE', 'Teams');
		
		break;
	
	case 'teams':

        define('_MS_TC_PLAYERS', 'Members');
        define('_MS_TC_INFO', 'Team Information');
        define('_MS_TC_COACHS', 'Coaches');
        define('_MS_TC_PTITLE', 'Team "%s"');
		
		// ERRORES
		define('_MS_TC_ERRID','Select a team to view its information.');
		define('_MS_TC_ERRNOEXISTIS','There is no selected team.');
		
		break;
	
	case 'players':
		
		define('_MS_TC_PTITLE','%s: Player #%s');
		define('_MS_TC_DATA','Player Info');
		define('_MS_TC_NAME','Name:');
		define('_MS_TC_NUMBER','Number:');
		define('_MS_TC_TEAM','Team:');
		define('_MS_TC_AGE','Age:');
		define('_MS_TC_DATE','Height:');
		define('_MS_TC_BIO','Bio:');
		define('_MS_TC_LINK','Link:');
		define('_MS_TC_PLAYERS','Other players from %s');
		
		// ERRORES
		define('_MS_TC_ERRID','Select a member to view their information.');
		define('_MS_TC_ERRNOEXISTIS','Selected member does not exist.');
		
		break;
	
	case 'coachs':
		
		define('_MS_TC_PTITLE','Coach "%s"');
		define('_MS_TC_DATA','Coach Info');
		define('_MS_TC_NAME','Name:');
		define('_MS_TC_TEAM','Teams:');
		define('_MS_TC_DATE','Height:');
		define('_MS_TC_BIO','Bio:');
		
		break;
	
	case 'categories':
		
		define('_MS_TC_TEAMIN','Teams in this category');
		define('_MS_TC_PTITLE','Category "%s"');
		
		// ERRORES
		define('_MS_TC_ERRID','Select a category.');
		define('_MS_TC_ERRNOEXISTIS','Selected category does not exist.');
		
		break;
	
	case 'comments':
		
		define('_MS_TC_PTITLE','Comments Submission');
		
		define('_MS_TC_FTITLE','Submit Comments');
		define('_MS_TC_FNAME','Your name:');
		define('_MS_TC_FEMAIL','Your email:');
		define('_MS_TC_FCOMMENT','Comment:');
		define('_MS_TC_FCODE','Enter the security code:');
		define('_MS_TC_COMFROM','Comments on %s');
		define('_MS_TC_COMTHX','Thanks for your feedback.');
		
		// ERRORES
		define('_MS_TC_ERRID','The Session ID has expired. Please try again.');
		define('_MS_TC_ERRFIELDS','Please complete all fields.');
		
		break;
		
}

?>