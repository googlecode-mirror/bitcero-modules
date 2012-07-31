<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage files for download in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function dt_block_categories($options){

    $tpl = RMTemplate::get();
    $tpl->add_xoops_style('blocks.css','dtransport');

    include_once XOOPS_ROOT_PATH.'/modules/dtransport/class/dtfunctions.class.php';
    $categories = array();
    $dtfunc = new DTFunctions();

    $dtfunc->getCategos($categories, 0, 0, array(), false, 1);

    $block = array();
    foreach($categories as $cat){

        if($cat['jumps']>$options[0]-1 && $options[0]>0) continue;

        $block['categories'][] = $cat;

    }

    return $block;

}

function dt_block_categories_edit($options){

    $tpl = RMTemplate::get();
    $tpl->add_xoops_style('admin_block.css','dtransport');

    ob_start()
    ?>
    <div class="dt_table">
        <div class="dt_row">
            <div class="dt_cell">
                <label for="cat-level"><?php _e('Show categories levels:','dtransport'); ?></label>
            </div>
            <div class="dt_cell">
                <select name="options[0]" id="cat-level">
                    <option value="0"<?php if($options[0]==0): ?> selected="selected"<?php endif; ?>><?php _e('All levels','dtransport'); ?></option>
                    <option value="1"<?php if($options[0]==1): ?> selected="selected"<?php endif; ?>><?php _e('1 level','dtransport'); ?></option>
                    <option value="2"<?php if($options[0]==2): ?> selected="selected"<?php endif; ?>><?php _e('2 levels','dtransport'); ?></option>
                    <option value="3"<?php if($options[0]==3): ?> selected="selected"<?php endif; ?>><?php _e('3 levels','dtransport'); ?></option>
                    <option value="4"<?php if($options[0]==4): ?> selected="selected"<?php endif; ?>><?php _e('4 levels','dtransport'); ?></option>
                    <option value="5"<?php if($options[0]==5): ?> selected="selected"<?php endif; ?>><?php _e('5 levels','dtransport'); ?></option>
                </select>
            </div>
        </div>
    </div>
    <?php
    $ret = ob_get_clean();
    return $ret;
}