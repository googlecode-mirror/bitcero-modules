<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

require '../../mainfile.php';

// Switch page
include 'include/parse.php';

if(isset($explore) && $explore!=''){
    include 'explore.php';
}elseif(isset($usr) && $usr!=''){
    include 'user.php';
}elseif(isset($cp) && $cp!=''){
    include 'cpanel.php';
}elseif(isset($search)){
    include 'search.php';
}elseif(isset($postcard)){
    include 'postcard.php';
} else {
    include 'home.php';
}
