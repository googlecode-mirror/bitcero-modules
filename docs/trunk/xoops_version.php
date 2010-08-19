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
$modversion['templates'][0]['file'] = 'rd_resall.html';
$modversion['templates'][0]['description'] = '';
$modversion['templates'][1]['file'] = 'rd_resindextoc.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'rd_header.html';
$modversion['templates'][2]['description'] = '';

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

/*
$modversion['subpages']['index'] = _MI_AH_INDEX;
$modversion['subpages']['resource'] = _MI_AH_RESOURCE;
$modversion['subpages']['content'] = _MI_AH_CONTENT;
$modversion['subpages']['edit'] = _MI_AH_EDIT;
$modversion['subpages']['publish'] = _MI_AH_PUBLISH;
$modversion['subpages']['search'] = _MI_AH_PSEARCH;
*/
