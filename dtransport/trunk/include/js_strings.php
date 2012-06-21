<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------
?>

var DT_ERROR = 'error';
var DT_OK = 'ok';
var DT_MSG = '';
var DT_URL = '<?php echo XOOPS_URL; ?>/modules/dtransport';

var jsLang = {
    'checkForm': '<?php _e('Verifying form fields...','dtransport'); ?>',
    'errForm': '<?php _e('There are some errors in form fields. Please verify those fields marked with red color','dtransport'); ?>',
    'okForm': '<?php _e('Form fields verified successfully!','dtransport'); ?>',
    savingDown: '<?php _e('Saving Download...','dtransport'); ?>'
};