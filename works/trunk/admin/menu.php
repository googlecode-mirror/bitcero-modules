<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Module for personals and professionals portfolios
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('works');

$i = 0;
$adminmenu[$i]['title'] = __('Dashboard', 'works');
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]['icon'] = "../images/dashboard.png";
$adminmenu[$i]['location'] = "dashboard";

$i++;
$adminmenu[$i]['title'] = __('Categories', 'works');
$adminmenu[$i]['link'] = "admin/categos.php";
$adminmenu[$i]['icon'] = "../images/cats16.png";
$adminmenu[$i]['location'] = "categories";
$adminmenu[$i]['options'] = array(
	array('title'=>__('List all', 'works'),'link'=>'categos.php', 'selected'=>'categories'),
	array('title'=>__('Add Category', 'works'),'link'=>'categos.php?op=new', 'selected'=>'newcategory')
);

$i++;
$adminmenu[$i]['title'] = __('Customer Types','works');
$adminmenu[$i]['link'] = "admin/types.php";
$adminmenu[$i]['icon'] = "../images/types.png";
$adminmenu[$i]['location'] = "customertypes";
$adminmenu[$i]['options'] = array(
	array('title'=>__('List', 'works'),'link'=>'types.php', 'selected'=>'types'),
	array('title'=>__('Add multiple types', 'works'),'link'=>'types.php?op=new', 'selected'=>'newtype')
);

$i++;
$adminmenu[$i]['title'] = __('Customers','works');
$adminmenu[$i]['link'] = "admin/clients.php";
$adminmenu[$i]['icon'] = "../images/clients.png";
$adminmenu[$i]['location'] = "customers";
$adminmenu[$i]['options'] = array(
	array('title'=>__('List', 'works'),'link'=>'clients.php', 'selected'=>'customers'),
	array('title'=>__('Add Customer', 'works'),'link'=>'clients.php?op=new', 'selected'=>'newcustomer')
);

$i++;
$adminmenu[$i]['title'] = __('Works','works');
$adminmenu[$i]['link'] = "admin/works.php";
$adminmenu[$i]['icon'] = "../images/works.png";
$adminmenu[$i]['location'] = "works";
$adminmenu[$i]['options'] = array(
    array('title'=>__('List', 'works'),'link'=>'works.php', 'selected'=>'works'),
    array('title'=>__('Add Work', 'works'),'link'=>'works.php?op=new', 'selected'=>'newwork')
);
$op = rmc_server_var($_GET, 'op', '');
if ($op=='edit'){
    $adminmenu[4]['options'][] = array(
        'title'=>'Custom fields',
        'link'=>'works.php?id='.rmc_server_var($_GET, 'id', 0).'&op=meta'
    );
}


