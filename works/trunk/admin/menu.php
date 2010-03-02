<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Module for personals and professionals portfolios
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('works','admin_');

$adminmenu[0]['title'] = __('Dashboard', 'admin_works');
$adminmenu[0]['link'] = "admin/index.php";
$adminmenu[0]['icon'] = "../images/dashboard.png";
$adminmenu[0]['location'] = "dashboard";

$adminmenu[1]['title'] = __('Categories', 'admin_works');
$adminmenu[1]['link'] = "admin/categos.php";
$adminmenu[1]['icon'] = "../images/cats16.png";
$adminmenu[1]['location'] = "categories";
$adminmenu[1]['options'] = array(
								array('title'=>__('Add Category', 'admin_works'),'link'=>'categos.php?op=new', 'selected'=>'newcategory')
							);

$adminmenu[4]['title'] = __('Customer Types','admin_works');
$adminmenu[4]['link'] = "admin/types.php";
$adminmenu[4]['icon'] = "../images/types.png";
$adminmenu[4]['location'] = "cutomertypes";

$adminmenu[2]['title'] = __('Customers','admin_works');
$adminmenu[2]['link'] = "admin/clients.php";
$adminmenu[2]['icon'] = "../images/clients.png";
$adminmenu[2]['location'] = "customers";

$adminmenu[3]['title'] = __('Works','admin_works');
$adminmenu[3]['link'] = "admin/works.php";
$adminmenu[3]['icon'] = "../images/works.png";
$adminmenu[3]['location'] = "works";

