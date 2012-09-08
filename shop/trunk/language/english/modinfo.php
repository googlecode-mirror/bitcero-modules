<?php
// $Id$
// --------------------------------------------------------------
// MiniShop 3
// Module for catalogs
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale("shop");

define('_MI_MS_MODTITLE',__('Section Title','shop'));
define('_MI_MS_SHOWHEAD',__('Show module header','shop'));
define('_MI_MS_URLMODE',__('Enable friendly URLs','shop'));
define('_MI_MS_URLMODED',__('Define the way in wich MiniShop will be format the products and categories URLs. Friendly URLs needs mod_rewrite or equivalent system.','shop'));
define('_MI_MS_BASEDIR',__('Base path for friendly URLs','shop'));
define('_MI_MS_EDITOR',__('Editor type for products from','shop'));
define('_MI_MS_EDITOR_VISUAL',__('Visual Editor','shop'));
define('_MI_MS_EDITOR_HTML',__('HTML Editor','shop'));
define('_MI_MS_EDITOR_XOOPS',__('XoopsCode Editor','shop'));
define('_MI_MS_EDITOR_SIMPLE',__('Simple Editor','shop'));
define('_MI_MS_MAXSIZE',__('Image max file size','shop'));
define('_MI_MS_MAXSIZED',__('Specify this value in KB','shop'));
define('_MI_MS_IMGSIZE',__('Image size','shop'));
define('_MI_MS_IMGSIZED',__('Specify the normal image size in pixels using format "width|height".','shop'));
define('_MI_MS_IMGREDIM',__('Resizing method for normal images','shop'));
define('_MI_MS_THSSIZE',__('Thumbnail size','shop'));
define('_MI_MS_THSSIZED',__('Specify the thumbnail image size in pixels using format "width|height".','shop'));
define('_MI_MS_THSREDIM',__('Resizing method for thumbnail images','shop'));
define('_MI_MS_XPAGE',__('Products per page','shop'));
define('_MI_MS_COLS',__('Columns for catalog','shop'));
define('_MI_MS_HOMEPRODS',__('Home page listing type','shop'));
define('_MI_MS_CURFOR',__('Currency format','shop'));
define('_MI_MS_CURFORD',__('You can specify the currency format based on PHP sprintf() function (eg. $%s will format $5.00).','shop'));
define('_MI_MS_EMAIL',__('Email to send messages','shop'));
define('_MI_MS_BULINK',__('Buy link when its not been specified in product details','shop'));
define('_MI_MS_BULINKD',__('This link will be used in all products that not have a custom buy link. You can use templates: {NAME}, {ID} and {PRICE}','shop'));
define('_MI_MS_HRECENTS',__('Number of recent products in home page','shop'));
define('_MI_MS_HPOP',__('Number of popular products in home page','shop'));
define('_MI_MS_HRANDOM',__('Number of random products in home page','shop'));
