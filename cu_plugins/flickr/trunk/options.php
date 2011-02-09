<?php
// $Id$
// --------------------------------------------------------------
// MyFlickr Plugin for Common Utilities
// Plugin to show flickr photos wherever you want in XOOPS.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$options['apikey'] = array(
        'caption'   =>  __('Your flickr API key','flickr'),
        'desc'      =>  sprintf(__('Input here your API key. If you do\'nt have any key yet, please goto to %s and get one.','flickr'), '<a href="http://www.flickr.com/services/apps/create/apply" target="_blank">App Garden</a>'),
        'fieldtype' =>  'textbox',
        'valuetype' =>  'text',
        'value'   =>  ''
);

$options['showcp'] = array(
    'caption' => __('Do you want to show Flickr block in Control Panel?','flickr'),
    'desc'      =>  __('By enabling this option, MyFlickr will show a block in the Control Panel start page.','flickr'),
    'fieldtype' =>  'yesno',
    'valuetype' =>  'int',
    'value'   =>  0
);
