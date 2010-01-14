<?php
// $Id: xoops_version.php 22 2009-09-13 07:42:57Z i.bitcero $
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$modversion['name'] = 'Common Utilities';
$modversion['version'] = 2.00;
$modversion['releasedate'] = "08 Jan 2010";
$modversion['status'] = "Stable";
$modversion['description'] = 'Container a lot of clases and functions used by Red México Modules';
$modversion['author'] = "BitC3R0";
$modversion['credits'] = "Red México, BitC3R0";
$modversion['help'] = "";
$modversion['license'] = "GPL 2";
$modversion['official'] = 0;
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "rmcommon";
$modversion['icon16'] = "images/rmc16.png";
$modversion['icon24'] = 'images/rmc24.png';
$modversion['rmnative'] = 1;

$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "index.php";
$modversion['adminmenu'] = "menu.php";

$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

$modversion['tables'][0] = 'api_events';
$modversion['tables'][1] = 'api_methods';
$modversion['tables'][2] = 'api_objects';
$modversion['tables'][3] = 'rmc_img_cats';

// Templates
$modversion['templates'][1]['file'] = 'rmc_comments_display.html';
$modversion['templates'][1]['description'] = 'Comments list';
$modversion['templates'][2]['file'] = 'rmc_comments_form.html';
$modversion['templates'][2]['description'] = 'Shows the comments form';

/**
* Events file
*/
$modversion['config'][1]['name'] = 'eventsfile';
$modversion['config'][1]['title'] = '_MI_RMC_EVENTSFILE';
$modversion['config'][1]['description'] = '_MI_RMC_EVENTSFILED';
$modversion['config'][1]['formtype'] = 'textbox';
$modversion['config'][1]['valuetype'] = 'text';
$modversion['config'][1]['default'] = XOOPS_CACHE_PATH."/".md5('events').'.php';

// Images store type
$modversion['config'][2]['name'] = 'imagestore';
$modversion['config'][2]['title'] = '_MI_RMC_IMAGESTORE';
$modversion['config'][2]['description'] = '';
$modversion['config'][2]['formtype'] = 'yesno';
$modversion['config'][2]['valuetype'] = 'int';
$modversion['config'][2]['default'] = 1;

// Editor
$modversion['config'][3]['name'] = 'editor_type';
$modversion['config'][3]['title'] = '_MI_RMC_EDITOR';
$modversion['config'][3]['description'] = '';
$modversion['config'][3]['formtype'] = 'select';
$modversion['config'][3]['valuetype'] = 'text';
$modversion['config'][3]['default'] = 'tiny';
$modversion['config'][3]['options'] = array('_MI_RMC_EDITOR_VISUAL'=>'tiny','_MI_RMC_EDITOR_HTML'=>'html','_MI_RMC_EDITOR_XOOPS'=>'xoops','_MI_RMC_EDITOR_SIMPLE'=>'simple');

// Images Categories list limit number
$modversion['config'][4]['name'] = 'catsnumber';
$modversion['config'][4]['title'] = '_MI_RMC_IMGCATSNUMBER';
$modversion['config'][4]['description'] = '';
$modversion['config'][4]['formtype'] = 'textbox';
$modversion['config'][4]['valuetype'] = 'int';
$modversion['config'][4]['default'] = 10;

$modversion['config'][5]['name'] = 'imgsnumber';
$modversion['config'][5]['title'] = '_MI_RMC_IMGSNUMBER';
$modversion['config'][5]['description'] = '';
$modversion['config'][5]['formtype'] = 'textbox';
$modversion['config'][5]['valuetype'] = 'int';
$modversion['config'][5]['default'] = 20;

// Secure Key
$modversion['config'][6]['name'] = 'secretkey';
$modversion['config'][6]['title'] = '_MI_RMC_SECREY';
$modversion['config'][6]['description'] = '_MI_RMC_SECREYD';
$modversion['config'][6]['formtype'] = 'textbox';
$modversion['config'][6]['valuetype'] = 'text';
if (!isset($xoopsSecurity)) $xoopsSecurity = new XoopsSecurity();
$modversion['config'][6]['default'] = $xoopsSecurity->createToken();

// Formato HTML5
$modversion['config'][7]['name'] = 'dohtml';
$modversion['config'][7]['title'] = '_MI_RMC_DOHTML';
$modversion['config'][7]['description'] = '';
$modversion['config'][7]['formtype'] = 'yesno';
$modversion['config'][7]['valuetype'] = 'int';
$modversion['config'][7]['default'] = 1;

$modversion['config'][8]['name'] = 'dosmileys';
$modversion['config'][8]['title'] = '_MI_RMC_DOSMILE';
$modversion['config'][8]['description'] = '';
$modversion['config'][8]['formtype'] = 'yesno';
$modversion['config'][8]['valuetype'] = 'int';
$modversion['config'][8]['default'] = 1;

$modversion['config'][9]['name'] = 'doxcode';
$modversion['config'][9]['title'] = '_MI_RMC_DOXCODE';
$modversion['config'][9]['description'] = '';
$modversion['config'][9]['formtype'] = 'yesno';
$modversion['config'][9]['valuetype'] = 'int';
$modversion['config'][9]['default'] = 1;

$modversion['config'][10]['name'] = 'doimage';
$modversion['config'][10]['title'] = '_MI_RMC_DOIMAGE';
$modversion['config'][10]['description'] = '';
$modversion['config'][10]['formtype'] = 'yesno';
$modversion['config'][10]['valuetype'] = 'int';
$modversion['config'][10]['default'] = 0;

$modversion['config'][11]['name'] = 'dobr';
$modversion['config'][11]['title'] = '_MI_RMC_DOBR';
$modversion['config'][11]['description'] = '';
$modversion['config'][11]['formtype'] = 'yesno';
$modversion['config'][11]['valuetype'] = 'int';
$modversion['config'][11]['default'] = 0;