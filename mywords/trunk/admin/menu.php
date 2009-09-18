<?php
// $Id: menu.php 48 2009-09-17 14:53:50Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

RMTemplate::get()->add_menu(__('Dashboard','admin_mywords'), './index.php', '../images/dashboard.png', 'dashboard');
RMTemplate::get()->add_menu(__('Categories','admin_mywords'), 'categories.php', '../images/categos.png', 'categories', $options);

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
RMTemplate::get()->add_menu(__('Posts','admin_mywords'), 'posts.php', '../images/post.png', 'posts', $options);
RMTemplate::get()->add_menu(__('Editors','admin_mywords'), 'editors.php', '../images/editor.png', 'editors');

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
RMTemplate::get()->add_menu(__('Bookmarks','admin_mywords'), 'bookmarks.php', '../images/bookmark.png', 'bookmarks', $options);

unset($options);

// TOOLBAR
RMTemplate::get()->add_tool(__('Dashboard','admin_mywords'), './index.php', '../images/dashboard.png', 'dashboard');
RMTemplate::get()->add_tool(__('Posts','admin_mywords'), './posts.php', '../images/post.png', 'posts');
RMTemplate::get()->add_tool(__('Categories','admin_mywords'), './categories.php', '../images/categos.png', 'categories');
RMTemplate::get()->add_tool(__('Editors','admin_mywords'), './editors.php', '../images/editor.png', 'editors');
RMTemplate::get()->add_tool(__('Bookmarks','admin_mywords'), './bookmarks.php', '../images/bookmark.png', 'bookmarks');
RMTemplate::get()->add_tool(__('Help','admin_mywords'), '#', '../images/help.png', '');