<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('mywords','');

$adminmenu[] = array(
    'title'=>__('Dashboard','mywords'),
    'link'=>"admin/index.php",
    'icon'=>"../images/dashboard.png",
    'location'=>"dashboard"
);

$adminmenu[] = array(
    'title'=>__('Categories','mywords'),
    'link'=>"admin/categories.php",
    'icon'=>"../images/categos.png",
    'location'=>"categories"
);

$adminmenu[] = array(
    'title'=>__('Tags','mywords'),
    'link'=>"admin/tags.php",
    'icon'=>"../images/tag.png",
    'location'=>"tags"
);

$options = array();
$options[] = array(
    'title'     => __('List','mywords'),
    'link'      => 'posts.php',
    'selected'  => 'posts_list' // RMSUBLOCATION constant defines wich submenu options is selected
);
$options[] = array(
    'title'     => __('Add New','mywords'),
    'link'      => 'posts.php?op=new',
    'selected'  => 'new_post' // RMSUBLOCATION constant defines wich submenu options is selected
);

$adminmenu[] = array(
    'title'=>__('Posts','mywords'),
    'link'=>"admin/posts.php",
    'icon'=>"../images/post.png",
    'location'=>"posts",
    'options'=>$options
);

$adminmenu[] = array(
    'title'=>__('Editors','mywords'),
    'link'=>"admin/editors.php",
    'icon'=>"../images/editor.png",
    'location'=>"editors"
);

$adminmenu[] = array(
    'title'=>__('Social Sites','mywords'),
    'link'=>"admin/bookmarks.php",
    'icon'=>"../images/bookmark.png",
    'location'=>"bookmarks",
);

$adminmenu[] = array(
    'title'=>__('Trackbacks','mywords'),
    'link'=>"admin/trackbacks.php",
    'icon'=>"../images/trackbacks.png",
    'location'=>"trackbacks",
);

