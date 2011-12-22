<?php
// $Id$
// --------------------------------------------------------------
// bXpress
// A simple forums module for XOOPS and Common Utilities
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$amod = xoops_getActiveModules();
if(!in_array("rmcommon",$amod)){
    $error = "<strong>WARNING:</strong> bXpress requires %s to be installed!<br />Please install %s before trying to use bXpress";
    $error = str_replace("%s", '<a href="http://www.redmexico.com.mx/w/common-utilities/" target="_blank">Common Utilities</a>', $error);
    xoops_error($error);
    $error = '%s is not installed! This might cause problems with functioning of bXpress and entire system. To solve, install %s or uninstall bXpress and then delete module folder.';
    $error = str_replace("%s", '<a href="http://www.redmexico.com.mx/w/common-utilities/" target="_blank">Common Utilities</a>', $error);
    trigger_error($error, E_USER_WARNING);
    echo "<br />";
}

if (!function_exists("__")){
    function __($text, $d){
        return $text;
    }
}

if(function_exists("load_mod_locale")) load_mod_locale('bxpress');

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
$modversion['icon48'] = "images/icon48.png";
$modversion['onInstall'] = 'include/install.php';

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
    'default' => 0,
);
$modversion['config'][] = array( 
    'name' => 'htbase',
    'title' => '_MI_BX_BASEPATH',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => '/modules/bxpress',
);

$modversion['config'][] = array(
    'name' => 'forum_title',
    'title' => '_MI_BX_CNFTITLE',
    'description' => '_MI_BX_CNFTITLE_DESC',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => __('Welcome to bXpress Forums','bxpress')
);

$modversion['config'][] = array(
    'name' => 'maxfilesize',
    'title' => '_MI_BX_CNFMAXFILESIZE',
    'description' => '_MI_BX_CNFMAXFILESIZE_DESC',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => 500
);

$modversion['config'][] = array(
    'name' => 'showcats',
    'title' => '_MI_BX_SHOWCATS',
    'description' => '_MI_BX_SHOWCATS_DESC',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0
);

// Búsqueda
$modversion['config'][] = array(
    'name' => 'search',
    'title' => '_MI_BX_SEARCHANON',
    'description' => '',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0
);

// HTML
$modversion['config'][] = array(
    'name' => 'html',
    'title' => '_MI_BX_HTML',
    'description' => '',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0
);

// Prefijo para usuarios Anónimos
$modversion['config'][] = array(
    'name' => 'anonymous_prefix',
    'title' => '_MI_BX_APREFIX',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => 'guest_'
);

// Mensajes Nuevos
$modversion['config'][] = array(
    'name' => 'time_new',
    'title' => '_MI_BX_TIMENEW',
    'description' => '_MI_BX_TIMENEW_DESC',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 600
);

// Numero de mensajes en el formulario de envio
$modversion['config'][] = array(
    'name' => 'numpost',
    'title' => '_MI_BX_NUMPOST',
    'description' => '_MI_BX_NUMPOST_DESC',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 10
);

// Numero de mensajes en cada página
$modversion['config'][] = array(
    'name' => 'perpage',
    'title' => '_MI_BX_PERPAGE',
    'description' => '_MI_BX_PERPAGE_DESC',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 15
);

// Numero de temas en cada página
$modversion['config'][] = array(
    'name' => 'topicperpage',
    'title' => '_MI_BX_TPERPAGE',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 30
);

// formato de Fechas
$modversion['config'][] = array(
    'name' => 'dates',
    'title' => '_MI_BX_DATES',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => 'm/d/Y'
);

// Límite de archivos adjuntos por mensaje
$modversion['config'][] = array(
    'name' => 'attachlimit',
    'title' => '_MI_BX_ATTACHLIMIT',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 5
);

// Directorio para adjuntos
$modversion['config'][] = array(
    'name' => 'attachdir',
    'title' => '_MI_BX_ATTACHDIR',
    'description' => '_MI_BX_ATTACHDIR_DESC',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => XOOPS_UPLOAD_PATH.'/bxpress'
);

// Mensajes Fijos
$modversion['config'][] = array(
    'name' => 'sticky',
    'title' => '_MI_BX_STICKY',
    'description' => '_MI_BX_STICKY_DESC',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 1
);

// Rangos para mensajes fijos
$modversion['config'][] = array(
    'name' => 'sticky_posts',
    'title' => '_MI_BX_STICKYPOSTS',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 1000
);

// Anuncios en el módulo
$modversion['config'][] = array(
    'name' => 'announcements',
    'title' => '_MI_BX_ANNOUNCEMENTS',
    'description' => '',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 1
);

// Numero de Anuncios en el módulo
$modversion['config'][] = array(
    'name' => 'announcements_max',
    'title' => '_MI_BX_ANNOUNCEMENTSMAX',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 1
);

// Modo para los anuncios
$modversion['config'][] = array(
    'name' => 'announcements_mode',
    'title' => '_MI_BX_ANNOUNCEMENTSMODE',
    'description' => '',
    'formtype' => 'select',
    'valuetype' => 'int',
    'default' => 0,
    'options' => array('_MI_BX_ANNOUNCEMENTSMODE1'=>0,'_MI_BX_ANNOUNCEMENTSMODE2'=>1)
);

//Tiempo de temas recientes
$modversion['config'][] = array(
    'name' => 'time_topics',
    'title' => '_MI_BX_TIMETOPICS',
    'description' => '_MI_BX_DESCTIMETOPICS',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 24
);

//Tiempo de temas recientes
$modversion['config'][] = array(
    'name' => 'rssdesc',
    'title' => '_MI_BX_RSSDESC',
    'description' => '',
    'formtype' => 'textarea',
    'valuetype' => 'text',
    'default' => ''
);

//Ordenar por mensajes recientes
$modversion['config'][] = array(
    'name' => 'order_post',
    'title' => '_MI_BX_ORDERPOST',
    'description' => '_MI_BX_DESCORDERPOST',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => '0'
);

// Notificaciones
$modversion['hasNotification'] = 1;
$modversion['notification']['lookup_file'] = 'include/notification.php';
$modversion['notification']['lookup_func'] = 'bbNotifications';

$modversion['notification']['category'][1]['name'] = 'forum';
$modversion['notification']['category'][1]['title'] = '_MI_BX_NOT_FORUMCAT';
$modversion['notification']['category'][1]['description'] = '_MI_BX_NOT_FORUMCAT_DESC';
$modversion['notification']['category'][1]['subscribe_from'] = 'forum.php';
$modversion['notification']['category'][1]['item_name'] = 'id';
$modversion['notification']['category'][1]['allow_bookmark'] = 1;

$modversion['notification']['category'][2]['name'] = 'topic';
$modversion['notification']['category'][2]['title'] = '_MI_BX_NOT_TOPICCAT';
$modversion['notification']['category'][2]['description'] = '_MI_BX_NOT_TOPICCAT_DESC';
$modversion['notification']['category'][2]['subscribe_from'] = 'topic.php';
$modversion['notification']['category'][2]['item_name'] = 'id';
$modversion['notification']['category'][2]['allow_bookmark'] = 1;

$modversion['notification']['category'][3]['name'] = 'any_forum';
$modversion['notification']['category'][3]['title'] = '_MI_BX_NOT_ANY_FORUM';
$modversion['notification']['category'][3]['description'] = '_MI_BX_NOT_ANY_FORUM_DESC';
$modversion['notification']['category'][3]['subscribe_from'] = 'index.php';
$modversion['notification']['category'][3]['item_name'] = '';
$modversion['notification']['category'][3]['allow_bookmark'] = 1;

$modversion['notification']['event'][1]['name'] = 'newtopic';
$modversion['notification']['event'][1]['category'] = 'forum';
$modversion['notification']['event'][1]['title'] = '_MI_BX_NOTNEWTOPIC';
$modversion['notification']['event'][1]['caption'] = '_MI_BX_NOTNEWTOPICCAPTION';
$modversion['notification']['event'][1]['description'] = '_MI_BX_NOTNEWTOPICCAPTION_DESC';
$modversion['notification']['event'][1]['mail_template'] = 'new_topic';
$modversion['notification']['event'][1]['mail_subject'] = '_MI_BX_NOTNEWTOPIC_SUBJECT';

$modversion['notification']['event'][2]['name'] = 'newpost';
$modversion['notification']['event'][2]['category'] = 'topic';
$modversion['notification']['event'][2]['title'] = '_MI_BX_NOTNEPOST';
$modversion['notification']['event'][2]['caption'] = '_MI_BX_NOTNEPOST_CAPTION';
$modversion['notification']['event'][2]['description'] = '_MI_BX_NOTNEPOST_DESC';
$modversion['notification']['event'][2]['mail_template'] = 'new_post';
$modversion['notification']['event'][2]['mail_subject'] = '_MI_BX_NOTNEPOST_SUBJECT';

$modversion['notification']['event'][3]['name'] = 'postanyforum';
$modversion['notification']['event'][3]['category'] = 'any_forum';
$modversion['notification']['event'][3]['title'] = '_MI_BX_NOTNEPOSTANYFORUM';
$modversion['notification']['event'][3]['caption'] = '_MI_BX_NOTNEPOSTANYFORUM_CAPTION';
$modversion['notification']['event'][3]['description'] = '_MI_BX_NOTNEPOSTANYFORUM_DESC';
$modversion['notification']['event'][3]['mail_template'] = 'new_postanyforum';
$modversion['notification']['event'][3]['mail_subject'] = '_MI_BX_NOTNEPOSTANYFORUM_SUBJECT';

$modversion['notification']['event'][4]['name'] = 'postforum';
$modversion['notification']['event'][4]['category'] = 'forum';
$modversion['notification']['event'][4]['title'] = '_MI_BX_NOTNEPOSTFORUM';
$modversion['notification']['event'][4]['caption'] = '_MI_BX_NOTNEPOSTFORUM_CAPTION';
$modversion['notification']['event'][4]['description'] = '_MI_BX_NOTNEPOSTFORUM_DESC';
$modversion['notification']['event'][4]['mail_template'] = 'new_postforum';
$modversion['notification']['event'][4]['mail_subject'] = '_MI_BX_NOTNEPOSTFORUM_SUBJECT';

// Sindicación RSS
$modversion['rss']['name'] = '_MI_BX_RSSNAME'; 			// NOmbre del elemento
$modversion['rss']['file'] = 'include/rss.php';			// Archivo donde se localizan las funciones
$modversion['rss']['desc'] = 'bxpress_rssdesc';			// Devuelve la descripción del elemento
$modversion['rss']['feed'] = 'bxpress_rssfeed';			// Devuelve el menu de opciones del elemento
$modversion['rss']['show'] = 'bxpress_rssshow';			// Devuelve el archivo xml

// Bloque Recientes
$modversion['blocks'][0]['file'] = "bxpress_recents.php";
$modversion['blocks'][0]['name'] = '_MI_BX_BKRECENT';
$modversion['blocks'][0]['description'] = "";
$modversion['blocks'][0]['show_func'] = "bxpress_recents_show";
$modversion['blocks'][0]['edit_func'] = "bxpress_recents_edit";
$modversion['blocks'][0]['template'] = 'bk_bxpress_recents.html';
$modversion['blocks'][0]['options'] = array(10,1,1,1,0);

//Páginas del Módulo
$modversion['subpages']['index'] = _MI_BX_INDEX;
$modversion['subpages']['forums'] = _MI_BX_FORUM;
$modversion['subpages']['topics'] = _MI_BX_TOPIC;
$modversion['subpages']['post'] = _MI_BX_POST;
$modversion['subpages']['edit'] = _MI_BX_EDIT;
$modversion['subpages']['moderate'] = _MI_BX_MODERATE;
$modversion['subpages']['report'] = _MI_BX_REPORT;
$modversion['subpages']['search'] = _MI_BX_SEARCH;
