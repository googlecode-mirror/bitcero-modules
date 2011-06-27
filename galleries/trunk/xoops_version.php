<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$modversion['name'] = "MyGalleries 3.0";
$modversion['version'] = 3;
$modversion['rmversion'] = array('number'=>3,'revision'=>015,'status'=>0,'name'=>'MyGalleries');
$modversion['description'] = __('Módulo para el manejo de galerías de imágenes en XOOPS y Common Utilities', 'galleries');
$modversion['icon32'] = 'images/icon32.png';
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
$modversion['onUninstall']="include/uninstall.php";

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
$modversion['templates'][22]['description'] = '';
$modversion['templates'][23]['file'] = 'gs_postcard.html';
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


$modversion['category'][0]['name'] = '_MI_GS_GENERALCNF';
$modversion['category'][0]['desc'] = '';
$modversion['category'][0]['id'] = 'general';
$modversion['category'][0]['order'] = 0;
$modversion['category'][0]['icon'] = '';

$modversion['category'][1]['name'] = '_MI_GS_FORMATCNF';
$modversion['category'][1]['desc'] = '';
$modversion['category'][1]['id'] = 'format';
$modversion['category'][1]['order'] = 0;
$modversion['category'][1]['icon'] = '';

// Titulo de la Sección
$modversion['config'][1]['name'] = 'section_title';
$modversion['config'][1]['title'] = '_MI_GS_SECTIONTITLE';
$modversion['config'][1]['description'] = '';
$modversion['config'][1]['formtype'] = 'textbox';
$modversion['config'][1]['valuetype'] = 'text';
$modversion['config'][1]['default'] ='Gallery System';
$modversion['config'][1]['size'] = 50;
$modversion['config'][1]['order'] = 1;

//Url Amigables
$modversion['config'][2]['name'] = 'urlmode';
$modversion['config'][2]['description'] = '';
$modversion['config'][2]['title'] = '_MI_GS_URLMODE';
$modversion['config'][2]['formtype'] = 'yesno';
$modversion['config'][2]['valuetype'] = 'int';
$modversion['config'][2]['default'] ='1';
$modversion['config'][2]['order'] = 1;

//Permitir a todos los usuarios subir imágenes
$modversion['config'][3]['name'] = 'submit';
$modversion['config'][3]['description'] = '';
$modversion['config'][3]['title'] = '_MI_GS_ALLOWEDALLS';
$modversion['config'][3]['formtype'] = 'yesno';
$modversion['config'][3]['valuetype'] = 'int';
$modversion['config'][3]['default'] = 0;
$modversion['config'][3]['order'] = 2;

//Grupos que pueden subir imágenes
$modversion['config'][4]['name'] = 'groups';
$modversion['config'][4]['description'] = '';
$modversion['config'][4]['title'] = '_MI_GS_GROUPSPICS';
$modversion['config'][4]['formtype'] = 'group_multi';
$modversion['config'][4]['valuetype'] = 'array';
$modversion['config'][4]['default'] =array(XOOPS_GROUP_ADMIN,XOOPS_GROUP_USERS);
$modversion['config'][4]['order'] = 3;

//Número de caracteres mínimos en etiquetas
$modversion['config'][5]['name'] = 'min_tag';
$modversion['config'][5]['description'] = '';
$modversion['config'][5]['size'] = 10;
$modversion['config'][5]['title'] = '_MI_GS_MINTAG';
$modversion['config'][5]['formtype'] = 'textbox';
$modversion['config'][5]['valuetype'] = 'int';
$modversion['config'][5]['default'] ='3';
$modversion['config'][5]['order'] = 4;

//Número de caracteres máximos de etiquetas
$modversion['config'][6]['name'] = 'max_tag';
$modversion['config'][6]['description'] = '';
$modversion['config'][6]['size'] = 10;
$modversion['config'][6]['title'] = '_MI_GS_MAXTAG';
$modversion['config'][6]['formtype'] = 'textbox';
$modversion['config'][6]['valuetype'] = 'int';
$modversion['config'][6]['default'] ='50';
$modversion['config'][6]['order'] = 5;

//Cuota de disco
$modversion['config'][7]['name'] = 'quota';
$modversion['config'][7]['title'] = '_MI_GS_QUOTA';
$modversion['config'][7]['description'] = '_MI_GS_DESCQUOTA';
$modversion['config'][7]['size'] = 10;
$modversion['config'][7]['formtype'] = 'textbox';
$modversion['config'][7]['valuetype'] = 'int';
$modversion['config'][7]['default'] ='3';
$modversion['config'][7]['order'] = 6;

//Directorio para almacenar imágenes
$modversion['config'][8]['name'] = 'storedir';
$modversion['config'][8]['title'] = '_MI_GS_STOREDIR';
$modversion['config'][8]['description'] = '';
$modversion['config'][8]['size'] = 50;
$modversion['config'][8]['formtype'] = 'textbox';
$modversion['config'][8]['valuetype'] = 'string';
$modversion['config'][8]['default'] = XOOPS_UPLOAD_PATH.'/galleries/';
$modversion['config'][8]['order'] = 7;

//Modo para mostrar imágenes
$modversion['config'][9]['name'] = 'storemode';
$modversion['config'][9]['title'] = '_MI_GS_STOREMODE';
$modversion['config'][9]['description'] = '';
$modversion['config'][9]['formtype'] = 'select';
$modversion['config'][9]['valuetype'] = 'int';
$modversion['config'][9]['default'] ='0';
$modversion['config'][9]['order'] = 8;
$modversion['config'][9]['options'] = array(_MI_GS_SECURE=>1,_MI_GS_NORMAL=>0);

//imagen miniatura
$modversion['config'][10]['name'] = 'image_ths';
$modversion['config'][10]['title'] = '_MI_GS_THS';
$modversion['config'][10]['description'] = '_MI_GS_THS_DESC';
$modversion['config'][10]['size'] = 10;
$modversion['config'][10]['formtype'] = 'textarea';
$modversion['config'][10]['valuetype'] = 'array';
$modversion['config'][10]['default'] =array(100,100);
$modversion['config'][10]['order'] = 9;
$modversion['config'][10]['category'] = 'format';

//Imagen grande
$modversion['config'][11]['name'] = 'image';
$modversion['config'][11]['title'] = '_MI_GS_IMAGE';
$modversion['config'][11]['description'] = '_MI_GS_IMAGE_DESC';
$modversion['config'][11]['size'] = 10;
$modversion['config'][11]['formtype'] = 'textarea';
$modversion['config'][11]['valuetype'] = 'array';
$modversion['config'][11]['default'] = array(500,500);
$modversion['config'][11]['order'] = 10;
$modversion['config'][11]['category'] = 'format';

//Tipo de redimension
$modversion['config'][12]['name'] = 'redim_image';
$modversion['config'][12]['title'] = '_MI_GS_REDIMIMAGE';
$modversion['config'][12]['description'] = '';
$modversion['config'][12]['size'] = 10;
$modversion['config'][12]['formtype'] = 'select';
$modversion['config'][12]['valuetype'] = 'int';
$modversion['config'][12]['default'] =0;
$modversion['config'][12]['order'] = 11;
$modversion['config'][12]['options'] = array('_MI_GS_CROPTHS'=>0,'_MI_GS_CROPBIG'=>1,'_MI_GS_CROPBOTH'=>2,'_MI_GS_REDIM'=>3);
$modversion['config'][12]['category'] = 'format';

//Tamaño del archivo de imagen
$modversion['config'][13]['name'] = 'size_image';
$modversion['config'][13]['title'] = '_MI_GS_SIZE';
$modversion['config'][13]['description'] = '_MI_GS_DESCSIZE';
$modversion['config'][13]['size'] = 10;
$modversion['config'][13]['formtype'] = 'textbox';
$modversion['config'][13]['valuetype'] = 'int';
$modversion['config'][13]['default'] =500;
$modversion['config'][13]['order'] = 12;

// Almacenar tamaños originales
$modversion['config'][14]['name'] = 'saveoriginal';
$modversion['config'][14]['title'] = '_MI_GS_SAVEORIGINAL';
$modversion['config'][14]['description'] = '';
$modversion['config'][14]['formtype'] = 'yesno';
$modversion['config'][14]['valuetype'] = 'int';
$modversion['config'][14]['default'] =1;
$modversion['config'][14]['order'] = 13;

// Conservar tamaños originales
$modversion['config'][15]['name'] = 'deleteoriginal';
$modversion['config'][15]['title'] = '_MI_GS_DELORIGINAL';
$modversion['config'][15]['description'] = '';
$modversion['config'][15]['formtype'] = 'yesno';
$modversion['config'][15]['valuetype'] = 'int';
$modversion['config'][15]['default'] =0;
$modversion['config'][15]['order'] = 14;

// Imágenes Recientes
$modversion['config'][16]['name'] = 'last_num';
$modversion['config'][16]['title'] = '_MI_GS_LASTNUM';
$modversion['config'][16]['description'] = '';
$modversion['config'][16]['formtype'] = 'textbox';
$modversion['config'][16]['valuetype'] = 'int';
$modversion['config'][16]['default'] =9;
$modversion['config'][16]['size'] =5;
$modversion['config'][16]['order'] = 15;
$modversion['config'][16]['category'] = 'format';

// Columnas para imágenes recientes
$modversion['config'][17]['name'] = 'last_cols';
$modversion['config'][17]['title'] = '_MI_GS_LASTCOLS';
$modversion['config'][17]['description'] = '';
$modversion['config'][17]['formtype'] = 'textbox';
$modversion['config'][17]['valuetype'] = 'int';
$modversion['config'][17]['default'] =3;
$modversion['config'][17]['size'] =5;
$modversion['config'][17]['order'] = 16;
$modversion['config'][17]['category'] = 'format';

// Número de Albumes Recientes
$modversion['config'][18]['name'] = 'sets_num';
$modversion['config'][18]['title'] = '_MI_GS_SETSNUM';
$modversion['config'][18]['description'] = '';
$modversion['config'][18]['formtype'] = 'textbox';
$modversion['config'][18]['valuetype'] = 'int';
$modversion['config'][18]['default'] =5;
$modversion['config'][18]['size'] =5;
$modversion['config'][18]['order'] = 17;
$modversion['config'][18]['category'] = 'format';

//Número de albumes
$modversion['config'][19]['name'] = 'limit_sets';
$modversion['config'][19]['title'] = '_MI_GS_LIMITSETS';
$modversion['config'][19]['description'] = '';
$modversion['config'][19]['formtype'] = 'textbox';
$modversion['config'][19]['valuetype'] = 'int';
$modversion['config'][19]['default'] =12;
$modversion['config'][19]['size'] =5;
$modversion['config'][19]['order'] = 18;
$modversion['config'][19]['category'] = 'format';

//Número de columnas de albumes
$modversion['config'][22]['name'] = 'cols_sets';
$modversion['config'][22]['title'] = '_MI_GS_COLSETS';
$modversion['config'][22]['description'] = '';
$modversion['config'][22]['formtype'] = 'textbox';
$modversion['config'][22]['valuetype'] = 'int';
$modversion['config'][22]['default'] =4;
$modversion['config'][22]['size'] =5;
$modversion['config'][22]['order'] = 18;
$modversion['config'][22]['category'] = 'format';

//Formato de imágenes de usuario
$modversion['config'][20]['name'] = 'user_format_mode';
$modversion['config'][20]['title'] = '_MI_GS_USRIMGFORMATMODE';
$modversion['config'][20]['description'] = '';
$modversion['config'][20]['formtype'] = 'yesno';
$modversion['config'][20]['valuetype'] = 'int';
$modversion['config'][20]['default'] =0;
$modversion['config'][20]['order'] = 19;
$modversion['config'][20]['category'] = 'format';

// Detalles del formato
$modversion['config'][21]['name'] = 'user_format_values';
$modversion['config'][21]['title'] = '_MI_GS_USRIMGFORMAT';
$modversion['config'][21]['description'] = '_MI_GS_USRIMGFORMAT_DESC';
$modversion['config'][21]['formtype'] = 'textarea';
$modversion['config'][21]['valuetype'] = 'array';
$modversion['config'][21]['default'] ="0|200|200|8|2|1|Pequeño";
$modversion['config'][21]['order'] = 20;
$modversion['config'][21]['category'] = 'format';

//Número de imágenes
$modversion['config'][23]['name'] = 'limit_pics';
$modversion['config'][23]['title'] = '_MI_GS_LIMITPICS';
$modversion['config'][23]['description'] = '';
$modversion['config'][23]['formtype'] = 'textbox';
$modversion['config'][23]['valuetype'] = 'int';
$modversion['config'][23]['default'] =12;
$modversion['config'][23]['size'] =5;
$modversion['config'][23]['order'] = 21;
$modversion['config'][23]['category'] = 'format';

// Columnas para imágenes recientes
$modversion['config'][24]['name'] = 'cols_pics';
$modversion['config'][24]['title'] = '_MI_GS_COLSPICS';
$modversion['config'][24]['description'] = '';
$modversion['config'][24]['formtype'] = 'textbox';
$modversion['config'][24]['valuetype'] = 'int';
$modversion['config'][24]['default'] =4;
$modversion['config'][24]['size'] =5;
$modversion['config'][24]['order'] = 22;
$modversion['config'][24]['category'] = 'format';

//Número de etiquetas a visualizar
$modversion['config'][25]['name'] = 'num_tags';
$modversion['config'][25]['title'] = '_MI_GS_NUMTAGS';
$modversion['config'][25]['description'] = '';
$modversion['config'][25]['formtype'] = 'textbox';
$modversion['config'][25]['valuetype'] = 'int';
$modversion['config'][25]['default'] =100;
$modversion['config'][25]['size'] =5;
$modversion['config'][25]['order'] = 23;
$modversion['config'][25]['category'] = 'format';

//Número de hits en etiquetas a visualizar
$modversion['config'][40]['name'] = 'hits_tags';
$modversion['config'][40]['title'] = '_MI_GS_HITSTAGS';
$modversion['config'][40]['description'] = '';
$modversion['config'][40]['formtype'] = 'textbox';
$modversion['config'][40]['valuetype'] = 'int';
$modversion['config'][40]['default'] =100;
$modversion['config'][40]['size'] =5;
$modversion['config'][40]['order'] = 24;
$modversion['config'][40]['category'] = 'format';

//Tamaño de fuente de etiqueta popular
$modversion['config'][26]['name'] = 'font_tags';
$modversion['config'][26]['title'] = '_MI_GS_FONTTAGS';
$modversion['config'][26]['description'] = '_MI_GS_DESCFONTTAGS';
$modversion['config'][26]['formtype'] = 'textbox';
$modversion['config'][26]['valuetype'] = 'int';
$modversion['config'][26]['default'] =40;
$modversion['config'][26]['size'] =5;
$modversion['config'][26]['order'] = 24;
$modversion['config'][26]['category'] = 'format';

//Número de imágenes en etiquetas
$modversion['config'][27]['name'] = 'num_imgstags';
$modversion['config'][27]['title'] = '_MI_GS_NUMIMGSTAGS';
$modversion['config'][27]['description'] = '';
$modversion['config'][27]['formtype'] = 'textbox';
$modversion['config'][27]['valuetype'] = 'int';
$modversion['config'][27]['default'] =12;
$modversion['config'][27]['size'] =5;
$modversion['config'][27]['order'] = 25;
$modversion['config'][27]['category'] = 'format';

// Columnas para imágenes en etiquetas
$modversion['config'][28]['name'] = 'cols_imgstags';
$modversion['config'][28]['title'] = '_MI_GS_COLSIMGSTAGS';
$modversion['config'][28]['description'] = '';
$modversion['config'][28]['formtype'] = 'textbox';
$modversion['config'][28]['valuetype'] = 'int';
$modversion['config'][28]['default'] =4;
$modversion['config'][28]['size'] =5;
$modversion['config'][28]['order'] = 26;
$modversion['config'][28]['category'] = 'format';

//Formato de imágenes de album
$modversion['config'][29]['name'] = 'set_format_mode';
$modversion['config'][29]['title'] = '_MI_GS_SETFORMATMODE';
$modversion['config'][29]['description'] = '';
$modversion['config'][29]['formtype'] = 'yesno';
$modversion['config'][29]['valuetype'] = 'int';
$modversion['config'][29]['default'] =0;
$modversion['config'][29]['order'] = 27;
$modversion['config'][29]['category'] = 'format';

// Detalles del formato de imágenes de album
$modversion['config'][30]['name'] = 'set_format_values';
$modversion['config'][30]['title'] = '_MI_GS_SETFORMAT';
$modversion['config'][30]['description'] = '_MI_GS_USRIMGFORMAT_DESC';
$modversion['config'][30]['formtype'] = 'textarea';
$modversion['config'][30]['valuetype'] = 'array';
$modversion['config'][30]['default'] ="0|70|70|30|5|0|Miniatura";
$modversion['config'][30]['order'] = 28;
$modversion['config'][30]['category'] = 'format';

//Formato de imagen grande de album
$modversion['config'][31]['name'] = 'setbig_format_values';
$modversion['config'][31]['title'] = '_MI_GS_SETBIGFORMAT';
$modversion['config'][31]['description'] = '_MI_GS_USRIMGFORMAT_DESC';
$modversion['config'][31]['formtype'] = 'textarea';
$modversion['config'][31]['valuetype'] = 'array';
$modversion['config'][31]['default'] ="0|260|260|1|1|1|260 x 260";
$modversion['config'][31]['order'] = 30;
$modversion['config'][31]['size'] = 50;
$modversion['config'][31]['category'] = 'format';

//Postales
$modversion['config'][32]['name'] = 'postcards';
$modversion['config'][32]['title'] = '_MI_GS_POSTCARDS';
$modversion['config'][32]['description'] = '';
$modversion['config'][32]['formtype'] = 'yesno';
$modversion['config'][32]['valuetype'] = 'int';
$modversion['config'][32]['default'] =1;
$modversion['config'][32]['order'] = 31;
$modversion['config'][32]['category'] = 'general';

//Número de imágenes en búsqueda
$modversion['config'][35]['name'] = 'num_search';
$modversion['config'][35]['title'] = '_MI_GS_NUMSEARCH';
$modversion['config'][35]['description'] = '';
$modversion['config'][35]['formtype'] = 'textbox';
$modversion['config'][35]['valuetype'] = 'int';
$modversion['config'][35]['default'] =12;
$modversion['config'][35]['size'] =5;
$modversion['config'][35]['order'] = 34;
$modversion['config'][35]['category'] = 'format';

// Columnas para imágenes recientes
$modversion['config'][36]['name'] = 'cols_search';
$modversion['config'][36]['title'] = '_MI_GS_COLSSEARCH';
$modversion['config'][36]['description'] = '';
$modversion['config'][36]['formtype'] = 'textbox';
$modversion['config'][36]['valuetype'] = 'int';
$modversion['config'][36]['default'] =4;
$modversion['config'][36]['size'] =5;
$modversion['config'][36]['order'] = 35;
$modversion['config'][36]['category'] = 'format';

//Formato de imágenes de búsqueda
$modversion['config'][37]['name'] = 'search_format_mode';
$modversion['config'][37]['title'] = '_MI_GS_SEARCHFORMATMODE';
$modversion['config'][37]['description'] = '_MI_GS_USRIMGFORMAT_DESC';
$modversion['config'][37]['formtype'] = 'yesno';
$modversion['config'][37]['valuetype'] = 'int';
$modversion['config'][37]['default'] =0;
$modversion['config'][37]['order'] = 36;
$modversion['config'][37]['category'] = 'format';

// Detalles del formato de imágenes de búsqueda
$modversion['config'][38]['name'] = 'search_format_values';
$modversion['config'][38]['title'] = '_MI_GS_SEARCHFORMAT';
$modversion['config'][38]['description'] = '_MI_GS_USRIMGFORMAT_DESC';
$modversion['config'][38]['formtype'] = 'textarea';
$modversion['config'][38]['valuetype'] = 'array';
$modversion['config'][38]['default'] ="0|300|300|10|1|1|300 X 300";
$modversion['config'][38]['order'] = 37;
$modversion['config'][38]['category'] = 'format';

// Duración en días de las postales
$modversion['config'][39]['name'] = 'time_postcard';
$modversion['config'][39]['title'] = '_MI_GS_TIMEPOSTCARD';
$modversion['config'][39]['description'] = '';
$modversion['config'][39]['formtype'] = 'textbox';
$modversion['config'][39]['valuetype'] = 'int';
$modversion['config'][39]['default'] ="5";
$modversion['config'][39]['order'] = 38;
$modversion['config'][39]['size'] =5;
$modversion['config'][39]['category'] = 'format';

// Comentarios
$modversion['hasComments'] = 1;
$modversion['comments']['pageName'] = 'user.php';
$modversion['comments']['itemName'] = 'id';
$modversion['comments']['extraParams']=array();

// Comment callback functions
$modversion['comments']['callbackFile'] = 'include/comments.php';
$modversion['comments']['callback']['approve'] = '';
$modversion['comments']['callback']['update'] = 'gs_com_update';

// Sindicación RSS
$modversion['rss']['name'] = '_MI_GS_RSSNAME'; 			// Nombre del elemento
$modversion['rss']['file'] = 'include/rss.php';			// Archivo donde se localizan las funciones
$modversion['rss']['desc'] = 'gs_rssdesc';			// Devuelve la descripción del elemento
$modversion['rss']['feed'] = 'gs_rssfeed';			// Devuelve el menu de opciones del elemento
$modversion['rss']['show'] = 'gs_rssshow';			// Devuelve el archivo xml

// Bloque de Fotografías
$modversion['blocks'][0]['file'] = "gs_photos.php";
$modversion['blocks'][0]['name'] = '_MI_GS_BKPICS';
$modversion['blocks'][0]['description'] = "";
$modversion['blocks'][0]['show_func'] = "gs_photos_show";
$modversion['blocks'][0]['edit_func'] = "gs_photos_edit";
$modversion['blocks'][0]['template'] = 'bk_gs_photos.html';
$modversion['blocks'][0]['options'] = array(4, 2, 0, 0, 1);

// Bloque de Albumes
$modversion['blocks'][1]['file'] = "gs_sets.php";
$modversion['blocks'][1]['name'] = '_MI_GS_BKSETS';
$modversion['blocks'][1]['description'] = "";
$modversion['blocks'][1]['show_func'] = "gs_sets_show";
$modversion['blocks'][1]['edit_func'] = "gs_sets_edit";
$modversion['blocks'][1]['template'] = 'bk_gs_sets.html';
$modversion['blocks'][1]['options'] = array(4, 2, 1, 1, 1);

//Páginas del módulo
$modversion['subpages']['index'] = _MI_GS_INDEX;
$modversion['subpages']['userpics'] = _MI_GS_USERPICS;
$modversion['subpages']['picsdetails'] = _MI_GS_PICSDETAILS;
$modversion['subpages']['userset'] = _MI_GS_USERSET;
$modversion['subpages']['panel'] = _MI_GS_CPANEL;
$modversion['subpages']['exploresets'] = _MI_GS_EXPLORESETS;
$modversion['subpages']['explorepics'] = _MI_GS_EXPLOREPICS;
$modversion['subpages']['tags'] = _MI_GS_TAGS;
$modversion['subpages']['exploretags'] = _MI_GS_EXPLORETAGS;
$modversion['subpages']['submit'] = _MI_GS_SUBMIT;
$modversion['subpages']['search'] = _MI_GS_SEARCH;
?>
