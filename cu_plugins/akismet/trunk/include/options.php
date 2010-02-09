<?php
// $Id$
// --------------------------------------------------------------
// Akismet plugin for Common Utilities
// Integrate Akismet API in Common Utilities
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// see: http://akismet.com
// --------------------------------------------------------------

$options['key'] = array(
        'caption'   =>  __('You Akismet API Key','akismet'),
        'desc'      =>  sprintf(__('You can obtain it directly from %s','akismet'), '<a href="http://akismet.com/personal/" target="_blank">Akismet</a>'),
        'fieldtype'      =>  'textbox',
        'valuetype' =>  'text',
        'value'   =>  ''
);
