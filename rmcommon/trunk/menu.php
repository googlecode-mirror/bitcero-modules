<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$adminmenu[0]['title'] = _MI_RMC_MENUDASH;
$adminmenu[0]['link'] = "index.php";
$adminmenu[0]['icon'] = "images/dashboard.png";
$adminmenu[0]['location'] = "dashboard";

$adminmenu[1]['title'] = _MI_RMC_MENUIMG;
$adminmenu[1]['link'] = "images.php";
$adminmenu[1]['icon'] = "images/images.png";
$adminmenu[1]['location'] = "imgmanager";
$adminmenu[1]['options'] = array(0 => array(
		'title'		=>	_MI_RMC_OPTICATS,
		'link'		=> 'images.php?action=showcats',
		'selected'	=> 'rmc_imgcats' // RMSUBLOCATION constant defines wich submenu options is selected
	), 1 => array(
		'title'		=>	_MI_RMC_OPTINEWCAT,
		'link'		=> 'images.php?action=newcat',
		'selected'	=> 'rmc_imgnewcat' // RMSUBLOCATION constant defines wich submenu options is selected
	), 2 => array(
		'title'		=>	_MI_RMC_MENUIMG,
		'link'		=> 'images.php',
		'selected'	=> 'rmc_images' // RMSUBLOCATION constant defines wich submenu options is selected
	), 4 => array(
		'title'		=>	_MI_RMC_OPTINEWIMGS,
		'link'		=> 'images.php?action=new',
		'selected'	=> 'rmc_newimages' // RMSUBLOCATION constant defines wich submenu options is selected
	)
);

$adminmenu[2]['title'] = _MI_RMC_MENUPLUGS;
$adminmenu[2]['link'] = "plugins.php";
$adminmenu[2]['icon'] = "images/plugin.png";
$adminmenu[2]['location'] = "plugins";
