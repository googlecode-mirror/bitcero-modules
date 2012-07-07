<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------
ob_start();
?>

var DT_ERROR = 'error';
var DT_OK = 'ok';
var DT_MSG = '';
var DT_URL = '<?php echo XOOPS_URL; ?>/modules/dtransport';

var jsLang = {
    'checkForm': '<?php _e('Verifying form fields...','dtransport'); ?>',
    'errForm': '<?php _e('There are some errors in form fields. Please verify those fields marked with red color','dtransport'); ?>',
    'okForm': '<?php _e('Form fields verified successfully!','dtransport'); ?>',
    savingDown: '<?php _e('Saving Download...','dtransport'); ?>',
    applying: '<?php _e('Applying changes. Please wait a second...','dtransport'); ?>',
    cancel: '<?php _e('Cancel','dtransport'); ?>',
    normal: '<?php _e('Normal','dtransport'); ?>',
    secure: '<?php _e('Protected','dtransport'); ?>',
    noSelectMsg: '<?php _e('Before to run this action, you must select at least one item!','dtransport'); ?>',
    confirmDeletion: '<?php _e('Do you really want to delete selected items?','dtransport'); ?>',
    groupName: '<?php _e('Provide a group name!','dtransport'); ?>',
    deleteFile: '<?php _e('Do you really want to delete this file? You will not be able to recover it!','dtransport'); ?>',
    noFile: '<?php _e('Before to save details select a file!','dtransport'); ?>',
    noURL: '<?php _e('Before to save details provide a file URL!','dtransport'); ?>',
    noTitle: '<?php _e('You must provide a title for this item','dtransport'); ?>',
    edit: '<?php _e('Edit','dtransport'); ?>',
    delete: '<?php _e('Delete','dtransport'); ?>',
    updateGroup: '<?php _e('Update Group','dtransport'); ?>',
    createGroup: '<?php _e('Create Group','dtransport'); ?>',
    saveData: '<?php _e('Save Changes','dtransport'); ?>',
    titleField: '<?php _e('Title:','dtransport'); ?>',
    descField: '<?php _e('Description:','dtransport'); ?>',
    deleteScreen: '<?php _e('Do you really want to delete this screenshot?','dtransport'); ?>'
};

<?php

$strings = ob_get_clean();

$tpl = RMTemplate::get();
$tpl->add_head_script($strings);
unset($strings);