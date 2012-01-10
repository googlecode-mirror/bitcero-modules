<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

if(function_exists('load_mod_locale')) load_mod_locale('dtransport');
//Inicio
$adminmenu[] = array(
    'title' => __('Dashboard','dtransport'),
    'link' => "./admin/index.php",
    'icon' => "../images/dashboard.png",
    'location' => "dashboard"
);

//Categorias
$adminmenu[] = array(
    'title' => __('Categories','dtransport'),
    'link' => "./admin/categories.php",
    'icon' => "../images/categories.png",
    'location' => "categories"
);

//Elementos
$adminmenu[] = array(
    'title' => __('Downloads','dtransport'),
    'link' => "./admin/items.php",
    'icon' => "../images/item.png",
    'location' => 'items',
    'options' => array(
        array('title'=>__('All downloads','dtransport'),'link'=>'items.php','selected'=>'items'),
        array('title'=>__('Pending','dtransport'),'link'=>'items.php?type=wait','selected'=>'itemswait'),
        array('title'=>__('Edited','dtransport'),'link'=>'items.php?type=edit','selected'=>'itemsedited'),
        array('title'=>__('New Download','dtransport'),'link'=>'items.php?action=new','selected'=>'itemsedited')
    )
);

//Pantallas
$adminmenu[] = array(
    'title' => __('Screenshots','dtransport'),
    'link' => "./admin/screens.php",
    'icon' => "../images/shots.png",
    'location' => 'screenshots'
);

//Caracteristicas
$adminmenu[] = array(
    'title' => __('Features','dtransport'),
    'link' => "./admin/features.php",
    'icon' => "../images/features.png",
    'location' => 'features'
);

//Archivos
$adminmenu[] = array(
    'title' => __('Files','dtransport'),
    'link' => "admin/files.php",
    'icon' => "../images/files.png",
    'location' => 'files'
);

//Logs
$adminmenu[] = array(
    'title' => __('Logs','dtransport'),
    'link' => "admin/logs.php",
    'icon' => "../images/logs.png",
    'location' => 'logs'
);

//Licencias
$adminmenu[] = array(
    'title' => __('Licenses','dtransport'),
    'link' => "/admin/licenses.php",
    'icon' => "../images/license.png",
    'location' => 'licenses'
);

//Plataformas
$adminmenu[] = array(
    'title' => __('Platforms','dtransport'),
    'link' => "admin/platforms.php",
    'icon' => "../images/os.png",
    'location' => 'platforms'
);
