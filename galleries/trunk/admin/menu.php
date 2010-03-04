<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('galleries', 'admin_');

$adminmenu[0]['title'] = __('Dashboard','admin_galleries');
$adminmenu[0]['link'] = "admin/index.php";
$adminmenu[0]['icon'] = '../images/dashboard.png';
$adminmenu[0]['location'] = 'dashboard';

$adminmenu[1]['title'] = __('Albums','admin_galleries');
$adminmenu[1]['link'] = "admin/sets.php";
$adminmenu[1]['icon'] = '../images/album.png';
$adminmenu[1]['location'] = 'sets';
$adminmenu[1]['options'] = array(
	array('title'=>__('List albums','admin_galleries'),'link'=>'sets.php','selected'=>'sets'),
	array('title'=>__('Add album','admin_galleries'),'link'=>'sets.php?op=new','selected'=>'newalbum')
);

$adminmenu[2]['title'] = __('Tags','admin_galleries');
$adminmenu[2]['link'] = "admin/tags.php";
$adminmenu[2]['icon'] = '../images/tags.png';
$adminmenu[2]['location'] = 'tags';

$adminmenu[3]['title'] = __('Users','admin_galleries');
$adminmenu[3]['link'] = "admin/users.php";
$adminmenu[3]['icon'] = '../images/users.png';
$adminmenu[3]['location'] = 'users';

$adminmenu[4]['title'] = __('Images','admin_galleries');
$adminmenu[4]['link'] = "admin/images.php";
$adminmenu[4]['icon'] = '../images/images.png';
$adminmenu[4]['location'] = 'images';

$adminmenu[5]['title'] = __('Postcards', 'admin_galleries');
$adminmenu[5]['link'] = "admin/postcards.php";
$adminmenu[5]['icon'] = '../images/postcard.png';
$adminmenu[5]['location'] = 'postcards';
