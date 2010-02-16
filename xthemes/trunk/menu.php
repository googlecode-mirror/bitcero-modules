<?php
// $Id: menu.php 8 2009-08-26 17:00:35Z i.bitcero $
// --------------------------------------------------------------
// I.Themes
// Module for manage themes by Red Mexico
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// License: GPL v2
// --------------------------------------------------------------

defined('XOOPS_ROOT_PATH') or die();

load_mod_locale('xthemes');

$adminmenu[1]['title'] = __('Dashboard','xthemes');
$adminmenu[1]['link'] = 'index.php';
$adminmenu[1]['icon'] = "images/dashboard.png";
$adminmenu[1]['location'] = 'dashboard';

$adminmenu[2]['title'] = __('Settings', 'xthemes');
$adminmenu[2]['link'] = 'index.php?op=config';
$adminmenu[2]['icon'] = "images/settings.png";
$adminmenu[2]['location'] = 'settings';

$adminmenu[3]['title'] = __('Catalog','xthemes');
$adminmenu[3]['link'] = '#';
$adminmenu[3]['icon'] = "images/catalog.png";
$adminmenu[3]['location'] = 'catalog';

$adminmenu[4]['title'] = __('About','xthemes');
$adminmenu[4]['link'] = 'index.php?op=about';
$adminmenu[4]['icon'] = "images/about.png";
$adminmenu[4]['location'] = 'catalog';
