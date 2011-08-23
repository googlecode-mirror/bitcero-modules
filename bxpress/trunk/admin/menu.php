<?php
// $Id$
// --------------------------------------------------------------
// EXMBB Forums
// An simple forums module for XOOPS and Common Utilities
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$adminmenu[0]['title'] = __('Dashboard','exmbb');
$adminmenu[0]['link'] = "admin/index.php";
$adminmenu[0]['icon'] = '../images/dash.png';
$adminmenu[0]['location'] = "dashboard";

$adminmenu[1]['title'] = __('Categories','exmbb');
$adminmenu[1]['link'] = "admin/categos.php";
$adminmenu[1]['icon'] = '../images/categos.png';
$adminmenu[1]['location'] = "categories";
$adminmenu[1]['options'] = array(
    array('title'=>__('All categories','docs'), 'link'=>'categos.php', 'selected'=>'categories'),
    array('title'=>__('New category','docs'), 'link'=>'categos.php?action=new', 'selected'=>'newcategory'),
);

$adminmenu[2]['title'] = __('Forums','exmbb');
$adminmenu[2]['link'] = "admin/forums.php";
$adminmenu[2]['icon'] = '../images/forums.png';
$adminmenu[2]['location'] = "forums";


$adminmenu[3]['title'] = __('Announcements','exmbb');
$adminmenu[3]['link'] = "admin/announcements.php";
$adminmenu[3]['icon'] = '../images/bell.png';
$adminmenu[3]['location'] = "messages";

$adminmenu[4]['title'] = __('Reports','exmbb');
$adminmenu[4]['link'] = "admin/reports.php";
$adminmenu[4]['icon'] = '../images/reports.png';
$adminmenu[4]['location'] = "reports";

$adminmenu[5]['title'] = __('Prune','exmbb');
$adminmenu[5]['link'] = "admin/prune.php";
$adminmenu[5]['icon'] = '../images/prune.png';
$adminmenu[5]['location'] = "prune";

