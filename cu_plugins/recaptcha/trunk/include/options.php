<?php
// $Id$
// --------------------------------------------------------------
// Recaptcha plugin for Common Utilities
// Allows to integrate recaptcha in comments or forms
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$options['public'] = array(
        'caption'   =>  __('Your public key','recaptcha'),
        'desc'      =>  __('You can get it directly from your recaptcha account.','recaptcha'),
        'fieldtype'      =>  'textbox',
        'size'      =>  50,
        'valuetype' =>  'text',
        'value'   =>  ''
);

$options['private'] = array(
        'caption'   =>  __('Your private key','recaptcha'),
        'desc'      =>  __('You can get it directly from your recaptcha account.','recaptcha'),
        'fieldtype'      =>  'textbox',
        'size'      =>  50,
        'valuetype' =>  'text',
        'value'   =>  ''
);

$options['show'] = array(
        'caption'   =>  __('Show captcha to Webmasters?','recaptcha'),
        'desc'      =>  __('If this options is disble, recaptcha will not show to webmasters','recaptcha'),
        'fieldtype'      =>  'yesno',
        'valuetype' =>  'int',
        'value'   =>  1
);

$options['theme'] = array(
        'caption'   =>  __('Theme for widget','recaptcha'),
        'desc'      =>  __('Select the theme that you wisth to use for recaptcha widget.','recaptcha'),
        'fieldtype'      =>  'select',
        'valuetype' =>  'text',
        'value'   =>  'red',
        'options' => array(
            __('Red (default theme)', 'recaptcha') => 'red',
            __('White', 'recaptcha') => 'white',
            __('Black Glass', 'recaptcha') => 'blackglass',
            __('Clean',  'recaptcha') => 'clean'
        )
);
