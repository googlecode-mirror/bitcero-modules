<?php
// $Id$
// --------------------------------------------------------------
// bXpress
// A simple forums module for XOOPS and Common Utilities
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$modversion['name'] = __('bXpress','bxpress');
$modversion['description'] = __('A simple forums module for XOOPS and common utilities.','bxpress');
$modversion['rmversion'] = array('number'=>2,'revision'=>0,'status'=>-2,'name'=>__('bXpress','bxpress'));
$modversion['rmnative'] = 1;
$modversion['version'] = '1.3';
$modversion['icon32'] = 'images/forum32.png';
$modversion['icon24'] = 'images/forum.png';
$modversion['icon16'] = 'images/forum16.png';
$modversion['author'] = "BitC3R0";
$modversion['authormail'] = "i.bitcero@gmail.com";
$modversion['authorweb'] = "Red México";
$modversion['authorurl'] = "http://www.redmexico.com.mx";
$modversion['credits'] = "Red México";
$modversion['help'] = "";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 0;
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "bxpress";
$modversion['icon48'] = "images/logo.png";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Main section
$modversion['hasMain'] = 1;

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.php";
$modversion['search']['func'] = "bxpressSearch";

// SQL
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

// DB Tables
$modversion['tables'][0] = 'bxpress_announcements';
$modversion['tables'][1] = 'bxpress_attachments';
$modversion['tables'][2] = 'bxpress_categories';
$modversion['tables'][3] = 'bxpress_forums';
$modversion['tables'][4] = 'bxpress_posts';
$modversion['tables'][5] = 'bxpress_posts_text';
$modversion['tables'][6] = 'bxpress_report';
$modversion['tables'][6] = 'bxpress_topics';

$modversion['templates'][] = array('file'=>'bxpress_index_categos.html','description'=>'');
$modversion['templates'][] = array('file'=>'bxpress_index_forums.html','description'=>'');
$modversion['templates'][] = array('file'=>'bxpress_forum.html','description'=>'');
$modversion['templates'][] = array('file'=>'bxpress_header.html','description'=>'');
$modversion['templates'][] = array('file'=>'bxpress_postform.html','description'=>'');
$modversion['templates'][] = array('file'=>'bxpress_topic.html','description'=>'');
$modversion['templates'][] = array('file'=>'bxpress_powered.html','description'=>'');
$modversion['templates'][] = array('file'=>'bxpress_moderate.html','description'=>'');
$modversion['templates'][] = array('file'=>'bxpress_moderateforms.html','description'=>'');
$modversion['templates'][] = array('file'=>'bxpress_announcements.html','description'=>'');
$modversion['templates'][] = array('file'=>'bxpress_report.html','description'=>'');
$modversion['templates'][] = array('file'=>'bxpress_search.html','description'=>'');

/**
 * Settings
 */
$modversion['config'][] = array(
    'name' => 'forum_title',
    'title' => '_MI_BX_CNFTITLE',
    'description' => '_MI_BX_CNFTITLE_DESC',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => __('Welcome to bXpress Forums','bxpress'),
);

// URL rewriting
$modversion['config'][] = array( 
    'name' => 'urlmode',
    'title' => '_MI_BX_URLMODE',
    'description' => '_MI_BX_URLMODED',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 1,
);

$modversion['config'][] = array(
    'name' => 'forum_title',
    'title' => '_MI_BX_CNFTITLE',
    'description' => '_MI_BX_CNFTITLE_DESC',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => __('Welcome to bXpress Forums','bxpress'),
    'order' => 2
);

$modversion['config'][] = array(
    'name' => 'maxfilesize',
    'title' => '_MI_BX_CNFMAXFILESIZE',
    'description' => '_MI_BX_CNFMAXFILESIZE_DESC',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => 500,
    'order' => 4
);

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
$modversion['config'][18]['default'] = XOOPS_UPLOAD_PATH.'/bxpress';
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
$modversion['rss']['desc'] = 'bxpress_rssdesc';			// Devuelve la descripción del elemento
$modversion['rss']['feed'] = 'bxpress_rssfeed';			// Devuelve el menu de opciones del elemento
$modversion['rss']['show'] = 'bxpress_rssshow';			// Devuelve el archivo xml

// Bloque Recientes
$modversion['blocks'][0]['file'] = "bxpress_recents.php";
$modversion['blocks'][0]['name'] = '_MI_BB_BKRECENT';
$modversion['blocks'][0]['description'] = "";
$modversion['blocks'][0]['show_func'] = "bxpress_recents_show";
$modversion['blocks'][0]['edit_func'] = "bxpress_recents_edit";
$modversion['blocks'][0]['template'] = 'bk_bxpress_recents.html';
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
