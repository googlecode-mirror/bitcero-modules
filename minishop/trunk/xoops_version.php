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
    "show_categories"
);

// Blocks
// Ranking
// Subpáginas
/*$modversion['subpages'] = array('index'=>,
							    'post'=>_MI_MW_SPPOST,
							    'catego'=>_MI_MW_SPCATEGO,
							    'author'=>_MI_MW_SPAUTHOR,
							    'submit'=>_MI_MW_SPSUBMIT);*/
