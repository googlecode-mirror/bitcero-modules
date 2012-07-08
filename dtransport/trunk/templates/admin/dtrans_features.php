<h1 class="rmc_titles dt_titles"><span style="background-position: left -32px;">&nbsp;</span><?php echo sprintf(__('Features of "%s"','dtransport'), $sw->getVar('name')); ?></h1>

<form name="frmfeat" id="frm-feats" method="POST" action="features.php">
    <div class="dt_options">
        <select name="action" id="bulk-top">
            <option value="" selected="selected"><?php _e('Bulk actions...','dtransport'); ?></option>
            <option value="delete"><?php _e('Delete Features','dtransport'); ?></option>
        </select>
        <input type="button" id="the-op-top" value="<?php _e('Apply','docs'); ?>" onclick="before_submit('frm-feats');" />
    </div>
    <table width="100%" class="outer" cellspacing="1">
        <thead>
            <tr class="head" align="center">
                <th width="20"><input type="checkbox" name="checkAll" id="checkall" onclick="$('#frm-feats').toggleCheckboxes(':not(#checkall)');" /></th>
                <th><?php _e('ID','dtransport'); ?></th>
                <th><?php _e('Title','dtransport'); ?></th>
                <th><?php _e('Created','dtransport'); ?></th>
                <th><?php _e('Modified','dtransport'); ?></th>
                <th><{$lang_options}></th>
            </tr>
        </thead>
        <tfoot>
            <tr class="head" align="center">
                <th width="20"><input type="checkbox" name="checkAll2" id="checkall2" onclick="$('#frm-feats').toggleCheckboxes(':not(#checkall2)');" /></th>
                <th><?php _e('ID','dtransport'); ?></th>
                <th><?php _e('Title','dtransport'); ?></th>
                <th><?php _e('Created','dtransport'); ?></th>
                <th><?php _e('Modified','dtransport'); ?></th>
                <th><{$lang_options}></th>
            </tr>
        </tfoot>
        <tbody>
            <?php if(empty($features)): ?>
            <tr class="even" align="center">
                <td colspan="6"><?php _e('There are not features created for this download item','dtransport'); ?></td>
            </tr>
            <?php endif; ?>
            <?php foreach($features as $feature): ?>
            <tr class="<{cycle values='even,odd'}>">
                <td><input type="checkbox" name="ids[]" id="item-<?php echo $feature['id']; ?>" value="<?php echo $feature['id']; ?>" /></td>
                <td align="center" width="20"><strong><?php echo $feature['id']; ?></strong></td>
                <td><?php echo $feature['title']; ?></td>
                <td align="center"><?php echo $feature['created']; ?></td>
                <td align="center"><?php echo $feature['modified']; ?></td>
                <td align="center">
                    <a href="features.php?action=edit&amp;id=<?php echo $feature['id']; ?>&amp;item=<?php echo $item; ?>"><?php _e('Edit','dtransport'); ?></a> |
                    <a href="#" onclick="dt_check_delete(<?php echo $feature['id']; ?>, 'frm-feats');"><?php _e('Delete','dtransport'); ?></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="dt_options">
        <select name="actionb" id="bulk-bottom">
            <option value="" selected="selected"><?php _e('Bulk actions...','dtransport'); ?></option>
            <option value="delete"><?php _e('Delete Features','dtransport'); ?></option>
        </select>
        <input type="button" id="the-op-bottom" value="<?php _e('Apply','docs'); ?>" onclick="before_submit('frm-categories');" />
    </div>
    <input type="hidden" name="item" value="<?php echo $item; ?>" />
</form>