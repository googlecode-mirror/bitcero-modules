<?php
// $Id: main.php 70 2009-02-09 01:25:31Z BitC3R0 $
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

define('_MS_EXMBB_INDEX','Forum Index');
define('_MS_EXMBB_SEARCH','Search');
define('_MS_EXMBB_SEARCHQ','search:');
define('_MS_EXMBB_SESSINVALID','Sorry, your ID sesion is not longer valid');
define('_MS_EXMBB_JUMPO','Change Forum:');
define('_MS_EXMBB_GO','Go!');
define('_MS_EXMBB_PAGES','Pages:');
define('_MS_EXMBB_ANNOUNCEMENT','Announcement');
define('_MS_EXMBB_NEWTOPIC','New Topic');

// Fechas 
define('_MS_EXMBB_TODAY', "Today %s");
define('_MS_EXMBB_YESTERDAY', "Yesterday %s");

// Correo
define('_AS_EXMBB_ADMSUBJECT','New Topic in %s');
define('_AS_EXMBB_ADMSUBJECTPOST','Edited Post in %s');


switch(BB_LOCATION){
    case 'index':
        
        define('_MS_EXMBB_FORUM','Forum');
        define('_MS_EXMBB_TOPICS','Topics');
        define('_MS_EXMBB_POSTS','Posts');
        define('_MS_EXMBB_LASTPOST','Last Post');
        define('_MS_EXMBB_LASTUSER','Last registered user:');
        define('_MS_EXMBB_REGNUM','Registered user connected:');
        define('_MS_EXMBB_ANNUM','Anonymous users connected:');
        define('_MS_EXMBB_TOTALUSERS','Registered users:');
        define('_MS_EXMBB_TOTALTOPICS','Total topics:');
        define('_MS_EXMBB_TOTALPOSTS','Total posts:');
        define('_MS_EXMBB_BY','by %s');
        
        break;  
       
    case 'forum':
        
        define('_MS_EXMBB_TOPIC','Topic');
        define('_MS_EXMBB_REPLIES','Replies');
        define('_MS_EXMBB_VIEWS','Views');
        define('_MS_EXMBB_LASTPOST','Last Post');
        define('_MS_EXMBB_BY','by %s');
        define('_MS_EXMBB_NONEW','No News');
        define('_MS_EXMBB_WITHNEW','With News');
        define('_MS_EXMBB_HOTNONEW','Popular without new posts');
        define('_MS_EXMBB_HOTNEW','Popular with new posts');
        define('_MS_EXMBB_STICKY','Sticky:');
        define('_MS_EXMBB_NEWPOSTS','New Posts');
        define('_MS_EXMBB_MODERATE','Moderate Forum');
        define('_MS_EXMBB_CLOSED','Closed:');
        
        // ERRORES
        define('_MS_EXMBB_NOID','Specify a forum');
        define('_MS_EXMBB_NOEXISTS','The specify forum does not exist');
        define('_MS_EXMBB_NOALLOWED','Sorry, you do not have permission to view this forum');
        
        break;
    
    case 'posts':
    	
    	// Formulario
    	define('_MS_EXMBB_FTOPICTITLE','Create New Topic');
    	define('_MS_EXMBB_FEDITTITLE','Edit Topic');
    	define('_MS_EXMBB_FREPLYTITLE','Reply');
    	define('_MS_EXMBB_FMSGTIP','Write your post and send it');
    	define('_MS_EXMBB_SUBJECT','Topic Subject:');
    	define('_MS_EXMBB_NAME','Your Name:');
    	define('_MS_EXMBB_EMAIL','Your E-mail:');
    	define('_MS_EXMBB_MSG','Post');
    	define('_MS_EXMBB_BBCODE','BB Codes');
    	define('_MS_EXMBB_SMILES','Emoticons');
    	define('_MS_EXMBB_HTML','HTML');
    	define('_MS_EXMBB_BR','Wrap Lines');
    	define('_MS_EXMBB_IMG','Images');
    	define('_MS_EXMBB_ATTACH','Attach File:');
    	define('_MS_EXMBB_EXTS','Allowed File Types: %s');
    	define('_MS_EXMBB_TOPICREV','Topic Review (Newest First)');
    	define('_MS_EXMBB_EXATTACH','Archivos Adjuntos');
    	define('_MS_EXMBB_EXATTACHTIP','You can upload new files to this post. You have a limit 
    					of <strong>%s</strong> attachment per post.');
    	define('_MS_EXMBB_CURATTACH','Cuerrent Attachments');
    	define('_MS_EXMBB_UPLOAD','Upload File');
    	define('_MS_EXMBB_SAVECHANGES','Save Changes');
    	define('_MS_EXMBB_DELFILE','Delete File(s)');
    	define('_MS_EXMBB_STICKYTOPIC','Sticky Topic');
    	
    	// Mensajes
    	define('_MS_EXMBB_POSTOK','Your post has been succesfully sent!');
    	define('_MS_EXMBB_POSTOKEDIT','Your post has been succesfully edited!');
    	define('_MS_EXMBB_POSTOKERR','The message was posted but some errors happened while the process:');
    	define('_MS_EXMBB_ATTACHOK','File attached successfully!');
    	define('_MS_EXMBB_DELETEOK','Files succesfully deleted!');
    	
    	// ERRORES
    	define('_MS_EXMB_ERRFORUMNEW','You must specify a forum to create a topic');
    	define('_MS_EXMBB_TOPICNOEXISTS','The specified topic does not exist');
    	define('_MS_EXMBB_FORUMNOEXISTS','The specified forum does not exist');
    	define('_MS_EXMBB_NOPERM','Sorry, you do not have permission to do this action');
    	define('_MS_EXMBB_ERRPOST','The message could not be posted, please try again.');
    	define('_MS_EXMBB_ERRPOSTEDIT','The topic could not be modified, please try it again.');
    	define('_MS_EXMBB_NOMSGID','Please specify the topic edit');
    	define('_MS_EXMBB_POSTNOEXISTS','The specified topic does not exist');
    	define('_MS_EXMBB_ERRATLIMIT','You have reached the maximum attachments number for this post');
    	define('_MS_EXMBB_ERRSAVEATTACH','The file was not saved.');
    	define('_MS_EXMBB_ERRSELECTFILES','You have not selected any file to delete!');
    	define('_MS_EXMBB_ERRHAPPEN','Some errors occured while doing this operation:');
    	
    	break;
    
    case 'topics':
    	
    	define('_MS_EXMBB_REPLY','Reply');
    	define('_MS_EXMBB_REPORT','Report');
    	define('_MS_EXMBB_QUOTE','Quote');
    	define('_MS_EXMBB_REGISTERED','Registered: %s');
    	define('_MS_EXMBB_UPOSTS','Posts: %u');
    	define('_MS_EXMBB_UIP','IP: %s');
    	define('_MS_EXMBB_UONLINE','¡On Line!');
    	define('_MS_EXMBB_UOFFLINE','Disconnected');
    	define('_MS_EXMBB_ATTACHMENTS','Attachments');
    	
    	define('_MS_EXMBB_MOVE','Move Topic');
    	define('_MS_EXMBB_OPEN','Unlock Topic');
    	define('_MS_EXMBB_CLOSE','Lock Topic');
    	define('_MS_EXMBB_STICKY','Sticky Topic');
    	define('_MS_EXMBB_UNSTICKY','Unsticky Topic');
    	define('_MS_EXMBB_TOPICCLOSED','Locked Topic');
		define('_MS_EXMBB_APPROVED','Approved');
		define('_MS_EXMBB_NOAPPROVED','Unnapproves');
    	define('_MS_EXMBB_TOPICNOAPPROVED','Unnaproved Topic');
		define('_MS_EXMBB_EDIT','Edited Text');
		define('_MS_EXMBB_APP','Approve');
		define('_MS_EXMBB_NOAPP','Unapprove');

    	// ERRORES
    	define('_MS_EXMBB_ERRID','Please, specify a valid topic');
    	define('_MS_EXMBB_ERREXISTS','The specified topic does not exists.');
    	define('_MS_EXMBB_NOALLOWED','Sorry, you don\'t have permissions to view this forum');
    	
    	break;
    
    case 'delete':
    	
    	// Mensajes
    	define('_MS_EXMBB_DELTITLE','Delete Message');
    	define('_MS_EXMBB_DELCONF','Do you really wish to delete this message?');
    	define('_MS_EXMBB_DELWARN','<strong>Warning:</strong> This is the first post in the topic. By deleting this all posts will be deleted also.');
    	define('_MS_EXMBB_DELOK','Post deleted successfully!');
    	define('_MS_EXMBB_DELOKTOPIC','Topic deleted successfully!');
    	
    	// ERRORES
    	define('_MS_EXMBB_NOMSGID','Please specify a post to edit!');
    	define('_MS_EXMBB_POSTNOEXISTS','The specified post doesn\'t exists');
    	define('_MS_EXMBB_NOPERM','Sorry, you don\'t have permission to do this action');
    	define('_MS_EXMBB_NODELTOPIC','The topic could not be deleted!');
    	define('_MS_EXMBB_NODEL','The post could not be deleted!');
    	
    	break;
    
    case 'moderate':
    	
    	define('_MX_EXMBB_MODERATING','Moderating');
    	define('_MS_EXMBB_TOPIC','Topic');
        define('_MS_EXMBB_REPLIES','Replies');
        define('_MS_EXMBB_VIEWS','Views');
        define('_MS_EXMBB_LASTPOST','Last Post');
        define('_MS_EXMBB_STICKY','Sticky:');
        define('_MS_EXMBB_BY','by %s');
        define('_MS_EXMBB_MOVE','Move');
        define('_MS_EXMBB_MOVENOW','Move Topics');
        define('_MS_EXMBB_OPEN','Unlock');
        define('_MS_EXMBB_CLOSE','Lock');
        define('_MS_EXMBB_DOSTICKY','Sticky');
		define('_MS_EXMBB_APPROVED','Approved');
        define('_MS_EXMBB_DOUNSTICKY','Unsticky');
		define('_MS_EXMBB_APP','Approve');
		define('_MS_EXMBB_NOAPP','Unapprove');
        define('_MS_EXMBB_DELNOW','Delete Topics!');
        define('_MS_EXMBB_DELTITLE','Delete forum topics');
        define('_MS_EXMBB_DELCONF','Do you really wish to delete the selected topics?');
        
        // Mover Temas
        define('_MS_EXMBB_MOVETITLE','Move Topics');
        define('_MS_EXMBB_MOVETARGET','Please, select the forum you want to move the selected topics:');
        define('_MS_EXMBB_MOVEOK','The topics has been relocated.');
        define('_MS_EXMBB_ACTIONOK','Action completed.');
    	
    	// ERRORES
    	define('_MS_EXMBB_ERRID','Specify the forum you want moderate');
    	define('_MS_EXMBB_ERREXISTS','The specified forum doesn\'t exists');
    	define('_MS_EXMBB_NOPERM','Sorry, you don\'t have permissions to do this action');
    	define('_MS_EXMBB_SELTOPIC','Select at least a topic to moderate');
    	define('_MS_EXMBB_NOSELMOVEFOR','Please, select the forum you want to move the selected topics');
		define('_MS_EXMBB_NOTID','Invalid Post');
		define('_MS_EXMBB_NOTEXIST','This post doesn\'t exists');
    	
    	break;
    	
    case 'report':

		define('_MS_EXMBB_REPORT','Report');
		define('_MS_EXMBB_ADDREPORT','Please enter a short reason why you are reporting this post');

		define('_MS_EXMBB_POSTNOTVALID','Invalid Post');
		define('_MS_EXMBB_POSTNOTEXIST','This post doesn\'t exists');
		define('_MS_EXMBB_SAVEREPORT','The report was stored successfully');
		define('_MS_EXMBB_SAVENOTREPORT','The report wasn\'t stored');
		define('_MS_EXMBB_REPORTPOST','Report Post');
 		
		break;
		
    case 'search':
    
		define('_MS_EXMBB_NOTWORD','You must provide at least a word to search');
		define('_MS_EXMBB_ALLTOPICS','All Topics');
		define('_MS_EXMBB_RECENTTOPICS','Recent topics');
		define('_MS_EXMBB_ANUNSWERED','Unanswered Topics');
		define('_MS_EXMBB_ALLWORDS','All Words');
		define('_MS_EXMBB_ANYWORDS','Any Word');
		define('_MS_EXMBB_EXACTPHRASE','Exact Phrase');
		define('_MS_EXMBB_TOPIC','Topic');
        define('_MS_EXMBB_REPLIES','Replies');
        define('_MS_EXMBB_VIEWS','Views');
		define('_MS_EXMBB_LAST','Last Post');
      	define('_MS_EXMBB_NEWTOPIC','New Post');
        define('_MS_EXMBB_BY','by %s');
        define('_MS_EXMBB_STICKY','Sticky:');
        define('_MS_EXMBB_NEWPOSTS','New Posts');
        define('_MS_EXMBB_CLOSED','Locked:');
		define('_MS_EXMBB_FORUM','Forum');
		define('_MS_EXMBB_DATE','Posted');
	
		break;
		
    case 'files':
    
		define('_MS_EXMBB_NOTID','Invalid File');
		define('_MS_EXMBB_NOEXISTS','Inexistent File');
		
		break;

}
?>