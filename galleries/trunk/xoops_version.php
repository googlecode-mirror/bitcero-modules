<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$amod = xoops_getActiveModules();
if(!in_array("rmcommon",$amod)){
    $error = "<strong>WARNING:</strong> MyGalleries requires %s to be installed!<br />Please install %s before trying to use MyGalleries";
    $error = str_replace("%s", '<a href="http://www.redmexico.com.mx/w/common-utilities/" target="_blank">Common Utilities</a>', $error);
    xoops_error($error);
    $error = '%s is not installed! This might cause problems with functioning of MyGalleries and entire system. To solve, install %s or uninstall MyGalleries and then delete module folder.';
    $error = str_replace("%s", '<a href="http://www.redmexico.com.mx/w/common-utilities/" target="_blank">Common Utilities</a>', $error);
    trigger_error($error, E_USER_WARNING);
    echo "<br />";
}

if (!function_exists("__")){
    function __($text, $d){
        return $text;
    }
}

if(function_exists("load_mod_locale")) load_mod_locale('galleries');

$modversion['name'] = "MyGalleries 3.0";
$modversion['version'] = 3.0;
$modversion['rmversion'] = array('number'=>3,'revision'=>039,'status'=>0,'name'=>'MyGalleries');
$modversion['description'] = __('Module to publish and manage photo galleries in XOOPS using Common Utilities', 'galleries');
$modversion['icon32'] = 'images/icon32.png';
$modversion['icon48'] = 'images/browse_set.png';
$modversion['icon24'] = 'images/icon24.png';
$modversion['icon16'] = 'images/images.png';
$modversion['author'] = "BitC3R0";
$modversion['authormail'] = "i.bitcero@gmail.com";
$modversion['authorweb'] = "Red México";
$modversion['authorurl'] = "http://www.redmexico.com.mx";
$modversion['credits'] = "Red México";
$modversion['help'] = "http://www.redmexico.com.mx/modules/galleries/";
$modversion['license'] = "GPL see LICENSE";
$modversion['rmnative'] = 1;
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "galleries";
$modversion['onUninstall']="include/install.php";
$modversion['onInstall']="include/install.php";

// Social links
$modversion['social'][0] = array('title' => __('BitCERO Twitter', 'rmcommon'),'type' => 'twitter','url' => 'http://www.twitter.com/bitcero/');
$modversion['social'][1] = array('title' => __('BitCERO LinkedIn', 'rmcommon'),'type' => 'linkedin','url' => 'http://www.linkedin.com/bitcero/');
$modversion['social'][2] = array('title' => __('Red México Twitter', 'rmcommon'),'type' => 'twitter','url' => 'http://www.twitter.com/redmexico/');
$modversion['social'][3] = array('title' => __('Red México Facebook', 'rmcommon'),'type' => 'facebook','url' => 'http://www.facebook.com/redmexico/');

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Admin things
$modversion['hasMain'] = 1;

// Busqueda
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.php";
$modversion['search']['func'] = "gsSearch";

// Plantillas
$modversion['templates'][1]['file'] = 'gs_index.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'gs_header.html';
$modversion['templates'][2]['description'] = '';
$modversion['templates'][3]['file'] = 'gs_sets.html';
$modversion['templates'][3]['description'] = '';
$modversion['templates'][4]['file'] = 'gs_userpics.html';
$modversion['templates'][4]['description'] = '';
$modversion['templates'][5]['file'] = 'gs_navigation_pages.html';
$modversion['templates'][5]['description'] = '';
$modversion['templates'][6]['file'] = 'gs_pics.html';
$modversion['templates'][6]['description'] = '';
$modversion['templates'][7]['file'] = 'gs_tags.html';
$modversion['templates'][7]['description'] = '';
$modversion['templates'][8]['file'] = 'gs_imgdetails.html';
$modversion['templates'][8]['description'] = '';
$modversion['templates'][9]['file'] = 'gs_imagestag.html';
$modversion['templates'][9]['description'] = '';
$modversion['templates'][10]['file'] = 'gs_setpics.html';
$modversion['templates'][10]['description'] = '';
$modversion['templates'][11]['file'] = 'gs_search.html';
$modversion['templates'][11]['description'] = '';
$modversion['templates'][12]['file'] = 'gs_searchformat.html';
$modversion['templates'][12]['description'] = '';
$modversion['templates'][13]['file'] = 'gs_comments.html';
$modversion['templates'][13]['description'] = '';
$modversion['templates'][14]['file'] = 'gs_submit.html';
$modversion['templates'][14]['description'] = '';
$modversion['templates'][15]['file'] = 'gs_panel.html';
$modversion['templates'][15]['description'] = '';
$modversion['templates'][16]['file'] = 'gs_formpics.html';
$modversion['templates'][16]['description'] = '';
$modversion['templates'][17]['file'] = 'gs_formaddsets.html';
$modversion['templates'][17]['description'] = '';
$modversion['templates'][18]['file'] = 'gs_panel_sets.html';
$modversion['templates'][18]['description'] = '';
$modversion['templates'][19]['file'] = 'gs_postcard_form.html';
$modversion['templates'][19]['description'] = '';
$modversion['templates'][20]['file'] = 'gs_panel_bookmarks.html';
$modversion['templates'][20]['description'] = '';
$modversion['templates'][21]['file'] = 'gs_panel_friends.html';
$modversion['templates'][21]['description'] = '';
$modversion['templates'][22]['file'] = 'gs_postcard.html';
$modversion['templates'][22]['description'] = '';
$modversion['templates'][23]['file'] = 'gs_item_data.html';
$modversion['templates'][23]['description'] = '';

// SQL
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

// Tablas
$modversion['tables'][0] = 'gs_images';
$modversion['tables'][1] = 'gs_postcards';
$modversion['tables'][2] = 'gs_sets';
$modversion['tables'][3] = 'gs_setsimages';
$modversion['tables'][4] = 'gs_tags';
$modversion['tables'][5] = 'gs_tagsimages';
$modversion['tables'][6] = 'gs_users';
$modversion['tables'][7] = 'gs_favourites';
$modversion['tables'][8] = 'gs_friends';

// Titulo de la Sección
$modversion['config'][] = array(
    'name' => 'section_title',
    'title' => '_MI_GS_SECTIONTITLE',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' =>'Gallery System',
    'size' => 50
);

//Url Amigables
$modversion['config'][] = array(
    'name' => 'urlmode',
    'description' => '',
    'title' => '_MI_GS_URLMODE',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' =>'0'
);

//Url Amigables
$modversion['config'][] = array(
    'name' => 'urlbase',
    'description' => '',
    'title' => '_MI_GS_URLBASE',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' =>'/modules/galleries'
);

//Permitir a todos los usuarios subir imágenes
$modversion['config'][] = array(
    'name' => 'submit',
    'description' => '',
    'title' => '_MI_GS_ALLOWEDALLS',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0
);

//Grupos que pueden subir imágenes
$modversion['config'][] = array(
    'name' => 'groups',
    'description' => '',
    'title' => '_MI_GS_GROUPSPICS',
    'formtype' => 'group_multi',
    'valuetype' => 'array',
    'default' => array(XOOPS_GROUP_ADMIN,XOOPS_GROUP_USERS)
);

//Cuota de disco
$modversion['config'][] = array(
    'name' => 'quota',
    'title' => '_MI_GS_QUOTA',
    'description' => '_MI_GS_DESCQUOTA',
    'size' => 10,
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' =>'3'
);

//Directorio para almacenar imágenes
$modversion['config'][] = array(
    'name' => 'storedir',
    'title' => '_MI_GS_STOREDIR',
    'description' => '',
    'size' => 50,
    'formtype' => 'textbox',
    'valuetype' => 'string',
    'default' => XOOPS_UPLOAD_PATH.'/galleries/'
);

//imagen miniatura
$modversion['config'][] = array(
    'name' => 'image_ths',
    'title' => '_MI_GS_THS',
    'description' => '_MI_GS_THS_DESC',
    'size' => 10,
    'formtype' => 'textarea',
    'valuetype' => 'array',
    'default' => array(100,100),
    'category' => 'format'
);

//Imagen grande
$modversion['config'][] = array(
    'name' => 'image',
    'title' => '_MI_GS_IMAGE',
    'description' => '_MI_GS_IMAGE_DESC',
    'size' => 10,
    'formtype' => 'textarea',
    'valuetype' => 'array',
    'default' => array(500,500),
    'category' => 'format'
);

//Tipo de redimension
$modversion['config'][] = array(
    'name' => 'redim_image',
    'title' => '_MI_GS_REDIMIMAGE',
    'description' => '',
    'size' => 10,
    'formtype' => 'select',
    'valuetype' => 'int',
    'default' => 0,
    'options' => array(__('Crop thumbnail','galleries')=>0,__('Crop image','galleries')=>1,__('Crop both','galleries')=>2,__('Only resize','galleries')=>3),
    'category' => 'format'
);

// Enable QuickView
$modversion['config'][] = array(
    'name' => 'quickview',
    'title' => '_MI_GS_QUICKVIEW',
    'description' => '_MI_GS_QUICKVIEWD',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 1
);

//Tamaño del archivo de imagen
$modversion['config'][] = array(
    'name' => 'size_image',
    'title' => '_MI_GS_SIZE',
    'description' => '_MI_GS_DESCSIZE',
    'size' => 10,
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 500
);

// Almacenar tamaños originales
$modversion['config'][] = array(
    'name' => 'saveoriginal',
    'title' => '_MI_GS_SAVEORIGINAL',
    'description' => '',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 1
);

// Conservar tamaños originales
$modversion['config'][] = array(
    'name' => 'deleteoriginal',
    'title' => '_MI_GS_DELORIGINAL',
    'description' => '',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0
);

// Imágenes Recientes
$modversion['config'][] = array(
    'name' => 'last_num',
    'title' => '_MI_GS_LASTNUM',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 9,
    'size' => 5,
    'category' => 'format'
);

// Número de Albumes Recientes
$modversion['config'][] = array(
    'name' => 'sets_num',
    'title' => '_MI_GS_SETSNUM',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 5,
    'size' => 5,
    'category' => 'format'
);

$modversion['config'][] = array(
    'name' => 'sets_num_images',
    'title' => '_MI_GS_SETSNUMIMGS',
    'description' => '_MI_GS_SETSNUMIMGSD',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 4,
    'category' => 'format'
);

//Número de albumes
$modversion['config'][] = array(
    'name' => 'limit_sets',
    'title' => '_MI_GS_LIMITSETS',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 12,
    'size' => 5,
    'category' => 'format'
);

//Número de imágenes
$modversion['config'][] = array(
    'name' => 'limit_pics',
    'title' => '_MI_GS_LIMITPICS',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 12,
    'size' => 5,
    'category' => 'format'
);

//Número de etiquetas a visualizar
$modversion['config'][] = array(
    'name' => 'num_tags',
    'title' => '_MI_GS_NUMTAGS',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 100,
    'size' => 5,
    'category' => 'format'
);

//Número de caracteres mínimos en etiquetas
$modversion['config'][] = array(
    'name' => 'min_tag',
    'description' => '',
    'size' => 10,
    'title' => '_MI_GS_MINTAG',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' =>'3'
);

//Número de caracteres máximos de etiquetas
$modversion['config'][] = array(
    'name' => 'max_tag',
    'description' => '',
    'size' => 10,
    'title' => '_MI_GS_MAXTAG',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' =>'50'
);

//Número de hits en etiquetas a visualizar
$modversion['config'][] = array(
    'name' => 'hits_tags',
    'title' => '_MI_GS_HITSTAGS',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 100,
    'size' => 5,
    'category' => 'format'
);

//Tamaño de fuente de etiqueta popular
$modversion['config'][] = array(
    'name' => 'font_tags',
    'title' => '_MI_GS_FONTTAGS',
    'description' => '_MI_GS_DESCFONTTAGS',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 40,
    'size' => 5,
    'category' => 'format'
);

//Número de imágenes en etiquetas
$modversion['config'][] = array(
    'name' => 'num_imgstags',
    'title' => '_MI_GS_NUMIMGSTAGS',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 12,
    'size' => 5,
    'category' => 'format'
);

// Show navigation bar as images
$modversion['config'][] = array(
    'name' => 'navimages',
    'title' => '_MI_GS_NAVIMAGES',
    'description' => '_MI_GS_NAVIMAGESD',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 1
);
$modversion['config'][] = array(
    'name' => 'navimages_num',
    'title' => '_MI_GS_NAVIMAGESNUM',
    'description' => '_MI_GS_NAVIMAGESNUMD',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 2
);

//Formato de imágenes de usuario
$modversion['config'][] = array(
    'name' => 'user_format_mode',
    'title' => '_MI_GS_USRIMGFORMATMODE',
    'description' => '',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0,
    'category' => 'format'
);

// Detalles del formato
$modversion['config'][] = array(
    'name' => 'user_format_values',
    'title' => '_MI_GS_USRIMGFORMAT',
    'description' => '_MI_GS_USRIMGFORMAT_DESC',
    'formtype' => 'textarea',
    'valuetype' => 'array',
    'default' =>"0|200|200|8|2|1|200 x 200",
    'category' => 'format'
);

//Formato de imágenes de album
$modversion['config'][] = array(
    'name' => 'set_format_mode',
    'title' => '_MI_GS_SETFORMATMODE',
    'description' => '',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0,
    'category' => 'format'
);

// Detalles del formato de imágenes de album
$modversion['config'][] = array(
    'name' => 'set_format_values',
    'title' => '_MI_GS_SETFORMAT',
    'description' => '_MI_GS_USRIMGFORMAT_DESC',
    'formtype' => 'textarea',
    'valuetype' => 'array',
    'default' => "0|70|70|30|5|0|70 x 70",
    'category' => 'format'
);

//Formato de imagen grande de album
$modversion['config'][] = array(
    'name' => 'setbig_format_values',
    'title' => '_MI_GS_SETBIGFORMAT',
    'description' => '_MI_GS_USRIMGFORMAT_DESC',
    'formtype' => 'textarea',
    'valuetype' => 'array',
    'default' =>"0|260|260|1|1|1|260 x 260",
    'size' => 50,
    'category' => 'format'
);

//Número de imágenes en búsqueda
$modversion['config'][] = array(
    'name' => 'num_search',
    'title' => '_MI_GS_NUMSEARCH',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 12,
    'size' => 5,
    'category' => 'format'
);

//Formato de imágenes de búsqueda
$modversion['config'][] = array(
    'name' => 'search_format_mode',
    'title' => '_MI_GS_SEARCHFORMATMODE',
    'description' => '_MI_GS_USRIMGFORMAT_DESC',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0,
    'category' => 'format'
);

// Detalles del formato de imágenes de búsqueda
$modversion['config'][] = array(
    'name' => 'search_format_values',
    'title' => '_MI_GS_SEARCHFORMAT',
    'description' => '_MI_GS_USRIMGFORMAT_DESC',
    'formtype' => 'textarea',
    'valuetype' => 'array',
    'default' =>"0|300|300|10|1|1|300 X 300",
    'category' => 'format'
);

//Postales
$modversion['config'][] = array(
    'name' => 'postcards',
    'title' => '_MI_GS_POSTCARDS',
    'description' => '',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 1,
    'category' => 'general'
);

// Duración en días de las postales
$modversion['config'][] = array(
    'name' => 'time_postcard',
    'title' => '_MI_GS_TIMEPOSTCARD',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 5,
    'size' => 5,
    'category' => 'format'
);

// Sindicación RSS
$modversion['rss']['name'] = '_MI_GS_RSSNAME'; 			// Nombre del elemento
$modversion['rss']['file'] = 'include/rss.php';			// Archivo donde se localizan las funciones
$modversion['rss']['desc'] = 'gs_rssdesc';			// Devuelve la descripción del elemento
$modversion['rss']['feed'] = 'gs_rssfeed';			// Devuelve el menu de opciones del elemento
$modversion['rss']['show'] = 'gs_rssshow';			// Devuelve el archivo xml

// Bloque de Fotografías
$modversion['blocks'][0]['file'] = "gs_photos.php";
$modversion['blocks'][0]['name'] = __('Pictures','galleries');
$modversion['blocks'][0]['description'] = __('Show pictures based on configured parameters','galleries');
$modversion['blocks'][0]['show_func'] = "gs_photos_show";
$modversion['blocks'][0]['edit_func'] = "gs_photos_edit";
$modversion['blocks'][0]['template'] = 'bk_gs_photos.html';
$modversion['blocks'][0]['options'] = array(4, 2, 0, 0, 1);

// Bloque de Albumes
$modversion['blocks'][1]['file'] = "gs_sets.php";
$modversion['blocks'][1]['name'] = __('Albums','galleries');
$modversion['blocks'][1]['description'] = "";
$modversion['blocks'][1]['show_func'] = "gs_sets_show";
$modversion['blocks'][1]['edit_func'] = "gs_sets_edit";
$modversion['blocks'][1]['template'] = 'bk_gs_sets.html';
$modversion['blocks'][1]['options'] = array(4, 2, 1, 1);

//Páginas del módulo
$modversion['subpages']['index'] = __('Index','galleries');
$modversion['subpages']['userpics'] = __('User pictures','galleries');
$modversion['subpages']['picsdetails'] = __('Picture details','galleries');
$modversion['subpages']['userset'] = __('User album','galleries');
$modversion['subpages']['panel'] = __('Control panel','galleries');
$modversion['subpages']['exploresets'] = __('Explore albums','galleries');
$modversion['subpages']['explorepics'] = __('Explore pictures','galleries');
$modversion['subpages']['tags'] = __('Tags','galleries');
$modversion['subpages']['exploretags'] = __('Explore tags','galleries');
$modversion['subpages']['submit'] = __('Upload pictures','galleries');
$modversion['subpages']['search'] = __('Search','galleries');
