<?php
// $Id: admin.php 13 2009-08-31 00:45:24Z i.bitcero $
// --------------------------------------------------------
// MyWords
// Manejo de Artículos
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

# Cadenas Generales
define('_AS_MW_DBOK','DataBase Succesfully updated');
define('_AS_MW_DBERROR','Error ocurred while performing this operation');
define('_AS_MW_ID','ID');
define('_AS_MW_ERRTOKEN','Sesion ID not valid');
define('_AS_MW_NEXTPAGE','Next Page');
define('_AS_MW_PREVPAGE','Prevous Page');

# Barra de Navegación
define('_AS_MW_HOME','Module Status');
define('_AS_MW_CATEGOS','Categories');
define('_AS_MW_POSTS','Articles');
define('_AS_MW_EDITORS','Editors');
define('_AS_MW_CONFIGS','Configurations');
define('_AS_MW_IMPORT','Importr');

define('_AS_MW_ERRHTACCESS','The file <strong>"%s"</strong> can not be modified for the serverand it is neccesary to do it to change the links management method.
		Please modify the file writing permissions or modify it manually.');

switch (MW_LOCATION){
	case 'index':
		
		define('_AS_MW_STATUS','Module currents status');
		define('_AS_MW_ABOUT','About Natural Press');
		define('_AS_MW_MOREINFO','Natural Press Information');
		define('_AS_MW_CATEGOSNUM','%s Categories');
		define('_AS_MW_POSTSNUM','%s Articles');
		define('_AS_MW_APPROVENUM','%s Approved');
		define('_AS_MW_APPROVENUM_ALT','%s Approved Articles');
		define('_AS_MW_WAITNUM','%s Waiting');
		define('_AS_MW_WAITNUM_DESC','%s Articles waiting for approval');
		define('_AS_MW_COMSNUM','%s Coments');
		define('_AS_MW_TRACKSNUM','%s Trackbacks');
		define('_AS_MW_EDITORSNUM','%s Editors');
		define('_AS_MW_REPLACESNUM','%s Replaces');
		define('_AS_MW_DETAILS','See Details');
		define('_AS_MW_VISIT','Visit Us');
		define('_AS_MW_HELP','Help');
		define('_AS_MW_CLICK','Click Here');
        define('_AS_MW_SOCIAL','%u Sites');
		
		define('_AS_MW_RECENTS','Recent %s Articles Sent');
		define('_AS_MW_AUTHOR','Author: ');
		define('_AS_MW_VERINFO','MyWords Information');
		define('_AS_MW_SENTDATE','Sent:');
		define('_AS_MW_APPROVED','Approved:');
		
		break;
		
	case 'categos':
		
		define('_AS_MW_CATLOCATION','Categories');
		define('_AS_MW_NEWCAT','Create Category');
		define('_AS_MW_LIST','Categories');
		define('_AS_MW_NAME','Name');
		define('_AS_MW_DESC','Description');
		define('_AS_MW_CATPOSTS','Envíos');
		define('_AS_MW_NEWTITLE','Create New Category');
		define('_AS_MW_EDITTITLE','Edit Category');
		define('_AS_MW_CATPARENT','Parent Category');
		define('_AS_MW_INMENU','Show as Menu');
		define('_AS_MW_ISMENU','Menu');
		define('_AS_MW_READGROUPS','Gropus with Reading Permission');
		define('_AS_MW_WRITEGROUPS','Groups with Writing permission');
		define('_AS_MW_DELETECATEGO','Delete Category');
		
		// MENSAJES
		define('_AS_MW_CONFIRMDEL','<strong>Do you really wish to delte this category?</strong>');
		define('_AS_MW_DELETEDESC','This action will not delete the existing articles, only the relations between them and the category the reason because you will asign the articles to a new category.<br />The subcategories will be asign to the superior category.');
		
		// ERRORES
		define('_AS_MW_ERRNAME','You must specify a name for this category');
		define('_AS_MW_ERREXISTS','Already exist the specified category');
		define('_AS_MW_ERRID','You must specify a valid category');
		
		break;
	case 'posts':
		define('_AS_MW_POSTSPAGE','Articles');
		define('_AS_MW_POSTSAPPROVEDTITLE','Approved Articles');
		define('_AS_MW_POSTSWAITTITLE','Artticles Waiting for Approval');
		define('_AS_MW_NEWPOST','Write Article');
		define('_AS_MW_TITLE','Article Tittle');
		define('_AS_MW_DATEPOST','Post Date');
		define('_AS_MW_POSTCATS','Categories');
		define('_AS_MW_ISAPPROVED','Approved');
		define('_AS_MW_TRACKBACKS','Trackbacks');
		define('_AS_MW_COMMENTS','Coments');
		define('_AS_MW_AUTHOR','Author');
		define('_AS_MW_NEWPOSTTITLE','Create Article');
		define('_AS_MW_EDITPOSTTITLE','Edit Article');
		define('_AS_MW_POSTTEXT','article Content');
		define('_AS_MW_CONTENTDESC','To divide the text and to show only a piece of the Homepage write <strong>[home]</strong> in the piece you wish to devide. The text that appears after that tag will be shown only when the user login the article.');
		define('_AS_MW_STATUS','Post Status');
		define('_AS_MW_PUBLIC','Public');
		define('_AS_MW_PRIVATE','Private');
		define('_AS_MW_SAVEANDRETURN','Save and Keep on Editing');
		define('_AS_MW_SAVE','Save');
		define('_AS_MW_PUBLISH','Publish');
		define('_AS_MW_SENDTRACKS','Send Trackbacks to:');
		define('_AS_MW_TRACKSDESC','Separate multiples URIs with a space.<br /><a href="http://es.wikipedia.org/wiki/TrackBack" target="_blank">Trackbacks</a>');
		define('_AS_MW_OPTIONALDATA','Optional Data');
		define('_AS_MW_TRACKSPINGED','Trackbacks sent');
		define('_AS_MW_POSTFRIEND','Title for the friendly URL');
		define('_AS_MW_ALLOWPINGS','Receive Pings');
		define('_AS_MW_ONLYADVANCE','Show only advance in the homepage');
		define('_AS_MW_ONLYADVANCE_DESC','To show only a part of the text n the homepage insert the word "<strong>[home]</strong>" in the place you wish to divide the text.');
		define('_AS_MW_XCODE','Enable XoopsCode');
		define('_AS_MW_EXCERPT','Coment for the trackbacks');
		define('_AS_MW_EXCERPTDESC','This text is send as a coment while doing a ping to the linked article. If it is empty the module will send the first 50 words of the article body');
		define('_AS_MW_PUSER','Post Owner');
		define('_AS_MW_POSTSAPPROVED','Approved');
		define('_AS_MW_POSTSWAIT','Waiting');
		
		define('_AS_MW_APPROVEPOSTS','Approve Posts');
		define('_AS_MW_UNAPPROVEPOSTS','Do not Approve Posts');
		
		define('_AS_MW_CONFIRMDEL','Do you really wish to delete this article?. All the comments made in the post will be deleted also.');
		
		define('_AS_MW_COMMWAIT','Waiting for Approval');
		define('_AS_MW_APROVADOS','Aproved');
		define('_AS_MW_ALL','All');
		define('_AS_MW_USER','User');
		define('_AS_MW_EMAIL','Email');
		define('_AS_MW_DATECOM','Sent');
		define('_AS_MW_APPROVED','Approved');
		define('_AS_MW_APROVAR','approve');
		define('_AS_MW_DELSEL', 'Delete Selected Elements');
		define('_AS_MW_APROVESEL', 'Approve Selected Elements');
		define('_AS_MW_CONFIRMDELCOMS','Do you really wish to delete the selected comments?');
		define('_AS_MW_CONFIRMDELTRACK', 'Do you really wish to delete this trackback?');
		define('_AS_MW_CONFIRMDELTRACKS','Do you really wish to delete the selected los trackbacks?');
		
		define('_AS_MW_TRACKSFOR','Trackbacks registered to "%s"');
		define('_AS_MW_TRACKTITLE','Title');
		define('_AS_MW_BLOG','Blog');
		define('_AS_MW_URL','Url');
		define('_AS_MW_SEARCH','Search');
		define('_AS_MW_RESULTS','Results for Page');
		define('_AS_MW_SHOWALL','Show All');
		define('_AS_MW_BLOCKIMG','Image to show in block');
		
		define('_AS_MW_METATITLE','Custom Fields');
		
		# ERRORES
		define('_AS_MW_ERRTITLE','Please provide a title for this article');
		define('_AS_MW_ERRFRIENDTITLE','Please provide a title for the friendly url');
		define('_AS_MW_ERRTEXT','You had not written the content for this article');
		define('_AS_MW_ERRCATS', 'You must select at least a category for this article');
		define('_AS_MW_NOID','Specify a valid article');
		define('_AS_MW_NOTRACKID','You must specify a valid trackback');
		define('_AS_MW_TRACKNOSEL','You had not selected Trackbacks');
		define('_AS_MW_SELECTONE','Select at least an element to modify');
		
		break;
	
	case 'import':
		
		define('_AS_MW_NOINTALLED','The module <strong>"%s"</strong> had not been installed yet');
		define('_AS_MW_CONFIRMIMPORT','Do you really wish to import the module information <strong>"%s"</strong>?');
		break;
	
	case 'editors': 
		
		define('_AS_MW_EDITORSLNG','Authorised Editors List');
		define('_AS_MW_EDITORSTIP','The <strong>editores</strong> are authorized persons to publish articles on personas autorizadas para publicar artículos sin necesidad de moderación. Todos los artículos que estas personas envien serán publicados inmediatamente');
		define('_AS_MW_UNAME','User');
		define('_AS_MW_JOINED','Since');
		define('_AS_MW_POSTSSEND','Posts');
		define('_AS_MW_SEEPOSTS','See Posts');
		define('_AS_MW_SELECTUSER','Select Users');
		define('_AS_MW_USERDESC','You can select several users at the same time to add them as editors');
		define('_AS_MW_ADDEDITORS','Add Editors');
		define('_AS_MW_SEARCHUSR','Search Users:');
		define('_AS_MW_SEARCHUSERS','Search');
		define('_AS_MW_CONFIRMDEL','Do you really wish to delte this editor?');
		define('_AS_MW_EDCATEGOS','You can Publish in the Following Categories');
		define('_AS_MW_ALL','All Categories');
		
		#ERRORES
		define('_AS_MW_ERRKEYWORD','You must specify a word to search');
		define('_AS_MW_ERRSELECT','You must select at least a user to add as editor');
		define('_AS_MW_ERRUID','Specify a valid editor');
		
		break;
		
	case 'configs':
	
		define('_AS_MW_REPLACEMENTS','Replacements');
		define('_AS_MW_TOSEARCH','Search');
		define('_AS_MW_TOREPLACE','Replace');
		define('_AS_MW_NEWREPLACE','New Replace Expression');
		define('_AS_MW_EDITREPLACE','Editing Replace Expression');
		define('_AS_MW_CONFDEL','Do you really wish to delete this replace expression?');
		
		# ERRORES
		
		define('_AS_MW_NODATA','Please complete all the required fields');
		define('_AS_MW_SEXISTS','Already exist a expression with the same search text');
		define('_AS_MW_NOID','Specify a valid expression');
		
		break;
    
    case 'bookmarks':
        
        define('_AS_MW_BTITLE','Existing Bookmarks Sites');
        define('_AS_MW_BNAME','Name');
        define('_AS_MW_BURL','URL');
        define('_AS_MW_BACTIVE','Active');
        
        define('_AS_MW_LOC','Sites');
        define('_AS_MW_ADDBM','Add Site');
        define('_AS_MW_EDITBM','Edit Site');
        define('_AS_MW_NAME','Site Name');
        
        // Formulario
        define('_AS_MW_NTITLE','Add Bookmar Site');
        define('_AS_MW_ETITLE','Edit Bookmark Site');
        define('_AS_MW_NAME','Site name');
        
        // ERRORES
        define('_AS_MW_ERRID','Wrong ID');
        
        break;
        
}
?>