<?php
// $Id$
// --------------------------------------------------------
// Quick Pages
// Módulo para la publicación de páginas individuales
// CopyRight © 2007 - 2008. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.exmsystem.net
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
// @copyright: 2007 - 2008 Red México
// @author: BitC3R0

/**
 * Cadenas generales de la secci?n administrativa
 */
define('_AS_QP_HOME','Module Status');
define('_AS_QP_CATEGOS','Categories');
define('_AS_QP_PAGES','Pages');
define('_AS_QP_GOTOMODULE','View Module');
define('_AS_QP_DBOK','Database updated successfully');
define('_AS_QP_DBERROR','Errors happened executing this action');

define('_AS_QP_ID','ID');
define('_AS_QP_NAME','Name');

# Errores Generales
define('_AS_QP_ERRHTACCESS','The file <strong>"%s"</strong> cannot be modified by server and the changes on the manage links method requires the modification of it.
		Please, modify the writing permissions or edit the file manually.');
define('_AS_QP_ERRTOKEN','Wrong Session Id');

switch (QP_LOCATION){
	case 'index':
		
		define('_AS_QP_DETAILS','See Details');
		define('_AS_QP_CATEGOSNUM','%s Categories');
		define('_AS_QP_PAGESNUM','%s Pages');
		define('_AS_QP_PUBLICNUM','%s Published');
		define('_AS_QP_PUBLICNUM_ALT','%s Published Pages');
		define('_AS_QP_PRIVATENUM','%s Private');
		define('_AS_QP_PRIVATENUM_ALT','%s Private Pages');
		define('_AS_QP_VISIT','Visit Web Site');
		
		define('_AS_QP_VERSIONINFO','QuickPages Information');
		define('_AS_QP_MOREVPAGES','Most read pages');
		
		break;
	
	case 'categos':
		
		define('_AS_QP_LIST','Existing Categories');
		define('_AS_QP_CATLIST','Categories');
		define('_AS_QP_NEWCAT','New Category');
		define('_AS_QP_CATPAGES','Pages');
		define('_AS_QP_NEWTITLE','New Category');
		define('_AS_QP_EDITTITLE','Editing Category');
		define('_AS_QP_DESC','Description');
		define('_AS_QP_CATPARENT','Parent Category');
		
		define('_AS_QP_CONFIRMDEL','<strong>?Do you really wish to delete this category?</strong>');
		define('_AS_QP_DELETEDESC','Also this action will delete all pages in the selected category.');
		# ERRORES
		
		define('_AS_QP_ERRID','Specify a valid category');
		define('_AS_QP_ERRNAME','You must provide a name for this category');
		define('_AS_QP_ERREXISTS','Already exists a category with same name');
		
		break;
	
	case 'pages':
	
		define('_AS_QP_PUBLICPAGES','Published');
		define('_AS_QP_PRIVATEPAGES','Private');
		define('_AS_QP_NEWPAGE','New Page');
		define('_AS_QP_NEWLINKED','New Redirect');
		define('_AS_QP_PAGELIST','Existing Pages');
		define('_AS_QP_PRIVATELIST','Private Pages');
		define('_AS_QP_PUBLICLIST','Published Pages');
		define('_AS_QP_TITLE','Title');
		define('_AS_QP_DATEPAGE','Created');
		define('_AS_QP_MODPAGE','Modified');
		define('_AS_QP_MENUPAGE','In Menu');
		define('_AS_QP_CATEGO','Category');
		define('_AS_QP_READS','Reads');
		define('_AS_QP_ACCESS','Access');
		define('_AS_QP_SEARCH','Search:');
		define('_AS_QP_SHOWALL','Show All');
		define('_AS_QP_RESULTS','Results per Page:');
		define('_AS_QP_NEXTPAGE','Next');
		define('_AS_QP_PREVPAGE','Previuos');
		define('_AS_QP_PUBLICATE','Published Pages');
		define('_AS_QP_PRIVATIZE','Private Pages');
		define('_AS_QP_PUBLISHED','Pusblished');
		define('_AS_QP_PRIVATED','Private');
		define('_AS_QP_ORDER','Order');
		define('_AS_QP_SAVECHGS','Save Changes');
		define('_AS_QP_LINKED','Linked');
		
		# Formulario
		define('_AS_QP_NEWTITLE','New Page');
		define('_AS_QP_EDITTITLE','Edit Page');
		define('_AS_QP_NEWLINKTITLE','New Linked Page');
		define('_AS_QP_EDITLINKTITLE','Edit Linked Page');
		define('_AS_QP_URL','URL');
		define('_AS_QP_FRIENDTITLE','Short Title');
		define('_AS_QP_FRIENDDESC','This title will be used to make friendly URLs. This must not have blanks nor special characters. If it is not specified will be generated automatically.');
		define('_AS_QP_SHORTDESC','Short Description');
		define('_AS_QP_PAGETEXT','Page Content');
		define('_AS_QP_XCODE','Enable Xoops Code');
		define('_AS_QP_SAVEANDRETURN','Save and Return');
		define('_AS_QP_PAGESTATUS','Page Status');
		define('_AS_QP_GROUPS','Allowed Groups');
		define('_AS_QP_GROUPS_DESC','Only the selected groups will have access to this document.');
		define('_AS_QP_INMENU','Show in Menu');
		
		define('_AS_QP_CONFIRMDEL','Do you really wish to delete this page?');
		
		# ERRORES
		define('_AS_QP_NOID','Specify a valid page');
		define('_AS_QP_SAVE','Save');
		define('_AS_QP_PUBLISH','Publish');
		define('_AS_QP_ERRTITLE','You must provide a title for this page.');
		define('_AS_QP_ERRTEXT','You have not written anything for the page content.');
		define('_AS_QP_ERRCAT','Select a category for this page.');
		define('_AS_QP_ERREXISTS','Already exists a page with same short name. Please modify this or specify a new title.');
		define('_AS_QP_SELECTONE','Select at least a page to modify.');
		
		break;
}

?>