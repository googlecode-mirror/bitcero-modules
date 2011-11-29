<?php
// $Id: admin_loader.php 628 2011-05-13 05:15:28Z i.bitcero $
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

$rmc_config = RMFunctions::get()->configs();
$rmc_theme = isset($rmc_config['theme']) ? $rmc_config['theme'] : 'default';

if (!file_exists(RMCPATH.'/themes/'.$rmc_theme.'/admin_gui.php')){
	$rmc_theme = 'default';
}

RMTemplate::get()->add_style('general.css','rmcommon');

define('RMTHEMEPATH', RMCPATH.'/themes/'.$rmc_theme);
define('RMTHEMEURL', RMCURL.'/themes/'.$rmc_theme);

// Load theme events
RMEvents::get()->load_extra_preloads(RMTHEMEPATH, ucfirst($rmc_theme).'Theme');

header ("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
