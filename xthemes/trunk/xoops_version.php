<?php
// $Id: xoops_version.php 15 2009-09-11 18:16:01Z i.bitcero $
// --------------------------------------------------------------
// I.Themes
// Module for manage themes by Red Mexico
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// License: GPL v2
// --------------------------------------------------------------

$modversion['name'] = 'XThemes';
$modversion['version'] = 1.018;
$modversion['rmnative'] = '1';
$modversion['rmversion'] = array('number'=>1,'revision'=>18,'status'=>0,'name'=>'XThemes');
$modversion['description'] = 'A module to manage themes from Red México';
$modversion['credits'] = "Eduardo Cortés <i.bitcero@gmail.com>";
$modversion['author'] = "BitC3R0";
$modversion['authormail'] = "i.bitcero@gmail.com";
$modversion['authorweb'] = "Red México";
$modversion['authorurl'] = "http://www.redmexico.com.mx";
$modversion['help'] = "";
$modversion['license'] = "GPLv2";
$modversion['official'] = 0;
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "xthemes";
$modversion['icon16'] = "images/xthemes.png";
$modversion['icon24'] = 'images/xthemes24.png';

$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

$modversion['tables'][0] = "xtheme_config";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "index.php";
$modversion['adminmenu'] = "menu.php";

$modversion['hasMain'] = 0;
$modversion['hasSearch'] = 0;