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
$modversion['releasedate'] = "Mon: 26 July 2004";
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

$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "index.php";
$modversion['adminmenu'] = "menu.php";

$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

$modversion['tables'][0] = 'api_events';
$modversion['tables'][1] = 'api_methods';
$modversion['tables'][2] = 'api_objects';
$modversion['tables'][3] = 'rmc_img_cats';

/**
* Events file
*/
$modversion['config'][1]['name'] = 'eventsfile';
$modversion['config'][1]['title'] = '_MI_RMC_EVENTSFILE';
$modversion['config'][1]['description'] = '_MI_RMC_EVENTSFILED';
$modversion['config'][1]['formtype'] = 'textbox';
$modversion['config'][1]['valuetype'] = 'text';
$modversion['config'][1]['default'] = XOOPS_CACHE_PATH."/".md5('events').'.php';
