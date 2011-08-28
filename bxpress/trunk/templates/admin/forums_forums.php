<h1 class="rmc_titles"><?php _e('Forums Management','bxpress'); ?></h1>

<form name="frmForums" id="frm-forums" method="post" action="forums.php">
<!-- Bulk operations -->
<div class="bxpress_options">
    <select name="action" id="bulk-top">
        <option value=""><?php _e('Bulk actions...','bxpress'); ?></option>
        <option value="enable"><?php _e('Activate','bxpress'); ?></option>
        <option value="disable"><?php _e('Disable','bxpress'); ?></option>
        <option value="delete"><?php _e('Delete','bxpress'); ?></option>
    </select>
    <input type="button" id="the-op-top" value="<?php _e('Apply','bxpress'); ?>" onclick="before_submit('frm-forums');" />
</div>
<!--//-->
<table class="outer" cellspacing="1" cellpadding="0" width="100%">
    <thead>
    <tr align="center">
        <th width="20"><input type="checkbox" id="checkall" onchange="$('#frm-forums').toggleCheckboxes(':not(#checkall)');"></th>
        <th width="50"><?php _e('ID','bxpress'); ?></th>
        <th align="left"><?php _e('Name','bxpress'); ?></th>
        <th width="50"><?php _e('Topics','bxpress'); ?></th>
        <th width="50"><?php _e('Posts','bxpress'); ?></th>
        <th><?php _e('Category','bxpress'); ?></th>
        <th width="26"><?php _e('Active','bxpress'); ?></th>
        <th width="26"><?php _e('Attachments','bxpress'); ?></th>
        <th><?php _e('Order','bxpress'); ?></th>
    </tr>
    </thead>
    <tfoot>
    <tr align="center">
        <th width="20"><input type="checkbox" id="checkall2" onchange="$('#frm-forums').toggleCheckboxes(':not(#checkall2)');"></th>
        <th width="50"><?php _e('ID','bxpress'); ?></th>
        <th align="left"><?php _e('Name','bxpress'); ?></th>
        <th width="50"><?php _e('Topics','bxpress'); ?></th>
        <th width="50"><?php _e('Posts','bxpress'); ?></th>
        <th><?php _e('Category','bxpress'); ?></th>
        <th width="26"><?php _e('Active','bxpress'); ?></th>
        <th width="26"><?php _e('Attachments','bxpress'); ?></th>
        <th><?php _e('Order','bxpress'); ?></th>
    </tr>
    </tfoot>
    <tbody>
    <?php if(empty($forums)): ?>
    <tr class="even"><td colspan="9" align="center"><?php _e('There are not forums created yet!','bxpress'); ?></td></tr>
    <?php endif; ?>
    <?php foreach($forums as $forum): ?>
        <tr class="<{cycle values="even,odd"}>" align="center">
            <td><input type="checkbox" name="forums[<{$forum.id}>]" value="<{$forum.id}>" /></td>
            <td><strong><{$forum.id}></strong></td>
            <td align="left"><a href="../forum.php?id=<{$forum.id}>"><{$forum.title}></a></td>
            <td><{$forum.topics}></td>
            <td><{$forum.posts}></td>
            <td><{$forum.catego}></td>
            <td><img src="../images/<{if $forum.active}>ok<{else}>no<{/if}>.png" border="0" alt="" /></td>
            <td><img src="../images/<{if $forum.attach}>ok<{else}>no<{/if}>.png" border="0" alt="" /></td>
            <td><input type="text" name="orders[<{$forum.id}>]" value="<{$forum.order}>" size="5" style="text-align: center;" /></td>
            <td>
                <a href="?op=edit&amp;id=<{$forum.id}>"><{$lang_edit}></a> &bull;
                <a href="?op=delete&amp;id=<{$forum.id}>"><{$lang_delete}></a> &bull;
		<a href="?op=moderator&amp;id=<{$forum.id}>"><{$lang_moderator}></a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<!-- Bulk operations -->
<div class="bxpress_options">
    <select name="actionb" id="bulk-bottom">
        <option value=""><?php _e('Bulk actions...','bxpress'); ?></option>
        <option value="enable"><?php _e('Activate','bxpress'); ?></option>
        <option value="disable"><?php _e('Disable','bxpress'); ?></option>
        <option value="delete"><?php _e('Delete','bxpress'); ?></option>
    </select>
    <input type="button" id="the-op-bottom" value="<?php _e('Apply','bxpress'); ?>" onclick="before_submit('frm-forums');" />
</div>
<!--//-->
<?php echo $xoopsSecurity->getTokenHTML(); ?>
<input type="hidden" name="op" value="" />
</form>
