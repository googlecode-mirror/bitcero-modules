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
$modversion['rmnative'] = 1;
$modversion['rmversion'] = array('number'=>0,'revision'=>1,'status'=>-2,'name'=>'Contact Me!');
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

// Bloque Categorias
$modversion['blocks'][] = array(
    'file' => "contact_block.php",
    'name' => _MI_CT_BLOCK,
    'description' => _MI_CT_BLOCKD,
    'show_func' => "contact_block_show",
    'edit_func' => "",
    'template' => 'bk_contact.html'
);

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
