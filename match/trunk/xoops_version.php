<?php
// $Id$
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('match');

$modversion['name'] = "Matches";
$modversion['description'] = __('A module to manage matches','match');
$modversion['version'] = '0.1';
$modversion['icon32'] = 'images/icon32.png';
$modversion['icon24'] = 'images/icon24.png';
$modversion['author'] = "BitC3R0";
$modversion['authormail'] = "i.bitcero@gmail.com";
$modversion['authorweb'] = "Red México";
$modversion['authorurl'] = "http://www.redmexico.com.mx";
$modversion['credits'] = "Red México";
$modversion['help'] = "http://www.redmexico.com.mx/docs/mywords/";
$modversion['license'] = "GPL v2";
$modversion['official'] = 0;
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "match";
$modversion['icon48'] = "images/logo.png";
$modversion['icon16'] = "images/icon16.png";
$modversion['rmnative'] = 1;
$modversion['rmversion'] = array('number'=>0,'revision'=>30,'status'=>-2,'name'=>'Matches');

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

$modversion['hasMain'] = 1;

// MySQL File
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.php";
$modversion['search']['func'] = "mywords_search";

// Templates
$modversion['templates'] = array(
    0 => array('file' => 'mch_index.html', 'description' => ''),
    1 => array('file' => 'mch_rol.html', 'description' => '')
);

// Tables
$modversion['tables'] = array(
    "mch_categories",
    "mch_teams",
    'mch_players',
    'mch_coaches',
    'mch_champs',
    'mch_fields',
    'mch_role',
    'mch_score'
);

// Blocks
// Ranking
$modversion['blocks'][] = array(
    'file' => 'mch_ranking.php',
    'name' => __('Ranking', 'match'),
    'description' => __('Show the positions in ranking for all teams','match'),
    'show_func' => 'mch_ranking_bkshow',
    'edit_func' => 'mch_ranking_bkedit',
    'template' => 'mch_bk_ranking.html',
    'options' => '0|0|6'
);

// Roleplay
$modversion['blocks'][] = array(
    'file' => 'mch_roleplay.php',
    'name' => __('Role Play', 'match'),
    'description' => __('Show the role play for a single category','match'),
    'show_func' => 'mch_role_bkshow',
    'edit_func' => 'mch_role_bkedit',
    'template' => 'mch_bk_roleplay.html',
    'options' => '0|0|6'
);

// Configuration
$modversion['config'][] = array(
    'name' => 'title',
    'title' => '_MI_MCH_TITLE',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => __('Match','match')
);

$modversion['config'][] = array(
    'name' => 'urlmode',
    'title' => '_MI_MCH_URLMODE',
    'description' => '_MI_MCH_URLMODED',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0
);

$modversion['config'][] = array(
    'name' => 'htbase',
    'title' => '_MI_MCH_BASEDIR',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => '/modules/match'
);

$modversion['config'][] = array(
    'name' => 'logo_file_size',
    'title' => '_MI_MCH_LOGOFILESIZE',
    'description' => '_MI_MCH_LOGOFILESIZED',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => '500'
);

$modversion['config'][] = array(
    'name' => 'logo_size',
    'title' => '_MI_MCH_LOGOSIZE',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => '150'
);

// Photo size
$modversion['config'][] = array(
    'name' => 'photo_size',
    'title' => '_MI_MCH_PHOTOSIZE',
    'description' => '_MI_MCH_PHOTOSIZED',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => '500'
);
$modversion['config'][] = array(
    'name' => 'th_size',
    'title' => '_MI_MCH_THSIZE',
    'description' => '_MI_MCH_THSIZED',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => '150'
);

// Year range
$modversion['config'][] = array(
    'name' => 'year_range',
    'title' => '_MI_MCH_YEARRANGE',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => '1990:2020'
);

// Role play block
$modversion['config'][] = array(
    'name' => 'rolenum',
    'title' => '_MI_MCH_ROLENUM',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => '10'
);

// Rankign block
$modversion['config'][] = array(
    'name' => 'ranknum',
    'title' => '_MI_MCH_RANKNUM',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => '6'
);

// Subpáginas
/*$modversion['subpages'] = array('index'=>,
							    'post'=>_MI_MW_SPPOST,
							    'catego'=>_MI_MW_SPCATEGO,
							    'author'=>_MI_MW_SPAUTHOR,
							    'submit'=>_MI_MW_SPSUBMIT);*/
