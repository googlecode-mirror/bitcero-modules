<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Module for personals and professionals portfolios
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$modversion['name'] = "Professional Works";
$modversion['version'] = array('number'=>1,'revision'=>100,'status'=>0,'name'=>'Professional Works');
$modversion['description'] = _MI_PW_MODDESC;
$modversion['icon32'] = 'images/icon32.png';
$modversion['icon24'] = 'images/icon24.png';
$modversion['author'] = "BitC3R0";
$modversion['authorlink'] = "mailto:bitc3r0@gmail.com";
$modversion['authorweb'] = "Red México";
$modversion['authorurl'] = "http://www.redmexico.com.mx";
$modversion['url'] = 'www.exmsystem.org';
$modversion['credits'] = "Red México";
$modversion['help'] = "http://www.redmexico.com.mx/modules/ahelp/";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 1;
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "works";
$modversion['icon48'] = "images/logo.png";
$modversion['deflang'] = 'spanish';
$modversion['updatable'] = 1;
$modversion['updateurl'] = 'http://redmexico.com.mx/modules/vcontrol/check.php?id=10';
$modversion['onUpdate']="update.php";

//Archivo SQL
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Menu Principal
$modversion['hasMain'] = 1;

// Busqueda
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.php";
$modversion['search']['func'] = "pwSearch";

//Tablas creadas
$modversion['tables'][0] = "pw_categos";
$modversion['tables'][1] = "pw_works";
$modversion['tables'][2] = "pw_images";
$modversion['tables'][3] = "pw_clients";
$modversion['tables'][4] = "pw_types";

// Templates del Modulo
$modversion['templates'][0]['file'] = 'admin/pw_index.html';
$modversion['templates'][0]['description'] = '';
$modversion['templates'][1]['file'] = 'admin/pw_categories.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'admin/pw_clients.html';
$modversion['templates'][2]['description'] = '';
$modversion['templates'][3]['file'] = 'admin/pw_types.html';
$modversion['templates'][3]['description'] = '';
$modversion['templates'][4]['file'] = 'admin/pw_works.html';
$modversion['templates'][4]['description'] = '';
$modversion['templates'][5]['file'] = 'admin/pw_images.html';
$modversion['templates'][5]['description'] = '';

/*
// Front Section Templates
*/
$modversion['templates'][6]['file'] = 'pw_header.html';
$modversion['templates'][6]['description'] = '';
$modversion['templates'][7]['file'] = 'pw_index.html';
$modversion['templates'][7]['description'] = '';
$modversion['templates'][8]['file'] = 'pw_recent.html';
$modversion['templates'][8]['description'] = '';
$modversion['templates'][9]['file'] = 'pw_featured.html';
$modversion['templates'][9]['description'] = '';
$modversion['templates'][10]['file'] = 'pw_catego.html';
$modversion['templates'][10]['description'] = '';
$modversion['templates'][11]['file'] = 'pw_work.html';
$modversion['templates'][11]['description'] = '';
$modversion['templates'][12]['file'] = 'pw_navigation_pages.html';
$modversion['templates'][12]['description'] = '';
$modversion['templates'][13]['file'] = 'pw_witem.html';
$modversion['templates'][13]['description'] = '';

// Manejo de URLS
$modversion['config'][0]['name'] = 'urlmode';
$modversion['config'][0]['title'] = '_MI_PW_URLMODE';
$modversion['config'][0]['description'] = '_MI_PW_URLMODE_DESC';
$modversion['config'][0]['formtype'] = 'yesno';
$modversion['config'][0]['valuetype'] = 'int';
$modversion['config'][0]['default'] = 0;

// Directorio base para htaccess
$modversion['config'][1]['name'] = 'htbase';
$modversion['config'][1]['title'] = '_MI_PW_BASEDIR';
$modversion['config'][1]['description'] = '';
$modversion['config'][1]['formtype'] = 'textbox';
$modversion['config'][1]['valuetype'] = 'text';
$modversion['config'][1]['default'] = str_replace('http://'.$_SERVER['SERVER_NAME'],'', XOOPS_URL);
$modversion['config'][1]['order'] = 1;

//Título
$modversion['config'][2]['name'] = 'title';
$modversion['config'][2]['title'] = '_MI_PW_TITLE';
$modversion['config'][2]['description'] = '_MI_PW_DESCTITLE';
$modversion['config'][2]['formtype'] = 'textbox';
$modversion['config'][2]['valuetype'] = 'text';
$modversion['config'][2]['size'] = 50;
$modversion['config'][2]['default'] ='Professional Works';
$modversion['config'][2]['order'] = 2;

//Imagen principal
$modversion['config'][3]['name'] = 'image_main';
$modversion['config'][3]['description'] = '_MI_PW_DESCIMAGEMAIN';
$modversion['config'][3]['size'] = 10;
$modversion['config'][3]['title'] = '_MI_PW_IMAGEMAIN';
$modversion['config'][3]['formtype'] = 'textbox';
$modversion['config'][3]['valuetype'] = 'array';
$modversion['config'][3]['default'] = "400|400";
$modversion['config'][3]['order'] = 3;

//Miniatura de la imagen principal
$modversion['config'][4]['name'] = 'image_main_ths';
$modversion['config'][4]['description'] = '_MI_PW_THSMAIN_DESC';
$modversion['config'][4]['size'] = 10;
$modversion['config'][4]['title'] = '_MI_PW_THSMAIN';
$modversion['config'][4]['formtype'] = 'textbox';
$modversion['config'][4]['valuetype'] = 'array';
$modversion['config'][4]['default'] = "100|100";
$modversion['config'][4]['order'] = 4;

//Imagen grande
$modversion['config'][5]['name'] = 'image';
$modversion['config'][5]['description'] = '_MI_PW_DESCIMAGE';
$modversion['config'][5]['size'] = 10;
$modversion['config'][5]['title'] = '_MI_PW_IMAGE';
$modversion['config'][5]['formtype'] = 'textbox';
$modversion['config'][5]['valuetype'] = 'array';
$modversion['config'][5]['default'] = "400|400";
$modversion['config'][5]['order'] = 5;

//Imagen miniatura
$modversion['config'][6]['name'] = 'image_ths';
$modversion['config'][6]['description'] = '_MI_PW_DESCTHS';
$modversion['config'][6]['size'] = 10;
$modversion['config'][6]['title'] = '_MI_PW_THS';
$modversion['config'][6]['formtype'] = 'textbox';
$modversion['config'][6]['valuetype'] = 'array';
$modversion['config'][6]['default'] = "100|100";
$modversion['config'][6]['order'] = 6;

//Tipo de redimension
$modversion['config'][7]['name'] = 'redim_image';
$modversion['config'][7]['description'] = '';
$modversion['config'][7]['size'] = 10;
$modversion['config'][7]['title'] = '_MI_PW_REDIMIMAGE';
$modversion['config'][7]['formtype'] = 'select';
$modversion['config'][7]['valuetype'] = 'int';
$modversion['config'][7]['default'] =0;
$modversion['config'][7]['order'] = 7;
$modversion['config'][7]['options'] = array('_MI_PW_CROPTHS'=>0,'_MI_PW_CROPBIG'=>1,'_MI_PW_CROPBOTH'=>2,'_MI_PW_REDIM'=>3);

//Tamaño del archivo de imagen
$modversion['config'][8]['name'] = 'size_image';
$modversion['config'][8]['description'] = '_MI_PW_DESCSIZE';
$modversion['config'][8]['size'] = 10;
$modversion['config'][8]['title'] = '_MI_PW_SIZE';
$modversion['config'][8]['formtype'] = 'textbox';
$modversion['config'][8]['valuetype'] = 'int';
$modversion['config'][8]['default'] =200;
$modversion['config'][8]['order'] = 8;

//Mostrar el costo del trabajo
$modversion['config'][9]['name'] = 'cost';
$modversion['config'][9]['description'] = '';
$modversion['config'][9]['size'] = 10;
$modversion['config'][9]['title'] = '_MI_PW_COST';
$modversion['config'][9]['formtype'] = 'yesno';
$modversion['config'][9]['valuetype'] = 'int';
$modversion['config'][9]['default'] =0;
$modversion['config'][9]['order'] = 9;

//Formato de moneda
$modversion['config'][10]['name'] = 'format_currency';
$modversion['config'][10]['description'] = '';
$modversion['config'][10]['size'] = 10;
$modversion['config'][10]['title'] = '_MI_PW_FORMATCURRENCY';
$modversion['config'][10]['formtype'] = 'text';
$modversion['config'][10]['valuetype'] = 'string';
$modversion['config'][10]['default'] ='$ %s';
$modversion['config'][10]['order'] = 10;

//Número de Trabajos Recientes
$modversion['config'][11]['name'] = 'num_recent';
$modversion['config'][11]['description'] = '_MI_PW_DESCRECENT';
$modversion['config'][11]['size'] = 10;
$modversion['config'][11]['title'] = '_MI_PW_RECENT';
$modversion['config'][11]['formtype'] = 'text';
$modversion['config'][11]['valuetype'] = 'int';
$modversion['config'][11]['default'] ='5';
$modversion['config'][11]['order'] = 11;

//Número de Trabajos Recientes
$modversion['config'][12]['name'] = 'num_featured';
$modversion['config'][12]['description'] = '_MI_PW_DESCFEATURED';
$modversion['config'][12]['size'] = 10;
$modversion['config'][12]['title'] = '_MI_PW_FEATURED';
$modversion['config'][12]['formtype'] = 'text';
$modversion['config'][12]['valuetype'] = 'int';
$modversion['config'][12]['default'] ='5';
$modversion['config'][12]['order'] = 12;

//Otros trabajos
$modversion['config'][13]['name'] = 'other_works';
$modversion['config'][13]['description'] = '_MI_PW_DESCOTHERWORKS';
$modversion['config'][13]['title'] = '_MI_PW_OTHERWORKS';
$modversion['config'][13]['formtype'] = 'select';
$modversion['config'][13]['valuetype'] = 'int';
$modversion['config'][13]['default'] ='0';
$modversion['config'][13]['order'] = 13;
$modversion['config'][13]['options'] = array('_MI_PW_CATEGO'=>0,'_MI_PW_FEATUREDS'=>1);

//Número de Otros Trabajos
$modversion['config'][14]['name'] = 'num_otherworks';
$modversion['config'][14]['description'] = '_MI_PW_DESCNUMOTHER';
$modversion['config'][14]['size'] = 10;
$modversion['config'][14]['title'] = '_MI_PW_NUMOTHER';
$modversion['config'][14]['formtype'] = 'text';
$modversion['config'][14]['valuetype'] = 'int';
$modversion['config'][14]['default'] ='5';
$modversion['config'][14]['order'] = 14;

//Páginas del módulo
$modversion['subpages']['index'] = _MI_PW_PINDEX;
$modversion['subpages']['recent'] = _MI_PW_PRECENTS;
$modversion['subpages']['featured'] = _MI_PW_PFEATUREDS;
$modversion['subpages']['work'] = _MI_PW_PWORK;
$modversion['subpages']['category'] = _MI_PW_PCATEGOS;

// Bloques
$modversion['blocks'][1]['file'] = "pw_works.php";
$modversion['blocks'][1]['name'] = '_MI_PW_WORKS';
$modversion['blocks'][1]['description'] = '';
$modversion['blocks'][1]['show_func'] = "pw_works_show";
$modversion['blocks'][1]['edit_func'] = "pw_works_edit";
$modversion['blocks'][1]['template'] = 'pw_bk_works.html';
$modversion['blocks'][1]['options'] = array(0, 0, 0, 1, 1, 1, 0);

$modversion['blocks'][2]['file'] = "pw_comments.php";
$modversion['blocks'][2]['name'] = '_MI_PW_COMMENTS';
$modversion['blocks'][2]['description'] = '';
$modversion['blocks'][2]['show_func'] = "pw_comments_show";
$modversion['blocks'][2]['edit_func'] = "pw_comments_edit";
$modversion['blocks'][2]['template'] = 'pw_bk_comments.html';
$modversion['blocks'][2]['options'] = array(1, 0);

$modversion['blocks'][3]['file'] = "pw_cats.php";
$modversion['blocks'][3]['name'] = '_MI_PW_BKCATS';
$modversion['blocks'][3]['description'] = '';
$modversion['blocks'][3]['show_func'] = "pw_categories_show";
$modversion['blocks'][3]['edit_func'] = "";
$modversion['blocks'][3]['template'] = 'pw_bk_cats.html';
$modversion['blocks'][3]['options'] = array(1);

?>
