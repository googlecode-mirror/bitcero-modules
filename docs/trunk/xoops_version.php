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
// @copyright: 2007 - 2008 Red México

$modversion['name'] = 'EXMBB Forums';
$modversion['description'] = _MI_RMF_DESC;
$modversion['author'] = "BitC3R0";
$modversion['authorlink'] = "mailto:bitc3r0@gmail.com";
$modversion['authorweb'] = "Red México";
$modversion['authorurl'] = "http://www.redmexico.com.mx";
$modversion['credits'] = "Red México";
$modversion['help'] = "";
$modversion['license'] = "See GPL License";
$modversion['official'] = 1;
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "exmbb";
$modversion['version'] = array('number'=>1,'revision'=>11,'status'=>0,'name'=>'EXMBB Forums');
$modversion['icon48'] = "images/logo.png";
$modversion['icon24'] = "images/icon24.png";
$modversion['deflang'] = "english";
$modversion['url'] = "www.exmsystem.net";
$modversion['onUninstall']="include/uninstall.php";
$modversion['updatable'] = 1;
$modversion['updateurl'] = 'http://redmexico.com.mx/modules/vcontrol/check.php?id=3';

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Sección Frontal
$modversion['hasMain'] = 1;

// Busqueda
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.php";
$modversion['search']['func'] = "exmbbSearch";

// SQL
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

// Tablas
$modversion['tables'][0] = 'exmbb_announcements';
$modversion['tables'][1] = 'exmbb_attachments';
$modversion['tables'][2] = 'exmbb_categories';
$modversion['tables'][3] = 'exmbb_forums';
$modversion['tables'][4] = 'exmbb_posts';
$modversion['tables'][5] = 'exmbb_posts_text';
$modversion['tables'][6] = 'exmbb_report';
$modversion['tables'][6] = 'exmbb_topics';

$modversion['templates'][0]['file'] = 'admin/forums_categos.html';
$modversion['templates'][0]['description'] = '';
$modversion['templates'][1]['file'] = 'admin/forums_forums.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'exmbb_index_categos.html';
$modversion['templates'][2]['description'] = '';
$modversion['templates'][3]['file'] = 'exmbb_index_forums.html';
$modversion['templates'][3]['description'] = '';
$modversion['templates'][4]['file'] = 'exmbb_forum.html';
$modversion['templates'][4]['description'] = '';
$modversion['templates'][5]['file'] = 'exmbb_header.html';
$modversion['templates'][5]['description'] = '';
$modversion['templates'][6]['file'] = 'exmbb_postform.html';
$modversion['templates'][6]['description'] = '';
$modversion['templates'][7]['file'] = 'exmbb_topic.html';
$modversion['templates'][7]['description'] = '';
$modversion['templates'][8]['file'] = 'exmbb_powered.html';
$modversion['templates'][8]['description'] = '';
$modversion['templates'][9]['file'] = 'exmbb_moderate.html';
$modversion['templates'][9]['description'] = '';
$modversion['templates'][10]['file'] = 'exmbb_moderateforms.html';
$modversion['templates'][10]['description'] = '';
$modversion['templates'][11]['file'] = 'admin/forums_index.html';
$modversion['templates'][11]['description'] = '';
$modversion['templates'][12]['file'] = 'admin/forums_announcements.html';
$modversion['templates'][12]['description'] = '';
$modversion['templates'][13]['file'] = 'exmbb_announcements.html';
$modversion['templates'][13]['description'] = '';
$modversion['templates'][14]['file'] = 'exmbb_report.html';
$modversion['templates'][14]['description'] = '';
$modversion['templates'][15]['file'] = 'admin/forums_reports.html';
$modversion['templates'][15]['description'] = '';
$modversion['templates'][16]['file'] = 'exmbb_search.html';
$modversion['templates'][16]['description'] = '';

/**
 * Opciones de Configuración del módulo
 * La descripción de cada una de las opciones es:
 *
 * 1. Título del Foro
 * 2. Directorio para almacenar archivos enviados como adjuntos
 * 3. Tamaño máximo de los archivos a cargar (Por defecto 1 MB)
 * 4. 
 */
$modversion['config'][1]['name'] = 'forum_title';
$modversion['config'][1]['title'] = '_MI_RMF_CNFTITLE';
$modversion['config'][1]['description'] = '_MI_RMF_CNFTITLE_DESC';
$modversion['config'][1]['formtype'] = 'textbox';
$modversion['config'][1]['valuetype'] = 'text';
$modversion['config'][1]['default'] = 'Bienvenido a RMSOFT Forum 1.0';
$modversion['config'][1]['order'] = 2;

$modversion['config'][3]['name'] = 'maxfilesize';
$modversion['config'][3]['title'] = '_MI_RMF_CNFMAXFILESIZE';
$modversion['config'][3]['description'] = '_MI_RMF_CNFMAXFILESIZE_DESC';
$modversion['config'][3]['formtype'] = 'textbox';
$modversion['config'][3]['valuetype'] = 'text';
$modversion['config'][3]['default'] = 500;
$modversion['config'][3]['order'] = 4;

$modversion['config'][4]['name'] = 'showcats';
$modversion['config'][4]['title'] = '_MI_BB_SHOWCATS';
$modversion['config'][4]['description'] = '_MI_BB_SHOWCATS_DESC';
$modversion['config'][4]['formtype'] = 'yesno';
$modversion['config'][4]['valuetype'] = 'int';
$modversion['config'][4]['default'] = 0;
$modversion['config'][4]['order'] = 5;

// Búsqueda
$modversion['config'][7]['name'] = 'search';
$modversion['config'][7]['title'] = '_MI_BB_SEARCHANON';
$modversion['config'][7]['description'] = '';
$modversion['config'][7]['formtype'] = 'yesno';
$modversion['config'][7]['valuetype'] = 'int';
$modversion['config'][7]['default'] = 0;
$modversion['config'][7]['order'] = 8;

// Editor
$modversion['config'][8]['name'] = 'editor';
$modversion['config'][8]['title'] = '_MI_BB_EDITOR';
$modversion['config'][8]['description'] = '';
$modversion['config'][8]['formtype'] = 'select';
$modversion['config'][8]['valuetype'] = 'text';
$modversion['config'][8]['default'] = 'dhtml';
$modversion['config'][8]['order'] = 9;
$modversion['config'][8]['options'] = array('_MI_BB_EDITOR1'=>'dhtml','_MI_BB_EDITOR2'=>'tiny',
											'_MI_BB_EDITOR3'=>'fck','_MI_BB_EDITOR4'=>'textarea');

// HTML
$modversion['config'][9]['name'] = 'html';
$modversion['config'][9]['title'] = '_MI_BB_HTML';
$modversion['config'][9]['description'] = '';
$modversion['config'][9]['formtype'] = 'yesno';
$modversion['config'][9]['valuetype'] = 'int';
$modversion['config'][9]['default'] = 0;
$modversion['config'][9]['order'] = 9;

// Prefijo para usuarios Anónimos
$modversion['config'][11]['name'] = 'anonymous_prefix';
$modversion['config'][11]['title'] = '_MI_BB_APREFIX';
$modversion['config'][11]['description'] = '';
$modversion['config'][11]['formtype'] = 'textbox';
$modversion['config'][11]['valuetype'] = 'text';
$modversion['config'][11]['default'] = 'guest_';
$modversion['config'][11]['order'] = 11;
$modversion['config'][11]['size'] = 15;

// Mensajes Nuevos
$modversion['config'][12]['name'] = 'time_new';
$modversion['config'][12]['title'] = '_MI_BB_TIMENEW';
$modversion['config'][12]['description'] = '_MI_BB_TIMENEW_DESC';
$modversion['config'][12]['formtype'] = 'textbox';
$modversion['config'][12]['valuetype'] = 'int';
$modversion['config'][12]['default'] = 600;
$modversion['config'][12]['order'] = 12;
$modversion['config'][12]['size'] = 15;

// Numero de mensajes en el formulario de envio
$modversion['config'][13]['name'] = 'numpost';
$modversion['config'][13]['title'] = '_MI_BB_NUMPOST';
$modversion['config'][13]['description'] = '_MI_BB_NUMPOST_DESC';
$modversion['config'][13]['formtype'] = 'textbox';
$modversion['config'][13]['valuetype'] = 'int';
$modversion['config'][13]['default'] = 10;
$modversion['config'][13]['order'] = 13;
$modversion['config'][13]['size'] = 8;

// Numero de mensajes en cada página
$modversion['config'][14]['name'] = 'perpage';
$modversion['config'][14]['title'] = '_MI_BB_PERPAGE';
$modversion['config'][14]['description'] = '_MI_BB_PERPAGE_DESC';
$modversion['config'][14]['formtype'] = 'textbox';
$modversion['config'][14]['valuetype'] = 'int';
$modversion['config'][14]['default'] = 15;
$modversion['config'][14]['order'] = 14;
$modversion['config'][14]['size'] = 8;

// Numero de temas en cada página
$modversion['config'][15]['name'] = 'topicperpage';
$modversion['config'][15]['title'] = '_MI_BB_TPERPAGE';
$modversion['config'][15]['description'] = '';
$modversion['config'][15]['formtype'] = 'textbox';
$modversion['config'][15]['valuetype'] = 'int';
$modversion['config'][15]['default'] = 30;
$modversion['config'][15]['order'] = 15;
$modversion['config'][15]['size'] = 8;

// formato de Fechas
$modversion['config'][16]['name'] = 'dates';
$modversion['config'][16]['title'] = '_MI_BB_DATES';
$modversion['config'][16]['description'] = '';
$modversion['config'][16]['formtype'] = 'textbox';
$modversion['config'][16]['valuetype'] = 'text';
$modversion['config'][16]['default'] = 'm/d/Y';
$modversion['config'][16]['order'] = 16;
$modversion['config'][16]['size'] = 15;

// Límite de archivos adjuntos por mensaje
$modversion['config'][17]['name'] = 'attachlimit';
$modversion['config'][17]['title'] = '_MI_BB_ATTACHLIMIT';
$modversion['config'][17]['description'] = '';
$modversion['config'][17]['formtype'] = 'textbox';
$modversion['config'][17]['valuetype'] = 'int';
$modversion['config'][17]['default'] = 5;
$modversion['config'][17]['order'] = 17;
$modversion['config'][17]['size'] = 5;

// Directorio para adjuntos
$modversion['config'][18]['name'] = 'attachdir';
$modversion['config'][18]['title'] = '_MI_BB_ATTACHDIR';
$modversion['config'][18]['description'] = '_MI_BB_ATTACHDIR_DESC';
$modversion['config'][18]['formtype'] = 'textbox';
$modversion['config'][18]['valuetype'] = 'text';
$modversion['config'][18]['default'] = XOOPS_UPLOAD_PATH.'/exmbb';
$modversion['config'][18]['order'] = 18;
$modversion['config'][18]['size'] = 50;

// Mensajes Fijos
$modversion['config'][19]['name'] = 'sticky';
$modversion['config'][19]['title'] = '_MI_BB_STICKY';
$modversion['config'][19]['description'] = '_MI_BB_STICKY_DESC';
$modversion['config'][19]['formtype'] = 'yesno';
$modversion['config'][19]['valuetype'] = 'int';
$modversion['config'][19]['default'] = 1;
$modversion['config'][19]['order'] = 19;

// Rangos para mensajes fijos
$modversion['config'][20]['name'] = 'sticky_posts';
$modversion['config'][20]['title'] = '_MI_BB_STICKYPOSTS';
$modversion['config'][20]['description'] = '';
$modversion['config'][20]['formtype'] = 'textbox';
$modversion['config'][20]['valuetype'] = 'int';
$modversion['config'][20]['default'] = 1000;
$modversion['config'][20]['order'] = 20;
$modversion['config'][20]['size'] = 20;

// Anuncios en el módulo
$modversion['config'][21]['name'] = 'announcements';
$modversion['config'][21]['title'] = '_MI_BB_ANNOUNCEMENTS';
$modversion['config'][21]['description'] = '';
$modversion['config'][21]['formtype'] = 'yesno';
$modversion['config'][21]['valuetype'] = 'int';
$modversion['config'][21]['default'] = 1;
$modversion['config'][21]['order'] = 21;

// Numero de Anuncios en el módulo
$modversion['config'][22]['name'] = 'announcements_max';
$modversion['config'][22]['title'] = '_MI_BB_ANNOUNCEMENTSMAX';
$modversion['config'][22]['description'] = '';
$modversion['config'][22]['formtype'] = 'textbox';
$modversion['config'][22]['valuetype'] = 'int';
$modversion['config'][22]['default'] = 1;
$modversion['config'][22]['order'] = 22;
$modversion['config'][22]['size'] = 5;

// Modo para los anuncios
$modversion['config'][23]['name'] = 'announcements_mode';
$modversion['config'][23]['title'] = '_MI_BB_ANNOUNCEMENTSMODE';
$modversion['config'][23]['description'] = '';
$modversion['config'][23]['formtype'] = 'select';
$modversion['config'][23]['valuetype'] = 'int';
$modversion['config'][23]['default'] = 0;
$modversion['config'][23]['order'] = 23;
$modversion['config'][23]['options'] = array('_MI_BB_ANNOUNCEMENTSMODE1'=>0,'_MI_BB_ANNOUNCEMENTSMODE2'=>1);

//Tiempo de temas recientes
$modversion['config'][24]['name'] = 'time_topics';
$modversion['config'][24]['title'] = '_MI_BB_TIMETOPICS';
$modversion['config'][24]['description'] = '_MI_BB_DESCTIMETOPICS';
$modversion['config'][24]['formtype'] = 'textbox';
$modversion['config'][24]['valuetype'] = 'int';
$modversion['config'][24]['default'] = 24;
$modversion['config'][24]['order'] = 24;
$modversion['config'][24]['size'] = 5;

//Tiempo de temas recientes
$modversion['config'][25]['name'] = 'rssdesc';
$modversion['config'][25]['title'] = '_MI_BB_RSSDESC';
$modversion['config'][25]['description'] = '';
$modversion['config'][25]['formtype'] = 'textarea';
$modversion['config'][25]['valuetype'] = 'text';
$modversion['config'][25]['default'] = '';
$modversion['config'][25]['order'] = 25;
$modversion['config'][25]['size'] = 50;

//Ordenar por mensajes recientes
$modversion['config'][26]['name'] = 'order_post';
$modversion['config'][26]['title'] = '_MI_BB_ORDERPOST';
$modversion['config'][26]['description'] = '_MI_BB_DESCORDERPOST';
$modversion['config'][26]['formtype'] = 'yesno';
$modversion['config'][26]['valuetype'] = 'int';
$modversion['config'][26]['default'] = '0';
$modversion['config'][26]['order'] = 26;

// Notificaciones
$modversion['hasNotification'] = 1;
$modversion['notification']['lookup_file'] = 'include/notification.php';
$modversion['notification']['lookup_func'] = 'bbNotifications';

$modversion['notification']['category'][1]['name'] = 'forum';
$modversion['notification']['category'][1]['title'] = '_MI_BB_NOT_FORUMCAT';
$modversion['notification']['category'][1]['description'] = '_MI_BB_NOT_FORUMCAT_DESC';
$modversion['notification']['category'][1]['subscribe_from'] = 'forum.php';
$modversion['notification']['category'][1]['item_name'] = 'id';
$modversion['notification']['category'][1]['allow_bookmark'] = 1;

$modversion['notification']['category'][2]['name'] = 'topic';
$modversion['notification']['category'][2]['title'] = '_MI_BB_NOT_TOPICCAT';
$modversion['notification']['category'][2]['description'] = '_MI_BB_NOT_TOPICCAT_DESC';
$modversion['notification']['category'][2]['subscribe_from'] = 'topic.php';
$modversion['notification']['category'][2]['item_name'] = 'id';
$modversion['notification']['category'][2]['allow_bookmark'] = 1;

$modversion['notification']['category'][3]['name'] = 'any_forum';
$modversion['notification']['category'][3]['title'] = '_MI_BB_NOT_ANY_FORUM';
$modversion['notification']['category'][3]['description'] = '_MI_BB_NOT_ANY_FORUM_DESC';
$modversion['notification']['category'][3]['subscribe_from'] = 'index.php';
$modversion['notification']['category'][3]['item_name'] = '';
$modversion['notification']['category'][3]['allow_bookmark'] = 1;

$modversion['notification']['event'][1]['name'] = 'newtopic';
$modversion['notification']['event'][1]['category'] = 'forum';
$modversion['notification']['event'][1]['title'] = '_MI_BB_NOTNEWTOPIC';
$modversion['notification']['event'][1]['caption'] = '_MI_BB_NOTNEWTOPICCAPTION';
$modversion['notification']['event'][1]['description'] = '_MI_BB_NOTNEWTOPICCAPTION_DESC';
$modversion['notification']['event'][1]['mail_template'] = 'new_topic';
$modversion['notification']['event'][1]['mail_subject'] = '_MI_BB_NOTNEWTOPIC_SUBJECT';

$modversion['notification']['event'][2]['name'] = 'newpost';
$modversion['notification']['event'][2]['category'] = 'topic';
$modversion['notification']['event'][2]['title'] = '_MI_BB_NOTNEPOST';
$modversion['notification']['event'][2]['caption'] = '_MI_BB_NOTNEPOST_CAPTION';
$modversion['notification']['event'][2]['description'] = '_MI_BB_NOTNEPOST_DESC';
$modversion['notification']['event'][2]['mail_template'] = 'new_post';
$modversion['notification']['event'][2]['mail_subject'] = '_MI_BB_NOTNEPOST_SUBJECT';

$modversion['notification']['event'][3]['name'] = 'postanyforum';
$modversion['notification']['event'][3]['category'] = 'any_forum';
$modversion['notification']['event'][3]['title'] = '_MI_BB_NOTNEPOSTANYFORUM';
$modversion['notification']['event'][3]['caption'] = '_MI_BB_NOTNEPOSTANYFORUM_CAPTION';
$modversion['notification']['event'][3]['description'] = '_MI_BB_NOTNEPOSTANYFORUM_DESC';
$modversion['notification']['event'][3]['mail_template'] = 'new_postanyforum';
$modversion['notification']['event'][3]['mail_subject'] = '_MI_BB_NOTNEPOSTANYFORUM_SUBJECT';

$modversion['notification']['event'][4]['name'] = 'postforum';
$modversion['notification']['event'][4]['category'] = 'forum';
$modversion['notification']['event'][4]['title'] = '_MI_BB_NOTNEPOSTFORUM';
$modversion['notification']['event'][4]['caption'] = '_MI_BB_NOTNEPOSTFORUM_CAPTION';
$modversion['notification']['event'][4]['description'] = '_MI_BB_NOTNEPOSTFORUM_DESC';
$modversion['notification']['event'][4]['mail_template'] = 'new_postforum';
$modversion['notification']['event'][4]['mail_subject'] = '_MI_BB_NOTNEPOSTFORUM_SUBJECT';

// Sindicación RSS
$modversion['rss']['name'] = '_MI_BB_RSSNAME'; 			// NOmbre del elemento
$modversion['rss']['file'] = 'include/rss.php';			// Archivo donde se localizan las funciones
$modversion['rss']['desc'] = 'exmbb_rssdesc';			// Devuelve la descripción del elemento
$modversion['rss']['feed'] = 'exmbb_rssfeed';			// Devuelve el menu de opciones del elemento
$modversion['rss']['show'] = 'exmbb_rssshow';			// Devuelve el archivo xml

// Bloque Recientes
$modversion['blocks'][0]['file'] = "exmbb_recents.php";
$modversion['blocks'][0]['name'] = '_MI_BB_BKRECENT';
$modversion['blocks'][0]['description'] = "";
$modversion['blocks'][0]['show_func'] = "exmbb_recents_show";
$modversion['blocks'][0]['edit_func'] = "exmbb_recents_edit";
$modversion['blocks'][0]['template'] = 'bk_exmbb_recents.html';
$modversion['blocks'][0]['options'] = array(10,1,1,1,0);

//Páginas del Módulo
$modversion['subpages']['index'] = _MI_BB_INDEX;
$modversion['subpages']['forums'] = _MI_BB_FORUM;
$modversion['subpages']['topics'] = _MI_BB_TOPIC;
$modversion['subpages']['post'] = _MI_BB_POST;
$modversion['subpages']['edit'] = _MI_BB_EDIT;
$modversion['subpages']['moderate'] = _MI_BB_MODERATE;
$modversion['subpages']['report'] = _MI_BB_REPORT;
$modversion['subpages']['search'] = _MI_BB_SEARCH;
?>
