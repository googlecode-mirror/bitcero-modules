<?php
// $Id$
// --------------------------------------------------------------
// MyFolder
// Advanced Portfolio System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------
load_mod_locale('myfolder', 'admin_');

$adminmenu[0]['title'] = __('Existing items','admin_myfolder');
$adminmenu[0]['link'] = "admin/index.php";
$adminmenu[0]['icon'] = "../images/existing.gif";
$adminmenu[0]['location'] = "index";

$adminmenu[1]['title'] = __('Add Item','admin_myfolder');
$adminmenu[1]['link'] = "admin/index.php?op=new";
$adminmenu[1]['icon'] = "../images/new.png";
$adminmenu[1]['location'] = "newitem";

$adminmenu[2]['title'] = _MI_RMMF_AM3;
$adminmenu[2]['link'] = "admin/categos.php";
$adminmenu[2]['icon'] = "../images/cats.gif";
$adminmenu[2]['location'] = "categories";

$adminmenu[3]['title'] = _MI_RMMF_AM4;
$adminmenu[3]['link'] = "admin/categos.php?op=new";
$adminmenu[3]['icon'] = "../images/newcat.gif";
$adminmenu[3]['location'] = "newcategory";
