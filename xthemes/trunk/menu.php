<?php
// $Id: menu.php 8 2009-08-26 17:00:35Z i.bitcero $
// --------------------------------------------------------------
// I.Themes
// Module for manage themes by Red Mexico
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// License: GPL v2
// --------------------------------------------------------------

defined('XOOPS_ROOT_PATH') or die();

$adminmenu[1]['title'] = 'Themes';
$adminmenu[1]['link'] = 'index.php';

$adminmenu[2]['title'] = 'Plugins';
$adminmenu[2]['link'] = 'index.php?op=plugins';

$adminmenu[3]['title'] = 'Configure';
$adminmenu[3]['link'] = 'index.php?op=config';

$adminmenu[4]['title'] = 'Catalog';
$adminmenu[4]['link'] = '#';

$adminmenu[5]['title'] = 'About';
$adminmenu[5]['link'] = 'index.php?op=about';
