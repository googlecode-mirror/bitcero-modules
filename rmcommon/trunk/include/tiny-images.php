<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include '../../../mainfile.php';
include XOOPS_ROOT_PATH.'/modules/rmcommon/loader.php';
//XoopsLogger::getInstance()->activated = false;
//XoopsLogger::getInstance()->renderingEnabled = false;



RMTemplate::get()->add_style('editor_img.css', 'rmcommon');

RMEventsApi::get()->run_event('rm_loading_editorimages', '');

include RMTemplate::get()->get_template('editor_image.php', 'module', 'rmcommon');
