<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$adminmenu[0]['title'] = _MI_RMC_MENUIMG;
$adminmenu[0]['link'] = "images.php";
$adminmenu[0]['icon'] = "images/images.png";
$adminmenu[0]['options'] = array(0 => array(
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
	), 3 => array(
		'title'		=>	_MI_RMC_OPTINEWIMG,
		'link'		=> 'images.php?action=new',
		'selected'	=> 'rmc_newimage' // RMSUBLOCATION constant defines wich submenu options is selected
	), 4 => array(
		'title'		=>	_MI_RMC_OPTINEWIMGS,
		'link'		=> 'images.php?action=newbulk',
		'selected'	=> 'rmc_newimages' // RMSUBLOCATION constant defines wich submenu options is selected
	)
);
