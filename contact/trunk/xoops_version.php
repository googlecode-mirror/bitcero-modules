<?php
// $Id$
// --------------------------------------------------------------
// Contact
// A simple contact module for Xoops
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$modversion['name'] = "Contact Me!";
$modversion['rmnative'] = 1.036;
$modversion['rmversion'] = array('number'=>0,'revision'=>36,'status'=>0,'name'=>'Contact Me!');
$modversion['version'] = 0.001;
$modversion['description'] = _MI_CT_DESC;
$modversion['author'] = "BitC3R0";
$modversion['authormail'] = "i.bitcero@gmail.com";
$modversion['authorweb'] = "Red México";
$modversion['authorurl'] = "http://www.redmexico.com.mx";
$modversion['credits'] = "Red México";
$modversion['help'] = "http://redmexico.com.mx/docs/quickpages";
$modversion['license'] = "See GPL License";
$modversion['official'] = 0;
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "contact";
$modversion['icon16'] = "images/logo16.png";
$modversion['icon24'] = "images/logo24.png";
$modversion['icon32'] = "images/logo32.png";
$modversion['icon48'] = "images/logo48.png";

$modversion['social'][0] = array('title' => __('BitCERO Twitter', 'rmcommon'),'type' => 'twitter','url' => 'http://www.twitter.com/bitcero/');
$modversion['social'][1] = array('title' => __('BitCERO LinkedIn', 'rmcommon'),'type' => 'linkedin','url' => 'http://www.linkedin.com/bitcero/');
$modversion['social'][2] = array('title' => __('Red México Twitter', 'rmcommon'),'type' => 'twitter','url' => 'http://www.twitter.com/redmexico/');
$modversion['social'][3] = array('title' => __('Red México Facebook', 'rmcommon'),'type' => 'facebook','url' => 'http://www.facebook.com/redmexico/');

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Menu Principal
$modversion['hasMain'] = 1;

// Database
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][0] = "contactme";

// Plantillas del Módulo
$modversion['templates'][1]['file'] = 'contact_form.html';
$modversion['templates'][1]['description'] = '';

// Search
$modversion['hasSearch'] = 0;

// MODULE SETTINGS //

// Module Email
$modversion['config'][] = array(
    'name' => 'mail',
    'title' => '_MI_CT_EMAIL',
    'description' => '_MI_CT_EMAILD',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => $xoopsConfig['adminmail']
);

// Welcome message
$modversion['config'][] = array(
    'name' => 'information',
    'title' => '_MI_CT_INFO',
    'description' => '',
    'formtype' => 'textarea',
    'valuetype' => 'text',
    'default' => ''
);

// Module URL
$modversion['config'][] = array(
    'name' => 'url',
    'title' => '_MI_CT_URL',
    'description' => '_MI_CT_URLD',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => XOOPS_URL.'/modules/contact/'
);

// Pagination
$modversion['config'][] = array(
    'name' => 'limit',
    'title' => '_MI_CT_LIMIT',
    'description' => '_MI_CT_LIMITD',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 10
);

// Pagination
$modversion['config'][] = array(
    'name' => 'quote',
    'title' => '_MI_CT_QUOTE',
    'description' => '_MI_CT_QUOTE',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 1
);
