<?php
// $Id: xoops_version.php 15 2009-09-11 18:16:01Z i.bitcero $
// --------------------------------------------------------------
// I.Themes
// Module for manage themes by Red Mexico
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// License: GPL v2
// --------------------------------------------------------------

$modversion['name'] = 'xThemes Manager';
$modversion['version'] = 1.0;
$modversion['description'] = 'A module to manage themes from TemasWeb.com';
$modversion['credits'] = "Eduardo Cortés <i.bitcero@gmail.com>";
$modversion['author'] = "Eduardo Cortés <i.bitcero@gmail.com>";
$modversion['help'] = "";
$modversion['license'] = "GPLv2";
$modversion['official'] = 0;
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "xthemes";
$modversion['icon16'] = "images/xthemes.png";
$modversion['icon24'] = 'images/xthemes24.png';

$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

$modversion['tables'][0] = 'xtheme_config';

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "index.php";
$modversion['adminmenu'] = "menu.php";

$modversion['hasMain'] = 0;
$modversion['hasSearch'] = 0;