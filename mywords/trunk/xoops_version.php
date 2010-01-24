<?php
// $Id: xoops_version.php 53 2009-09-18 06:02:06Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('mywords','admin_');

$modversion['name'] = "MyWords";
$modversion['description'] = _MI_MW_DESC;
$modversion['version'] = '2.0';
$modversion['icon32'] = 'images/icon32.png';
$modversion['icon24'] = 'images/icon24.png';
$modversion['author'] = "BitC3R0";
$modversion['authorlink'] = "mailto:bitc3r0@gmail.com";
$modversion['authorweb'] = "Red México";
$modversion['authorurl'] = "http://www.redmexico.com.mx";
$modversion['url'] = 'www.exmsystem.net';
$modversion['credits'] = "Red México";
$modversion['help'] = "http://www.redmexico.com.mx/modules/ahelp/";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 1;
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "mywords";
$modversion['icon48'] = "images/logo.png";
$modversion['icon16'] = "images/icon16.png";
$modversion['deflang'] = 'spanish';
$modversion['updatable'] = 1;
$modversion['updateurl'] = 'http://redmexico.com.mx/modules/vcontrol/check.php?id=5';
$modversion['rmnative'] = 1;

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Archivo SQL
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.func.php";
$modversion['search']['func'] = "mywords_search";

// Tablas
$modversion['tables'][0] = "mw_categos";
$modversion['tables'][1] = "mw_posts";
$modversion['tables'][2] = "mw_catpost";
$modversion['tables'][3] = "mw_trackbacks";
$modversion['tables'][4] = "mw_editors";
$modversion['tables'][5] = "mw_bookmarks";
$modversion['tables'][6] = "mw_meta";
$modversion['tables'][7] = "mw_tags";

// Plantillas
$modversion['templates'][1]['file'] = 'mywords_index.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'mywords_post.html';
$modversion['templates'][2]['description'] = '';
$modversion['templates'][3]['file'] = 'mywords_commentform.html';
$modversion['templates'][3]['description'] = '';
$modversion['templates'][4]['file'] = 'mywords_comment.html';
$modversion['templates'][4]['description'] = '';
$modversion['templates'][5]['file'] = 'mywords_cats.html';
$modversion['templates'][5]['description'] = '';
$modversion['templates'][6]['file'] = 'mywords_author.html';
$modversion['templates'][6]['description'] = '';
$modversion['templates'][7]['file'] = 'mywords_trackback.html';
$modversion['templates'][7]['description'] = '';
$modversion['templates'][8]['file'] = 'mywords_pagenav.html';
$modversion['templates'][8]['description'] = '';
$modversion['templates'][9]['file'] = 'mywords_submit_form.html';
$modversion['templates'][9]['description'] = '';
$modversion['templates'][10]['file'] = 'mywords_single_post.html';
$modversion['templates'][10]['description'] = '';
// Plantillas Administrativas
$modversion['templates'][11]['file'] = 'admin/mywords_categos.html';
$modversion['templates'][11]['description'] = '';
$modversion['templates'][12]['file'] = 'admin/mywords_editors.html';
$modversion['templates'][12]['description'] = '';
$modversion['templates'][13]['file'] = 'admin/mywords_theindex.html';
$modversion['templates'][13]['description'] = '';
$modversion['templates'][14]['file'] = 'admin/mywords_posts.html';
$modversion['templates'][14]['description'] = '';
$modversion['templates'][15]['file'] = 'admin/mywords_replacements.html';
$modversion['templates'][15]['description'] = '';
$modversion['templates'][16]['file'] = 'admin/mywords_trackbacks.html';
$modversion['templates'][16]['description'] = '';
$modversion['templates'][17]['file'] = 'admin/mywords_bookmarks.html';
$modversion['templates'][17]['description'] = '';

// Formato de los enlaces
$modversion['config'][0]['name'] = 'permalinks';
$modversion['config'][0]['title'] = '_MI_MW_PERMAFORMAT';
$modversion['config'][0]['description'] = '_MI_MW_PERMA_DESC';
$modversion['config'][0]['formtype'] = 'select';
$modversion['config'][0]['valuetype'] = 'int';
$modversion['config'][0]['default'] = 1;
$modversion['config'][0]['options'] = array(_MI_MW_PERMA_DEF=>1, _MI_MW_PERMA_DATE=>2, _MI_MW_PERMA_NUMS=>3);

$modversion['config'][1]['name'] = 'basepath';
$modversion['config'][1]['title'] = '_MI_MW_BASEPATH';
$modversion['config'][1]['description'] = '_MI_MW_BASEPATHD';
$modversion['config'][1]['formtype'] = 'textbox';
$modversion['config'][1]['valuetype'] = 'text';
$modversion['config'][1]['size'] = '50';
$modversion['config'][1]['default'] = '/blog';
$modversion['config'][1]['order'] = 0;

// Tags limit
$modversion['config'][2]['name'] = 'tags_widget_limit';
$modversion['config'][2]['title'] = '_MI_MW_WIDGETTAGS';
$modversion['config'][2]['description'] = '';
$modversion['config'][2]['formtype'] = 'textbox';
$modversion['config'][2]['valuetype'] = 'int';
$modversion['config'][2]['default'] = 10;

// Posts list limit number
$modversion['config'][3]['name'] = 'posts_limit';
$modversion['config'][3]['title'] = '_MI_MW_PPP';
$modversion['config'][3]['description'] = '';
$modversion['config'][3]['formtype'] = 'textbox';
$modversion['config'][3]['valuetype'] = 'int';
$modversion['config'][3]['default'] = 10;

// CSS File
$modversion['config'][4]['name'] = 'use_css';
$modversion['config'][4]['title'] = '_MI_MW_CSS';
$modversion['config'][4]['description'] = '';
$modversion['config'][4]['formtype'] = 'yesno';
$modversion['config'][4]['valuetype'] = 'int';
$modversion['config'][4]['default'] = 1;

// CSS Path
$modversion['config'][5]['name'] = 'css_file';
$modversion['config'][5]['title'] = '_MI_MW_CSSFILE';
$modversion['config'][5]['description'] = '';
$modversion['config'][5]['formtype'] = 'textbox';
$modversion['config'][5]['valuetype'] = 'text';
$modversion['config'][5]['default'] = XOOPS_URL.'/modules/mywords/css/main.css';

/*
// Envío de Artículo
$modversion['config'][1]['name'] = 'submit';
$modversion['config'][1]['title'] = '_MI_MW_ALLOWSUBMIT';
$modversion['config'][1]['description'] = '';
$modversion['config'][1]['formtype'] = 'yesno';
$modversion['config'][1]['valuetype'] = 'int';
$modversion['config'][1]['default'] = 1;

// Permitir envio a usuarios anonimos
$modversion['config'][2]['name'] = 'anonimo';
$modversion['config'][2]['title'] = '_MI_MW_ALLOWANONYM';
$modversion['config'][2]['description'] = '';
$modversion['config'][2]['formtype'] = 'yesno';
$modversion['config'][2]['valuetype'] = 'int';
$modversion['config'][2]['default'] = 0;

// Autoaprovación
$modversion['config'][3]['name'] = 'aproveuser';
$modversion['config'][3]['title'] = '_MI_MW_USERAUTO';
$modversion['config'][3]['description'] = '';
$modversion['config'][3]['formtype'] = 'yesno';
$modversion['config'][3]['valuetype'] = 'int';
$modversion['config'][3]['default'] = 0;

$modversion['config'][4]['name'] = 'aproveano';
$modversion['config'][4]['title'] = '_MI_MW_ANOAUTO';
$modversion['config'][4]['description'] = '';
$modversion['config'][4]['formtype'] = 'yesno';
$modversion['config'][4]['valuetype'] = 'int';
$modversion['config'][4]['default'] = 0;

// Permitir recepción de Pings
$modversion['config'][5]['name'] = 'pings';
$modversion['config'][5]['title'] = '_MI_MW_ALLOWPINGS';
$modversion['config'][5]['description'] = '_MI_MW_ALLOWPINGS_DESC';
$modversion['config'][5]['formtype'] = 'yesno';
$modversion['config'][5]['valuetype'] = 'int';
$modversion['config'][5]['default'] = 1;

// Archivos por página
$modversion['config'][6]['name'] = 'limite';
$modversion['config'][6]['title'] = '_MI_MW_LIMITE';
$modversion['config'][6]['description'] = '';
$modversion['config'][6]['formtype'] = 'textbox';
$modversion['config'][6]['valuetype'] = 'int';
$modversion['config'][6]['default'] = 10;
$modversion['config'][6]['size'] = 5;

// Usar hoja de estilos del módulo
$modversion['config'][7]['name'] = 'css';
$modversion['config'][7]['title'] = '_MI_MW_CSS';
$modversion['config'][7]['description'] = '';
$modversion['config'][7]['formtype'] = 'yesno';
$modversion['config'][7]['valuetype'] = 'int';
$modversion['config'][7]['default'] = 1;

// Longitud en carácteres del texto para trackbacks
$modversion['config'][8]['name'] = 'tracklen';
$modversion['config'][8]['title'] = '_MI_MW_TRACKLEN';
$modversion['config'][8]['description'] = '';
$modversion['config'][8]['formtype'] = 'textbox';
$modversion['config'][8]['valuetype'] = 'int';
$modversion['config'][8]['default'] = 100;

// Tamaño de la imágen para el bloque de noticias
$modversion['config'][9]['name'] = 'imgsize';
$modversion['config'][9]['title'] = '_MI_MW_BIMGSIZE';
$modversion['config'][9]['description'] = '_MI_MW_BIMGSIZE_DESC';
$modversion['config'][9]['formtype'] = 'textbox';
$modversion['config'][9]['valuetype'] = 'array';
$modversion['config'][9]['default'] = 50|50;
$modversion['config'][9]['size'] = 5;

// Tamaño de la imágen para el bloque de noticias
$modversion['config'][10]['name'] = 'filesize';
$modversion['config'][10]['title'] = '_MI_MW_FILESIZE';
$modversion['config'][10]['description'] = '';
$modversion['config'][10]['formtype'] = 'textbox';
$modversion['config'][10]['valuetype'] = 'int';
$modversion['config'][10]['default'] = '100';
$modversion['config'][10]['size'] = 5;

// Imágen por Defecto
$modversion['config'][11]['name'] = 'defimg';
$modversion['config'][11]['title'] = '_MI_MW_DEFIMG';
$modversion['config'][11]['description'] = '_MI_MW_BIMGSIZE_DESC';
$modversion['config'][11]['formtype'] = 'textbox';
$modversion['config'][11]['valuetype'] = 'text';
$modversion['config'][11]['size'] = 50;
$modversion['config'][11]['default'] = XOOPS_URL.'/modules/mywords/images/defimg.png';

// Descripción para la sindicación
$modversion['config'][12]['name'] = 'rssdesc';
$modversion['config'][12]['title'] = '_MI_MW_RSSDESC';
$modversion['config'][12]['description'] = '';
$modversion['config'][12]['formtype'] = 'editor';
$modversion['config'][12]['valuetype'] = 'text';
$modversion['config'][12]['size'] = '90%';
$modversion['config'][12]['default'] = '';

*/

// Bloque Categorias
$modversion['blocks'][1]['file'] = "block.cats.php";
$modversion['blocks'][1]['name'] = '_MI_MW_BKCATEGOS';
$modversion['blocks'][1]['description'] = "";
$modversion['blocks'][1]['show_func'] = "mywordsBlockCats";
$modversion['blocks'][1]['edit_func'] = "mywordsBlockCatsEdit";
$modversion['blocks'][1]['template'] = 'bk_mywords_categos.html';
$modversion['blocks'][1]['options'] = "1";

// Bloque Recientes
$modversion['blocks'][2]['file'] = "block.recent.php";
$modversion['blocks'][2]['name'] = '_MI_MW_BKRECENT';
$modversion['blocks'][2]['description'] = "";
$modversion['blocks'][2]['show_func'] = "mywordsBlockRecent";
$modversion['blocks'][2]['edit_func'] = "mywordsBlockRecentEdit";
$modversion['blocks'][2]['template'] = 'bk_mywords_recent.html';
$modversion['blocks'][2]['options'] = "1|50|5|1|d-m-Y|0";

// Sindicación RSS
$modversion['rss']['name'] = '_MI_MW_RSSNAME'; 			// NOmbre del elemento
$modversion['rss']['file'] = 'include/rss.php';			// Archivo donde se localizan las funciones
$modversion['rss']['desc'] = 'mywords_rssdesc';			// Devuelve la descripción del elemento
$modversion['rss']['feed'] = 'mywords_rssfeed';			// Devuelve el menu de opciones del elemento
$modversion['rss']['show'] = 'mywords_rssshow';			// Devuelve el archivo xml

// Subpáginas
$modversion['subpages'] = array('index'=>_MI_MW_SPINDEX,
							    'post'=>_MI_MW_SPPOST,
							    'catego'=>_MI_MW_SPCATEGO,
							    'author'=>_MI_MW_SPAUTHOR,
							    'submit'=>_MI_MW_SPSUBMIT);
