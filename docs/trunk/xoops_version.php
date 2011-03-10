<?php
// $Id$
// --------------------------------------------------------------
// Rapid Docs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('docs');

$modversion['name'] = __('RapidDocs','docs');
$modversion['description'] = __('Create documentation in Xoops, quicky and an advanced way.','docs');
$modversion['rmversion'] = array('number'=>1,'revision'=>36,'status'=>-2,'name'=>__('RapidDocs','docs'));
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

//Base de datos
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

$modversion['tables'][0] = 'rd_resources';
$modversion['tables'][1] = 'rd_sections';
$modversion['tables'][2] = 'rd_references';
$modversion['tables'][3] = 'rd_figures';
$modversion['tables'][4] = 'rd_votedata';
$modversion['tables'][5] = 'rd_edits';

//Formato de acceso a información
$modversion['config'][] = array(
    'name' => 'permalinks',
    'title' => '_MI_RD_URLSMODE',
    'description' => '_MI_RD_URLSMODED',
    'formtype' => 'select',
    'valuetype' => 'int',
    'default' => 0,
    'options' => array(__('PHP Default','docs')=>0,__('Name based','docs')=>1)
);

$modversion['config'][] = array(
    'name' => 'subdomain',
    'title' => '_MI_RD_SUBDOMAIN',
    'description' => '_MI_RD_SUBDOMAIND',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => ''
);

$modversion['config'][] = array(
    'name' => 'htpath',
    'title' => '_MI_RD_BASEPATH',
    'description' => '_MI_RD_BASEPATHD',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => '/modules/ahelp'
);

// Configuración de la generación de Índices
$modversion['config'][] = array(
    'name' => 'display_type',
    'title' => '_MI_RD_DISPLAYMETH',
    'description' => '_MI_RD_DISPLAYMETHD',
    'formtype' => 'select',
    'valuetype' => 'int',
    'default' => 1,
    'options' => array(__('As list','docs')=>0,__('As table','docs')=>1)
);

$modversion['config'][] = array(
    'name' => 'index_cols',
    'title' => '_MI_RD_COLS',
    'description' => '_MI_RD_COLSD',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 3
);

$modversion['config'][] = array(
    'name' => 'index_num',
    'title' => '_MI_RD_NUMRES',
    'description' => '_MI_RD_NUMRESD',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 15
);

$modversion['config'][] = array(
    'name' => 'createres',
    'title' => '_MI_RD_CREATEENABLED',
    'description' => '',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 1
);

$modversion['config'][] = array(
    'name' => 'create_groups',
    'title' => '_MI_RD_CREATENEW',
    'description' => '_MI_RD_CREATENEWD',
    'formtype' => 'group_multi',
    'valuetype' => 'array',
    'default' => 1
);

$modversion['config'][] = array(
    'name' => 'approved',
    'title' => '_MI_RD_APPROVE',
    'description' => '',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0
);

$modversion['config'][] = array(
    'name' => 'attrs',
    'title' => '_MI_RD_ATTRS',
    'description' => '_MI_RD_ATTRSD',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => 'class="figures" style="float: left; margin: 0 10px 5px 0;"'
);


// Bloques
$modversion['blocks'][] = array(
    'file' => 'rd_resources.php',
    'name' => __('Resources','docs'),
    'description' => __('List of resources','docs'),
    'show_func' => 'rd_block_resources',
    'edit_func' => 'rd_block_resources_edit',
    'template' => 'rd_bk_resources.html',
    'options' => "recents|5|0|1|1|0|0"
);

$modversion['blocks'][] = array(
    'file' => 'rd_index.php',
    'name' => __('Resource TOC','docs'),
    'description' => __('Table of content for a specific resource','docs'),
    'show_func' => 'rd_block_index',
    'edit_func' => '',
    'template' => 'rd_bk_index.html',
    'options' => ''
);
