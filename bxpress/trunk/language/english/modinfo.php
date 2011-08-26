<?php
// $Id$
// --------------------------------------------------------------
// bXpress
// A simple forums module for XOOPS and Common Utilities
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

# Opciones de configuración del módulo
define('_MI_BX_CNFTITLE',__('Forum Title','bxpress'));
define('_MI_BX_CNFTITLE_DESC', __('This title will be show in the Home page and in special sections.','bxpress'));
define('_MI_BX_URLMODE', __('Enable URLs rewriting','bxpress'));
define('_MI_BX_URLMODED', __('By enabling this option, bXpress will be capable to use friendly urls.','bxpress'));

define('_MI_RMF_CNFSTOREFILES','Directory for the file storage');
define('_MI_RMF_CNFSTOREFILES_DESC','In this folder will be stored the posts attachments and the categories images');

define('_MI_RMF_CNFMAXFILESIZE','Maximum allowed size for the sent files (en KB)');
define('_MI_RMF_CNFMAXFILESIZE_DESC','The files sent in the forums will be limited to this size, bigger file size will be ignored.');

define('_MI_BB_SHOWCATS','Show Categories in the Home Page');
define('_MI_BB_SHOWCATS_DESC','If this option is enable the forums will be ordered by categories in the Home Page.');
define('_MI_BB_URLMODE','URLS Manage Mode');
define('_MI_RMF_URLDEF','PHP Default Mode');
define('_MI_RMF_URLNAME','Based on Names');

define('_MI_BB_TOPICLIMIT','Topics Number per Page');
define('_MI_BB_POSTLIMIT','Message Number per Page');

define('_MI_BB_SEARCHANON','Enable search for anonymous users');

define('_MI_BB_EDITOR','Editor Type for the posts');
define('_MI_BB_EDITOR1','DHTML');
define('_MI_BB_EDITOR2','TinyMCE');
define('_MI_BB_EDITOR3','FCKEditor');
define('_MI_BB_EDITOR4','TextArea');

define('_MI_BB_HTML','Allow HTML in the posts');
define('_MI_BB_FILESIZE','Maximum file size for the attachment');
define('_MI_BB_FILESIZE_DESC','Specify this value in Kilobytes');
define('_MI_BB_APREFIX','Anonymous Users Prefix');
define('_MI_BB_TIMENEW','Time to mark a post as new');
define('_MI_BB_TIMENEW_DESC','Specify this value in seconds');
define('_MI_BB_NUMPOST','Post Limit in Review');
define('_MI_BB_NUMPOST_DESC','Post Maximun Number that will be shown in the post form.');
define('_MI_BB_PERPAGE','Post Number per Page');
define('_MI_BB_PERPAGE_DESC','This value can be configured individually for every user.');
define('_MI_BB_TPERPAGE','Topics Number per Page');
define('_MI_BB_DATES','Date Format');
define('_MI_BB_ATTACHLIMIT','Límite de Archivos Adjuntos por Mensaje');
define('_MI_BB_ATTACHDIR','Directory to storage the attachment');
define('_MI_BB_ATTACHDIR_DESC','This directory must exist in the server and must have vidor y writing permisions.');
define('_MI_BB_STICKY','Activate Sticky Posts');
define('_MI_BB_STICKY_DESC','This option will create topics like "sticky". The sticky topics always will appear in the first positions.<br />
							 Even when this option is disabled with the administrators and moderators will create sticky posts.');
define('_MI_BB_STICKYPOSTS','Required post number for a user to publish sticky topics');
define('_MI_BB_ANNOUNCEMENTS', 'Activate announcements in the module');
define('_MI_BB_ANNOUNCEMENTSMAX', 'Maximum number of announcements to show');
define('_MI_BB_ANNOUNCEMENTSMODE', 'Mode to show announcements');
define('_MI_BB_ANNOUNCEMENTSMODE1', 'Recents');
define('_MI_BB_ANNOUNCEMENTSMODE2', 'Random');

// Notificaciones
// Foros
define('_MI_BB_NOT_FORUMCAT','Forums');
define('_MI_BB_NOT_FORUMCAT_DESC','Notifications related with forums');
define('_MI_BB_NOTNEWTOPIC','New Added Topic');
define('_MI_BB_NOTNEWTOPICCAPTION','Notify when a new topic is created in this forum');
define('_MI_BB_NOTNEWTOPICCAPTION_DESC','Envía una notificación cuando un nuevo tema se crea en un foro determinado');
define('_MI_BB_NOTNEWTOPIC_SUBJECT','New Topic Added');

// Temas
define('_MI_BB_NOT_TOPICCAT','Topics');
define('_MI_BB_NOT_TOPICCAT_DESC','Notifications related to topics');
define('_MI_BB_NOTNEPOST','New Post sent');
define('_MI_BB_NOTNEPOST_CAPTION','Notify when a new post is send in this topic');
define('_MI_BB_NOTNEPOST_DESC','Send a notification when a new topic is sent on a topic');
define('_MI_BB_NOTNEPOST_SUBJECT','A new post has been sent');

//Mensaje en cualquier foro
define('_MI_BB_NOT_ANY_FORUM','All forums');
define('_MI_BB_NOT_ANY_FORUM_DESC','Notifications related to any forum');
define('_MI_BB_NOTNEPOSTANYFORUM','New post in any forum');
define('_MI_BB_NOTNEPOSTANYFORUM_CAPTION','Notify when a new topic is sent in any forum');
define('_MI_BB_NOTNEPOSTANYFORUM_DESC','Send a notification when a new topic is sent in any forum');
define('_MI_BB_NOTNEPOSTANYFORUM_SUBJECT','New topic sent');


//Nuevo mensaje en foro
define('_MI_BB_NOTNEPOSTFORUM','New Topic in forum');
define('_MI_BB_NOTNEPOSTFORUM_CAPTION','Notify when a new topic is sent in this forum');
define('_MI_BB_NOTNEPOSTFORUM_DESC','Sent a notification when a new topic is sent to this forum');
define('_MI_BB_NOTNEPOSTFORUM_SUBJECT','New topic sent');


//Tiempo de temas recientes
define('_MI_BB_TIMETOPICS','Recent topis time');
define('_MI_BB_DESCTIMETOPICS','Time to mark a topic as recent.'); 

define('_MI_BB_RSSDESC','Description of the Syndication option');
// Sindicación
define('_MI_BB_RSSNAME','Forums Syndication');
define('_MI_BB_RSSALL','All Recent Topics');
define('_MI_BB_RSSALLDESC','Suscribe to all messages in all forums');
define('_MI_BB_RSSNAMEFORUM','Syndication of topics on %s');

//Ordenar temas por mensajes recientes
define('_MI_BB_ORDERPOST','Order topics for recent post');
define('_MI_BB_DESCORDERPOST','Indicate if the forum topics will be ordered per recent topics');

// Bloques
define('_MI_BB_BKRECENT','Topics with new Posts');

//Páginas del módulo
define('_MI_BB_INDEX','Home Page');
define('_MI_BB_FORUM','Forum Page');
define('_MI_BB_TOPIC','Topic Page');
define('_MI_BB_POST','Post Page');
define('_MI_BB_EDIT','Post Editing');
define('_MI_BB_MODERATE','Forum Moderation');
define('_MI_BB_REPORT','Reports');
define('_MI_BB_SEARCH','Search');