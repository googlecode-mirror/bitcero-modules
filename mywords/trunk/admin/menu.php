<?php
// $Id: menu.php 48 2009-09-17 14:53:50Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include_once XOOPS_ROOT_PATH.'/modules/rmcommon/loader.php';

$adminmenu[] = array(
    'title'=>__('Dashboard','admin_mywords'),
    'link'=>"admin/index.php",
    'icon'=>"../images/dashboard.png",
    'location'=>"dashboard"
);

$adminmenu[] = array(
    'title'=>__('Categories','admin_mywords'),
    'link'=>"admin/categories.php",
    'icon'=>"../images/categos.png",
    'location'=>"categories"
);

$adminmenu[] = array(
    'title'=>__('Tags','admin_mywords'),
    'link'=>"admin/tags.php",
    'icon'=>"../images/tag.png",
    'location'=>"tags"
);

$options = array();
$options[] = array(
    'title'     => __('List','admin_mywords'),
    'link'      => 'posts.php',
    'selected'  => 'posts_list' // RMSUBLOCATION constant defines wich submenu options is selected
);
$options[] = array(
    'title'     => __('Add New','admin_mywords'),
    'link'      => 'posts.php?op=new',
    'selected'  => 'new_post' // RMSUBLOCATION constant defines wich submenu options is selected
);

$adminmenu[] = array(
    'title'=>__('Posts','admin_mywords'),
    'link'=>"admin/posts.php",
    'icon'=>"../images/post.png",
    'location'=>"posts",
    'options'=>$options
);

$adminmenu[] = array(
    'title'=>__('Editors','admin_mywords'),
    'link'=>"admin/editors.php",
    'icon'=>"../images/editor.png",
    'location'=>"editors",
);

$options = array();
$options[] = array(
    'title'     => __('List Sites','admin_mywords'),
    'link'      => 'bookmarks.php',
    'selected'  => 'bookmarks' // RMSUBLOCATION constant defines wich submenu options is selected
);
$options[] = array(
    'title'     => __('Add New','admin_mywords'),
    'link'      => 'bookmarks.php?op=new',
    'selected'  => 'bookmark_new' // RMSUBLOCATION constant defines wich submenu options is selected
);

$adminmenu[] = array(
    'title'=>__('Bookmarks','admin_mywords'),
    'link'=>"admin/bookmarks.php",
    'icon'=>"../images/bookmark.png",
    'location'=>"bookmarks",
    'options'=>$options
);
