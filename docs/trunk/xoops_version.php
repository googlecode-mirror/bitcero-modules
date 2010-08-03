<?php
// $Id$
// --------------------------------------------------------------
// Rapid Docs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$modversion['name'] = __('Rapid Docs','docs');
$modversion['description'] = __('Create documentation in Xoops, quicky and an advanced way.','docs');
$modversion['rmversion'] = array('number'=>1,'revision'=>0,'status'=>0,'name'=>'Ability Help');
$modversion['rmnative'] = 1;
$modversion['version'] = '1.0';
$modversion['icon32'] = 'images/icon32.png';
$modversion['icon24'] = 'images/icon24.png';
$modversion['icon16'] = 'images/icon16.png';
$modversion['author'] = "BitC3R0";
$modversion['authormail'] = "i.bitcero@gmail.com";
$modversion['authorweb'] = "Red México";
$modversion['authorurl'] = "http://www.redmexico.com.mx";
$modversion['credits'] = "Red México";
$modversion['help'] = "http://exmsystem.net/docs/manual-de-ability-help";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 0;
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "docs";
$modversion['icon48'] = "images/logo.png";
$modversion['onUninstall']="include/uninstall.php";
$modversion['updatable'] = 1;
$modversion['updateurl'] = 'http://redmexico.com.mx/modules/vcontrol/check.php?id=8';

// Administración
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

$modversion['hasMain'] = 1;

// Busqueda
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.php";
$modversion['search']['func'] = "ahelpSearch";

//Templates
$modversion['templates'][0]['file'] = 'ahelp_header.html';
$modversion['templates'][0]['description'] = '';
$modversion['templates'][1]['file'] = 'ahelp_footer.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'ahelp_index.html';
$modversion['templates'][2]['description'] = '';
$modversion['templates'][3]['file'] = 'ahelp_resources.html';
$modversion['templates'][3]['description'] = '';
$modversion['templates'][4]['file'] = 'ahelp_section.html';
$modversion['templates'][4]['description'] = '';
$modversion['templates'][5]['file'] = 'ahelp_quickindex.html';
$modversion['templates'][5]['description'] = '';
$modversion['templates'][6]['file'] = 'ahelp_resindex.html';
$modversion['templates'][6]['description'] = '';
$modversion['templates'][7]['file'] = 'ahelp_singles.html';
$modversion['templates'][7]['description'] = '';
$modversion['templates'][8]['file'] = 'ahelp_refslist.html';
$modversion['templates'][8]['description'] = '';
$modversion['templates'][9]['file'] = 'ahelp_printpage.html';
$modversion['templates'][9]['description'] = '';
$modversion['templates'][10]['file'] = 'ahelp_publish.html';
$modversion['templates'][10]['description'] = '';
$modversion['templates'][11]['file'] = 'ahelp_sec.html';
$modversion['templates'][11]['description'] = '';
$modversion['templates'][12]['file'] = 'ahelp_references.html';
$modversion['templates'][12]['description'] = '';
$modversion['templates'][13]['file'] = 'ahelp_figures.html';
$modversion['templates'][13]['description'] = '';
$modversion['templates'][14]['file'] = 'ahelp_viewsec.html';
$modversion['templates'][14]['description'] = '';
$modversion['templates'][15]['file'] = 'ahelp_search.html';
$modversion['templates'][15]['description'] = '';
$modversion['templates'][16]['file'] = 'ahelp_figures_resume.html';
$modversion['templates'][16]['description'] = '';

//Base de datos
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

$modversion['tables'][0] = 'rd_resources';
$modversion['tables'][1] = 'rd_sections';
$modversion['tables'][2] = 'rd_references';
$modversion['tables'][3] = 'rd_figures';
$modversion['tables'][4] = 'rd_votedata';
$modversion['tables'][5] = 'rd_edits';

// Homepage
$modversion['config'][0]['name'] = 'homepage';
$modversion['config'][0]['description'] = '_MI_AH_HOMETEXTD';
$modversion['config'][0]['size'] = 50;
$modversion['config'][0]['title'] = '_MI_AH_HOMETEXT';
$modversion['config'][0]['formtype'] = 'editor';
$modversion['config'][0]['valuetype'] = 'text';
$modversion['config'][0]['default'] ='';
$modversion['config'][0]['order'] = 0;

//Imagen
$modversion['config'][1]['name'] = 'image';
$modversion['config'][1]['description'] = '_MI_AH_DESCIMAGE';
$modversion['config'][1]['size'] = 11;
$modversion['config'][1]['title'] = '_MI_AH_IMAGE';
$modversion['config'][1]['formtype'] = 'textbox';
$modversion['config'][1]['valuetype'] = 'int';
$modversion['config'][1]['default'] =111;
$modversion['config'][1]['order'] = 1;

//Tipo de redimension
$modversion['config'][2]['name'] = 'redim_image';
$modversion['config'][2]['description'] = '';
$modversion['config'][2]['size'] = 10;
$modversion['config'][2]['title'] = '_MI_AH_REDIMIMAGE';
$modversion['config'][2]['formtype'] = 'select';
$modversion['config'][2]['valuetype'] = 'int';
$modversion['config'][2]['default'] =0;
$modversion['config'][2]['order'] = 2;
$modversion['config'][2]['options'] = array('_MI_AH_CROP'=>0,'_MI_AH_REDIM'=>1);

//Tamaño del archivo de imagen
$modversion['config'][3]['name'] = 'size_image';
$modversion['config'][3]['description'] = '_MI_AH_DESCSIZE';
$modversion['config'][3]['size'] = 10;
$modversion['config'][3]['title'] = '_MI_AH_FILE';
$modversion['config'][3]['formtype'] = 'textbox';
$modversion['config'][3]['valuetype'] = 'int';
$modversion['config'][3]['default'] =50;
$modversion['config'][3]['order'] = 3;

//Formato de acceso a información
$modversion['config'][4]['name'] = 'permalinks';
$modversion['config'][4]['description'] = '_MI_AH_DESCACCESS';
$modversion['config'][4]['title'] = '_MI_AH_ACCESS';
$modversion['config'][4]['formtype'] = 'select';
$modversion['config'][4]['valuetype'] = 'int';
$modversion['config'][4]['default'] =0;
$modversion['config'][4]['options'] = array('_MI_AH_PHP'=>0,'_MI_AH_ALPHA'=>1);

$modversion['config'][4]['name'] = 'htpath';
$modversion['config'][18]['description'] = '_MI_AH_BASEPATHD';
$modversion['config'][18]['title'] = '_MI_AH_BASEPATH';
$modversion['config'][18]['formtype'] = 'textbox';
$modversion['config'][18]['valuetype'] = 'text';
$modversion['config'][18]['default'] ='/modules/ahelp';
$modversion['config'][18]['order'] = 4;


//Editor de Título
$modversion['config'][5]['name'] = 'title';
$modversion['config'][5]['title'] = '_MI_AH_TITLE';
$modversion['config'][5]['description'] = '_MI_AH_TITLE_DESC';
$modversion['config'][5]['formtype'] = 'textbox';
$modversion['config'][5]['valuetype'] = 'text';
$modversion['config'][5]['size'] = 50;
$modversion['config'][5]['default'] ='';
$modversion['config'][5]['order'] = 5;

//Número de publicaciones a mostrar en lista
$modversion['config'][6]['name'] = 'public_limit';
$modversion['config'][6]['title'] = '_MI_AH_PUBLIC';
$modversion['config'][6]['description'] = '_MI_AH_DESCPUBLIC';
$modversion['config'][6]['formtype'] = 'textbox';
$modversion['config'][6]['valuetype'] = 'int';
$modversion['config'][6]['size'] = 10;
$modversion['config'][6]['default'] =10;
$modversion['config'][6]['order'] = 6;

//Contenidos recientes o populares
$modversion['config'][7]['name'] = 'text_type';
$modversion['config'][7]['title'] = '_MI_AH_PUBLICTYPE';
$modversion['config'][7]['description'] = '_MI_AH_DESCPUBLICTYPE';
$modversion['config'][7]['formtype'] = 'select';
$modversion['config'][7]['valuetype'] = 'int';
$modversion['config'][7]['default'] =0;
$modversion['config'][7]['order'] = 7;
$modversion['config'][7]['options'] = array('_MI_AH_RECENT'=>0,'_MI_AH_POPULAR'=>1,'_MI_AH_VOTES'=>2);

//Modificaciones recientes
$modversion['config'][19]['name'] = 'modified_limit';
$modversion['config'][19]['title'] = '_MI_AH_MODLIMIT';
$modversion['config'][19]['description'] = '';
$modversion['config'][19]['formtype'] = 'textbox';
$modversion['config'][19]['valuetype'] = 'text';
$modversion['config'][19]['default'] =5;
$modversion['config'][19]['order'] = 8;
$modversion['config'][19]['size'] = 10;

//Total de lecturas recomendadas a visualizar en pagina frontal
$modversion['config'][8]['name'] = 'recommend_limit';
$modversion['config'][8]['title'] = '_MI_AH_RECOMMEND';
$modversion['config'][8]['description'] = '_MI_AH_DESCRECOMMEND';
$modversion['config'][8]['formtype'] = 'textbox';
$modversion['config'][8]['valuetype'] = 'int';
$modversion['config'][8]['size'] = 10;
$modversion['config'][8]['default'] =10;
$modversion['config'][8]['order'] = 8;

// FORMATO DE LA INFORMACIÓN
// Ancho del indice
$modversion['config'][9]['name'] = 'index_width';
$modversion['config'][9]['title'] = '_MI_AH_INDEXWIDTH';
$modversion['config'][9]['description'] = '';
$modversion['config'][9]['formtype'] = 'textbox';
$modversion['config'][9]['valuetype'] = 'int';
$modversion['config'][9]['size'] = 10;
$modversion['config'][9]['default'] = 250;
$modversion['config'][9]['order'] = 9;

// Méotdo para las referencias
$modversion['config'][10]['name'] = 'refs_method';
$modversion['config'][10]['title'] = '_MI_AH_REFSMETHOD';
$modversion['config'][10]['description'] = '';
$modversion['config'][10]['formtype'] = 'select';
$modversion['config'][10]['valuetype'] = 'int';
$modversion['config'][10]['default'] = 0;
$modversion['config'][10]['options'] = array(_MI_AH_REFSMETHODBOTTOM=>0,_MI_AH_REFSMETHODDIV=>1);
$modversion['config'][10]['order'] = 10;

// Méotdo para las referencias
$modversion['config'][11]['name'] = 'refs_color';
$modversion['config'][11]['title'] = '_MI_AH_REFSCOLOR';
$modversion['config'][11]['description'] = '';
$modversion['config'][11]['formtype'] = 'textbox';
$modversion['config'][11]['valuetype'] = 'text';
$modversion['config'][11]['default'] = '#FFFFC0';
$modversion['config'][11]['size'] = 10;
$modversion['config'][11]['order'] = 10;

// Activar opción para imprimir
$modversion['config'][12]['name'] = 'print';
$modversion['config'][12]['title'] = '_MI_AH_PRINT';
$modversion['config'][12]['description'] = '';
$modversion['config'][12]['formtype'] = 'yesno';
$modversion['config'][12]['valuetype'] = 'int';
$modversion['config'][12]['default'] = 1;
$modversion['config'][12]['order'] = 11;

// Crear Recursos
$modversion['config'][13]['name'] = 'createres';
$modversion['config'][13]['title'] = '_MI_AH_CREATERES';
$modversion['config'][13]['description'] = '';
$modversion['config'][13]['formtype'] = 'yesno';
$modversion['config'][13]['valuetype'] = 'int';
$modversion['config'][13]['default'] = 0;
$modversion['config'][13]['order'] = 12;

// Crear Recursos
$modversion['config'][14]['name'] = 'create_groups';
$modversion['config'][14]['title'] = '_MI_AH_CREATEGROUPS';
$modversion['config'][14]['description'] = '';
$modversion['config'][14]['formtype'] = 'group_multi';
$modversion['config'][14]['valuetype'] = 'array';
$modversion['config'][14]['default'] = array(XOOPS_GROUP_ADMIN);
$modversion['config'][14]['order'] = 13;


//Aprobar automáticamente la publicación
$modversion['config'][15]['name'] = 'approved';
$modversion['config'][15]['title'] = '_MI_AH_APPROVED';
$modversion['config'][15]['description'] = '';
$modversion['config'][15]['formtype'] = 'yesno';
$modversion['config'][15]['valuetype'] = 'int';
$modversion['config'][15]['default'] = 0;
$modversion['config'][15]['order'] = 14;


//Dirección de correo en el que se enviarán la notificación de nueva publicacion no aprobada
global $xoopsConfig;
$modversion['config'][16]['name'] = 'mail';
$modversion['config'][16]['title'] = '_MI_AH_MAIL';
$modversion['config'][16]['description'] = '_MI_AH_DESCMAIL';
$modversion['config'][16]['formtype'] = 'textbox';
$modversion['config'][16]['valuetype'] = 'text';
$modversion['config'][16]['default'] = $xoopsConfig['adminmail'];
$modversion['config'][16]['size'] = 50;
$modversion['config'][16]['order'] = 15;

//Limite de publicaciones a visualizar en búsqueda
$modversion['config'][17]['name'] = 'search_limit';
$modversion['config'][17]['title'] = '_MI_AH_SEARCH';
$modversion['config'][17]['description'] = '_MI_AH_DESCSEARCH';
$modversion['config'][17]['formtype'] = 'textbox';
$modversion['config'][17]['valuetype'] = 'int';
$modversion['config'][17]['size'] = 10;
$modversion['config'][17]['default'] =15;
$modversion['config'][17]['order'] = 16;


// Bloques
$modversion['blocks'][1]['file'] = "ahelp_resources.php";
$modversion['blocks'][1]['name'] = '_MI_AH_BKRES';
$modversion['blocks'][1]['description'] = '_MI_AH_BKRES_DESC';
$modversion['blocks'][1]['show_func'] = "ahelp_block_resources";
$modversion['blocks'][1]['edit_func'] = "ahelp_block_resources_edit";
$modversion['blocks'][1]['template'] = 'ahelp_bk_resources.html';
$modversion['blocks'][1]['options'] = array(2,'recents',5,0,1,1,0,0);

// Index
$modversion['blocks'][2]['file'] = "ahelp_index.php";
$modversion['blocks'][2]['name'] = '_MI_AH_BKINDEX';
$modversion['blocks'][2]['description'] = '_MI_AH_BKINDEXD';
$modversion['blocks'][2]['show_func'] = "ahelp_block_index";
$modversion['blocks'][2]['edit_func'] = "";
$modversion['blocks'][2]['template'] = 'ahelp_bk_index.html';
$modversion['blocks'][2]['options'] = array();

//Páginas del módulo
$modversion['subpages']['index'] = _MI_AH_INDEX;
$modversion['subpages']['resource'] = _MI_AH_RESOURCE;
$modversion['subpages']['content'] = _MI_AH_CONTENT;
$modversion['subpages']['edit'] = _MI_AH_EDIT;
$modversion['subpages']['publish'] = _MI_AH_PUBLISH;
$modversion['subpages']['search'] = _MI_AH_PSEARCH;
