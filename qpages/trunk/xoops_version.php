<?php
// $Id$
// --------------------------------------------------------------
// Quick Pages
// Create simple pages easily and quickly
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$modversion['name'] = "Quick Pages";
$modversion['rmnative'] = 1;
$modversion['rmversion'] = array('number'=>1,'revision'=>357,'status'=>0,'name'=>'Quick Pages');
$modversion['version'] = 1.357;
$modversion['description'] = _MI_QP_DESC;
$modversion['author'] = "BitC3R0";
$modversion['authormail'] = "i.bitcero@gmail.com";
$modversion['authorweb'] = "Red México";
$modversion['authorurl'] = "http://www.redmexico.com.mx";
$modversion['credits'] = "Red México";
$modversion['help'] = "http://redmexico.com.mx/docs/quickpages";
$modversion['license'] = "See GPL License";
$modversion['official'] = 0;
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "qpages";
$modversion['icon16'] = "images/qpages-16.png";
$modversion['icon24'] = "images/icon24.png";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Menu Principal
$modversion['hasMain'] = 1;
$db =& Database::getInstance();
global $mc;
if (isset($xoopsModule) && $xoopsModule->dirname()=='qpages'){
$result = $db->query("SELECT titulo, titulo_amigo, id_page FROM ".$db->prefix("qpages_pages")." WHERE acceso='1' AND menu='1' ORDER BY titulo");
$i = 1;
while ($row = $db->fetchArray($result)){
	$modversion['sub'][$i]['name'] = $row['titulo'];
	$modversion['sub'][$i]['url'] = $mc['links'] ? "./$row[titulo_amigo]/" : "./page.php?page=$row[titulo_amigo]";
	$i++;
}
}

// Archivo SQL
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Tablas
$modversion['tables'][0] = "qpages_pages";
$modversion['tables'][1] = "qpages_categos";
$modversion['tables'][2] = "qpages_meta";

// Plantillas del Módulo
$modversion['templates'][1]['file'] = 'qpages_index.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'qpages_title.html';
$modversion['templates'][2]['description'] = '';
$modversion['templates'][3]['file'] = 'qpages_categos.html';
$modversion['templates'][3]['description'] = '';
$modversion['templates'][4]['file'] = 'qpages_page.html';
$modversion['templates'][4]['description'] = '';
$modversion['templates'][5]['file'] = 'qpages_homepage.html';
$modversion['templates'][5]['description'] = '';
$modversion['templates'][6]['file'] = 'admin/qp_categos.html';
$modversion['templates'][6]['description'] = '';
$modversion['templates'][7]['file'] = 'admin/qp_navbar.html';
$modversion['templates'][7]['description'] = '';
$modversion['templates'][8]['file'] = 'admin/qp_pages.html';
$modversion['templates'][8]['description'] = '';
$modversion['templates'][9]['file'] = 'admin/qp_theindex.html';
$modversion['templates'][9]['description'] = '';

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.func.php";
$modversion['search']['func'] = "qpages_search";

// Bloque Categorias
$modversion['blocks'][1]['file'] = "qpages_blocks.php";
$modversion['blocks'][1]['name'] = _MI_QP_BKCATS;
$modversion['blocks'][1]['description'] = "";
$modversion['blocks'][1]['show_func'] = "qpagesBlockCategos";
$modversion['blocks'][1]['edit_func'] = "";
$modversion['blocks'][1]['template'] = 'bk_qpages_categos.html';

$modversion['blocks'][2]['file'] = "qpages_blocks.php";
$modversion['blocks'][2]['name'] = _MI_QP_BKPAGES;
$modversion['blocks'][2]['description'] = "";
$modversion['blocks'][2]['show_func'] = "qpagesBlockPages";
$modversion['blocks'][2]['edit_func'] = "qpagesBlockPagesEdit";
$modversion['blocks'][2]['template'] = 'bk_qpages_pages.html';
$modversion['blocks'][2]['options'] = "0|10";


$modversion['config'][1]['name'] = 'links';
$modversion['config'][1]['title'] = '_MI_QP_CNFLINKS';
$modversion['config'][1]['description'] = '_MI_QP_CNFLINKS_DESC';
$modversion['config'][1]['formtype'] = 'select';
$modversion['config'][1]['valuetype'] = 'int';
$modversion['config'][1]['default'] = 0;
$modversion['config'][1]['options'] = array(_MI_QP_CNFLINKS1=>0,_MI_QP_CNFLINKS2=>1);

// URL base path
$modversion['config'][2]['name'] = 'basepath';
$modversion['config'][2]['title'] = '_MI_QP_BASEPATH';
$modversion['config'][2]['description'] = '_MI_QP_BASEPATHD';
$modversion['config'][2]['formtype'] = 'textbox';
$modversion['config'][2]['valuetype'] = 'text';
$modversion['config'][2]['default'] = "";

$modversion['config'][3]['name'] = 'texto';
$modversion['config'][3]['title'] = '_MI_QP_CNFHOMETEXT';
$modversion['config'][3]['description'] = '_MI_QP_CNFHOMETEXT_DESC';
$modversion['config'][3]['formtype'] = 'textarea';
$modversion['config'][3]['valuetype'] = 'text';
$modversion['config'][3]['default'] = "";

// Páginas relacionadas
$modversion['config'][4]['name'] = 'related';
$modversion['config'][4]['title'] = '_MI_QP_SHOWRELATED';
$modversion['config'][4]['description'] = '_MI_QP_SHOWRELATED_DESC';
$modversion['config'][4]['formtype'] = 'yesno';
$modversion['config'][4]['valuetype'] = 'int';
$modversion['config'][4]['default'] = 1;

// Número de páginas relacionadas
$modversion['config'][5]['name'] = 'related_num';
$modversion['config'][5]['title'] = '_MI_QP_RELATEDNUM';
$modversion['config'][5]['description'] = '';
$modversion['config'][5]['formtype'] = 'text';
$modversion['config'][5]['valuetype'] = 'int';
$modversion['config'][5]['default'] = 5;
$modversion['config'][5]['size'] = 5;

// Subpáginas
$modversion['subpages'] = array('index'=>_MI_QP_SUBINDEX,
							    'catego'=>_MI_QP_SUBCATS,
							    'page'=>_MI_QP_SUBPAGE);
