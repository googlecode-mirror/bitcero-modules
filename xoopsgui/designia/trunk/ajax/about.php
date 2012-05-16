<?php
// $Id$
// --------------------------------------------------------------
// Designia v1.0
// Theme for Common Utilities 2
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

//require dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).'/mainfile.php';
include '../../../../../include/cp_header.php';

$xoopsLogger->renderingEnabled = false;
$xoopsLogger->activated = false;

load_theme_locale('designia', '', true);

?>
<div class=th>
    <?php _e('About Designia Theme','designia'); ?>
</div>
<div class="des-about-content">
    <span class=ab_nets>
        <a href="http://www.twitter.com/redmexico" title="@redmexico" target="_blank"><img src="<?php echo RMCURL; ?>/themes/designia/images/twitter.png" alt="Twitter icon" /></a>
        <a href="http://www.facebook.com/redmexico" title="<?php _e('Red México on Facebook','designia'); ?>" target="_blank"><img src="<?php echo RMCURL; ?>/themes/designia/images/face.png" alt="Facebook icon" /></a>
    </span>
    <img src="<?php echo RMCURL; ?>/themes/designia/images/logab.png" alt="<?php _e('Designia Theme for Common Utilities','designia'); ?>" class="logo" />
    <div class="clear dark_bg"></div>
    <span class=ab_caption><?php _e('Name and Version:','designia'); ?></span>
    <span class=ab_data>Designia Theme v1.0</span>
    <span class=ab_caption><?php _e('Running on:','designia'); ?></span>
    <span class=ab_data><?php echo XOOPS_VERSION; ?></span>
    <span class=ab_caption><?php _e('Powered by:','designia'); ?></span>
    <span class=ab_data>Common Utilities <?php echo RMCVERSION; ?></span>
    <span class=ab_info>
        <?php echo sprintf(__('Designia Theme has been created by %s spacially for Common Utilities. You can find more XOOPS content in our Website or by following on my twitter account.','designia'), '<a href="http://www.redmexico.com.mx" target="_blank">Red Mexico</a>'); ?>
    </span>
</div>