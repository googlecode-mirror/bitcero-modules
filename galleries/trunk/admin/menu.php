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
$adminmenu[2]['options'] = array(
    array('title'=>__('List tags','admin_galleries'),'link'=>'tags.php','selected'=>'tags'),
    array('title'=>__('Add tags','admin_galleries'),'link'=>'tags.php?op=new','selected'=>'newtags')
);

$adminmenu[3]['title'] = __('Users','admin_galleries');
$adminmenu[3]['link'] = "admin/users.php";
$adminmenu[3]['icon'] = '../images/users.png';
$adminmenu[3]['location'] = 'users';
$adminmenu[3]['options'] = array(
    array('title'=>__('List users','admin_galleries'),'link'=>'users.php','selected'=>'users'),
    array('title'=>__('New user','admin_galleries'),'link'=>'users.php?op=new','selected'=>'newuser')
);

$adminmenu[4]['title'] = __('Images','admin_galleries');
$adminmenu[4]['link'] = "admin/images.php";
$adminmenu[4]['icon'] = '../images/images.png';
$adminmenu[4]['location'] = 'images';
$adminmenu[4]['options'] = array(
    array('title'=>__('List images','admin_galleries'),'link'=>'images.php','selected'=>'images'),
    array('title'=>__('Add image','admin_galleries'),'link'=>'images.php?op=new','selected'=>'newimage'),
    array('title'=>__('Add multiple images','admin_galleries'),'link'=>'images.php?op=newbulk','selected'=>'newbulk')
);

$adminmenu[5]['title'] = __('Postcards', 'admin_galleries');
$adminmenu[5]['link'] = "admin/postcards.php";
$adminmenu[5]['icon'] = '../images/postcard.png';
$adminmenu[5]['location'] = 'postcards';
