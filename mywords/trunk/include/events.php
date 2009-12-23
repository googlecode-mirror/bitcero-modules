<?php
// $Id: events.php 54 2009-09-18 15:12:49Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* This file contains all events and methods for RMCommon
*/

// Events
$rmc_events = array(
	'mw_newcategory_form',
    'mw_posts_form'
);

// Methods
$rmc_methods['mywords_right_widgets_controller'] = array(
	'event'	=>	'rmcevent_load_right_widgets',
	'file'	=>	'/modules/mywords/include/widgets.php'
);

$rmc_methods['mywords_left_widgets_controller'] = array(
    'event'    =>    'rmcevent_load_left_widgets',
    'file'    =>    '/modules/mywords/include/widgets.php'
);

$rmc_methods['mywords_images_links'] = array(
    'event'    =>    'rm_loading_single_editorimgs',
    'file'    =>    '/modules/mywords/include/rmapi.php'
);