<?php
// $Id: menu.php 8 2009-08-26 17:00:35Z i.bitcero $
// --------------------------------------------------------------
// I.Themes
// Module for manage themes by Red Mexico
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// License: GPL v2
// --------------------------------------------------------------

defined('XOOPS_ROOT_PATH') or die();

if(function_exists("load_mod_locale"))
    load_mod_locale("xthemes");

if(!function_exists("__")){
    function __($text){
        return $text;
    }
}

$adminmenu[1]['title'] = __('Dashboard','xthemes');
$adminmenu[1]['link'] = 'index.php';
$adminmenu[1]['icon'] = "images/xthemes32.png";
$adminmenu[1]['location'] = 'dashboard';

$adminmenu[2]['title'] = __('Settings','xthemes');
$adminmenu[2]['link'] = 'index.php?op=config';
$adminmenu[2]['icon'] = "images/settings.png";
$adminmenu[2]['location'] = 'settings';
