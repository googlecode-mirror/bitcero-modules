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

define('_AS_BB_SELECT','Select...');
define('_AS_BB_ID','ID');
define('_AS_BB_DBOK','Database updated successfully!');

// ERRORES GLOBALES
define('_AS_BB_ERRACTION','Errores were detected while doing this operation:<br /><br />');
define('_AS_BB_ERRTOKEN','Session ID has expired');

// Fechas
define('_MS_EXMBB_TODAY', "Today %s");
define('_MS_EXMBB_YESTERDAY', "Yesterday %s");

switch (BB_LOCATION){

	case 'index':
		
		define('_AS_BB_INDEX','Home');
		define('_AS_BB_CATEGOS','Categories');
		define('_AS_BB_CATEGOSNUM','%s Categories');
		define('_AS_BB_FORUMS','Forums');
		define('_AS_BB_FORUMSNUM','%s Forums');
		define('_AS_BB_ANOUN','Announcements');
		define('_AS_BB_ANOUNNUM','%s Announcements');
		define('_AS_BB_REPORTS','Reports');
		define('_AS_BB_REPORTSNUM','%s Reports');
		define('_AS_BB_PURGE','Prune');
		define('_AS_BB_CLICK','Click Here');
		define('_AS_BB_TOPICS','Topics');
		define('_AS_BB_TOPICSNUM','%s Topics');
		define('_AS_BB_ANUNSWERED','Unanswered');
		define('_AS_BB_ANUNSWEREDNUM','%s Posts');
		define('_AS_BB_RECENT','Recent');
		define('_AS_BB_RECENTNUM','%s Posts');
		define('_AS_BB_REDMEX','Red México');
		define('_AS_BB_SITE','Visit Us');
		define('_AS_BB_HELP','Help');
		
		define('_AS_BB_BY','By %s');
		define('_AS_BB_LASTTOPICS','Recent Posts');
		define('_AS_BB_VERSIONS','%s Information'); 

		break;
		
	case 'categories':
	    
        define('_AS_BB_CATEGOS','Categories');
        define('_AS_BB_EXISTING','Existing Categories');
		define('_AS_BB_NEWCATEGO','New Category');
		define('_AS_BB_DELCATEGO','Delete Category');
        define('_AS_BB_EDITCATEGO','Edit Category');
		define('_AS_BB_TITLE','Title');
		define('_AS_BB_DESC','Description');
        define('_AS_BB_ORDER','Order');
        define('_AS_BB_ACTIVE','Active');
		define('_AS_BB_ACTIV','Enable');
        
        define('_AS_BB_DISABLE','Disable');
        define('_AS_BB_SAVE','Save Changes');

		define('_AS_BB_DELETECONF','Do you really wan to delete the selected categories?');
		define('_AS_BB_ALLPERM','WARNING: This action will delete all the forums belonging to this categories. <br />It will delete the data permanently.');
        
        // Formulario
        define('_AS_BB_ACTIVATE','Activate');
        define('_AS_BB_SHOWDESC','Show Description');
        define('_AS_BB_GROUPS','Groups with Access');
        define('_AS_BB_FRIENDNAME','Name for the URL');
				
		// ERRORES
        define('_AS_BB_ERRID','The specified category is not valid');
        define('_AS_BB_ERRNOEXISTS','The specified category does not exist.');
        define('_AS_BB_ERREXISTS','There is already a category with the same name');
        define('_AS_BB_ERREXISTSF','There is already a category with the same name for this URL');
		define('_AS_BB_ERRUPLOADIMG','Unable to upload the provided image.');
		define('_AS_BB_ERRNOTCAT','You must specify at least one category to do this action');
		define('_AS_BB_ERRCATNOVALID','Category %s not valid  <br />');
		define('_AS_BB_ERRCATNOEXIST','Categoryía %s does not exist <br />');
		define('_AS_BB_ERRCATNODEL','Unable to delete the category %s <br />');
		define('_AS_BB_ERRCATNOSAVE','Unable to modify the category %s <br />');
        
        break;
    
    case 'forums':
        
        // Menu
        define('_AS_BB_FORUMS','Forums');
        define('_AS_BB_NEWFORUM','Create Forums');
        
        // Lista de Foros
        define('_AS_BB_LFORUMS','Existing Forums List');
        define('_AS_BB_LNAME','Name');
        define('_AS_BB_LNUMTOPICS','Topics');
        define('_AS_BB_LNUMPOSTS','Posts');
        define('_AS_BB_LCATEGO','Category');
        define('_AS_BB_LACTIVE','Active');
        define('_AS_BB_LATTACH','Attachment');
        define('_AS_BB_LORDER','Position');
        define('_AS_BB_LDEACTIVATE','Deactivate');
        define('_AS_BB_LACTIVATE','Activate');
        define('_AS_BB_LSAVE','Save Changes');

		//Moderadores
		define('_AS_BB_MODERATORS','Moderators');
		define('_AS_BB_LIST','Choose from the list of users');
		define('_AS_BB_USERS','Users');
		define('_AS_BB_NOTSAVE','Moderators Information not stored');
		        
        define('_AS_BB_DELETELOC','Delete Forum');
        define('_AS_BB_DELETECONF','<strong>Do you really want to delete this forum?</strong><br /><br />
        					WARNING: All the topics and messages in this forum will be deleted!');
        
        // Formulario
        define('_AS_BB_FNEW','Create Forum');
        define('_AS_BB_FEDIT','Edit Forum');
        define('_AS_BB_FCATEGO','Category');
        define('_AS_BB_FNAME','Forum Name');
        define('_AS_BB_FDESC','Description');
        define('_AS_BB_FPARENT','Parent Forum');
        define('_AS_BB_FACTIVATE','Activate Forum');
        define('_AS_BB_FALLOWSIG','Allow signatures in the posts');
        define('_AS_BB_FALLOWPREFIX','Allow Prefixes in the posts');
        define('_AS_BB_FTHRESHOLD','Answers to match a topic as popular');
        define('_AS_BB_FORDER','Order in the List');
        define('_AS_BB_FCREATE','Create Forum');
        define('_AS_BB_FATTACH','Allow attachments');
        define('_AS_BB_FATTACHSIZE','Maximum attachments file size');
        define('_AS_BB_FATTACHSIZE_DESC','Specify this value in Kilobytes');
        define('_AS_BB_FATTACHEXT','Allowed file types');
        define('_AS_BB_FATTACHEXT_DESC',"Specify the allowed file types separating each one with '|'");
        define('_AS_BB_GROUPSVIEW','Can View the forum');
        define('_AS_BB_GROUPSTOPIC','Can Start New Topics');
        define('_AS_BB_GROUPSREPLY','Can Answer');
        define('_AS_BB_GROUPSEDIT','Can Edit your post');
        define('_AS_BB_GROUPSDELETE','Can Delete');
        define('_AS_BB_GROUPSVOTE','Can Vote');
        define('_AS_BB_GROUPSATTACH','Can attach');
        define('_AS_BB_GROUPSAPPROVE','Can send without approval');
        
        // ERRORES
        define('_AS_BB_NOID','The specified forum is not valid');
        define('_AS_BB_NOEXISTS','The specified forum does not exist.');
        define('_AS_BB_NOSELECTFORUM','It is necessary to select at least one forum to do this operation.');
        
        break;
    
    case 'announcements':
    	
    	define('_AS_BB_ANOUNLOC','Announcements');
    	define('_AS_BB_ANOUNNEW','Create Announce');
    	
    	// Lista
    	define('_AS_BB_EXISTING','Existing Announcements');
    	define('_AS_BB_ANNOUNCEMENT','Announcement');
    	define('_AS_BB_EXPIRE','Expire');
    	define('_AS_BB_WHERE','Where');
    	define('_AS_BB_BY','By');
    	define('_AS_BB_DELETE','Delete Announcements');
    	
    	// Formulario
    	define('_AS_BB_NEWLOC','Create Announcement');
    	define('_AS_BB_EDITLOC','Edit Announcement');
    	define('_AS_BB_FANNOUNCEMENT','Announcement Text');
    	define('_AS_EXMBB_BBCODE','BB Codes');
    	define('_AS_EXMBB_SMILES','Emoticons');
    	define('_AS_EXMBB_HTML','HTML');
    	define('_AS_EXMBB_BR','Wrap Lines');
    	define('_AS_EXMBB_IMG','Images');
    	define('_AS_EXMBB_FEXPIRE','Expire');
    	define('_AS_EXMBB_FWHERE','Show on');
    	define('_AS_EXMBB_FWHERE0','Home Page');
    	define('_AS_EXMBB_FWHERE1','Forum');
    	define('_AS_EXMBB_FWHERE2','All Module');
    	define('_AS_EXMBB_FFORUM','Forum');
    	define('_AS_EXMBB_FFORUM_DESC','Please select the forum where this announcement will be shown. This option is only valid when "In Forum" has been selected.');
    	define('_AS_EXMBB_FCREATE','Create Announcement');
    	define('_AS_EXMBB_FEDIT','Edit Announcement');
    	
    	// Mensajes
    	define('_AS_EXMBB_CONFDELB','Do you really want to delete the selected announcements?');
    	
    	// ERRORES
    	define('_AS_EXMBB_ERRID','The specified announcement is not valid');
    	define('_AS_EXMBB_ERREXISTS','The specified announcement does not exist');
    	define('_AS_EXMBB_ERRCADUC','The expiration date must be higher than the current date');
    	define('_AS_EXMBB_ERRSEL','Select at least one announcement to delete.');
    	
    	break;

     case 'reports':
	
		define('_AS_EXMBB_SESSINVALID','Sorry, your ID session is not longer valid');
		//Barra de Reportes
		define('_AS_EXMBB_ALLREPORTS','All reports');
		define('_AS_EXMBB_REVREPORTS','Review Reports');
		define('_AS_EXMBB_REVNOTREPORTS','Reports Not Reviewed');
		define('_AS_EXMBB_REPORTS','Reports');
		define('_AS_EXMBB_LISTREPORTS','Reports List');
		define('_AS_EXMBB_REPORT','Report');
		define('_AS_EXMBB_POST','Message');
		define('_AS_EXMBB_DATE','Date');
		define('_AS_EXMBB_USER','User');
		define('_AS_EXMBB_ZAPPED','Zapped');
		define('_AS_EXMBB_ZAPPEDNAME','Zapped By');
		define('_AS_EXMBB_ZAPPEDTIME','Zapped Date');
		define('_AS_EXMBB_REVIEW','Review');
		define('_AS_EXMBB_NOTREVIEW','Not Review');
		define('_AS_EXMBB_OPTIONS','Options');
		define('_AS_EXMBB_REPREVIEW','Zapped Reports List');
		define('_AS_EXMBB_REPNOTREVIEW','Reports List Not Zapped');
		define('_AS_EXMBB_DELREPORT','Do you really want to delete this report?  \nThis action will delete the data permanently.');
		define('_AS_EXMBB_DELREPORTS','Do you really want to delete the selected reports? \nThis action will delete the data permanently.');
		//Error 
		define('_AS_EXMBB_ERRREPORTS','You must select at least one report');
		define('_AS_EXMBB_ERRORREPORT','Report %s is not valid <br />');
		define('_AS_EXMBB_NOTSAVE','The report was not modified %s <br />'); 
		define('_AS_EXMBB_NOTEXIST','Report %s does not exist <br />');
		define('_AS_EXMBB_NOTDELETE','The report was not deleted %s <br />');
		
		break;

    case 'purge':
		
		define('_AS_EXMBB_SESSINVALID','Sorry, your ID sesion is not longer valid');
		//Formulario de Purge	
		define('_AS_EXMBB_PURGE','Prune Topics');
		define('_AS_EXMBB_FORUMS','Forum you wish to prune topics');
		define('_AS_EXMBB_ALLFORUMS','All Forums');
		define('_AS_EXMBB_DAYSOLD','Days');
		define('_AS_EXMBB_DESCDAYSOLD','Delete topics older than these days');
		define('_AS_EXMBB_ALLTOPICS','All topics');
		define('_AS_EXMBB_TOPICSUNANSWERED','Unanswered Topics');
		define('_AS_EXMBB_DELETE','Topics to delete');
		define('_AS_EXMBB_TOPICSFIXED','Delete Sticky Topics');
		define('_AS_EXMBB_DELTOPICS','Do you really wish to delete the topics? \nThis action will delte the data permanently.');
		
		break;
        
}

?>