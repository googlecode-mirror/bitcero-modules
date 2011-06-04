<?php
// $Id$
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

require '../../../include/cp_header.php';

define('MCH_PATH',XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname());
define('MCH_URL', XOOPS_URL.'/modules/match');

// Create nest directories
if(!is_dir(XOOPS_UPLOAD_PATH.'/teams')){
    mkdir(XOOPS_UPLOAD_PATH.'/teams', 511);
}

if(!is_dir(XOOPS_UPLOAD_PATH.'/teams/players')){
    mkdir(XOOPS_UPLOAD_PATH.'/teams/players', 511);
    mkdir(XOOPS_UPLOAD_PATH.'/teams/players/ths', 511);
}

if(!is_dir(XOOPS_UPLOAD_PATH.'/teams/coaches')){
    mkdir(XOOPS_UPLOAD_PATH.'/teams/coaches', 511);
    mkdir(XOOPS_UPLOAD_PATH.'/teams/coaches/ths', 511);
}

define('MCH_UP_PATH', XOOPS_UPLOAD_PATH.'/teams');
define('MCH_UP_URL', XOOPS_UPLOAD_URL.'/teams');