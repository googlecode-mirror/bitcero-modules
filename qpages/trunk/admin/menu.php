<?php
// $Id$
// --------------------------------------------------------------
// Quick Pages
// Create simple pages easily and quickly
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$adminmenu[0]['title'] = __('Dashboard','qpages');
$adminmenu[0]['link'] = "admin/index.php";
$adminmenu[0]['icon'] = '../images/status.png';
$adminmenu[0]['location'] = 'dashboard';

$adminmenu[1]['title'] = __('Categories','qpages');
$adminmenu[1]['link'] = "admin/cats.php";
$adminmenu[1]['icon'] = '../images/cats.png';
$adminmenu[1]['location'] = 'categories';

$adminmenu[2]['title'] = __('Pages','qpages');
$adminmenu[2]['link'] = "admin/pages.php";
$adminmenu[2]['icon'] = '../images/pages.png';
