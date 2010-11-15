<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

if (!function_exists('__'))
    include_once XOOPS_ROOT_PATH.'/modules/rmcommon/loader.php';

$adminmenu[0]['title'] = __('Dashboard','rmcommon');
$adminmenu[0]['link'] = "index.php";
$adminmenu[0]['icon'] = "images/dashboard.png";
$adminmenu[0]['location'] = "dashboard";

$adminmenu[1]['title'] = __('Modules','rmcommon');
$adminmenu[1]['link'] = "modules.php";
$adminmenu[1]['icon'] = "images/modules.png";
$adminmenu[1]['location'] = "modules";

$adminmenu[2]['title'] = __('Images','rmcommon');
$adminmenu[2]['link'] = "images.php";
$adminmenu[2]['icon'] = "images/images.png";
$adminmenu[2]['location'] = "imgmanager";
$adminmenu[2]['options'] = array(0 => array(
		'title'		=>	__('Categories','rmcommon'),
		'link'		=> 'images.php?action=showcats',
		'selected'	=> 'rmc_imgcats' // RMSUBLOCATION constant defines wich submenu options is selected
	), 1 => array(
		'title'		=>	__('New category','rmcommon'),
		'link'		=> 'images.php?action=newcat',
		'selected'	=> 'rmc_imgnewcat' // RMSUBLOCATION constant defines wich submenu options is selected
	), 2 => array(
		'title'		=>	__('Images','rmcommon'),
		'link'		=> 'images.php',
		'selected'	=> 'rmc_images' // RMSUBLOCATION constant defines wich submenu options is selected
	), 4 => array(
		'title'		=>	__('Add images','rmcommon'),
		'link'		=> 'images.php?action=new',
		'selected'	=> 'rmc_newimages' // RMSUBLOCATION constant defines wich submenu options is selected
	)
);

$adminmenu[3]['title'] = __('Comments','rmcommon');
$adminmenu[3]['link'] = "comments.php";
$adminmenu[3]['icon'] = "images/comments.png";
$adminmenu[3]['location'] = "comments";

$adminmenu[4]['title'] = __('Plugins','rmcommon');
$adminmenu[4]['link'] = "plugins.php";
$adminmenu[4]['icon'] = "images/plugin.png";
$adminmenu[4]['location'] = "plugins";

$adminmenu[5]['title'] = __('Themes','rmcommon');
$adminmenu[5]['link'] = "#";
$adminmenu[5]['icon'] = "images/themes.png";
$adminmenu[5]['location'] = "";

include XOOPS_ROOT_PATH.'/class/xoopslists.php';
$themes = XoopsLists::getDirListAsArray(RMCPATH.'/themes');
foreach($themes as $dir){
    if (file_exists(RMCPATH.'/themes/'.$dir.'/admin_gui.php')){
        $adminmenu[5]['options'][] = array(
            'title' => ucfirst($dir),
            'link'  => 'index.php?action=theme&amp;theme='.$dir,
            'selected' => ''
        );
    }
}
