<?php
// $Id: xoops_version.php 649 2011-06-19 06:04:22Z i.bitcero $
// --------------------------------------------------------------
// MiniShop 3
// Module for catalogs
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('shop');

$modversion['name'] = "MiniShop 3";
$modversion['description'] = __('Module to create and manage online catalogs','shop');
$modversion['version'] = '3';
$modversion['icon32'] = 'images/icon32.png';
$modversion['icon24'] = 'images/icon24.png';
$modversion['author'] = "BitC3R0";
$modversion['authormail'] = "i.bitcero@gmail.com";
$modversion['authorweb'] = "Red México";
$modversion['authorurl'] = "http://www.redmexico.com.mx";
$modversion['credits'] = "Red México";
$modversion['help'] = "http://www.redmexico.com.mx/docs/minishop/";
$modversion['license'] = "GPL v2";
$modversion['official'] = 0;
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "shop";
$modversion['icon48'] = "images/icon48.png";
$modversion['icon16'] = "images/icon16.png";
$modversion['rmnative'] = 1;
$modversion['rmversion'] = array('number'=>3,'revision'=>0,'status'=>-2,'name'=>'MiniShop 3');

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

$modversion['hasMain'] = 1;

// MySQL File
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Search
$modversion['hasSearch'] = 0;
//$modversion['search']['file'] = "include/search.php";
//$modversion['search']['func'] = "mywords_search";

// Templates
$modversion['templates'] = array(
    //0 => array('file' => 'mch_index.html', 'description' => ''),
);

// Tables
$modversion['tables'] = array(
    "show_categories",
    'shop_catprods'
);

// Options
$modversion['config'][] = array(
    'name' => 'urlmode',
    'title' => '_MI_MS_URLMODE',
    'description' => '_MI_MS_URLMODED',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0
);

$modversion['config'][] = array(
    'name' => 'htbase',
    'title' => '_MI_MS_BASEDIR',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => '/modules/shop'
);

$modversion['config'][] = array(
    'name' => 'editor',
    'title' => '_MI_MS_EDITOR',
    'description' => '',
    'formtype' => 'select',
    'valuetype' => 'text',
    'default' => 'tiny',
    'options' => array(
        '_MI_MS_EDITOR_VISUAL'=>'tiny',
        '_MI_MS_EDITOR_HTML'=>'html',
        '_MI_MS_EDITOR_XOOPS'=>'xoops',
        '_MI_MS_EDITOR_SIMPLE'=>'simple'
    )
);

$modversion['config'][] = array(
    'name' => 'maxsize',
    'title' => '_MI_MS_MAXSIZE',
    'description' => '_MI_MS_MAXSIZED',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => '1024'
);

// Images
$modversion['config'][] = array(
    'name' => 'imgsize',
    'title' => '_MI_MS_IMGSIZE',
    'description' => '_MI_MS_IMGSIZED',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => '800|800'
);

$modversion['config'][] = array(
    'name' => 'imgredim',
    'title' => '_MI_MS_IMGREDIM',
    'description' => '',
    'formtype' => 'select',
    'valuetype' => 'int',
    'default' => '0',
    'options' => array(__('Resize','shop')=>0,__('Crop to fit width and height','shop')=>1)
);

$modversion['config'][] = array(
    'name' => 'thssize',
    'title' => '_MI_MS_THSSIZE',
    'description' => '_MI_MS_THSSIZED',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => '100|100'
);

$modversion['config'][] = array(
    'name' => 'thsredim',
    'title' => '_MI_MS_THSREDIM',
    'description' => '',
    'formtype' => 'select',
    'valuetype' => 'int',
    'default' => '1',
    'options' => array(__('Resize','shop')=>0,__('Crop to fit width and height','shop')=>1)
);


// Subpáginas
/*$modversion['subpages'] = array('index'=>,
							    'post'=>_MI_MW_SPPOST,
							    'catego'=>_MI_MW_SPCATEGO,
							    'author'=>_MI_MW_SPAUTHOR,
							    'submit'=>_MI_MW_SPSUBMIT);*/
