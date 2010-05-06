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
$adminmenu[2]['location'] = 'pages';

$cat = rmc_server_var($_REQUEST, 'cat', '');

$options[] = array(
    'title'     => __('List','qpages'),
    'link'      => 'pages.php?cat='.$cat,
    'selected'  => 'pages_list' // RMSUBLOCATION constant defines wich submenu options is selected
);

$options[] = array(
    'title'     => __('Published','qpages'),
    'link'      => 'pages.php?acceso=1&cat='.$cat,
    'selected'  => 'pages_public' // RMSUBLOCATION constant defines wich submenu options is selected
);

$options[] = array(
    'title'     => __('Drafts','qpages'),
    'link'      => 'pages.php?acceso=0&cat='.$cat,
    'selected'  => 'pages_private' // RMSUBLOCATION constant defines wich submenu options is selected
);

$options[] = array(
    'title'     => __('Add page','qpages'),
    'link'      => 'pages.php?op=new&cat='.$cat,
    'selected'  => 'pages_new' // RMSUBLOCATION constant defines wich submenu options is selected
);

$options[] = array(
    'title'     => __('Add linked page','qpages'),
    'link'      => 'pages.php?op=newlink&cat='.$cat,
    'selected'  => 'pages_linked' // RMSUBLOCATION constant defines wich submenu options is selected
);

$adminmenu[2]['options'] = $options;
