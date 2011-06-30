<?php
// $Id$
// --------------------------------------------------------------
// MiniShop 3
// Module for catalogs
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

RMTemplate::get()->add_tool(__('Dashboard','shop'), './index.php', '../images/dashboard.png', 'dashboard');
RMTemplate::get()->add_tool(__('Categories','shop'), './categories.php', '../images/category.png', 'categories');
RMTemplate::get()->add_tool(__('Products','shop'), './products.php', '../images/product.png', 'products');

// New toolbar buttons
RMEvents::get()->run_event('shop.get.toolbar', RMTemplate::get()->get_toolbar());

// New menus
global $xoopsModule;
RMEvents::get()->run_event('shop.get.menu', $xoopsModule->getAdminMenu());
