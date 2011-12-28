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

$adminmenu[] = array(
    'title' => __('Dashboard','rmcommon'),
    'link' => "index.php",
    'icon' => "images/dashboard.png",
    'location' => "dashboard"
);

$adminmenu[] = array(
    'title' => __('Modules','rmcommon'),
    'link' => "modules.php",
    'icon' => "images/modules.png",
    'location' => "modules"
);

$adminmenu[] = array(
    'title' => __('Blocks','rmcommon'),
    'link' => "blocks.php",
    'icon' => "images/blocks.png",
    'location' => "blocks"
);

$adminmenu[] = array(
    'title' => __('Users','rmcommon'),
    'link' => 'users.php',
    'icon' => 'images/users.png',
    'location' => 'users',
    'options' => array(
        array('title'=>__('All users','rmcommon'),'link'=>'users.php','selected'=>'allusers'),
        array('title'=>__('New user','rmcommon'),'link'=>'users.php?action=new','selected'=>'newuser'),
    )
);

$adminmenu[] = array(
    'title' => __('Images','rmcommon'),
    'link' => "images.php",
    'icon' => "images/images.png",
    'location' => "imgmanager",
    'options' => array(0 => array(
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
    )
);

$adminmenu[] = array(
    'title' => __('Comments','rmcommon'),
    'link' => "comments.php",
    'icon' => "images/comments.png",
    'location' => "comments"
);

$adminmenu[] = array(
    'title' => __('Plugins','rmcommon'),
    'link' => "plugins.php",
    'icon' => "images/plugin.png",
    'location' => "plugins"
);
