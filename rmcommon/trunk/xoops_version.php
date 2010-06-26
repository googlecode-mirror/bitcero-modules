<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$modversion['name'] = 'Common Utilities';
$modversion['version'] = 2.085;
$modversion['releasedate'] = "08 Jan 2010";
$modversion['status'] = "Stable";
$modversion['description'] = 'Container a lot of clases and functions used by Red México Modules';
$modversion['author'] = "BitC3R0";
$modversion['authormail'] = "i.bitcero@gmail.com";
$modversion['authorweb'] = "Red México";
$modversion['authorurl'] = "http://redmexico.com.mx";
$modversion['credits'] = "Red México, BitC3R0";
$modversion['help'] = "";
$modversion['license'] = "GPL 2";
$modversion['official'] = 0;
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "rmcommon";
$modversion['icon16'] = "images/rmc16.png";
$modversion['icon24'] = 'images/rmc24.png';
$modversion['rmnative'] = 1;
$modversion['rmversion'] = array('number'=>2,'revision'=>75,'status'=>-3,'name'=>'Common Utilities');

$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "index.php";
$modversion['adminmenu'] = "menu.php";

$modversion['hasMain'] = 1;

$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

$modversion['tables'][0] = 'rmc_img_cats';
$modversion['tables'][1] = 'rmc_comments';
$modversion['tables'][2] = 'rmc_comusers';
$modversion['tables'][3] = 'rmc_images';
$modversion['tables'][4] = 'rmc_plugins';
$modversion['tables'][5] = 'rmc_settings';

// Templates
$modversion['templates'][1]['file'] = 'rmc_comments_display.html';
$modversion['templates'][1]['description'] = 'Comments list';
$modversion['templates'][2]['file'] = 'rmc_comments_form.html';
$modversion['templates'][2]['description'] = 'Shows the comments form';

/**
* Language
*/
$modversion['config'][1]['name'] = 'lang';
$modversion['config'][1]['title'] = '_MI_RMC_LANG';
$modversion['config'][1]['description'] = '';
$modversion['config'][1]['formtype'] = 'textbox';
$modversion['config'][1]['valuetype'] = 'text';
$modversion['config'][1]['default'] = 'en_US';

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

// Comments
$modversion['config'][12]['name'] = 'enable_comments';
$modversion['config'][12]['title'] = '_MI_RMC_ENABLECOMS';
$modversion['config'][12]['description'] = '';
$modversion['config'][12]['formtype'] = 'yesno';
$modversion['config'][12]['valuetype'] = 'int';
$modversion['config'][12]['default'] = 1;

$modversion['config'][13]['name'] = 'anonymous_comments';
$modversion['config'][13]['title'] = '_MI_RMC_ANONCOMS';
$modversion['config'][13]['description'] = '';
$modversion['config'][13]['formtype'] = 'yesno';
$modversion['config'][13]['valuetype'] = 'int';
$modversion['config'][13]['default'] = 1;

$modversion['config'][14]['name'] = 'approve_reg_coms';
$modversion['config'][14]['title'] = '_MI_RMC_APPROVEREG';
$modversion['config'][14]['description'] = '';
$modversion['config'][14]['formtype'] = 'yesno';
$modversion['config'][14]['valuetype'] = 'int';
$modversion['config'][14]['default'] = 1;

$modversion['config'][15]['name'] = 'approve_anon_coms';
$modversion['config'][15]['title'] = '_MI_RMC_APPROVEANON';
$modversion['config'][15]['description'] = '';
$modversion['config'][15]['formtype'] = 'yesno';
$modversion['config'][15]['valuetype'] = 'int';
$modversion['config'][15]['default'] = 0;

$modversion['config'][16]['name'] = 'allow_edit';
$modversion['config'][16]['title'] = '_MI_RMC_ALLOWEDIT';
$modversion['config'][16]['description'] = '';
$modversion['config'][16]['formtype'] = 'yesno';
$modversion['config'][16]['valuetype'] = 'int';
$modversion['config'][16]['default'] = 0;

$modversion['config'][17]['name'] = 'edit_limit';
$modversion['config'][17]['title'] = '_MI_RMC_EDITLIMIT';
$modversion['config'][17]['description'] = '';
$modversion['config'][17]['formtype'] = 'textbox';
$modversion['config'][17]['valuetype'] = 'int';
$modversion['config'][17]['default'] = 1;

$modversion['config'][18]['name'] = 'mods_number';
$modversion['config'][18]['title'] = '_MI_RMC_MODSNUMBER';
$modversion['config'][18]['description'] = '';
$modversion['config'][18]['formtype'] = 'textbox';
$modversion['config'][18]['valuetype'] = 'int';
$modversion['config'][18]['default'] = 6;

$modversion['config'][19]['name'] = 'theme';
$modversion['config'][19]['title'] = __('Admin theme','rmcommon');
$modversion['config'][19]['description'] = '';
$modversion['config'][19]['formtype'] = 'textbox';
$modversion['config'][19]['valuetype'] = 'text';
$modversion['config'][19]['default'] = 'default';

$modversion['config'][20]['name'] = 'rssimage';
$modversion['config'][20]['title'] = __('Image for rss feeds','rmcommon');
$modversion['config'][20]['description'] = '';
$modversion['config'][20]['formtype'] = 'textbox';
$modversion['config'][20]['valuetype'] = 'text';
$modversion['config'][20]['default'] = XOOPS_URL.'/modules/rmcommon/images/rssimage.png';