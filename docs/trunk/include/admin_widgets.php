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
    
    ob_start();
?>
<div class="rd_widget_form">
    <form name="frmoptions" method="post" onsubmit="return false;">
    <label>
        <input type="checkbox" name="return" id="wreturn" value="1" checked="checked" onchange="$(this).is(':checked')?$('#secreturn').val(1):$('#secreturn').val(0);" />
        <?php _e('Save and return','docs'); ?>
    </label>
    <input type="submit" value="<?php _e('Save Section','docs'); ?>" onclick="$('#frm-section').submit();" />
    <input type="button" value="<?php _e('Discard Section','docs'); ?>" onclick="window.location.href = 'sections.php?id=<?php echo $res->id(); ?>';" />
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
    global $res, $rmc_config;
    
    $ret['title'] = __('Notes &amp; References','docs');
    $count = 0;
    $references = RDFunctions::references($res->id(),&$count, '',0,6);

    $nav = new RMPageNav($count, 6, 1, 3);
    $nav->target_url('javascript:;" onclick="docsAjax.getNotes('.$res->id().',6,{PAGE_NUM},\'rd-wd-references\')');
    RMTemplate::get()->add_script('../include/js/scripts.php?file=ajax.js');

    ob_start();
?>
<div id="rd-wd-references">
    <ul>
    <?php
        if(count($references)<=0) _e('There are not exists references for this resource yet!','docs');
        foreach($references as $ref):
    ?>
        <li><a href="javascript:;" onclick="docsAjax.insertIntoEditor('[note:<?php echo $ref['id']; ?>]','<?php echo $rmc_config['editor_type']; ?>');"><?php echo $ref['title']; ?></a></li>
    <?php
        endforeach;
    ?>
    </ul>
    <?php $nav->display(false); ?>
</div>
<?php
    $ret['content'] = ob_get_clean();
    return $ret;
    
}

/**
* Shows the figures for a resource
*/
function rd_widget_figures(){
    global $res, $rmc_config;
    
    $ret['title'] = __('Resource Figures','docs');
    $count = 0;
    $figures = RDFunctions::figures($res->id(),&$count, '',0,6);

    $nav = new RMPageNav($count, 6, 1, 3);
    $nav->target_url('javascript:;" onclick="docsAjax.getFigures('.$res->id().',6,{PAGE_NUM},\'rd-wd-figures\')');
    RMTemplate::get()->add_script('../include/js/scripts.php?file=ajax.js');

    ob_start();
?>
<div id="rd-wd-figures">
    <ul>
    <?php
        if(count($figures)<=0) _e('There are not exists figures for this resource yet!','docs');
        foreach($figures as $fig):
    ?>
        <li><a href="javascript:;" onclick="docsAjax.insertIntoEditor('[figure:<?php echo $fig['id']; ?>]','<?php echo $rmc_config['editor_type']; ?>');"><?php echo $fig['desc']; ?></a></li>
    <?php
        endforeach;
    ?>
    </ul>
    <?php $nav->display(false); ?>
</div>
<?php
    $ret['content'] = ob_get_clean();
    return $ret;
    
}
