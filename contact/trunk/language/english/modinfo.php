<?php
// $Id$
// --------------------------------------------------------------
// Contact
// A simple contact module for Xoops
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('contact');

define('_MI_CT_DESC', __('A simple contact module for Xoops','contact'));

// Settings
define('_MI_CT_EMAIL',__('Email Recipient','contact'));
define('_MI_CT_EMAILD',__('Specify the email where messages will be sent to','contact'));
define('_MI_CT_INFO', __('Information message','contact'));
define('_MI_CT_URL', __('Module URL', 'contact'));
define('_MI_CT_URLD', __('Specify here the URL where ContactMe! will work. This will be used for redirections and other things.','contact'));
define('_MI_CT_LIMIT',__('Messages per page','contact'));
define('_MI_CT_LIMITD',__('This value specifies the limit of messages to show on each page in dashboard.','contact'));
define('_MI_CT_QUOTE', __('Quote message text when reply','contact'));
define('_MI_CT_QUOTED', __('When this option is enabled, ContactMe! will include the original message as a quoted text in reply.','contact'));

// Block
define('_MI_CT_BLOCK',__('ContactMe!','contact'));
define('_MI_CT_BLOCKD', __('Shows a block with a contact form that can be put in any block canvas.','contact'));
