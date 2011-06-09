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

// Configuration
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

// Days of game
$modversion['config'][] = array(
    'name' => 'days',
    'title' => '_MI_MCH_DAYS',
    'description' => '_MI_MCH_DAYSD',
    'formtype' => 'select_multi',
    'valuetype' => 'array',
    'default' => '6|7',
    'options' => array(
        __('Monday','match') => 1,
        __('Tuesday','match') => 2,
        __('Wednesday','match') => 3,
        __('Thursday','match') => 4,
        __('Friday','match') => 5,
        __('Saturday','match') => 6,
        __('Sunday','match') => 7
    )
);

// Plantillas
//$modversion['templates'][1]['file'] = 'mywords_index.html';
//$modversion['templates'][1]['description'] = '';


// Subpáginas
/*$modversion['subpages'] = array('index'=>,
							    'post'=>_MI_MW_SPPOST,
							    'catego'=>_MI_MW_SPCATEGO,
							    'author'=>_MI_MW_SPAUTHOR,
							    'submit'=>_MI_MW_SPSUBMIT);*/
