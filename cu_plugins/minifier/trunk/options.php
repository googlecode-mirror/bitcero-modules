<?php
// $Id$
// --------------------------------------------------------------
// Minifier Plugin for Common Utilities
// Minify all scripts and styles sheets added trough RMTemplate
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

// Enable/Disable plugin behaviour
$options['enable'] = array(
    'caption'   =>  __('Enable minifier','minifier'),
    'desc'      =>  '',
    'fieldtype'      =>  'yesno',
    'valuetype' =>  'int',
    'value'   =>  '1'
);

// Enable builder
$options['min_enableBuilder'] = array(
    'caption'   =>  __('Enable builder app','minifier'),
    'desc'      =>  __('Allow use of the Minify URI Builder app. Only set this to true while you need it.','minifier'),
    'fieldtype'      =>  'yesno',
    'valuetype' =>  'int',
    'value'   =>  '0'
);

// Enable/Disable builder
$options['min_errorLogger'] = array(
    'caption'   =>  __('Enable log messages','minifier'),
    'desc'      =>  __('Set to true to log messages to FirePHP (Firefox Firebug addon).','minifier'),
    'fieldtype'      =>  'yesno',
    'valuetype' =>  'int',
    'value'   =>  '0'
);

// Document ROOT
$options['min_documentRoot'] = array(
    'caption'   =>  __('Document root','minifier'),
    'desc'      =>  __('Leave an empty string to use PHP\'s $_SERVER[\'DOCUMENT_ROOT\'].','minifier'),
    'fieldtype'      =>  'text',
    'valuetype' =>  'text',
    'value'   =>  ''
);

// Enable/Disable builder
$options['min_cacheFileLocking'] = array(
    'caption'   =>  __('Cache file locking','minifier'),
    'desc'      =>  __('Cache file locking. Set to false if filesystem is NFS. On at least one NFS system flock-ing attempts stalled PHP for 30 seconds!','minifier'),
    'fieldtype'      =>  'yesno',
    'valuetype' =>  'int',
    'value'   =>  1
);

// Imports at top
$options['options_bubbleCssImports'] = array(
    'caption'   =>  __('CSS imports at top','minifier'),
    'desc'      =>  __('Combining multiple CSS files can place @import declarations after rules, which
 is invalid. Minify will attempt to detect when this happens and place a
 warning comment at the top of the CSS output. To resolve this you can either 
 move the @imports within your CSS files, or enable this option, which will 
 move all @imports to the top of the output. Note that moving @imports could 
 affect CSS values (which is why this option is disabled by default).','minifier'),
    'fieldtype'      =>  'yesno',
    'valuetype' =>  'int',
    'value'   =>  0
);

// Max Age
$options['options_maxAge'] = array(
    'caption'   =>  __('Cache max age','minifier'),
    'desc'      =>  __('Cache-Control: max-age value sent to browser (in seconds). After this period, the browser will send another conditional GET.','minifier'),
    'fieldtype'      =>  'text',
    'valuetype' =>  'int',
    'value'   =>  1800
);