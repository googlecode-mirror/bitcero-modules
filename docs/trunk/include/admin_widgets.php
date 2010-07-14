<?php
// $Id$
// --------------------------------------------------------------
// Rapid Docs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* This file contains the functions for all widgets
* that will be shown in admin section
*/

load_mod_locale('docs');

/**
* Show the options widget
*/
function rd_widget_options(){
    global $res, $xoopsUser, $xoopsSecurity;
    
    $ret['title'] = __('Section Options','docs');
    $sections = array();
    RDFunctions::getSectionTree($sections, 0, 0, $res->id(), 'id_sec, title', isset($sec) ? $sec->id() : 0);
    $usrfield = new RMFormUser('','uid',false,isset($sec) ? array($sec->getVar('uid')) : $xoopsUser->getVar('uid'));
    ob_start();
?>
<div class="rd_widget_form">
    <form name="frmoptions" method="post" action="">
    <p><?php echo sprintf(__('Resource: %s','docs'), '<strong><a href="sections.php?id='.$res->id().'">'.$res->getVar('title').'</a></strong>'); ?></p>
    <p>
    <label for="sec-parent"><?php _e('Parent section:','docs'); ?></label>
    <select name="parent" id="sec-parent">
        <option value=""><?php _e('Select...','docs'); ?></option>
        <?php foreach($sections as $k): ?>
        <option value="<?php echo $k['id_sec']; ?>"<?php if(isset($sec) && $sec->parent()==$k['id_sec']): ?> selected="selected"<?php endif; ?>><?php echo str_repeat('--', $k['saltos']).' '.$k['title']; ?></option>
        <?php endforeach; ?>
    </select>
    <label for="sec-order"><?php _e('Display order:','docs'); ?></label>
    <input type="text" size="5" id="sec-order" name="order" value="<?php echo isset($sec) ? $sec->getVar('order') : 0; ?>" />
    <label><?php _e('Author:','docs'); ?></label>
    <?php echo $usrfield->render(); ?>
    </p>
    </form>
</div>
<?php
    $ret['content'] = ob_get_clean();
    return $ret;
}

/**
* Show the references created for a resource
*/
function rd_widget_references(){
    global $res;
    
    $ret['title'] = __('Notes & References','docs');
    $references = RDFunctions::references($res->id());
    ob_start();
?>
<div id="rd-wd-refrences">
<?php
    if(count($references)<=0) _e('There are not exists references for this resource yet!','docs');
    foreach($references as $ref):
?>
    
<?php
    endforeach;
?>
</div>
<?php
    $ret['content'] = ob_get_clean();
    return $ret;
    
}

/**
* Shows the figures for a resource
*/
function rd_widget_figures(){
    
    $ret['title'] = __('Resource Figures','docs');
    $ret['content'] = 'Figures';
    return $ret;
    
}
