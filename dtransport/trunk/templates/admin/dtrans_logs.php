<h1 class="rmc_titles dt_titles"><span style="background-position: left -32px;">&nbsp;</span><?php echo sprintf(__('"%s" Change Log','dtransport'), $sw->getVar('name')); ?></h1>

<form name="frmlog" id="frm-log" method="POST" action="logs.php">
<div class="dt_options">
    <select name="action" id="bulk-top">
        <option value="" selected="selected"><?php _e('Bulk actions...','dtransport'); ?></option>
        <option value="delete"><?php _e('Delete Logs','dtransport'); ?></option>
    </select>
    <input type="button" id="the-op-top" value="<?php _e('Apply','docs'); ?>" onclick="before_submit('frm-categories');" />
</div>
<table class="outer" width="100%" cellspacing="1">
    <thead>
	<tr align="center">
		<th width="20"><input type="checkbox" name="checkAll" id="checkall" onclick="$('#frm-log').toggleCheckboxes(':not(#checkall)');" /></th>
		<th><?php _e('ID','dtransport'); ?></th>
		<th><?php _e('Log title','dtransport'); ?></th>
		<th><?php _e('Date','dtransport'); ?></th>
        <th><?php echo __('Content','dtransport'); ?></th>
	</tr>
    </thead>
    <tfoot>
    <tr align="center">
        <th width="20"><input type="checkbox" name="checkAll2" id="checkall2" onclick="$('#frm-log').toggleCheckboxes(':not(#checkall2)');" /></th>
        <th><?php _e('ID','dtransport'); ?></th>
        <th><?php _e('Log title','dtransport'); ?></th>
        <th><?php _e('Date','dtransport'); ?></th>
        <th><?php echo __('Content','dtransport'); ?></th>
    </tr>
    </tfoot>
    <tbody>
    <?php if(empty($logs)): ?>
        <tr class="head">
            <td colspan="5" align="center"><?php _e('There are not logs for this download item.','dtransport'); ?></td>
        </tr>
    <?php endif; ?>
	<?php foreach($logs as $log): ?>
        <tr class="<?php echo tpl_cycle('even,odd'); ?>" align="center" valign="top">
            <td><input type="checkbox" name="ids[]" id="item-<?php echo $log['id']; ?>" value="<?php echo $log['id']; ?>" /></td>
            <td width="20"><strong><?php echo $log['id']; ?></strong></td>
            <td align="left">
                <?php echo $log['title']; ?>
                <span class="rmc_options">
                    <a href="logs.php?action=edit&amp;id=<?php echo $log['id']; ?>&amp;item=<?php echo $item; ?>"><?php _e('Edit','dtransport'); ?></a> |
                    <a href="#" onclick="dt_check_delete(<?php echo $log['id']; ?>,'frm-log');"><?php _e('Delete','dtransport'); ?></a>
                </span>
            </td>
            <td><?php echo $log['date']; ?></td>
            <td align="left"><?php echo $log['log']; ?></td>
        </tr>
	<?php endforeach; ?>
    </tbody>
</table>
<div class="dt_options">
    <select name="actionb" id="bulk-bottom">
        <option value="" selected="selected"><?php _e('Bulk actions...','dtransport'); ?></option>
        <option value="delete"><?php _e('Delete Logs','dtransport'); ?></option>
    </select>
    <input type="button" id="the-op-bottom" value="<?php _e('Apply','docs'); ?>" onclick="before_submit('frm-categories');" />
</div>
<input type="hidden" name="item" value="<?php echo $item; ?>" />
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>

