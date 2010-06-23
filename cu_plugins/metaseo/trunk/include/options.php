<?php
// $Id$
// --------------------------------------------------------------
// MetaSEO plugin for Common Utilities
// Optimize searchs by adding meta description and keywords to you rtemplate
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$options['len'] = array(
        'caption'   =>  __('Description length','metaseo'),
        'desc'      =>  __('Specify the text length to insert in meta description.','metaseo'),
        'fieldtype'      =>  'textbox',
        'size'      =>  10,
        'valuetype' =>  'int',
        'value'   =>  150
);

$options['meta'] = array(
        'caption'   =>  __('Use a custom field from MyWords and Quick Pages','metaseo'),
        'desc'      =>  __('You can use a custom field value directly from MyWords in order to specify the content for meta description tag.','metaseo'),
        'fieldtype'      =>  'yesno',
        'valuetype' =>  'int',
        'value'   =>  0
);

$options['meta_name'] = array(
        'caption'   =>  __('Custom field name for descriptions','metaseo'),
        'desc'      =>  __('Specify here the custom field name to use as description (eg. meta_desc).','metaseo'),
        'fieldtype'      =>  'textbox',
        'valuetype' =>  'text',
        'value'   =>  ''
);

$options['meta_keys'] = array(
        'caption'   =>  __('Custom field name for keywords','metaseo'),
        'desc'      =>  __('Specify here the custom field name to use as keywords. Remember that each word in MyWords custom field must be separated by comma (eg. xoops,cms,etc).','metaseo'),
        'fieldtype'      =>  'textbox',
        'valuetype' =>  'text',
        'value'   =>  ''
);

