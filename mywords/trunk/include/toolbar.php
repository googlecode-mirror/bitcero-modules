<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* This file shows the toolbar and allows to other objects to create
* menus and buttons
*/
RMTemplate::get()->add_tool(__('Dashboard','admin_mywords'), './index.php', '../images/dashboard.png', 'dashboard');
RMTemplate::get()->add_tool(__('Categories','admin_mywords'), './categories.php', '../images/categos.png', 'categories');
RMTemplate::get()->add_tool(__('Tags','admin_mywords'), './tags.php', '../images/tag.png', 'tags');
RMTemplate::get()->add_tool(__('Posts','admin_mywords'), './posts.php', '../images/post.png', 'posts');
RMTemplate::get()->add_tool(__('Editors','admin_mywords'), './editors.php', '../images/editor.png', 'editors');
RMTemplate::get()->add_tool(__('Social Sites','admin_mywords'), './bookmarks.php', '../images/bookmark.png', 'bookmarks');
RMTemplate::get()->add_tool(__('Trackbacks','admin_mywords'), './trackbacks.php', '../images/trackbacks.png', 'trackbacks');
RMTemplate::get()->add_tool(__('Help','admin_mywords'), '#', '../images/help.png', '');

// New toolbar buttons
RMEvents::get()->run_event('mywords.get_toolbar', RMTemplate::get()->get_toolbar());

// New menus
global $xoopsModule;
RMEvents::get()->run_event('mywords.get_menu', $xoopsModule->getAdminMenu());