<?php
// $Id: admin_loader.php 52 2009-09-18 06:01:48Z i.bitcero $
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* Admin loader file
*/

//require_once 'loader.php';

// Usefull admin clases
$tpl = RMTemplate::get();

$rmc_theme = isset($rmc_config['theme']) ? $rmc_config['theme'] : 'default';

if (!file_exists(RMCPATH.'/templates/'.$rmc_theme.'/admin_gui.php')){
	$rmc_theme = 'default';
}

RMTemplate::get()->add_style('general.css','rmcommon');

define('RMTHEMEPATH', RMCPATH.'/themes/'.$rmc_theme);
define('RMTHEMEURL', RMCURL.'/themes/'.$rmc_theme);
