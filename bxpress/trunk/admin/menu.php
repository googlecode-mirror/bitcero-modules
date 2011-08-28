<?php
// $Id$
// --------------------------------------------------------------
// bXpress Forums
// An simple forums module for XOOPS and Common Utilities
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$adminmenu[] = array(
    'title' => __('Dashboard','bxpress'),
    'link' => "admin/index.php",
    'icon' => '../images/dash.png',
    'location' => "dashboard"
);

$adminmenu[] = array(
    'title' => __('Categories','bxpress'),
    'link' => "admin/categos.php",
    'icon' => '../images/categos.png',
    'location' => "categories",
    'options' => array(
        array('title'=>__('All categories','docs'), 'link'=>'categos.php', 'selected'=>'categories')
    )
);

$adminmenu[] = array(
    'title' => __('Forums','bxpress'),
    'link' => "admin/forums.php",
    'icon' => '../images/forums.png',
    'location' => "forums",
    'options' => array(
        array('title'=>__('All forums','bxpress'), 'link'=>'forums.php','selected'=>'forums'),
        array('title'=>__('New forum','bxpress'), 'link'=>'forums.php?action=new','selected'=>'newforum'),
        array('title'=>__('Moderators','bxpress'), 'link'=>'forums.php?action=moderator','selected'=>'moderators')
    )
);


$adminmenu[3]['title'] = __('Announcements','bxpress');
$adminmenu[3]['link'] = "admin/announcements.php";
$adminmenu[3]['icon'] = '../images/bell.png';
$adminmenu[3]['location'] = "messages";

$adminmenu[4]['title'] = __('Reports','bxpress');
$adminmenu[4]['link'] = "admin/reports.php";
$adminmenu[4]['icon'] = '../images/reports.png';
$adminmenu[4]['location'] = "reports";

$adminmenu[5]['title'] = __('Prune','bxpress');
$adminmenu[5]['link'] = "admin/prune.php";
$adminmenu[5]['icon'] = '../images/prune.png';
$adminmenu[5]['location'] = "prune";

